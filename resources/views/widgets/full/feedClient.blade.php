@if($permissions["view"])  
  @if (count($feeds) > 0)

 <?php $count = 0; ?>
@foreach ($feeds as $feed)
@if($feed->sourceType == "notification")
@if($feed->action == "message")
 <div class="clientFeedContainer {{ ($count % 2 == 1) ? "floatRight" : "" }}">
    <div class="clientFeedAction">
        <div class="clientFeedAction1 typeNotification" onClick='window.location="/Client/{{ $feed->fromId }}/"'><div class="centerVerticalText  center">View Client</div></div>
        <div class="clientFeedMessage" {{ Notifications::respondToAction($feed->action,$feed->fromId) }}><div class="centerVerticalText"><img class="iconFeed" src="/img/IconMessage.png" />Message {{{ $feed->firstName }}}</div></div>
        <div class="clientFeedRemove"  onClick='archiveFeed({{ $feed->feedId }},$(this),"Notification")'><div class="centerVerticalText bold center removeClientFeed margin0">X</div></div>  
    </div>
 <div class="clientFeedImage"><img src="/{{ Helper::image($feed->thumb) }}"/></div><div class="iconActivityFeed">{{ (Notifications::displayIcon($feed->type) != "" ? "<img src='/".Notifications::displayIcon($feed->type)."'/>" : "") }}</div> {{ $feed->message }} <span class='smalldate smalldateClientFeed'>{{ Helper::datetime($feed->date) }}</span></div>
@elseif($feed->action == "workout")
 <div class="clientFeedContainer {{ ($count % 2 == 1) ? "floatRight" : "" }}">
    <div class="clientFeedAction">
        <div class="clientFeedAction1 typeNotification" {{ Notifications::respondToAction($feed->action,$feed->fromId,$feed->link) }}><div class="centerVerticalText  center">View Workout</div></div>
        <div class="clientFeedMessage" {{ Notifications::respondToAction($feed->action,$feed->fromId) }}><div class="centerVerticalText"><img class="iconFeed" src="/img/IconMessage.png" />Message {{{ $feed->firstName }}}</div></div>
        <div class="clientFeedRemove"  onClick='archiveFeed({{ $feed->feedId }},$(this),"Notification")'><div class="centerVerticalText bold center removeClientFeed margin0">X</div></div>  
    </div>
 <div class="clientFeedImage"><img src="/{{ Helper::image($feed->thumb) }}"/></div><div class="iconActivityFeed">{{ (Notifications::displayIcon($feed->type) != "" ? "<img src='/".Notifications::displayIcon($feed->type)."'/>" : "") }}</div> {{ $feed->message }} <span class='smalldate smalldateClientFeed'>{{ Helper::datetime($feed->date) }}</span></div>
 @else
<div class="clientFeedContainer {{ ($count % 2 == 1) ? "floatRight" : "" }}">
    <div class="clientFeedAction">
        <div class="clientFeedAction1 typeNotification" onClick='window.location="/Client/{{ $feed->fromId }}/"'><div class="centerVerticalText  center">View Client</div></div>
        <div class="clientFeedMessage" {{ Notifications::respondToAction($feed->action,$feed->fromId) }}><div class="centerVerticalText"><img class="iconFeed" src="/img/IconMessage.png" />Message {{{ $feed->firstName }}}</div></div>
        <div class="clientFeedRemove" onClick='archiveFeed({{ $feed->feedId }},$(this),"Notification")'><div class="centerVerticalText bold center removeClientFeed margin0">X</div></div>  
    </div>
 <div class="clientFeedImage"><img src="/{{ Helper::image($feed->thumb) }}"/></div><div class="iconActivityFeed">{{ (Notifications::displayIcon($feed->type) != "" ? "<img src='/".Notifications::displayIcon($feed->type)."'/>" : "") }}</div> {{ $feed->message }} <span class='smalldate smalldateClientFeed '>{{ Helper::datetime($feed->date) }}</span></div>
@endif
@else
@if($feed->action == "message")
 <div class="clientFeedContainer {{ ($count % 2 == 1) ? "floatRight" : "" }}">
    <div class="clientFeedAction">
        <div class="clientFeedAction1 typeFeed" onClick='window.location="/Client/{{ $feed->userId }}/"'><div class="centerVerticalText  center">View Client</div></div>
        <div class="clientFeedMessage" {{ Notifications::respondToAction($feed->action,$feed->userId) }}><div class="centerVerticalText"><img class="iconFeed" src="/img/IconMessage.png" />Message {{{ $feed->firstName }}}</div></div>
        <div class="clientFeedRemove"  onClick='archiveFeed({{ $feed->feedId }},$(this),"Feed")'><div class="centerVerticalText bold center removeClientFeed margin0">X</div></div>  
    </div>
 <div class="clientFeedImage"><img src="/{{ Helper::image($feed->thumb) }}"/></div><div class="iconActivityFeed">{{ (Notifications::displayIcon($feed->type) != "" ? "<img src='/".Notifications::displayIcon($feed->type)."'/>" : "") }}</div> {{ $feed->message }} <span class='smalldate smalldateClientFeed'>{{ Helper::datetime($feed->date) }}</span></div>
@elseif($feed->action == "workout")
 <div class="clientFeedContainer {{ ($count % 2 == 1) ? "floatRight" : "" }}">
    <div class="clientFeedAction">
        <div class="clientFeedAction1 typeFeed" {{ Notifications::respondToAction($feed->action,$feed->userId,$feed->link) }}><div class="centerVerticalText  center">View Workout</div></div>
        <div class="clientFeedMessage" {{ Notifications::respondToAction($feed->action,$feed->userId) }}><div class="centerVerticalText"><img class="iconFeed" src="/img/IconMessage.png" />Message {{{ $feed->firstName }}}</div></div>
        <div class="clientFeedRemove"  onClick='archiveFeed({{ $feed->feedId }},$(this),"Feed")'><div class="centerVerticalText bold center removeClientFeed margin0">X</div></div>  
    </div>
 <div class="clientFeedImage"><img src="/{{ Helper::image($feed->thumb) }}"/></div><div class="iconActivityFeed">{{ (Notifications::displayIcon($feed->type) != "" ? "<img src='/".Notifications::displayIcon($feed->type)."'/>" : "") }}</div> {{ $feed->message }} <span class='smalldate smalldateClientFeed'>{{ Helper::datetime($feed->date) }}</span></div>
 @else
<div class="clientFeedContainer {{ ($count % 2 == 1) ? "floatRight" : "" }}">
    <div class="clientFeedAction">
        <div class="clientFeedAction1 typeFeed" onClick='window.location="/Client/{{ $feed->userId }}/"'><div class="centerVerticalText  center">View Client</div></div>
        <div class="clientFeedMessage" {{ Notifications::respondToAction($feed->action,$feed->userId) }}><div class="centerVerticalText"><img class="iconFeed" src="/img/IconMessage.png" />Message {{{ $feed->firstName }}}</div></div>
        <div class="clientFeedRemove" onClick='archiveFeed({{ $feed->feedId }},$(this),"Feed")'><div class="centerVerticalText bold center removeClientFeed margin0">X</div></div>  
    </div>
 <div class="clientFeedImage"><img src="/{{ Helper::image($feed->thumb) }}"/></div><div class="iconActivityFeed">{{ (Notifications::displayIcon($feed->type) != "" ? "<img src='/".Notifications::displayIcon($feed->type)."'/>" : "") }}</div> {{ $feed->message }} <span class='smalldate smalldateClientFeed '>{{ Helper::datetime($feed->date) }}</span></div>
@endif
@endif

 <?php $count++; ?>
@endforeach


 @else
    {{ Messages::showEmptyMessage("FeedEmpty",$permissions["self"]) }}
@endif


@if($total > count($feeds))
<div class="clearfix"></div>
    <div class="btmbuttonholder">
                <div class="clearfix"></div>
                    <span class="hrborder"></span>
                    <a href="javascript:void(0)" onclick="callWidget('w_feedClient_full',{{ count($feeds) }},{{ $clientId }},$(this))" class="greybtn">More Feeds</a>
                </div>
@endif

@else
    {{ Messages::showEmptyMessage("NoPermissions") }}
@endif

<script>


 function archiveFeed(id,obj,type){
       
         $.ajax(
            {
                url : "/widgets/clientsFeed/Archive/"+type+"/"+id,
                type: "GET",
                success:function(data, textStatus, jqXHR) 
                {
                    successMessage(data);
                    callWidget('w_feedClient_full',{{ count($feeds) }},{{ $clientId }}),
                    refreshWidgets();
                },
                error: function(jqXHR, textStatus, errorThrown) 
                {
                    errorMessage(jqXHR.responseText);
                },
            });
    }


</script>