@extends('app')

@section('content')
    @include('errors/list')

    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">

                    <div class="panel-heading clearfix">
                        Languages
                        <a class="btn btn-default pull-right" href="{{ url('languages/create') }}" role="button">
                            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add new
                        </a>
                    </div>

                    <div class="panel-body">
                        <table class="table table-striped table-hover">

                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Locale</th>
                                <th>Options</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($languages as $language)
                                <tr>
                                    <td>{{ $language->name }}</td>
                                    <td>{{ $language->locale }}</td>
                                    <td>
                                        <a href="{{ route('languages.edit', $language->id) }}" class="btn btn-default">
                                            Edit
                                        </a>

                                        {!! Form::open(['route' => ['languages.destroy', $language->id], 'method' => 'DELETE', 'style' => 'display: inline']) !!}
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
