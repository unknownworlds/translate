@extends('app')

@section('content')
    <div class="container">

        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">

                    <div class="panel-heading clearfix">
                        <h3 class="panel-title">Select language</h3>
                    </div>

                    <div class="panel-body">
                        @include('errors/list')
                        @if (Session::has('message'))
                            <div class="alert alert-info">
                                {{ Session::get('message') }}
                            </div>
                        @endif
                        @if (Session::has('stats'))
                            <div class="alert alert-info">
                                Import statistics:
                                <ul>
                                    <li>
                                        Strings processed:
                                        {{ Session::get('stats')['processedBaseStrings'] }}
                                    </li>
                                    <li>
                                        Unrecognized base strings:
                                        {{ Session::get('stats')['unknownBaseStrings'] }}
                                    </li>
                                    <li>
                                        Empty or unnecessary translations:
                                        {{ Session::get('stats')['emptyOrUnnecessaryTranslations'] }}
                                    </li>
                                    <li>
                                        Translations already present and accepted:
                                        {{ Session::get('stats')['translationsAlreadyAcceptedInDatabase'] }}
                                    </li>
                                    <li>
                                        Translation already present but not accepted:
                                        {{ Session::get('stats')['translationsAlreadyInDatabaseButNotAccepted'] }}
                                    </li>
                                    <li>
                                        Translations already present that got accepted:
                                        {{ Session::get('stats')['translationsAlreadyInDatabaseAccepted'] }}
                                    </li>
                                    <li>
                                        Translations already present, accepted and different from the import (skipped
                                        because of the option checked):
                                        {{ Session::get('stats')['forceSkippedAlreadyTranslated'] }}
                                    </li>
                                    <li>
                                        New translations added:
                                        {{ Session::get('stats')['newTranslationsAccepted'] }}
                                    </li>
                                    <li>
                                        Processed locked strings:
                                        {{ Session::get('stats')['processedLockedStrings'] }}
                                    </li>
                                </ul>
                            </div>
                        @endif

                        {{ html()->form('POST', url('tools/file-import'))->open() }}
                        <div class="form-group">
                            {{ html()->label('Project:', 'project_id') }}
                            {{ html()->select('project_id', $projects)->class('form-control') }}
                        </div>

                        <div class="form-group">
                            {{ html()->label('Language:', 'language_id') }}
                            {{ html()->select('language_id', $languages)->class('form-control') }}
                        </div>

                        <div class="form-group">
                            {{ html()->label('User:', 'user_id') }}
                            {{ html()->select('user_id', $users)->class('form-control') }}
                        </div>

                        <div class="form-group">
                            {{ html()->label('Input type:', 'input_type') }}
                            {{ html()->select('input_type', $supportedFileFormats)->class('form-control') }}
                        </div>

                        <div class="form-group">
                            <label>Options:</label>
                            <div class="checkbox">
                                <label>
                                    {{ html()->checkbox('accept_translations', false) }}
                                    Automatically accept all translations from this import.
                                    Enabled if you trust the translation provider.
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    {{ html()->checkbox('ignore_already_translated', false) }}
                                    Ignore keys that already have an accepted translation. Enabled if you do not want to
                                    overwrite approved translations.
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    {{ html()->checkbox('overwrite_locked_strings', false) }}
                                    Overwrite locked strings.
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            {{ html()->label('Data input:', 'input') }}
                            {{ html()->textarea('data')->class('form-control')->attributes(['cols' => 50, 'rows' => 10])->html(e(old('data', ''))) }}
                        </div>

                        <div class="form-group">
                            {{ html()->submit('Process')->class('btn btn-primary form-control') }}
                        </div>
                        {{ html()->form()->close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
