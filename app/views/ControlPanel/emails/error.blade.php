@extends("layouts.email")


@section("content")
		
		<h2>Error in of {{ $url }}</h2>
		<div>
		{{ $exception }}
		</div>
@endsection