<?php $change = false; ?>

@if ($notificationsNew->count() > 0) 
@foreach ($notificationsNew as $notification)
 <div class="notificatinlist rownew<?php if($change){$change = !$change; echo "0";} else {$change = !$change; echo "1";}?> row<?php if($change){$change = !$change; echo "0";} else {$change = !$change; echo "1";}?> clearfix">
        	<!--<div class="noticationclose"><a href="#" class="closebtn">close</a></div>-->
            <div class="notificationimg"><a href="/{{ ($notification->user) ? $notification->user->getURL() : "" }}">
            	<img src="/{{ Helper::image( ($notification->user) ? $notification->user->thumb : "") }}"/>
            </a></div>
            <div class="notificationinfo">
            	<div class="datenot">{{ Helper::datetime($notification->created_at) }}</div>
                @if($notification->action != "")
            	   <a class="orange" href='javascript:void(0)' {{ Notifications::respondToAction($notification->action,$notification->fromId) }}>{{ $notification->message }}</a> 
                @else
                    {{ $notification->message }}
                @endif
            </div>
        </div>
@endforeach
@endif

<?php $change = false; ?>
@if ($notificationsOld->count() > 0) 
@foreach ($notificationsOld as $notification)
 <div class="notificatinlist row row<?php if($change){$change = !$change; echo "0";} else {$change = !$change; echo "1";}?> clearfix">
        	<!--<div class="noticationclose"><a href="#" class="closebtn">close</a></div>-->
            <div class="notificationimg"><a href="/{{ ($notification->user) ? $notification->user->getURL() : "" }}">
            	<img src="/{{ Helper::image( ($notification->user) ? $notification->user->thumb : "") }}"/>
            </a></div>
            <div class="notificationinfo">
            	<div class="datenot">{{ Helper::datetime($notification->created_at) }}</div>
                @if($notification->action != "")
            	   <a class="orange" href='javascript:void(0)' {{ Notifications::respondToAction($notification->action,$notification->fromId) }}>{{ $notification->message }}</a>
                @else
                    {{ $notification->message }}
                @endif
            </div>
        </div>
@endforeach
@else
    
@endif

@if ($notificationsOld->count() == 0 and $notificationsNew->count() == 0) 
 <div class="notificatinlist row row1 clearfix" style="height:50px;">
    <br/>{{ Messages::showEmptyMessage("NoNotifications") }}
 </div>

@endif


@if($totalOld > $notificationsOld->count())
 <div class="btmbuttonholder" style="margin-bottom:10px">
    <div class="clearfix"></div>
    <div class="hrborder"></div>
    <a href="javascript:void(0)" onclick="callNotifications(<?php echo $notificationsOld->count(); ?>);" class="greybtn">More Notifications</a>
 </div>
@endif
