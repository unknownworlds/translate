@extends('app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">

                <div class="panel panel-default">
                    <div class="panel-heading">Frequently Asked Questions</div>

                    <div class="panel-body">
                        <div>
                            <h4>Are you going to spam my email?</h4>

                            <p>Nope. Your email can be used ONLY in two ways: 1) account password reset 2) Lukas
                                (@lnowaczek) contacting
                                you directly to ask if you want to be an admin of a language you contributed to.</p>

                            <hr>
                        </div>

                        <div>
                            <h4>I found a bug / have a feature request. Who should I contact?</h4>

                            <p>Shoot an email at lukas@unknownworlds.com or ping @lnowaczek on Twitter.</p>

                            <hr>
                        </div>

                        <div>
                            <h4>I added some translations but they aren't visible in the game!</h4>

                            <p>After you add a translation, it must be approved by an admin. Each language have separate
                                admins. If given language have no admins, we will contact most active people and invite
                                them to be an admin for given language.</p>

                            <hr>
                        </div>

                        <div>
                            <h4>I have found a string that's not used in the game!</h4>

                            <p>It can happen. If you think you found an old/unused string, please report it to
                                lukas@unknownworlds.com</p>

                            <hr>
                        </div>

                        <div>
                            <h4>I can't translate given string to my language!</h4>

                            <p>We know that some things can't be translated to other languages because of grammar
                                issues. If you'll encounter such issue, send an email describing it to
                                lukas@unknownworlds.com.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
