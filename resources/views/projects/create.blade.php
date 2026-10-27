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

                        <h2>New project</h2>

                        {{ html()->form('POST', url('projects'))->open() }}
                        @include('projects/partials/form', ['submitButton' => 'Add project'])
                        {{ html()->form()->close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
