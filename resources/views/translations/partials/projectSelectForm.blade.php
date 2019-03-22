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
                    {!! Form::button('Load data', ['class' => 'btn btn-primary form-control', '@click' => 'loadData()']) !!}
                </div>
                {!! Form::close() !!}

                <div v-show="projectHandlers[currentProject] == 2 && isRoot">
                    <hr>

                    <button class="btn btn-default" @click="showNewBaseStringForm()">
                        Add string
                    </button>
                </div>

                <div v-show="baseStrings.length">
                    <hr>

                    <ul class="list-inline">
                        <li><b>Language administrators:</b></li>
                        <li v-for="user in admins">@{{user.name}}</li>
                        <li v-show="!admins.length">No admins for this language</li>
                    </ul>

                    <ul class="list-inline">
                        <li><b>Top contributors:</b></li>
                        <li v-for="user in topUsers">@{{user.name}} (@{{user.count}})</li>
                        <li v-show="!topUsers.length">No one contributed yet</li>
                    </ul>

                    <hr>

                    <div class="filters clearfix">
                        <button class="btn btn-default" @click="hideAccepted()"
                                :class="{'btn-success': acceptedStringsHidden}">Hide strings with accepted
                            translations
                        </button>

                        <button class="btn btn-default" @click="showPendingOnly()"
                                :class="{'btn-success': showingPendingOnly}">Show pending translations only
                        </button>

                        <input id="searchInput" type="text" class="form-control pull-right"
                               v-model="searchInput" placeholder="Search...">
                    </div>
                </div>

                <div v-show="isAdmin || whiteboard.text">
                    <hr>
                    <h4>Admin whiteboard</h4>
                </div>

                <div v-if="isAdmin">
                    <div class="form-group">
                    <textarea id="adminWhiteboard" class="form-control resize-vertically"
                              placeholder="Type a message visible to everyone..." rows="1"
                              v-model="whiteboard.text"></textarea>
                    </div>

                    <div>
                        <button class="btn btn-default" @click="saveWhiteboard()">Save</button>
                        <div class="pull-right" v-if="whiteboard.text">
                            <span>Revision ID#@{{whiteboard.id}}, @{{whiteboard.created_at}},
                                last edit by @{{whiteboard.user.name}}</span>
                        </div>
                    </div>
                </div>

                <div v-if="whiteboard.text && !isAdmin">
                    <div class="well">@{{ whiteboard.text }}</div>
                    <div>
                        <div class="pull-right" v-if="whiteboard.text">
                            <span>Revision ID#@{{whiteboard.id}}, @{{whiteboard.created_at}},
                                last edit by @{{whiteboard.user.name}}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
