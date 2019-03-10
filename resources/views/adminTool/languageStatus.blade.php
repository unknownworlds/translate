@extends('app')

@section('content')
    @include('errors/list')

    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">

                    <div class="panel-heading clearfix">
                        Admin tool: language status summary
                    </div>

                    <div class="panel-body">
                        <table class="table table-striped table-hover">

                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Number of admins</th>
                                <th>Translation progress</th>
                                <th>Unaccepted strings</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($languages as $language)
                                <tr>
                                    <td>{{ $language->name }}</td>
                                    <td>{{ $languageAdmins[$language->name] ?? "No admins!" }}</td>
                                    <td>
                                        {{ $acceptedStrings[$language->id] ?? 0 }} / {{ $baseStrings ?? 0 }}
                                        @if($baseStrings > 0)
                                        ({{ round(($acceptedStrings[$language->id] ?? 0)  /  $baseStrings * 100, 2) }}%)
                                        @endif
                                    </td>
                                    <td>{{ $unacceptedStrings[$language->id] ?? 0 }}</td>
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
