@extends('app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading clearfix">
                    Languages
                    <a class="btn btn-default pull-right" href="{{ url('languages/create') }}" role="button">Add new</a>
                </div>

				<div class="panel-body">
					You are logged in!!! <br>
                    This space will be occupied with log and translation status.
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
