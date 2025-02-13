@extends("layouts.email")


@section("content")
		<?php if($user){ $user = unserialize($user); } ?>
		<h2>Feedback</h2>
		<div>
		<table>
					<tr>
		@if($user and $user != "")
			
							<th>User:</th>
							<td>{{ $user->getCompleteName() }}</td>
					
		@endif
		<tr>
				<th>Date</th>
				<td>{{ $date }}</td>
		</tr>
		<tr>
				<th>Feedback</th>
				<td>{{ $feedback }}</td>
		</tr>
		</tr>
			</table>

		</div>
@endsection