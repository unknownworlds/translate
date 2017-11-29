@extends('app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">

                <div class="panel panel-default">
                    <div class="panel-heading">Guidelines</div>

                    <div class="panel-body">

                        <p>Guidelines for Subnautica:</p>

                        <ul>
                            <li>Feel free to translate creature names</li>
                            <li>Translation must be the same case as the original</li>
                            <li>
                                Remember to include all special characters (like "&bull;") and placeholders (like "{0}")
                            </li>
                            <li>Please leave empty strings as-is if you don't know what do do with them</li>
                            <li>
                                Translation keys similar to <b>Spinefish.TooltipIngredient</b> are alternative versions
                                of given string ("Spinefish" in this example). They allow you to give the same string
                                different formatting. Is's useful when the same string is used in multiple places.
                            </li>
                            <li>
                                Language named "English (community)" allow you to propose your own, alternative version
                                of an original, English text. You can use it when you find a typo or if you'd like to
                                improve something else.
                            </li>
                        </ul>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
