@extends('app')

@section('content')
    @include('errors/list')

    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading clearfix">
                        Pages
                    </div>

                    <div class="panel-body">

                        @include('errors/list')

                        <h2>Edit page</h2>

                        {!! Form::model($page, ['route' => ['pages.update', $page->id], 'method' => 'PATCH']) !!}
                        @include('pages/partials/form', ['submitButton' => 'Save page'])
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
