@extends('app')

@section('content')
    <div class="container">

        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">

                    <div class="panel-heading clearfix">
                        <h3 class="panel-title">Translations transfer</h3>
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

                        {!! Form::open([ 'url' => 'tools/translations-transfer', 'method' => 'POST' ]) !!}
                        <div class="form-group">
                            {!! Form::label('source_project', 'Source project:') !!}
                            {!! Form::select('source_project', $projects, null, ['class' => 'form-control']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('target_project', 'Target project:') !!}
                            {!! Form::select('target_project', $projects, null, ['class' => 'form-control']) !!}
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
