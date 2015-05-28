@extends('app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">

                <div class="panel panel-default">
                    <div class="panel-heading">Instructions</div>

                    <div class="panel-body">

                        <h3>Introduction</h3>

                        <p>
                            Translation interface is used to translate multiple projects to multiple languages. Basic
                            workflow is as follows:
                        </p>
                        <ol>
                            <li>Go to translate page, select project and language</li>
                            <li>Add translations</li>
                            <li>Wait for admins to approve them. If translation gets approved, it will have a green
                                background and will be available in the next build of the game.
                            </li>
                        </ol>

                        <h3>Home page</h3>

                        <p>Home page contains news/alerts and 3 tabs:</p>
                        <ol>
                            <li>
                                Translation log - log of latest events, like string being translated.
                            </li>
                            <li>
                                Base strings updates - log of latest changes to source, English strings. If there
                                will be a new string added or deleted from given project, you'll see it here. If string
                                gets updated, all translations added to it will be deleted.
                            </li>
                            <li>
                                Translation progress - progress bars showing how far away are from reaching our goal -
                                fully translated projects!
                            </li>
                        </ol>

                        <h3>Translate page</h3>

                        <p>Heart of the whole application. If you have any problems with figuring out how it works,
                            please take a look at the image below.</p>

                        <p>
                            <img src="{{ asset('content/translation_interface.png') }}"
                                 alt="Translation page explainations"
                                 class="img-responsive"/>
                        </p>

                        <ol>
                            <li>Select a project and language you want to work on.</li>
                            <li>This section shows language administrators, that are responsible for given language.
                                They can approve strings to be used in-game or delete them. Top contributors is a list
                                of everyone who contributed to given language's translation.
                            </li>
                            <li>Trash / approve buttons. Visible only for administrators.</li>
                            <li>You can type new translation here and add it using the button to the right. Remember
                                that translation must be the same case as the original. It will appear in the game after
                                being accepted by an admin.
                            </li>
                            <li>Voting section. The number represents cumulative up and down votes.</li>
                            <li>An accepted string - there can be only one for given string, it will have a green
                                background.
                            </li>
                            <li>Original string key. It's used as an internal identifier to distinguish given string.
                            </li>
                            <li>Original string text. It's the actual string that needs to be translated.</li>
                        </ol>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
