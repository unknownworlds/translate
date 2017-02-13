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
                        Admin tool: potential admins
                    </div>

                    <div class="panel-body">
                        <table class="table table-striped table-hover">

                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Unaccepted</th>
                                <th>Accepted</th>
                                <th>Up votes received</th>
                                <th>Down votes received</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $counts[$user->user_id]['unaccepted_count'] ?? 0 }}</td>
                                    <td>{{ $counts[$user->user_id]['accepted_count'] ?? 0 }}</td>
                                    <td>+{{ $counts[$user->user_id]['up_votes_sum'] ?? 0 }}</td>
                                    <td>-{{ $counts[$user->user_id]['down_votes_sum'] ?? 0 }}
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
