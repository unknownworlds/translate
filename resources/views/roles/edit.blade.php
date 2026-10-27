@extends('app')

@section('content')
    @include('errors/list')

    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading clearfix">
                        Roles
                    </div>

                    <div class="panel-body">

                        @include('errors/list')

                        <h2>Edit role</h2>

                        {{ html()->modelForm($role, 'PATCH', route('roles.update', $role->id))->open() }}
                        @include('roles/partials/form', ['submitButton' => 'Save role'])
                        {{ html()->closeModelForm() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
