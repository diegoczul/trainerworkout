@if($permissions["view"])  
  @if ($feeds->count() > 0)
 <ul>
 <?php $count = 0; ?>
                @foreach ($feeds as $feed)

 <li class="row<?php echo (++$count%2 ? '0' : '1') ?>"><div class="smallImage"><img src="/{{ Helper::image($feed->user->thumb) }}"/></div> {{ $feed->message }} <span class='smalldate'>{{ Helper::datetime($feed->updated_at) }}</span></li>
 
@endforeach
</ul>

 @else
    {{ Messages::showEmptyMessage("FeedEmpty",$permissions["self"]) }}
@endif


@if($total > $feeds->count())
<div class="clearfix"></div>
    <div class="btmbuttonholder">
                <div class="clearfix"></div>
                    <span class="hrborder"></span>
                    <a href="javascript:void(0)" onclick="callWidgetExternal('w_feedClient',{{ $feeds->count() }},{{ $client }},$(this))" class="greybtn">More Feeds</a>
                </div>
@endif

@else
    {{ Messages::showEmptyMessage("NoPermissions") }}
@endif
