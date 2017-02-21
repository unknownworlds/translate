@extends('app')

@section('content')
    @include('errors/list')

    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading clearfix">
                        Select language
                    </div>

                    <div class="panel-body">
                        {!! Form::open([ 'class' => 'form-inline', 'method' => 'GET' ]) !!}

                        <div class="form-group">
                            {!! Form::select('language_id', $languages, $language, ['class' => 'form-control']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::submit('Load data', ['class' => 'btn btn-primary form-control']) !!}
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>

                <div class="panel panel-default">

                    <div class="panel-heading clearfix">
                        Admin tool: audit
                    </div>

                    <div class="panel-body">
                        <table class="table table-striped table-hover">

                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Unaccepted translations added</th>
                                <th>Accepted translations added</th>
                                <th>Up votes received</th>
                                <th>Down votes received</th>
                                <th>Translations accepted (last date)</th>
                                <th>Translations deleted (last date)</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($admins as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $counts[$user->id]['unaccepted_count'] ?? 0 }}</td>
                                    <td>{{ $counts[$user->id]['accepted_count'] ?? 0 }}</td>
                                    <td>+{{ $counts[$user->id]['up_votes_sum'] ?? 0 }}</td>
                                    <td>-{{ $counts[$user->id]['down_votes_sum'] ?? 0 }}</td>
                                    <td>{{ $counts[$user->id]['accepted_by_count'] ?? 'N/A' }} ({{ $counts[$user->id]['last_accepted'] ?? 'N/A' }})</td>
                                    <td>{{ $counts[$user->id]['deleted_by_count'] ?? 'N/A' }} ({{ $counts[$user->id]['last_deleted'] ?? 'N/A' }})</td>
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
