@php
    use App\Http\Libraries\Helper;
@endphp
@if($permissions["view"])
    @if (count($clients) > 0)
            <?php $i = 0; ?>
        @foreach ($clients as $client)
            @if($client->user)
                <div id="empty_container<?php echo $i ?>" class="parentClient" onclick="showHover(this);">
                    <div class="client_main_container" id="workout_box<?php echo $i ?>">
                        <!-- This div is the hover effect on each workout in the workouts page-->
                        <div class="clientsHover">
                            <div class="clientsHover_status">
                                <img src="{{asset('assets/img/exitPopup.svg')}}" class="hoverExit"
                                     onclick="showHover(this)">
                                <!-- *****  Client Name   ****** -->
                                <!-- <div class="clientInfo">
                                        <p>{{ Lang::get("content.sharedWorkout1") }} {{ $client->numberOfWorkoutsSharedFromTrainerToClient(Auth::user()->id) }} {{ Lang::get("content.sharedWorkout2") }} {{ ( $client->user and $client->user->getCompleteName() != "" ) ? $client->user->getCompleteName() :  ($client->user ? $client->user->email : "Client") }}</p>
                                        <p>{{ Lang::get("content.last workout shared") }}: {{ $client->latestWorkoutSharedName(Auth::user()->id) }}</p>
                                    </div> -->
                            </div>

                            <div class="clientsHover_BTNsContainer">
                                <!-- ******  View Workouts  ***** -->
                                <div class="clientsHover_BTNContainer hvr-grow" onclick="event.cancelBubble = true;">
                                    <a href="javascript:void(0)" class="clientsHoverBTN"
                                       onclick="subscribeToClient(this,{{ $client->id }})">
                                        <div class="clientsHover_BTNimg">
                                                <?php
                                                    $userUpdate = false;
                                                    if ($client->subscribeClient == 1) {
                                                        $userUpdate = true;
                                                    } else {
                                                        $userUpdate = false;
                                                    }
                                                ?>
                                            <img clientid="{{ $client->id }}" class="selectable {{ ($userUpdate) ? "objectSelected" : "" }}" src="{{asset('assets')}}/img/{{ ($userUpdate) ? "selectedWorkoutIcon" : "selectableWorkoutIcon" }}.svg" {{ ($userUpdate) ? "selected='selected'" : "" }}>
                                        </div>
                                        <div class="clientsHover_BTNtxt">
                                            <span>{{ Lang::get("content.Notify Activity") }}</span>
                                        </div>
                                    </a>
                                </div>

                                <div class="clientsHover_BTNContainer hvr-grow">
                                    <a href="{{ Lang::get("routes./Client/").$client->id."/".(( $client->user and $client->user->getCompleteName() != "" ) ? $client->user->getCompleteName() :  ($client->user ? $client->user->email : "Client")) }}"
                                       class="clientsHoverBTN">
                                        <div class="clientsHover_BTNimg">
                                            <img src="{{asset('assets/img/svg/goIn.svg')}}">
                                        </div>
                                        <div class="clientsHover_BTNtxt">
                                            <span>{{ Lang::get("content.ViewClientProfile") }}</span>
                                        </div>
                                    </a>
                                </div>
                                <!-- *****  Delete   ***** -->
                                <div class="clientsHover_BTNContainer hvr-grow" onclick="event.cancelBubble = true;">
                                    <a href="javascript:void(0)"
                                       onClick="deleteClients({{ $client->id }},$(this)); arguments[0].stopPropagation(); return false;"
                                       class="clientsHoverBTN">
                                        <div class="clientsHover_BTNimg">
                                            <img src="{{asset('assets/img/deleteWorkoutIcon.svg')}}">
                                        </div>
                                        <div class="clientsHover_BTNtxt">
                                            <span>{{ Lang::get("content.EndRelationship") }}</span>
                                        </div>
                                    </a>
                                </div>
                                <!-- ******   Select   ***** -->
                                <div class="clientsHover_BTNContainer hvr-grow" onclick="event.cancelBubble = true;">
                                    <a href="javascript:void(0)" class="clientsHoverBTN"
                                       onclick="putAllWorkoutsOnSelectMode(this,event);event.stopPropagation(); ">
                                        <div class="clientsHover_BTNimg">
                                            <img clientid="{{ $client->id }}" class="selectable" src="{{asset('/assets/img/selectableWorkoutIcon.svg')}}">
                                        </div>
                                        <div class="clientsHover_BTNtxt">
                                            <span>{{ Lang::get("content.Select") }}</span>
                                        </div>
                                    </a>
                                </div>
                            </div>  <!-- End of clientsHover_BTNsContainer -->
                        </div> <!-- The end of the hover div -->

                        <!-- This is the delete Portion that needs to replace the div with the class workout_overlay -->

                        <!-- <div class="deleting">
                                <img src="/img/tw-gif.gif">
                                <div class="deletingMessage">{{ Lang::get("content.deleting") }}</div>
                            </div>  -->
                        <div class="client_overlay">
                            <div class="client_baseInfo">
                                <img alt="{{$client->name}}"
                                     src="/{{ Helper::image(($client->user) ? $client->user->thumb : "") }}"/>
                                <div class="clientName">
                                    <span class="firstName">{{ ( $client->user and $client->user->firstName != "" ) ? $client->user->firstName :  "" }}</span>
                                    <span class="lastName orEmail">{{ ( $client->user and $client->user->lastName != "" ) ? $client->user->lastName :  ($client->user ? $client->user->email : "Client") }}</span>
                                </div>
                            </div>
                            <div class="client_workout_info">
                                <div class="client_nbWorkouts">
                                    <span>{{ Lang::get("content.Created") }}: </span>
                                    <span>{{ Helper::date($client->created_at) }}</span>
                                </div>
                                <div class="client_nbWorkouts">
                                    <span>{{ Lang::get("content.nbWorkouts") }}: </span>
                                    <span>{{ $client->numberOfWorkoutsSharedFromTrainerToClient(Auth::user()->id) }}</span>
                                </div>
                                <div class="client_lastPerformance">
                                    <span>{{ Lang::get("content.lastPerformance") }}: </span>
                                        <?php $days = $client->lastWorkoutPerformedFromTrainer(Auth::user()->id); ?>
                                    <span>{{ ($days == -1) ? Lang::get("content.NotPerformed") : $days." ".Lang::get("content.daysAgo") }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- End empty_container -->
                <?php $i++; ?>
            @endif
        @endforeach
        <script>
            function subscribeToClient(object, id) {
                var subscribeToClient = $(object).find(".selectable").attr('selected');
                if (typeof subscribeToClient !== typeof undefined && subscribeToClient !== false) {
                    $(object).find(".selectable").removeAttr("selected");
                    $(object).find(".selectable").attr("src", "/assets/img/selectableWorkoutIcon.svg");
                    selectedItems.splice($.inArray($(object).find(".selectable").attr("workoutid"), selectedItems), 1);
                    subscribeToClient = false;
                } else {
                    var object2 = $(object).find(".selectable");
                    $(object).find(".selectable").attr('selected', "1");
                    $(object).find(".selectable").addClass('objectSelected');
                    object2.attr("src", "/assets/img/selectedWorkoutIcon.svg");
                    selectedItems.push($(object).find(".selectable").attr("workoutid"));
                    subscribeToClient = true;
                }

                console.log(subscribeToClient);

                $.ajax({
                    url: "{{{ Lang::get("routes./Clients/subscribe/toggle") }}}",
                    type: "POST",
                    data: {clientId: id, subscribeToClient: subscribeToClient},
                    success: function (data, textStatus, jqXHR) {
                        successMessage(data);
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        errorMessage(jqXHR.responseText);
                    }
                });
            }

            function deleteClients(clientid, obj) {
                if (confirm('{{ Lang::get("messages.Confirmation")  }}')) {

                    count = $(".objectSelected").length;

                    if (clientid !== null && clientid !== undefined) {
                        $(obj).closest(".loadingParent").find(".loading").show();
                        $.ajax({
                            url: "/widgets/clients/" + clientid,
                            type: "DELETE",

                            success: function (data, textStatus, jqXHR) {
                                successMessage(data);
                                widgetsToReload.push("w_clients");
                                refreshWidgets();
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                errorMessage(jqXHR.responseText);
                                $(".loading").hide();
                                if (!--count) {
                                    widgetsToReload.push("w_clients");
                                    refreshWidgets();
                                }
                            },
                        });
                    } else {
                        $(".objectSelected").each(function (i) {
                            $(this).closest(".loadingParent").find(".loading").show();
                            $.ajax({
                                url: "/widgets/clients/" + $(this).attr("clientid"),
                                type: "DELETE",

                                success: function (data, textStatus, jqXHR) {
                                    successMessage(data);
                                    if (!--count) {
                                        widgetsToReload.push("w_clients");
                                        refreshWidgets();
                                    }
                                },
                                error: function (jqXHR, textStatus, errorThrown) {
                                    errorMessage(jqXHR.responseText);
                                    $(".loading").hide();
                                    if (!--count) {
                                        widgetsToReload.push("w_clients");
                                        refreshWidgets();
                                    }
                                },
                            });
                        });
                    }
                }
            }

            function showHover(object) {
                $(object).find(".clientsHover").toggleClass("client_main_containerAlways");
            }

            function hideHover() {
                $(this).css("display", "none");
            }

        </script>
    @else
        @if(Auth::user()->getNumberOfWorkouts() < 2)
            <link href="https://fonts.googleapis.com/css?family=Indie+Flower" rel="stylesheet">
            <div class="noClients">
                <p>{{ Lang::get("content.onboardingClientOneWorkout") }}</p>
            </div>
        @else
            <link href="https://fonts.googleapis.com/css?family=Indie+Flower" rel="stylesheet">
            <div class="noClients">
                <p>{{ Lang::get("content.onboardingClientOneWorkout") }}</p>
            </div>
        @endif
    @endif

    @if($total > count($clients))
        <div class="btmbuttonholder">
            <span class="hrborder"></span>
            <a href="javascript:void(0)" onclick="callWidget('w_clients',{{ count($clients) }},null,$(this))"
               class="viewMore">{{ Lang::get("content.MoreClients") }}</a>
        </div>
    @endif
@else
    {!! Messages::showEmptyMessage("NoPermissions") !!}
@endif


<script>


</script>
