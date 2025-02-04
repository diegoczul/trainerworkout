@if ($messagesNew->count() > 0) 
@foreach ($messagesNew as $message)
<div class='tagslist greybg clearfix'>
        <div class='tagimgholder'>
            <div class='tagimg'><img src="/{{ Helper::image($notification->user()->first()->thumb) }}"/></div>
        </div>
        <div class='tagdetails'>
            {{ $notification->user()->firstName }} {{ $notification->user()->lastName }}
            {{ substr(strip_tags($message->message), 0, 65) }}
        </div>
    </div>
@endforeach
@else
    {{ Lang::get("messages.NoNewNotifications") }}
@endif

@if($totalNew > $notificationsNew->count())

@endif
