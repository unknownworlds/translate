@extends('app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">

                    <div class="panel-heading clearfix">
                        Projects
                        <a class="btn btn-default pull-right" href="{{ url('projects/create') }}" project="button">
                            Add new
                        </a>
                    </div>

                    <div class="panel-body">

                        @include('errors/list')

                        <table class="table table-striped table-hover">

                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>String count</th>
                                <th>Alterenative or empty strings</th>
                                <th>API key</th>
                                <th>Options</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($projects as $project)
                                <tr>
                                    <td>{{ $project->name }}</td>
                                    <td>{{ $baseStringCounts[$project->id] ?? 0 }}</td>
                                    <td>{{ $baseStringAlternativeCounts[$project->id] ?? 0 }}</td>
                                    <td>{{ $project->api_key }}</td>
                                    <td>
                                        <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-default">
                                            Edit
                                        </a>

                                        @if($project->data_input_handler == 3)
                                            <a href="{{ url("projects/{$project->id}/import") }}"
                                               class="btn btn-default">
                                                Import
                                            </a>
                                        @endif

                                        @if($project->data_output_handler == 2)
                                            <a href="{{ url("projects/{$project->id}/export") }}"
                                               class="btn btn-default">
                                                Export
                                            </a>
                                        @endif

                                        {!! Form::open(['route' => ['projects.destroy', $project->id], 'method' => 'DELETE', 'style' => 'display: inline']) !!}
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
