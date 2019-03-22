@extends('app')

@section('content')
    @include('errors/list')

    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">

                    <div class="panel-heading clearfix">
                        Pages
                        <a class="btn btn-default pull-right" href="{{ url('pages/create') }}" page="button">
                            Add new
                        </a>
                    </div>

                    <div class="panel-body">

                        @include('errors/list')

                        <table class="table table-striped table-hover">

                            <thead>
                            <tr>
                                <th>Title</th>
                                <th>Options</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($pages as $page)
                                <tr>
                                    <td>{{ $page->title }}</td>
                                    <td>
                                        <a href="{{ route('pages.edit', $page->id) }}" class="btn btn-default">
                                            Edit
                                        </a>

                                        {!! Form::open(['route' => ['pages.destroy', $page->id], 'method' => 'DELETE', 'style' => 'display: inline']) !!}
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
