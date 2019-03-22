@extends('app')

@section('content')
    @include('errors/list')

    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading clearfix">
                        Users
                    </div>

                    <div class="panel-body">

                        @include('errors/list')

                        <h2>Edit user</h2>

                        {!! Form::model($user, ['route' => ['users.update', $user->id], 'method' => 'PATCH']) !!}
                        @include('users/partials/form', ['submitButton' => 'Save user'])
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
