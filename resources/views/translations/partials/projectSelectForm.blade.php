<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">

            <div class="panel-heading clearfix">
                <h3 class="panel-title">Options & Tools</h3>
            </div>

            <div class="panel-body">
                {!! Form::open([ 'class' => 'form-inline' ]) !!}
                <div class="form-group">
                    {!! Form::label('project', 'Project:') !!}
                    {!! Form::select('project', $projectList, null, ['class' => 'form-control']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('language', 'Language:') !!}
                    {!! Form::select('language', $languages, null, ['class' => 'form-control']) !!}
                </div>

                <div class="form-group">
                    {!! Form::button('Load data', ['class' => 'btn btn-primary form-control', 'ng-click' => 'loadData()']) !!}
                </div>
                {!! Form::close() !!}

                <div ng-show="projectHandlers[currentProject] == 'Manual' && isRoot">
                    <hr>

                    <button class="btn btn-default" ng-click="showNewBaseStringForm()">
                        Add string
                    </button>
                </div>

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

                <div ng-if="isAdmin || whiteboard.text">
                    <hr>
                    <h4>Admin whiteboard</h4>
                </div>

                <div ng-if="isAdmin">
                    <div class="form-group">
                    <textarea id="adminWhiteboard" class="form-control resize-vertically"
                              placeholder="Type a message visible to everyone..." rows="1"
                              ng-model="whiteboard.text"></textarea>
                    </div>

                    <div>
                        <button class="btn btn-default" ng-click="saveWhiteboard()" ng-if="isAdmin">Save</button>
                        <div class="pull-right" ng-if="whiteboard.text">
                            <span>Revision ID#@{{whiteboard.id}}, @{{whiteboard.created_at}},
                                last edit by @{{whiteboard.user.name}}</span>
                        </div>
                    </div>
                </div>

                <div ng-if="whiteboard.text && !isAdmin">
                    <div class="well" ng-model="">@{{ whiteboard.text }}</div>
                    <div>
                        <div class="pull-right" ng-if="whiteboard.text">
                            <span>Revision ID#@{{whiteboard.id}}, @{{whiteboard.created_at}},
                                last edit by @{{whiteboard.user.name}}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
