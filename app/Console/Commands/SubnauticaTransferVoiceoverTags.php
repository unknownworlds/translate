<?php

namespace App\Console\Commands;

use App\Models\BaseString;
use App\Models\Language;
use App\Models\TranslatedString;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SubnauticaTransferVoiceoverTags extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subnautica:transfer-voiceover-tags {project : The ID of the project}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Transfer <delay> tags from English to other languages for Subnautica';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $projectId = $this->argument('project');
        $numberOfStringsUpdated = 0;

        $this->info('Bringing back deleted translations...');

        $deletedStringsCount = TranslatedString::where('project_id', '=', $projectId)
            ->where('deleted_at', '>=', Carbon::now()->subHours(8))
            ->withTrashed()
            ->count();
        $this->line('Got '.$deletedStringsCount.' strings that have to be brought back.');

        TranslatedString::where('project_id', '=', $projectId)
            ->where('deleted_at', '>=', Carbon::now()->subHours(8))
            ->withTrashed()
            ->restore();
        $this->line('Strings have been restored.');

        $this->info('Getting strings that have voiceover tags...');
        $baseStrings = BaseString::where('project_id', '=', $projectId)
            ->where(function ($query) {
                $query->where('text', 'like', '%<duration=%')
                    ->orWhere('text', 'like', '%<delay=%');
            })
            ->get();
        $this->line('Got '.$baseStrings->count().' strings that have to be processed.');

        $this->line('Extracting tags now.');
        $baseStringTags = [];
        foreach ($baseStrings as $baseString) {
            $lines = explode("\n", $baseString->text);
            foreach ($lines as $lineNumber => $line) {
                preg_match('/<delay=([0-9]+)>/', $line, $delay);
                preg_match('/<duration=([0-9]+)>/', $line, $duration);

                $baseStringTags[$baseString->id][$lineNumber] = ($delay[0] ?? '').($duration[0] ?? '');
            }
        }
        $affectedBaseStringIDs = array_keys($baseStringTags);
        $this->line('Tag extraction complete!');

        $this->info('Iterating on languages...');
        $languages = Language::all();
        foreach ($languages as $language) {
            $this->info('Getting strings from '.$language->name.'...');
            $translatedStrings = TranslatedString::where('language_id', '=', $language->id)
                ->whereIn('base_string_id', $affectedBaseStringIDs)
                ->get();

            $this->line('Got '.$translatedStrings->count().' strings that have to be processed.');

            foreach ($translatedStrings as $translatedString) {
                $lines = explode("\n", $translatedString->text);
                $newLines = [];

                foreach ($lines as $lineNumber => $line) {
                    $this->info('Removing old tags...');
                    $cleanedUpLine = preg_replace(['/<delay=([0-9]+)>/', '/<duration=([0-9]+)>/', '/###([0-9]+)/'], [''], $line);

                    $this->info('Adding new tags...');
                    $newLines[] = $cleanedUpLine.($baseStringTags[$translatedString->base_string_id][$lineNumber] ?? '');
                }

                $this->info('Updating string in the database...');
                $translatedString->update([
                    'text' => implode("\n", $newLines)
                ]);
                $numberOfStringsUpdated++;
            }
        }

        $this->info('Procedure done! '.$numberOfStringsUpdated.' strings updated.');

        return 0;
    }
}
