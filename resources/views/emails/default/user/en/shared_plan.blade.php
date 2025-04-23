@extends('layouts.emails')

@section('content')
    <tr>
        <td id="mainActionContainer">
            <table width="100%">
                <tr>
                    <td class="introMessage">
                        <h2>{{ $fromUser->firstName }} {{ $fromUser->lastName }} has shared a plan with you!</h2>
                    </td>
                </tr>
                @if ($comments)
                    <tr>
                        <td class="messageFromFriend">
                            <p class="friendMessage">"{{ $comments }}"</p>
                        </td>
                    </tr>
                @endif
                <tr>
                    <td class="actionButtonContent">
                        <a href="{{ url('/plans/shared/' . $sharing->access_link) }}">View the Plan</a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
@endsection
