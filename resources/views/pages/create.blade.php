@extends('app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading clearfix">
                        Pages
                    </div>

                    <div class="panel-body">

                        @include('errors/list')

                        <h2>New page</h2>

                        {!! Form::open(['url' => 'pages']) !!}
                            @include('pages/partials/form', ['submitButton' => 'Add page'])
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
