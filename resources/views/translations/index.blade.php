@extends('app')

@section('content')
    <div class="container" ng-controller="TranslateController as Translate">

        <div id="ajax-loader" ng-show="loading"><img src="img/ajax-loader.svg" alt="AJAX loader"/></div>

        @include('translations/partials/projectSelectForm')

        <div class="row">
            <div class="col-md-10 col-md-offset-1">

                <div class="panel panel-default" ng-repeat="baseString in baseStrings">
                    <div class="panel-heading">
                        <h3 class="panel-title">@{{baseString.key}}</h3>
                        <i style="white-space: pre-line">
                            @{{baseString.text}}
                        </i>
                    </div>
                    <div class="panel-body">
                        <ul class="list-group">
                            <li class="list-group-item clearfix"
                                ng-class="{'list-group-item-success': string.is_accepted}"
                                ng-repeat="string in strings[baseString.id]">
                                <div class="btn-group pull-right" role="group" aria-label="...">
                                    <button type="button" class="btn btn-default" disabled="disabled">
                                        @{{string.up_votes-string.down_votes}}
                                    </button>
                                    <button type="button" class="btn btn-default"
                                            ng-click="vote(baseString.id, string.id, 1)">
                                        <span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span>
                                    </button>
                                    <button type="button" class="btn btn-default"
                                            ng-click="vote(baseString.id, string.id, -1)">
                                        <span class="glyphicon glyphicon-thumbs-down" aria-hidden="true"></span>
                                    </button>
                                    <button type="button" class="btn btn-default"
                                            ng-click="trash(baseString.id, string.id)" ng-if="isAdmin">
                                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                                    </button>
                                    <button type="button" class="btn btn-default"
                                            ng-click="accept(baseString.id, string.id)" ng-if="isAdmin">
                                        <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                                    </button>
                                </div>
                                <p style="white-space: pre-line">
                                    @{{string.text}}
                                </p>
                            </li>
                        </ul>

                        <div class="row">
                            <div class="col-md-10">
                                <textarea id="stringInput@{{baseString.id}}" class="form-control resize-vertically"
                                          placeholder="Type new translation..." rows="1"></textarea>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-default btn-block" type="button" ng-click="add(baseString.id)">
                                    Add
                                </button>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

        </div>
    </div>
@endsection
