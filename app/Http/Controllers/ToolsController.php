<?php

namespace App\Http\Controllers;

use App\Models\BaseString;
use App\Http\Requests;
use App\Http\Requests\ToolsFileImportRequest;
use App\Models\Language;
use App\LanguageFileHandling\InputHandlers\InputHandlerFactory;
use App\Models\Project;
use App\Models\User;
use PDF;
use File;
use App\Models\TranslatedString;
use Storage;
use Symfony\Component\HttpFoundation\Request;

class ToolsController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'hasRole:Root|Admin']);
    }

    /**
     * Interface for importing a JSON file that was translated outside of the app
     *
     * @return Response
     */
    public function fileImport()
    {
        $projects = Project::orderBy('name')->pluck('name', 'id');
        $languages = Language::orderBy('name')->pluck('name', 'id');
        $users = User::orderBy('name')->pluck('name', 'id');
        $supportedFileFormats = InputHandlerFactory::$availableHandlers;

        return view('tools/fileImport', compact('projects', 'languages', 'users', 'supportedFileFormats'));
    }

    /**
     * Procedure for importing a file that was translated outside of the app
     *
     * @param  ToolsFileImportRequest  $request
     *
     * @return Response
     */
    public function processFileImport(ToolsFileImportRequest $request)
    {
        ini_set('max_execution_time', 180);

        $stats = [
            'processedBaseStrings' => 0,
            'unknownBaseStrings' => 0,
            'emptyOrUnnecessaryTranslations' => 0,
            'translationsAlreadyAcceptedInDatabase' => 0,
            'translationsAlreadyInDatabaseButNotAccepted' => 0,
            'translationsAlreadyInDatabaseAccepted' => 0,
            'forceSkippedAlreadyTranslated' => 0,
            'newTranslationsAccepted' => 0,
            'processedLockedStrings' => 0,

        ];

        $acceptTranslationsFormImport = $request->get('accept_translations') == 1;
        $ignoreIfAlreadyTranslated = $request->get('ignore_already_translated') == 1;
        $overwriteLockedStrings = $request->get('overwrite_locked_strings') == 1;

        $inputHandler = InputHandlerFactory::getFileHandler($request->get('input_type'), $request->get('data'));
        $translatedStrings = $inputHandler->getParsedInput();

        if (!$translatedStrings) {
            return redirect()->back()->withErrors(['Can\'t decode the input']);
        }

        foreach ($translatedStrings as $key => $text) {
            $stats['processedBaseStrings']++;

            $baseString = BaseString::where('project_id', '=', $request->get('project_id'))
                ->where('key', '=', $key)
                ->first();

            if (!$baseString) {
                $stats['unknownBaseStrings']++;
                continue;
            }

            if ($text == '' || $text == $baseString->text) {
                $stats['emptyOrUnnecessaryTranslations']++;
                continue;
            }

            if ($baseString->locked) {
                if (!$overwriteLockedStrings) {
                    continue;
                } else {
                    $stats['processedLockedStrings']++;
                }
            }

            $string = TranslatedString::where('project_id', '=', $request->get('project_id'))
                ->where('language_id', '=', $request->get('language_id'))
                ->where('base_string_id', '=', $baseString->id)
                ->where('text', '=', $text)
                ->first();

            if ($string != null && $string->is_accepted == true) {
                $stats['translationsAlreadyAcceptedInDatabase']++;
                continue;
            }

            if ($string != null && $acceptTranslationsFormImport == false) {
                $stats['translationsAlreadyInDatabaseButNotAccepted']++;
                continue;
            }

            if ($ignoreIfAlreadyTranslated) {
                $currentlyAcceptedString = TranslatedString::where('project_id', '=', $request->get('project_id'))
                    ->where('language_id', '=', $request->get('language_id'))
                    ->where('base_string_id', '=', $baseString->id)
                    ->where('is_accepted', '=', true)
                    ->first();

                if ($currentlyAcceptedString != null) {
                    $stats['forceSkippedAlreadyTranslated']++;
                    continue;
                }
            }

            if ($acceptTranslationsFormImport) {
                TranslatedString::where('project_id', '=', $request->get('project_id'))
                    ->where('language_id', '=', $request->get('language_id'))
                    ->where('base_string_id', '=', $baseString->id)
                    ->update([
                        'is_accepted' => false
                    ]);
            }

            if ($string != null && $acceptTranslationsFormImport == true) {
                $string->update([
                    'is_accepted' => true
                ]);

                $stats['translationsAlreadyInDatabaseAccepted']++;

                continue;
            }

            TranslatedString::create([
                'project_id' => $request->get('project_id'),
                'language_id' => $request->get('language_id'),
                'base_string_id' => $baseString->id,
                'user_id' => $request->get('user_id'),
                'text' => $text,
                'is_accepted' => $acceptTranslationsFormImport,
                'alternative_or_empty' => $baseString->alternative_or_empty
            ]);

            $stats['newTranslationsAccepted']++;
        }

        return redirect('tools/file-import')->with('message', 'Import complete')->with('stats', $stats);
    }

    public function translationQualityIndex()
    {
        $projects = Project::orderBy('name')->get();

        return view('tools/translationQualityIndex', compact('projects'));
    }

    public function translationQualityStrings(Request $request)
    {
        $project = Project::findOrFail($request->get('project_id'));
        $strings = BaseString::where('project_id', '=', $project->id)
            ->orderBy('key')->get();

        return view('tools/translationQualityStrings', compact('strings', 'project'));
    }

    public function translationQualityDownload(Request $request)
    {
        $project = Project::findOrFail($request->get('project_id'));
        $baseStrings = BaseString::where('project_id', '=', $project->id)
            ->where('quality_controlled', '=', true)
            ->orderBy('key')->get();
        $languages = Language::all();
        $baseStringIds = $baseStrings->pluck('id');

        $baseDir = public_path().'/output/'.$project->id;
        $tempDir = $baseDir.'/qapdfs_'.time();
        mkdir($tempDir, 0777, true);

        foreach ($languages as $language) {
            $translatedStrings = [];
            $translatedStringsRaw = TranslatedString::where('language_id', '=', $language->id)
                ->where('is_accepted', '=', 1)
                ->whereIn('base_string_id', $baseStringIds)
                ->with('User')
                ->get();
            foreach ($translatedStringsRaw as $string) {
                $translatedStrings[$string->base_string_id] = $string;
            }

            $pdf = PDF::loadView('pdfs.qualityControl',
                compact('project', 'baseStrings', 'translatedStrings', 'language'));

            $pdf->save($tempDir.'/'.$language->name.'.pdf');
//			return view( 'pdfs.qualityControl', compact( 'project', 'baseStrings', 'translatedStrings', 'language' ) );
//			return $pdf->save( $tempDir . '/' . $language->name . '.pdf' )->stream();
        }

        // zip & clean
        $outputFile = $baseDir.'/QA_PDFs.zip';
        if (is_file($outputFile)) {
            unlink($outputFile);
        }

        exec('cd '.$baseDir.'/ && zip -j QA_PDFs.zip '.$tempDir.'/*');
        File::deleteDirectory($tempDir);

        // return file
        return \Response::download($outputFile);
    }

    /**
     * translationsTransfer interface
     *
     * @return Response
     */
    public function translationsTransfer()
    {
        $projects = Project::orderBy('name')->pluck('name', 'id');

        return view('tools/translationsTransfer', compact('projects'));
    }

    /**
     * TODO: batch queries, make less database intensive
     * translationsTransfer procedure
     * allows for transfer accepted translations from one project to another
     * if the same translation keys exist in both
     *
     * @param  Request  $request
     *
     * @return Response
     */
    public function ProcessTranslationsTransfer(Request $request)
    {
        $sourceProject = $request->get('source_project');
        $targetProject = $request->get('target_project');

        $languages = Language::all();
        $copiedStrings = 0;

        // validate
        if ($sourceProject == $targetProject) {
            return back()->withErrors(['Source and target cannot be the same']);
        }

        // get base strings from both projects
        $sourceProjectKeys = BaseString::where('project_id', '=', $sourceProject)
            ->pluck('id', 'key')->toArray();
        $targetProjectKeys = BaseString::where('project_id', '=', $targetProject)
            ->pluck('id', 'key')->toArray();

        // iterate over target project strings
        foreach ($targetProjectKeys as $key => $id) {
            // skip if it's not present in source
            if (!array_key_exists($key, $sourceProjectKeys)) {
                continue;
            }

            // iterate over all languages
            $insertDataBatch = [];
            foreach ($languages as $language) {
                $existingTargetTranslation = TranslatedString::where('project_id', '=', $targetProject)
                    ->where('is_accepted', '=', true)
                    ->where('base_string_id', '=', $targetProjectKeys[$key])
                    ->where('language_id', '=', $language->id)
                    ->first();

                // skip if we already have an accepted translation
                if ($existingTargetTranslation != null) {
                    continue;
                }

                $existingSourceTranslation = TranslatedString::where('project_id', '=', $sourceProject)
                    ->where('is_accepted', '=', true)
                    ->where('base_string_id', '=', $sourceProjectKeys[$key])
                    ->where('language_id', '=', $language->id)
                    ->first();

                // skip if we don't have that translation ready in the source project
                if ($existingSourceTranslation == null) {
                    continue;
                }

                $insertDataBatch[] = [
                    'project_id' => $targetProject,
                    'language_id' => $existingSourceTranslation->language_id,
                    'base_string_id' => $targetProjectKeys[$key],
                    'text' => $existingSourceTranslation->text,
                    'is_accepted' => true,
                    'user_id' => $existingSourceTranslation->user_id,
                ];

                $copiedStrings++;
            }

            if (!empty($insertDataBatch)) {
                TranslatedString::create($insertDataBatch);
            }
        }

        return back()->withMessage('Copied '.$copiedStrings.' translations');
    }

    public function translationSpreadsheet()
    {
        $projects = Project::orderBy('name')->get();

        return view('tools/translationSpreadsheetIndex', compact('projects'));
    }

    public function translationSpreadsheetDownload(Request $request)
    {
        ini_set('max_execution_time', 120);
        ini_set('memory_limit', '256M');

        $project = Project::findOrFail($request->get('project_id'));
        $baseStrings = BaseString::where('project_id', '=', $project->id)
            ->orderBy('key')->get(['id', 'key', 'text']);
        $languages = Language::all();

        foreach ($baseStrings as $baseString) {
            $baseString->text = str_replace(["\r\n", "\n"], '\n', $baseString->text);
        }

        $translatedStrings = TranslatedString::where('project_id', '=', $project->id)
            ->where('is_accepted', '=', true)
            ->get(['base_string_id', 'text', 'language_id']);

        $translatedStringsCSVFriendly = [];
        foreach ($translatedStrings as $string) {
            $translatedStringsCSVFriendly[$string['language_id']][$string['base_string_id']] =
                html_entity_decode(str_replace(["\r\n", "\n", "\r"], '\n', $string['text']));
        }

        unset($translatedStrings);

        $output = view('tools/translationSpreadsheetCSV',
            compact('project', 'baseStrings', 'languages', 'translatedStringsCSVFriendly'));

        Storage::put('translation-spreadsheet.csv', $output->render());

        return Storage::download('translation-spreadsheet.csv');
    }

    public function wordCountsSpreadsheetDownload(Request $request)
    {
        ini_set('max_execution_time', 120);
        ini_set('memory_limit', '256M');

        $project = Project::findOrFail($request->get('project_id'));
        $languages = Language::all();

        $baseStrings = BaseString::where('project_id', '=', $project->id)
            ->orderBy('key')->get(['id', 'key', 'text']);
        $baseStringsText = $baseStrings->pluck('text', 'id')->toArray();
        $totalEnglishWordCount = 0;

        foreach ($baseStringsText as $value) {
            $totalEnglishWordCount += str_word_count($value);
        }

        $translatedStrings = TranslatedString::where('project_id', '=', $project->id)
            ->where('is_accepted', '=', true)
            ->get(['base_string_id', 'text', 'language_id']);

        $translatedWordCounts = [];
        foreach ($translatedStrings as $string) {
            if (!array_key_exists($string['language_id'], $translatedWordCounts)) {
                $translatedWordCounts[$string['language_id']] = 0;
            }

            $translatedWordCounts[$string['language_id']] += str_word_count($baseStringsText[$string['base_string_id']]);
        }

        $output = view('tools/translationSpreadsheetWordCountsCSV',
            compact('project', 'baseStrings', 'languages', 'totalEnglishWordCount', 'translatedWordCounts'));

        Storage::put('translated-word-count.csv', $output->render());

        return Storage::download('translated-word-count.csv');
    }

}
