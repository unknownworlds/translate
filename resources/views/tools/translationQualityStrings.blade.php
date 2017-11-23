@extends('app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">

                    <div class="panel-heading clearfix">
                        Quality control strings for {{ $project->name }}
                    </div>

                    <div class="panel-body">

                        @include('errors/list')

                        <table class="table table-striped table-hover">

                            <thead>
                            <tr>
                                <th>String</th>
                                <th>Options</th>
                            </tr>
                            </thead>

                            <tbody id="vueMe">
                            @foreach($strings as $string)
                                <tr>
                                    <td>
                                        <b>{{ $string->key }}</b><br>
                                        {{ $string->text }}
                                    </td>
                                    <td>
                                        <a @click="toggleSelection({{ $string->id }}, $event)"
                                           class="btn btn-default">{{ $string->quality_controlled ? 'Deselect' : 'Select' }}</a>
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

@section('scripts')
    <script src="{{ asset('/js/translation-quality.js') }}"></script>
@endsection