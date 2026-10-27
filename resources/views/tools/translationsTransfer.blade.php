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

                        {{ html()->form('POST', url('tools/translations-transfer'))->open() }}
                        <div class="form-group">
                            {{ html()->label('Source project:', 'source_project') }}
                            {{ html()->select('source_project', $projects)->class('form-control') }}
                        </div>

                        <div class="form-group">
                            {{ html()->label('Target project:', 'target_project') }}
                            {{ html()->select('target_project', $projects)->class('form-control') }}
                        </div>

                        <div class="form-group">
                            {{ html()->submit('Process')->class('btn btn-primary form-control') }}
                        </div>
                        {{ html()->form()->close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
