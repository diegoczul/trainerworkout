@if($permissions["view"])  
  @if (count($feeds) > 0)
 <ul>
 <?php $count = 0; ?>
                @foreach ($feeds as $feed)
@if($feed->action != "")
 <li class="row<?php echo (++$count%2 ? '0' : '1') ?> actionFeed" {{ Notifications::respondToAction($feed->action,$feed->fromId) }}><div class="smallImage"><img src="/{{ Helper::image($feed->thumb) }}"/></div> {{ $feed->message }} <span class='smalldate'>{{ Helper::datetime($feed->date) }}</span></li>
@else
  <li class="row<?php echo (++$count%2 ? '0' : '1') ?>"><div class="smallImage"><img src="/{{ Helper::image($feed->thumb) }}"/></div> {{ $feed->message }} <span class='smalldate'>{{ Helper::datetime($feed->date) }}</span></li>
@endif
@endforeach
</ul>

 @else
    {{ Messages::showEmptyMessage("FeedEmpty",$permissions["self"]) }}
@endif


@if($total > count($feeds))
<div class="clearfix"></div>
    <div class="btmbuttonholder">
                <div class="clearfix"></div>
                    <span class="hrborder"></span>
                    <a href="javascript:void(0)" onclick="callWidget('w_feedClients',{{ count($feeds) }},null,$(this))" class="greybtn">More Feeds</a>
                </div>
@endif

@else
    {{ Messages::showEmptyMessage("NoPermissions") }}
@endif
