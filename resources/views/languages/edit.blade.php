@extends('app')

@section('content')
    @include('errors/list')

    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading clearfix">
                        Languages
                    </div>

                    <div class="panel-body">
                        <h2>Edit language</h2>

                        {!! Form::model($language, ['route' => ['languages.update', $language->id], 'method' => 'PATCH']) !!}
                        @include('languages/partials/form', ['submitButton' => 'Save language'])
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
