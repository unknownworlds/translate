@extends('app')

@section('content')
    @include('errors/list')

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

                        {!! Form::open(['url' => 'projects']) !!}
                            @include('projects/partials/form', ['submitButton' => 'Add project'])
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
