@extends('app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading clearfix">
                        Users
                    </div>

                    <div class="panel-body">
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
