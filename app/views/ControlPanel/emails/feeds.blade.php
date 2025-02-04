@extends("layouts.email")

@section("content")
		
		<h2>Activity of {{ $date }}</h2>
		<div style="width:100%">
		<p>
		<?php $feeds = unserialize($feeds); ?>
		@foreach($feeds as $feed)
			@if($feed->user)
					<p>[{{ $feed->created_at }} {{ $feed->user->firstName }} {{ $feed->user->lastName }}]				   
					{{ $feed->message }}
					</p>
			@endif
		@endforeach
		
		</div>
@endsection