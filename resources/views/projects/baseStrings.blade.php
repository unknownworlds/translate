@extends('app')

@section('content')

    <div class="container" id="baseStrings">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">

                    <div class="panel-heading clearfix">
                        Base strings for {{ $project->name }}
                    </div>

                    <div class="panel-body">

                        <table class="table table-striped table-hover">

                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Key</th>
                                <th>Options</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($baseStrings as $string)
                                <tr>
                                    <td>{{ $string->id }}</td>
                                    <td>{{ $string->key }}</td>
                                    <td>
                                        <button type="button" class="btn btn-default"
                                                @click="restoreTranslations({{ $string->id }})">
                                            Restore translations
                                        </button>
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
    <script src="{{ asset('/js/base-strings.js') }}"></script>
@endsection