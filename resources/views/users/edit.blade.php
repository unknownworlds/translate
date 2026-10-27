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

                        {{ html()->modelForm($user, 'PATCH', route('users.update', $user->id))->open() }}
                        @include('users/partials/form', ['submitButton' => 'Save user'])
                        {{ html()->closeModelForm() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
