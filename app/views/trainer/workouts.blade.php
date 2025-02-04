@extends('layouts.trainer')


@section("header")
    {{ Helper::seo("trainerWorkouts") }}
@endsection


@section("headerExtra")
{{ HTML::style('fw/awesomplete-gh-pages/awesomplete.css'); }}
@endsection


@section('content')

<!-- content area starts here -->
<section id="content" class="workoutsPage">
    <div class="wrapper">

<!--   Membership message  -->
    @if(!Auth::user()->membershipValidButAtLimit())
        <div id="membershipMessage">
          <div class="upgradeMessage">
            <p>{{ Lang::get("content.upgrade1") }} <span>{{ Lang::get("content.upgrade2") }}  </span> <a href="{{ Lang::get("routes./MembershipManagement") }}">{{ Lang::get("content.upgrade3") }}</a>   
          </div>
        </div>
    @endif

<!-- Widget Begins -->

    @if( Auth::user()->getNumberOfWorkouts() > 5)
        <div class="widget searchWorkout">
            <h1>{{ Lang::get("content.SearchWorkouts") }}</h1>
            <p>{{ Lang::get("content.workouts/message1") }}</p>
        
            <div class="search_group">
                <input type="text" placeholder="{{ Lang::get("content.searchworkouts") }}" id="searchWorkouts" name="searchWorkouts" class="inputBox input_search_workout"  onkeyup="searchWorkouts(this.value)" />  

                <a class="searchButton" href="javascript:void(0)" onclick="searchWorkouts($('#searchWorkouts').val())">{{ Lang::get("content.Search") }}</a>

                <div class="hide-show_tags" id="showButton"><a href="javascript:void(0)" onClick="show()" >{{ Lang::get("content.workouts/message2") }}</a></div>   
                <div class="hide-show_tags" id="hideButton"><a href="javascript:void(0)" onClick="hide()">{{ Lang::get("content.workouts/message3") }}</a></div>
                <script> $('#showButton').show(); $('#hideButton').hide(); $("#w_tags").hide();</script>
            </div>
        </div>


        <div class="w_tags" id="w_tags">
        <!-- This is where the wokrouts go -->
        <!-- widgets/base/workouts -->
        </div>
    @endif

    <div class="widget">
        <div class="workouts">
            <div class="workoutHeader">
                <div class="workoutHeader_description">
                    <h1>{{ Lang::get("content.MyWorkouts") }}</h1>
                    <p>{{ Lang::get("content.Hereresidesalloftheworkoutsyouhavecreated") }}</p>
                </div>
                <div class="workouts_options workouts_options_down">
                    <a title="Archive" href="javascript:void(0)" class="moreOptionsButton" id="archiveWorkouts" onclick="archiveWorkouts()">
                        <svg width="20" height="16" viewBox="0 0 20 16" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <title>
                                archiveMore
                            </title>
                            <defs>
                                <rect id="a" x="1" y="4" width="18" height="12" rx="1.231"/>
                                <mask id="c" x="0" y="0" width="18" height="12" fill="#fff">
                                    <use xlink:href="#a"/>
                                </mask>
                                <rect id="b" y=".8" width="20" height="4" rx="1.231"/>
                                <mask id="d" x="0" y="0" width="20" height="4" fill="#fff">
                                    <use xlink:href="#b"/>
                                </mask>
                            </defs>
                            <g stroke="#369AD8" fill="none" fill-rule="evenodd">
                                <use mask="url(#c)" stroke-width="1.6" xlink:href="#a"/>
                                <use mask="url(#d)" stroke-width="1.6" xlink:href="#b"/>
                                <path d="M6.4 7.4h7.2" stroke-width=".8" stroke-linecap="round"/>
                            </g>
                        </svg>
                    </a>

                    <a title="Delete" href="javascript:void(0)" class="moreOptionsButton" id="deleteWorkouts" onClick="deleteWorkouts()">
                        <svg width="13" height="18" viewBox="0 0 13 18" xmlns="https://www.w3.org/2000/svg">
                            <title>
                                Delete Icon
                            </title>
                            <g stroke-width=".5" stroke="#369AD8" fill="none" fill-rule="evenodd">
                                <g>
                                    <rect y="1.702" width="13" height="1.702" rx=".413"/></rect>
                                    <rect x="4.875" width="3.25" height="1.276" rx=".413"/></rect>
                                    <path d="M1.22 3.523c0-.23.182-.414.413-.414h9.734c.23 0 .414.187.414.413V16.35c0 .91-.74 1.65-1.65 1.65H2.87a1.65 1.65 0 0 1-1.65-1.65V3.523z"/></path>
                                </g>
                                <g stroke-linecap="square">
                                    <path d="M9.14 6.3v8.51M6.5 6.3v8.51M3.86 6.3v8.51"/></path>
                                </g>
                            </g>
                        </svg>
                    </a>
                    <a title="Share" href="javascript:void(0)" class="moreOptionsButton" id="shareWorkouts" onClick="lightBox();">
                        <svg width="11" height="20" viewBox="0 0 11 20" xmlns="https://www.w3.org/2000/svg">
                            <title>
                                Share
                            </title>
                            <path d="M7.136 7.43C9.386 7.43 10 8 10 8.71v9.007C10 18.427 9.45 19 8.772 19H2.228C1.55 19 1 18.426 1 17.717V8.71c0-.707.818-1.28 2.66-1.28m-2.25-3L5.5 1l4.295 3.43M5.5 1.7v11.9" stroke="#369AD8" stroke-width=".9" fill="none" fill-rule="evenodd"/>
                        </svg>
                    </a>
                    <div title="Print" id="printWorkouts">
                        <a href="javascript:void(0)" class="moreOptionsButton">
                            <svg width="20" height="20" viewBox="0 0 20 20" xmlns="https://www.w3.org/2000/svg">
                                <title>
                                    print
                                </title>
                                <g stroke="#369AD8" fill="none" fill-rule="evenodd">
                                    <rect x="3.2" width="14" height="10" rx="1.2"/>
                                    <rect fill="#F2F2F2" y="4.8" width="20" height="10" rx="1.2"/>
                                    <circle stroke-width=".5" fill="#FFF" cx="14.2" cy="7.8" r=".8"/>
                                    <circle stroke-width=".5" fill="#FFF" cx="17" cy="7.8" r=".8"/>
                                    <g transform="translate(5.6 10.4)">
                                        <rect fill="#F2F2F2" width="8.8" height="9.6" rx="1.14"/></rect>
                                        <path d="M2.167 2.5h4.678M2.167 4.5h4.678M2.167 6.5h4.678" stroke-width=".5" stroke-linecap="square"/></path>
                                    </g>
                                </g>
                            </svg>
                        </a>
                        <div class="printMenu" id="printOptionMenu">

                            <ul>
                                <li id="printJpeg" onclick="downloadJPEG(this)">
                                    <svg width="20" height="27" viewBox="0 0 20 27" xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink">
                                        <title>
                                            Jpeg Icon hover
                                        </title>
                                        <defs>
                                            <path id="a" d="M0 .266h19.948V27H0"/></path>
                                            <path id="c" d="M0 .266h19.948V27H0V.266z"/></path>
                                        </defs>
                                        <g fill="none" fill-rule="evenodd">
                                            <mask id="b" fill="#fff">
                                                <use xlink:href="#a"/></use>
                                            </mask>
                                            <path d="M18.882 27H1.122a1.07 1.07 0 0 1-1.065-1.066V1.332C.057.746.537.266 1.122.266h14.013L19.7 4.83l.248 21.104A1.07 1.07 0 0 1 18.882 27" fill="#FFF" mask="url(#b)"/></path>
                                            <mask id="d" fill="#fff">
                                                <use xlink:href="#c"/></use>
                                            </mask>
                                            <g class="svgLines">
                                                <path d="M18.738 26.5H1.148a.656.656 0 0 1-.648-.662V1.435c0-.37.29-.67.648-.67h13.68V5.08c0 .138.113.25.25.25h4.313v20.51a.658.658 0 0 1-.65.662zm.458-21.81c.052.05.087.095.116.138h-3.984V.878c.027.022.055.05.09.084l3.778 3.728zm.35-.356L15.77.604c-.17-.166-.344-.338-.69-.338H1.147A1.16 1.16 0 0 0 0 1.434v24.403C0 26.477.515 27 1.148 27h17.59a1.16 1.16 0 0 0 1.154-1.163V5.077c0-.355-.174-.574-.345-.743z" fill="#369AD8" mask="url(#d)"/></path>
                                                <path d="M16.848 19.665H6.165l4.08-5.277 1.643 2.1a.24.24 0 0 0 .206.096.248.248 0 0 0 .198-.11l2.17-3.232 2.648 3.605v2.58c0 .132-.118.238-.262.238M2.87 19.428v-9.29c0-.128.117-.233.262-.233h13.716c.144 0 .26.105.26.234V16l-2.454-3.344a.25.25 0 0 0-.41.008l-2.175 3.24-1.63-2.082a.25.25 0 0 0-.396 0l-4.51 5.838H3.132c-.146 0-.264-.105-.264-.236m13.98-10.02H3.132c-.42 0-.763.33-.763.733v9.287a.75.75 0 0 0 .76.737h13.717c.42 0 .76-.33.76-.737v-9.29c.002-.404-.34-.733-.76-.733" fill="#369AD8"/></path>
                                                <path d="M6.326 12.425c.62 0 1.125.473 1.125 1.053S6.95 14.53 6.328 14.53c-.62 0-1.124-.472-1.124-1.052 0-.58.505-1.053 1.124-1.053m0 2.604c.896 0 1.625-.698 1.625-1.553 0-.856-.728-1.553-1.624-1.553-.895 0-1.624.697-1.624 1.553s.73 1.55 1.624 1.55" fill="#369AD8"/></path>
                                            </g>
                                        </g>
                                    </svg>
                                    <p>JPEG {{ Lang::get("content.view") }}</p>
                                </li>
                                <li id="printPdf" onclick="downloadPDF(this)">
                                    <svg width="20" height="27" viewBox="0 0 20 27" xmlns="https://www.w3.org/2000/svg">
                                        <title>
                                            grid
                                        </title>
                                        <g stroke="#369AD8" fill="none" fill-rule="evenodd">
                                            <path d="M0 .993C0 .445.454 0 1.008 0H15.23c.28 0 .65.162.84.382l3.595 4.103c.185.21.335.6.335.886v20.62c0 .558-.455 1.01-.992 1.01H.992A.993.993 0 0 1 0 26.007V.993z" stroke-width=".5" fill="#FFF"/>
                                            <path d="M16.848 9.405H3.132c-.42 0-.763.33-.763.734v9.288a.75.75 0 0 0 .762.737h13.716c.42 0 .76-.33.76-.737v-9.29c.002-.404-.34-.733-.76-.733M2.5 18.5h15m-15-2h15m-15-2h15m-15-2h15m-3-2.955v10.91m-3-10.91v10.91m-6-10.91v10.91m3-10.91v10.91"/>
                                            <path d="M15.4.5v4a.5.5 0 0 0 .51.5h3.64" stroke-width=".5" stroke-linecap="square"/>
                                        </g>
                                    </svg>
                                    <p>PDF {{ Lang::get("content.grid") }}</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                    {{-- <a href="javascript:void(0)" class="lessOptionsWorkoutButton" id="moreOptions" onclick="showMore()"></a> --}}
                    
                    <a href="{{ Lang::get("routes./Trainer/CreateWorkout") }}" class="addElementButton" id="createWorkout">{{ Lang::get("content.CreateNewWorkout") }}</a>
                </div>
            </div>
            <div id="w_workouts">
                <!-- /widgets/base/workouts.blade.php -->
            </div>                
        </div>
    </div>
</section>


@include('popups.shareWorkout')

<iframe id="downloader" style="display:none;"></iframe>
@endsection

@section('scripts')
<script>callWidget("w_workouts");</script>
<script>callWidget("w_tags");</script>


<script>

var archive = "false";
// $(document).ready(function(){ $("#m_workouts").addClass('active'); });


// $(document).ready(function () {
// workoutOptions();
// });

// $(window).scroll(function () {
// workoutOptions();
// });

// var $workouts_options = $(".workouts_options");
// var $offset = $(".workoutHeader_description").offset().top;
// $offset = $offset - 300;

// function workoutOptions() {
// if ($(window).scrollTop() > $offset) {
//     $workouts_options.addClass("workouts_options_down");
// } else {
//     $workouts_options.removeClass("workouts_options_down");
// }
// }

function viewArchivedWorkouts(){
    $("#viewUnArchivedWorkouts").css('display','inline-block');
    $("#viewArchivedWorkouts").hide();
    callWidget("w_workouts",null,null,null,{archive:'true'});
    archive = "true";
}

function viewUnArchivedWorkouts(){
    $("#viewUnArchivedWorkouts").hide();
    $("#viewArchivedWorkouts").css("display","inline-block");
    callWidget("w_workouts",null,null,null,null);
    archive = "false";
}

function showMenu(){
$("#printOptionMenu").slideDown(200);
}


function hide(){
$('#hideButton').hide();
$('#showButton').show();
$(".tag_menu").slideUp();
}


function show(){
$('#showButton').hide();
$('#hideButton').show();
$(".tag_menu").slideDown();
}

function downloadJPEG(obj){

    if(debug) console.log("PrintWorkoutJPEG");


    $(obj).closest(".loadingParent").find(".loading").show();
    //window.location.assign("{{ Lang::get("routes./Workouts/createUserDownload") }}/"+selectedItems.join(",")+"/JPEG");
    var url = "{{ Lang::get("routes./Workouts/createUserDownload") }}/"+selectedItems.join(",")+"/JPEG";
    triggerAjaxFileDownload(url);
    widgetsToReload.push("w_workouts");
    refreshWidgets();
    showLess();
}


function downloadPDF(obj){

    if(debug) console.log("PrintWorkoutPDF");


    $(obj).closest(".loadingParent").find(".loading").show();
    //window.location.assign("{{ Lang::get("routes./Workouts/createUserDownload") }}/"+selectedItems.join(",")+"/PDF");
    var url = "{{ Lang::get("routes./Workouts/createUserDownload") }}/"+selectedItems.join(",")+"/PDF";
    triggerAjaxFileDownload(url);
    widgetsToReload.push("w_workouts");
    refreshWidgets();
    showLess();
}

function downloadBoth(obj){
    if(debug) console.log("PrintWorkoutBoth");


    $(obj).closest(".loadingParent").find(".loading").show();
    //window.location.assign("{{ Lang::get("routes./Workouts/createUserDownload") }}/"+selectedItems.join(",")+"/JPEG/PDF");
    //document.getElementById('downloader').src = "{{ Lang::get("routes./Workouts/createUserDownload") }}/"+selectedItems.join(",")+"/JPEG/PDF";
    var url = "{{ Lang::get("routes./Workouts/createUserDownload") }}/"+selectedItems.join(",")+"/JPEG/PDF";
    triggerAjaxFileDownload(url);
    
    widgetsToReload.push("w_workouts");
    refreshWidgets();
    showLess(); 
}


function searchWorkouts(value){
// Show loader
showTopLoader();
typewatchGlobal(function() {
    var preload;
        $("#search_loader").show();
        $.ajax({
            'async': true,
            'url': '{{ Lang::get("routes./Workouts/Search") }}',
            'type': 'post',
            'data': {
                search: $("#searchWorkouts").val(),
                archive: archive,
            },
            'success': function(data) {
                $("#w_workouts").html(data);
                $("#search_loader").hide();
                hideTopLoader();
            }
        });
}, 300);
}




// function shareToEmail(el){

//     var div = el.closest('.sharewokoutform');
//     var email = div.find('input[name=toemail]').val();

//     //if (/^[A-z0-9._-]+@[A-z0-9-]+(\.[A-z]{2,}){1,2}$/.test(email)){
//         var preLoad = showLoadWithElement(el, 40, 'center');
//         $.ajax(
//             {
//                 url :"{{ Lang::get("routes./Workout/ShareByEmail") }}",
//                 type: "POST",
//                 data: { workoutId:div.attr('workout'),email : email,comments : $("#personalizedTxt").val() },
//                 success:function(data, textStatus, jqXHR) 
//                 {
                    
//                     parent.successMessage(data);
//                     //parent.$.fancybox.close();
//                     hideLoadWithElement(preLoad);
//                     hidelightboxWithoutE();
//                 },
//                 error: function(jqXHR, textStatus, errorThrown) 
//                 {
//                     parent.errorMessage(jqXHR.responseText);
//                     hideLoadWithElement(preLoad);
//                     hidelightboxWithoutE();
//                     $(".workoutsHover").each(function(){
//                         $(this).hide();
//                         $(this).removeAttr("style");
//                         showLess();
//                         $(this).removeClass("workout_main_containerAlways");
//                     });

                    
                    
//                 },
//                 statusCode: {
//                     400: function(jqXHR) {
//                         if(jqXHR.responseText != ""){
//                             errorMessage(jqXHR.responseText);
//                         }else {
                            
//                         }
                        
//                     }
//                 }
//             });
//     //}

// }

$(document).ready(function(){
    if($(".sharewokoutform").attr("workout") == "" && parent.selectedItems != ""){
        $(".sharewokoutform").attr("workout",parent.selectedItems);
    }
});

$(document).ready(function(){
    $(".menu_workouts").addClass("selected");
});

</script>
@endsection

