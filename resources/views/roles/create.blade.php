@extends('app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading clearfix">
                        Roles
                    </div>

                    <div class="panel-body">

                        @include('errors/list')

                        <h2>New role</h2>

                        {!! Form::open(['url' => 'roles']) !!}
                        @include('roles/partials/form', ['submitButton' => 'Add role'])
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
