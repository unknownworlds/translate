@extends('app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading clearfix">
                        Import data to {{ $project->name }}
                    </div>

                    <div class="panel-body">

                        @include('errors/list')

                        {!! Form::model($project, ['url' => url("projects/{$project->id}/import"), 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
                        <div class="form-group">
                            {!! Form::label('file', 'Select file:') !!}
                            {!! Form::file('file') !!}
                        </div>

                        <div class="form-group">
                            {!! Form::submit('Import', ['class' => 'btn btn-primary form-control']) !!}
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
