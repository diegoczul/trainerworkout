@php
    use App\Http\Libraries\Helper;
@endphp
@extends("layouts.trainer")
@section("header")
    {!! Helper::seo("employeeManagement",array("name"=>$exercise->name)) !!}
@endsection

@section("content")
    <section id="content" class="clearfix">
        <div class="wrapper">
            <div class="widgets fullwidthwidget shadow marginleftnone">
                <button class="bluebtn" onClick="window.history.back()" value="">{{ Lang::get("content.Back") }}</button>
            </div>
            <div class="widgets fullwidthwidget shadow marginleftnone">
                <h1>{{ Lang::get("content.Exercise") }} {{ $exercise->name }}</h1>
                <a class="editExerciseLink" href="{{ Lang::get("routes./EditExercise") }}/{{ $exercise->id }}">{{ Lang::get("content.edit") }}</a>
                <!-- add exercises -->
                <div class="add-exercises clearfix">
                    <!-- add exercises form -->
                    <div class="fltleft exercisesblockleft marginleftnone">
                        <input name="action" value="addexercise" type="hidden"/>
                        <p><strong> {{ Lang::get("content.Description") }}: </strong></p>
                        <p> {!! $exercise->description !!} </p>
                        <p><strong> {!! ($exercise->nameEngine) ? "".Lang::get("content.Othernamesforthisexercise").":</strong></p><p> ".$exercise->nameEngine : "" !!} </p>
                        <p><strong> {{ Lang::get("content.Bodygroup") }}: </strong></p>
                        <p> {!! ($exercise->bodygroup) ? $exercise->bodygroup->name : "" !!} </p>
                        <p><strong> {{ Lang::get("content.Listofequipmentneeded") }}: </strong></p>
                        <fieldset>
                            @foreach($exercise->equipments as $equip)
                                <p>{{ $equip->equipments->name }}</p>
                            @endforeach
                        </fieldset>

                        <p><strong> {{ Lang::get("content.Listofoptionalequipment") }}: </strong></p>
                        <fieldset>
                            @foreach($exercise->equipmentsOptional as $equip)
                                <p>{{ $equip->equipments->name }}</p>
                            @endforeach
                        </fieldset>

                        @if ($exercise->video != "")
                            <a href="/{{ $exercise->video}}" style="display:block;width:500px; height:400px" id="player"> </a>
                        @endif
                        @if($exercise->youtube != "")
{{--                            <iframe id="ytplayer" type="text/html" width="500" height="315" src="https://www.youtube.com/embed/{{$exercise->youtube }}"">?autoplay=1" frameborder="0"> </iframe>--}}
                        @endif
                    </div>

                    <div class="fltright exercisesblockright">
                        <div id="image1" class="showDelete showRotate">
                            <img class="image refreshImage" src="/{{ Helper::image($exercise->image) }}"/>
                            @if($exercise->editPermissions())
                                <a href="javascript:void(0)" onClick="rotateLeft({{ $exercise->id }},$(this),1); arguments[0].stopPropagation(); return false;" class="showRotateReceiver inlineIcon"><img src="{{asset('assets/img/rotate_left.png')}}"/></a>
                                <a href="javascript:void(0)" onClick="rotateRight({{ $exercise->id }},$(this),1); arguments[0].stopPropagation(); return false;" class="showRotateReceiver2 inlineIcon"><img src="{{asset('assets/img/rotate_right.png')}}"/></a>
                            @endif
                        </div>
                        <div id="image2" class="showDelete showRotate" style="margin-left: 20px">
                            <img class="image refreshImage" src="/{{ Helper::image($exercise->image2) }}"/>
                            @if($exercise->editPermissions())
                                <a href="javascript:void(0)" onClick="rotateLeft({{ $exercise->id }},$(this),2); arguments[0].stopPropagation(); return false;" class="showRotateReceiver inlineIcon"><img src="{{asset('assets/img/rotate_left.png')}}"/></a>
                                <a href="javascript:void(0)" onClick="rotateRight({{ $exercise->id }},$(this),2); arguments[0].stopPropagation(); return false;" class="showRotateReceiver2 inlineIcon"><img src="{{asset('assets/img/rotate_right.png')}}"/></a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section("scripts")
{{--    <script type="text/javascript" src="/fw/flowplayer/flowplayer-3.2.2.min.js"></script>--}}
{{--    <script>--}}

{{--        jQuery(document).ready(function () {--}}

{{--            flowplayer('player', '/fw/flowplayer/flowplayer-3.2.2.swf', {--}}
{{--                wmode: "transparent", clip: {--}}

{{--                    autoPlay: true,--}}

{{--                    autoBuffering: true--}}
{{--                }--}}

{{--            });--}}
{{--        });--}}


{{--    </script>--}}


    <script type="text/javascript">
        $(document).ready(function () {
            $("#m_exercises").addClass('active');
        });

        function rotateLeft(id, object, imageNumber) {
            $.ajax({
                url: "{{ Lang::get("routes./Exercises/Rotate/Left") }}",
                type: "POST",
                data: {id: id, imageNumber: imageNumber},
                success: function (data, textStatus, jqXHR) {
                    successMessage(data);
                    //callWidget("exercises_full");
                    refreshImages("refreshImage");
                    //location.reload();
                    //window.location = window.location;

                },
                error: function (jqXHR, textStatus, errorThrown) {
                    errorMessage(jqXHR.responseText);
                },
                statusCode: {
                    500: function () {
                        if (jqXHR.responseText != "") {
                            errorMessage(jqXHR.responseText);
                        } else {

                        }

                    }
                }
            });
        }

        function rotateRight(id, object, imageNumber) {
            $.ajax({
                url: "{{ Lang::get("routes./Exercises/Rotate/Right") }}",
                type: "POST",
                data: {id: id, imageNumber: imageNumber},
                success: function (data, textStatus, jqXHR) {
                    successMessage(data);
                    refreshImages("refreshImage");
                    //location.reload();
                    //window.location = window.location;
                    //callWidget("exercises_full");
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    errorMessage(jqXHR.responseText);
                },
                statusCode: {
                    500: function () {
                        if (jqXHR.responseText != "") {
                            errorMessage(jqXHR.responseText);
                        } else {

                        }

                    }
                }
            });
        }
    </script>
@endsection
