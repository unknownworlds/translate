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

            </div>
        </div>
    </div>
</div>
