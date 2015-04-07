@extends('app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">Dashboard</div>

				<div class="panel-body">
                    <ul>
					@foreach($log as $entry)
                        <li>{{ $entry->text }}</li>
                    @endforeach
                    </ul>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
