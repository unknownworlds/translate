@extends('app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading clearfix">
                        Languages
                    </div>

                    <div class="panel-body">
                        <h2>New language</h2>

                        {!! Form::open(['url' => 'languages']) !!}
                            @include('languages/partials/form', [$submitButton => 'Add language'])
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
