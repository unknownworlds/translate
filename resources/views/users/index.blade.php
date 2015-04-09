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

                        <table class="table table-striped table-hover">

                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Roles</th>
                                <th>Options</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>
                                        @foreach($user->roles as $role)
                                        {{ $role->name }}
                                        @endforeach
                                    </td>
                                    <td>
                                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-default">
                                            Edit
                                        </a>

                                        {!! Form::open(['route' => ['users.destroy', $user->id], 'method' => 'DELETE', 'style' => 'display: inline']) !!}
                                        {!! Form::submit('Delete', ['class' => 'btn btn-default']) !!}
                                        {!! Form::close() !!}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>

                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
