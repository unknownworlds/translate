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
                        <h2>Edit role</h2>

                        {!! Form::model($role, ['route' => ['roles.update', $role->id], 'method' => 'PATCH']) !!}
                            @include('roles/partials/form', ['submitButton' => 'Save role'])
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
