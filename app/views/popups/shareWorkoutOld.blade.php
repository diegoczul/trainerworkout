{{ HTML::style('css/innerStyles.css') }}
{{ HTML::style('fw/jquery-ui-1.11.1.custom/jquery-ui.min.css'); }}
{{ HTML::style('fw/chosen_v1/chosen.css'); }}
{{ HTML::style('fw/autocomplete/foxycomplete.css'); }}
    
<!-- @extends('layouts.popup') -->

@section('content')
<div class="popup_container sharewokoutform" workout='{{ $workout->id }}'>
        <div class="header">
            <div class="upper_header"><h1>Share {{{ $workout->name }}}</h1></div>
            <div class="lower_header">
                <ul>
                    <li id="nav_search"  onclick="showSearchTab()" class="active"><a href="javascript:void(0)"><i class="fa fa-search"></i>search</a></li>
                    <li id="nav_email" onclick="showEmailTab()"><a href="javascript:void(0)"><i class="fa fa-envelope"></i>email</a></li>
                    <li id="nav_url" onclick="showURLTab()"><a href="javascript:void(0)"><i class="fa fa-link"></i>url</a></li>
                </ul>
            </div>
        </div>

        <div class="share_content">
                <!-- TAB: SHARE -->
                <section  id="tab_search" class="share_search">
                    <div class="input_container">
                        <div class="input_label">Search friend or client by name</div>
                        <input type="text" name='searchMessage' id="searchMessage" autocomplete="off">
                        <input type="hidden" id="to" name="to">
                        <input type="hidden" id="friend" name="friend">
                        <?php /*  <a href="" class="button search">Search</a> */ ?>
                        <div class="input_sub_label" id="results"></div>
                    </div>
                    <div class="button_container">
                        <a href="javascript:void(0)" onclick="shareToMyFriend($(this));" class="button send">Send</a>
                    </div>
                </section>

                <!-- TAB: EMAIL -->
                <section id="tab_email" class="share_email">
                    <div class="input_container">
                        <div class="input_label">Enter the emails below</div>
                        <input type="text" name="toemail" placeholder="e-mail of your friend to share a workout">
                        <div class="input_sub_label">You can add multiple email by separating them with a coma</div>
                    </div>
                    <div class="button_container">
                        <a href="javascript:void(0)" onclick="shareToEmail($(this));" class="button send">Send</a>
                    </div>
                </section>

                <!-- TAB: URL -->
                <section id="tab_url" class="share_url">
                    <div class="input_container">
                        <div class="input_label">Give this link to your friends</div>
                        <input type="text" name="" id="copy_value" placeholder="{{ URL::secure('/Share/Workout/'.Sharings::previewSharing(Auth::user()->id, NULL, $workout->id, "Workout").'/'); }}" disabled="disabled">
                        <div class="input_sub_label">Whomever has this link will be able to add this workout</div>
                        <a href="#" id="copy_link" class="button copy">Copy Link</a>
                    </div>
                    <div class="button_container">
                        <a href="javascript:void(0)" onclick="parent.$.fancybox.close();" class="button send">Close</a>
                    </div>
                </section>
        </div>
   </div>




@endsection

@section("scripts")
<script type="text/javascript" src="/fw/clipboard/ZeroClipboard.js"></script>
<script type="text/javascript">
var link = "{{ URL::secure('/Share/Workout/'.Sharings::previewSharing(Auth::user()->id, NULL, $workout->id, "Workout").'/'); }}";
delete clip;
var clip = null;

var copy_link = $("a#copy_link");
$(document).ready(function(){
    ZeroClipboard.setDefaults( { moviePath: '/fw/clipboard/ZeroClipboard.swf' } );
    clip = new ZeroClipboard();
    clip.copy_link = copy_link;
    clip.on('load', function(){
        clip.setText(link);
        clip.glue($('a#copy_link'));
    });
    clip.setText(link);
    clip.glue($('a#copy_link'));
    clip.on('dataRequested', function(client, args){
        clip.setText(link);
         clip.copy_link.addClass("clicked");
         clip.copy_link.text("Link Copied");
    });
});
$(function() {
        function log( message ) {
            $( "<div>" ).text( message ).prependTo( "#log" );
            $( "#log" ).scrollTop( 0 );
        }

        $( "#searchMessage" ).autocomplete({
                source: "/widgets/friends/suggest",
                minLength: 2,
                response: function(event,ui){
                     if (ui.content.length === 0) {
                        $("#results").text("No results found");
                    }
                },
                select: function( event, ui ) {
                    $( "#searchMessage" ).val( ui.item.firstName+" "+ui.item.lastName );

                    $( "#friend" ).val( ui.item.followingId );
                    return false;
                }
        }) .autocomplete( "instance" )._renderItem = function( ul, item ) {
            $("#results").text("");
            var image = "/{{ Helper::image('') }}";
            if(item.thumb != null){
                image = "/"+item.thumb;
            }
            return $( "<li style='cursor:pointer' class='clientinfo marginleftnone clearfix'>" )
            .append( "<a class='image fltleft'><img width:45; height:45; src='"+image+"'/></a><div class='detail'>" + item.firstName + "<br>" + item.lastName + "</div>")
            .appendTo( ul );
        };
    });


function test(){
    alert();
}

function shareToMyFriend(el){
    if ($("#friend").val() != ''){
        var preLoad = showLoadWithElement(el, 0, 'center');
        $.ajax(
            {
                url :"/Workout/ShareByUser",
                type: "POST",
                data: { workoutId:{{ $workout->id }},user :$("#friend").val()},
                success:function(data, textStatus, jqXHR) 
                {
                    parent.successMessage(data);
                    parent.$.fancybox.close();
                    hideLoadWithElement(preLoad);
                },
                error: function(jqXHR, textStatus, errorThrown) 
                {
                    hideLoadWithElement(preLoad);
                    errorMessage(jqXHR.responseText);
                    alert(jqXHR.responseText);
                }
            });
    } else
        error_mess('Please, choose a friend');
    }

function shareForAll(el){
    var div = el.closest('.sharewokoutform');
    var preLoad = showLoadWithElement(el, 0, 'center');
    $.ajax(
            {
                url :"/Workout/ShareByLink",
                type: "POST",
                data: { workoutId:{{ $workout->id }}},
                success:function(data, textStatus, jqXHR) 
                {
                    
                    parent.successMessage(data);
                    parent.$.fancybox.close();
                    hideLoadWithElement(preLoad);
                },
                error: function(jqXHR, textStatus, errorThrown) 
                {
                    hideLoadWithElement(preLoad);
                    errorMessage(jqXHR.responseText);
                    alert(jqXHR.responseText);
                }
            });
}

function shareToEmail(el){

    var div = el.closest('.sharewokoutform');
    var email = div.find('input[name=toemail]').val();

    if (/^[A-z0-9._-]+@[A-z0-9-]+(\.[A-z]{2,}){1,2}$/.test(email)){
        var preLoad = showLoadWithElement(el, 0, 'center');
        $.ajax(
            {
                url :"/Workout/ShareByEmail",
                type: "POST",
                data: { workoutId:div.attr('workout'),email : email},
                success:function(data, textStatus, jqXHR) 
                {
                    
                    parent.successMessage(data);
                    parent.$.fancybox.close();
                    hideLoadWithElement(preLoad);
                },
                error: function(jqXHR, textStatus, errorThrown) 
                {
                    hideLoadWithElement(preLoad);
                    errorMessage(jqXHR.responseText);
                }
            });
    }

}

// Hide Tabs
$("#tab_email").hide();
$("#tab_url").hide();
// Blur Navigtion
$("#nav_email").css("opacity","0.7");
$("#nav_url").css("opacity","0.7");


var currentTab = "search";

function showSearchTab(){
    $("#nav_"+currentTab).removeClass("active");
    $("#nav_search").addClass("active");
    $("#nav_search").css("opacity","1");

    $("#nav_email").css("opacity","0.7");
    $("#nav_url").css("opacity","0.7");

    $("#tab_"+currentTab).hide();
    $("#tab_search").fadeIn();
    currentTab = "search";
}

function showEmailTab(){
    $("#nav_"+currentTab).removeClass("active");
    $("#nav_email").addClass("active");
    $("#nav_email").css("opacity","1");


    $("#nav_search").css("opacity","0.7");
    $("#nav_url").css("opacity","0.7");


    $("#tab_"+currentTab).hide(); 
    $("#tab_email").fadeIn();
    currentTab = "email";
}

function showURLTab(){
    $("#nav_"+currentTab).removeClass("active");
    $("#nav_url").addClass("active");
    $("#nav_url").css("opacity","1");

    $("#nav_search").css("opacity","0.7");
    $("#nav_email").css("opacity","0.7");

    $("#tab_"+currentTab).hide();
    $("#tab_url").fadeIn();
    currentTab = "url";
}

$("a#copy_link").click(function(){
    $("a#copy_link").addClass("clicked");
    $("a#copy_link").text("Link Copied");
});

</script>
@endsection