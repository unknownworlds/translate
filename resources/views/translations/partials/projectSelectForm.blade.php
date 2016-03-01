<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">

            <div class="panel-heading clearfix">
                <h3 class="panel-title">Select language</h3>
            </div>

            <div class="panel-body">
                {!! Form::open([ 'class' => 'form-inline' ]) !!}
                <div class="form-group">
                    {!! Form::label('project', 'Project:') !!}
                    {!! Form::select('project', $projects, null, ['class' => 'form-control']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('language', 'Language:') !!}
                    {!! Form::select('language', $languages, null, ['class' => 'form-control']) !!}
                </div>

                <div class="form-group">
                    {!! Form::button('Load data', ['class' => 'btn btn-primary form-control', 'ng-click' => 'loadData()']) !!}
                </div>
                {!! Form::close() !!}

                <div ng-show="baseStrings.length">
                    <hr>

                    <ul class="list-inline">
                        <li><b>Language administrators:</b></li>
                        <li ng-repeat="user in admins">@{{user.name}}</li>
                        <li ng-show="!admins.length">No admins for this language</li>
                    </ul>

                    <ul class="list-inline">
                        <li><b>Top contributors:</b></li>
                        <li ng-repeat="user in topUsers">@{{user.name}} (@{{user.count}})</li>
                        <li ng-show="!topUsers.length">Noone contributed yet</li>
                    </ul>

                    <hr>

                    <div class="filters">
                        <button class="btn btn-default" ng-click="hideAccepted()"
                                ng-class="{'btn-success': acceptedStringsHidden}">Hide strings with accepted
                            translations
                        </button>

                        <button class="btn btn-default" ng-click="showPendingOnly()"
                                ng-class="{'btn-success': showingPendingOnly}">Show pending translations only
                        </button>

                        <input id="searchInput" type="text" class="form-control pull-right clearfix"
                               ng-model="searchInput" ng-model-options="{debounce: 250}"
                               placeholder="Search...">
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
