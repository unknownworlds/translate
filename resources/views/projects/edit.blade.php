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

                        <h2>Edit project</h2>

                        {!! Form::model($project, ['route' => ['projects.update', $project->id], 'method' => 'PATCH']) !!}
                            @include('projects/partials/form', ['submitButton' => 'Save project'])
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
