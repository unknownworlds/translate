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

                        {{ html()->modelForm($page, 'PATCH', route('pages.update', $page->id))->open() }}
                        @include('pages/partials/form', ['submitButton' => 'Save page'])
                        {{ html()->closeModelForm() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
