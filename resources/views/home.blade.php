@extends('app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Log</div>

                    <div class="panel-body">
                        <div class="alert alert-info">
                            <strong>Important!</strong> Application is in testing phase. Things might break. Please
                            report any bugs and feature requests to lukas@unknownworlds.com or via twitter
                            to @lnowaczek.
                        </div>

                        <ul>
                            @foreach($log as $entry)
                                <li>{{ $entry->text }} ({{ $entry->created_at->diffForHumans() }})</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
