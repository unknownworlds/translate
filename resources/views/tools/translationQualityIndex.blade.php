@extends('app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">

                    <div class="panel-heading clearfix">
                        Projects
                    </div>

                    <div class="panel-body">

                        @include('errors/list')

                        <table class="table table-striped table-hover">

                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Options</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($projects as $project)
                                <tr>
                                    <td>{{ $project->name }}</td>
                                    <td>
                                        <a href="/tools/translation-quality-pdfs/strings?project_id={{ $project->id }}" class="btn btn-default">
                                            Select strings
                                        </a>
                                        <a href="/tools/translation-quality-pdfs/download?project_id={{ $project->id }}" class="btn btn-default">
                                            Download PDFs
                                        </a>
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
