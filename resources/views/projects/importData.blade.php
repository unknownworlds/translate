@extends('app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading clearfix">
                        Import data to {{ $project->name }}
                    </div>

                    <div class="panel-body">

                        @include('errors/list')

                        {{ html()->modelForm($project, 'POST', url("projects/{$project->id}/import"))->acceptsFiles()->open() }}
                        <div class="form-group">
                            {{ html()->label('Select file:', 'file') }}
                            {{ html()->file('file') }}
                        </div>

                        <div class="form-group">
                            {{ html()->submit('Import')->class('btn btn-primary form-control') }}
                        </div>
                        {{ html()->closeModelForm() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
