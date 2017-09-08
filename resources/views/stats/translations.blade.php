@extends('app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">

                    <div class="panel-heading clearfix">Number of translations per day</div>

                    <div class="panel-body">
                        <div>
                            <canvas id="translationsPerDay" width="400" height="150"></canvas>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        var translationsPerDay = {!! json_encode($created) !!}
    </script>

    <script src="{{ asset('js/stats-translations.js') }}"></script>
@endsection