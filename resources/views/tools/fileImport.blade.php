@extends('app')

@section('content')
    <div class="container">

        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">

                    <div class="panel-heading clearfix">
                        <h3 class="panel-title">Select language</h3>
                    </div>

                    <div class="panel-body">
                        @include('errors/list')
                        @if (Session::has('message'))
                            <div class="alert alert-info">
                                <ul class="list">
                                    <li>{{ Session::get('message') }}</li>
                                </ul>
                            </div>
                        @endif

                        {!! Form::open([ 'url' => 'tools/file-import', 'method' => 'POST' ]) !!}
                        <div class="form-group">
                            {!! Form::label('project_id', 'Project:') !!}
                            {!! Form::select('project_id', $projects, null, ['class' => 'form-control']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('language_id', 'Language:') !!}
                            {!! Form::select('language_id', $languages, null, ['class' => 'form-control']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('user_id', 'User:') !!}
                            {!! Form::select('user_id', $users, null, ['class' => 'form-control']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('input_type', 'Input type:') !!}
                            {!! Form::select('input_type', $supportedFileFormats, null, ['class' => 'form-control']) !!}
                        </div>

                        <div class="form-group">
                            <label>Options:</label>
                            <div class="checkbox">
                                <label>
                                    {!! Form::checkbox('accept_translations', true, false) !!}
                                    Automatically accept all translations from this import.
                                    Enabled if you trust the translation provider.
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('input', 'Data input:') !!}
                            {!! Form::textarea('data', null, ['class' => 'form-control']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::submit('Process', ['class' => 'btn btn-primary form-control']) !!}
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
