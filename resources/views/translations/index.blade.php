@extends('app')

@section('content')
    <div class="container" ng-controller="TranslateController as Translate">

        @include('translations/partials/projectSelectForm')

        <div class="row">
            <div class="col-md-10 col-md-offset-1">

                <div class="panel panel-default" ng-repeat="baseString in baseStrings">
                    <div class="panel-heading">
                        <h3 class="panel-title">@{{baseString.key}}</h3>
                        <i>
                            @{{baseString.text}}
                        </i>
                    </div>
                    <div class="panel-body">
                        <ul class="list-group">
                            <li class="list-group-item clearfix" ng-class="{'list-group-item-success': string.is_accepted}" ng-repeat="string in strings[baseString.id]">
                                <div class="btn-group pull-right" role="group" aria-label="...">
                                    <button type="button" class="btn btn-default" disabled="disabled">
                                        @{{string.up_votes-string.down_votes}}
                                    </button>
                                    <button type="button" class="btn btn-default" ng-click="vote(baseString.id, string.id, 1)" >
                                        <span class="glyphicon glyphicon glyphicon-arrow-up"
                                              aria-hidden="true"></span>
                                    </button>
                                    <button type="button" class="btn btn-default" ng-click="vote(baseString.id, string.id, -1)">
                                        <span class="glyphicon glyphicon glyphicon-arrow-down"
                                              aria-hidden="true"></span>
                                    </button>
                                    <button type="button" class="btn btn-default" ng-click="trash(baseString.id, string.id)" ng-if="isAdmin">
                                        <span class="glyphicon glyphicon glyphicon-trash" aria-hidden="true"></span>
                                    </button>
                                    <button type="button" class="btn btn-default" ng-click="accept(baseString.id, string.id)" ng-if="isAdmin">
                                        <span class="glyphicon glyphicon glyphicon glyphicon-ok"
                                              aria-hidden="true"></span>
                                    </button>
                                </div>

                                @{{string.text}}
                            </li>
                        </ul>

                        <div class="input-group">
                            <input id="stringInput@{{baseString.id}}" type="text" class="form-control" placeholder="Type new translation...">

                            <div class="input-group-btn">
                                <button class="btn btn-default" type="button" ng-click="add(baseString.id)">Add</button>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

        </div>
    </div>
@endsection
