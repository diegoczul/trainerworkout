@use('App\Http\Libraries\Helper')

@extends("layouts.trainer")

@section("header")
    {!! Helper::seo("exercises") !!}
@endsection

@section("content")
    <section id="content" class="clearfix">

        @if(Auth::user()->getNumberOfExercises() > 6)
            <div class="searchContainer">
                <div class="searchWrapper exerciseSearch">
                    <h4>{{Lang::get("content.YouExerciselibrary")}}</h4>
                    <p>{{Lang::get("content.YourPersonalExercises")}}</p>
                    <div class="searchField">
                        <input id="exercise_search" name="exercise_search" placeholder="{{ Lang::get("content.searchPlaceholder") }}">
                        <button onClick="searchExercise()">{{ Lang::get("content.Search") }}</button>
                    </div>
                    <div class="tagContainer">
                        <ul class="tabs">
                            <li class="tab" onclick="openTab('tags-exercise')">{{ Lang::get("content.Exercise Type")}}</li>
                            <li class="tab" onclick="openTab('tags-muscle')">{{ Lang::get("content.Muscle Group")}}</li>
                            <li class="tab" onclick="openTab('tags-equipment')">{{ Lang::get("content.Equipment")}}</li>
                        </ul>

                        <div id="tags-exercise" class="tabContent">
                            @foreach($exercisesTypes as $exercisesType)
                                <div class="searchTag" onClick='addToFilter("{{ $exercisesType->name }}","type",{{ $exercisesType->id }})'>{{{ $exercisesType->name }}}</div>
                            @endforeach
                        </div>
                        <div id="tags-muscle" class="tabContent">
                            @foreach($bodyGroups as $bodyGroup)
                                <div class="searchTag" onClick='addToFilter("{{ $bodyGroup->name }}","bodygroup",{{ $bodyGroup->id }})'>{{{ $bodyGroup->name }}}</div>
                            @endforeach
                        </div>
                        <div id="tags-equipment" class="tabContent">
                            @foreach($equipments as $equipment)
                                <div class="searchTag" onClick='addToFilter("{{ $equipment->name }}","equipment",{{ $equipment->id }})'>{{{ $equipment->name }}}</div>
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>
        @else
            <div class="searchContainer exerciseSearch">
                <div class="searchWrapper">
                    <h4>{{Lang::get("content.YouExerciselibrary")}}</h4>
                </div>
            </div>
        @endif

        <div class="searchWrapper">
            <div class="exercises">
                <!-- <a class="addExercise" href="{{ Lang::get("routes./Exercises/addExercise") }}"><i class="fa fa-plus"></i>{{ Lang::get("content.AddExercises") }}</a> -->
                <button class="addExercise" onclick="addExercise()"><i class="fa fa-plus"></i>{{ Lang::get("content.AddExercises") }}</button>

                <!-- exercises starts here -->
                <div class="exercises_list">
                    <div id="w_exercises_full" class="searchResultsContainer widgetList">
                        <!-- /view/widgets/full/exercises -->
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="exerciseOverlay overlayKillParent">
        <div class="overlayKillChild"></div>
        <div class="addExerciseContainer overlayKillContent">
            <svg class="c-menu__close" width="12" height="12" viewBox="0 0 12 12" xmlns="http://www.w3.org/2000/svg">
                <title>
                    closeDark
                </title>
                <path d="M8.138 6L11.71 2.43c.383-.382.384-1.02-.01-1.413L10.988.3A.996.996 0 0 0 9.574.29L6 3.864 2.426.29A.996.996 0 0 0 1.013.3L.3 1.016a1.005 1.005 0 0 0-.01 1.413L3.86 6 .29 9.576c-.383.38-.384 1.02.01 1.413l.713.714a.996.996 0 0 0 1.413.01L6 8.14l3.574 3.573a.996.996 0 0 0 1.413-.01l.714-.715a1.005 1.005 0 0 0 .01-1.413L8.14 6z" fill="#2C3E50" fill-rule="evenodd"/>
            </svg>
            <div class="addexercise">
                <h1>{{Lang::get("content.AddingAnExercise")}}</h1>
                <div class="addexercise-indicatorContainer">
                    <div class="indicatorSubContainer">
                        <label>step</label><label class="step labelStepOne"></label><label>/2</label>
                    </div>
                    <div class="indicatorSubContainer">
                        <div class="switcher" onclick="addExerciseSwtichStep()">
                            <div class="indicator stepOne"></div>
                        </div>
                    </div>
                </div>
                {{ Form::open(array('url' => Lang::get("routes./Exercises/AddExercise"), 'enctype' => 'multipart/form-data', "files"=>true, 'name' => 'exercise')) }}
                <p class="required">*{{ Lang::get("content.required") }}</p>
                <div class="addexercise-stepContainer">
                    <div class="addexercise-step active">
                        <label for="">{{ Lang::get("content.Exercisename") }}*</label>
                        <input type="text" name="name" placeholder="bench press, bicep curl, etc..." value="{{ request()->old('name') }}" tabindex="1">

                        <label for="">{{ Lang::get("content.Otherexercisenames") }}</label>
                        <input type="text" name="nameEngine" placeholder="bench press, bicep curl, etc..." value="{{ request()->old('nameEngine') }}" tabindex="2">

                        <input type="hidden" name="id"/>
                        <input name="action" value="addexercise" type="hidden"/>

                        <fieldset class="exerciseDescription">
                            <label for="description">{{ Lang::get("content.Exercisedescription") }}</label>
                            <textarea id="description" class="addexdescription" name="description" maxlength="500" placeholder="{{ Lang::get("content.Exercisedescription") }}">{{ request()->old("description") }}</textarea>
                            <div id="textarea_counter"></div>
                        </fieldset>

                        <div class="equipment">
                            <label for="muscleGroup" tabindex="3">{{ Lang::get("content.Musclegroups") }}*</label>
                            {{ FORM::select("bodygroup", [""=>Lang::get("content.MuscleGroupChoose")] + $bodygroups->toArray(), request()->old("bodygroup"), ["id"=>"muscleGroup", "data-placeholder"=> Lang::get("content.selectequipment"), "class"=>"select2-select w-100"]) }}
                        </div>

                        <div class="submit">
                            <label class="next" onclick="addExerciseSwtichStep()">Next step <img src="{{asset('assets/img/svg/arrowNext.svg')}}"></label>
                        </div>

                    </div>

                    <div class="addexercise-step">

                        <fieldset class="exerice_images">

                            <div class="col image">
                                <label for="img1" onclick="updateName(this)">{{ Lang::get("content.Uploadpicture") }}1</label>
                                <input id="img1" onclick="updateName(this)" type="file" name="image1" placeholder="" class="imageInput" accept="image/png, image/gif, image/jpeg">
                                <label for="img1" onclick="updateName(this)" class="button">{{ Lang::get("content.selecteimage")}} 1</label>
                            </div>

                            <div class="col image">
                                <label for="img2" onclick="updateName(this)">{{ Lang::get("content.Uploadpicture") }}2</label>
                                <input id="img2" onclick="updateName(this)" type="file" name="image2" placeholder="" class="imageInput" accept="image/png, image/gif, image/jpeg">
                                <label for="img2" onclick="updateName(this)" class="button">{{ Lang::get("content.selecteimage")}} 2</label>
                            </div>

                        </fieldset>

                        <fieldset class="exercise_video">
                            <div class="video uploadVideo image" style="margin-right: 10px">
                                <label for="video1" onclick="updateName(this)">{{ Lang::get("content.Uploadavideo") }} ({{ Lang::get("content.Max Size") }}: 256mb)</label>
                                <input id="video1" onclick="updateName(this)" type="file" name="video" placeholder="" class="imageInput" accept="video/mp4, video/mkv">
                                <label for="video1" onclick="updateName(this)" class="button">{{ Lang::get("content.selectVideo")}}</label>
                            </div>

                            <div class="video youtubeVideo">
                                <label for="img2">{{ Lang::get("content.Linktoyourvideoofthisexercise") }}</label>
                                <input type="text" placeholder="http://www.youtube.com/watch?" name="youtube">
                            </div>
                        </fieldset>

                        <fieldset class="execise_equipments">

                            <div class="equipment" style="margin-right: 10px">
                                <label for="equipment">{{ Lang::get("content.Listtheequipmentneeded") }}</label>
                                {{ Form::select("equipment[]",$equipmentsList,"",array("id"=>"equipment", "data-placeholder"=> Lang::get("content.selectequipment"), "class"=>"chosen-select", "multiple")) }}
                            </div>

                            <div class="equipment">
                                <label for="equipmentOptional">{{ Lang::get("content.Listoptionalequipment") }}</label>
                                {{ Form::select("equipmentOptional[]",$equipmentsList,"",array("id"=>"equipmentOptional", "data-placeholder"=> Lang::get("content.selectequipment"), "class"=>"chosen-select","multiple")) }}
                            </div>

                        </fieldset>
                        <div class="previous">
                            <span onclick="addExerciseSwtichStep()"><div class="previousButton"><img src="{{asset('assets/img/svg/arrowPrevious.svg')}}"></div> previous step</span>
                        </div>
                        <div class="submit">
                            <button type="submit" class="saveex" onClick="lightBoxLoadingTwSpinner();">{{ Lang::get("content.CreateExercise") }}</button>
                        </div>
                    </div>
                </div>
                {{ Form::close()}}
            </div>
        </div>
    </div>

@endsection

@section("scripts")
    <script type="text/javascript">
        onLoad = "self.focus();document.exercise.name.focus()"
    </script>

    <script>callWidget("w_exercises_full");</script>


    <script type="text/javascript">
        @if($errors->any())
            addExercise();
        @endif

        $(document).ready(function () {
            var text_max = 500;
            $('#textarea_counter').html(text_max + ' characters remaining');

            $('#description').keyup(function () {
                var text_length = $('#description').val().length;
                var text_remaining = text_max - text_length;

                $('#textarea_counter').html(text_remaining + ' characters remaining');
            });
        });


        $(document).ready(function () {
            $(".chosen-container-multi").css("width", "100%")
        });
        $(document).ready(function () {
            $("#m_exercises").addClass('active');
        });
        $(document).ready(function () {
            if ($("#w_exercises_full").find(".emptyMessage")) {
                // $(".exercises").css("text-align", "center").css("margin-top", "50px");
            }
        });

        // open exercsie adding
        function addExercise() {
            $(".overlayKillParent").addClass("overlayKillParent-active");
            $("#o-wrapper").addClass("no_padding");
            $(".overlayKillChild").click(function () {
                $(".overlayKillParent").removeClass("overlayKillParent-active");
                $("#o-wrapper").removeClass("no_padding");
            })
            $(".c-menu__close").click(function () {
                $(".overlayKillParent").removeClass("overlayKillParent-active");
                $("#o-wrapper").removeClass("no_padding");
            })
        }


        function goStepOne() {
            // adjust the step number
            $('.addexercise .indicatorSubContainer').find('.step').removeClass('labelStepTwo').addClass('labelStepOne');
            // move the step indicator
            $('.switcher .indicator').removeClass('stepTwo').addClass('stepOne');
            // move the input container
            $('.addexercise-stepContainer').removeClass('stepTwoActive');
            // place active class on the right addexercise-set
            var $addStep = $('.addexercise-stepContainer').find('.addexercise-step');

            $addStep.removeClass("active inactive");
            $addStep.first().addClass("active");
            setTimeout(function () {
                $addStep.last().addClass("inactive");
            }, 300);
        }

        function goStepTwo() {
            // adjust the step number
            $('.indicatorSubContainer').find('.step').removeClass('labelStepOne').addClass('labelStepTwo');
            // move the step indicator
            $('.switcher .indicator').removeClass('stepOne').addClass('stepTwo');
            // move the input container
            $('.addexercise-stepContainer').addClass('stepTwoActive');
            // place active class on the right addexercise-set
            var $addStep = $('.addexercise-stepContainer').find('.addexercise-step');

            $addStep.removeClass("active inactive");
            $addStep.last().addClass("active").show();
            setTimeout(function () {
                $addStep.first().addClass("inactive");
            }, 300);
        }

        // swtich step add exercise
        function addExerciseSwtichStep() {
            if ($('.indicatorSubContainer').find('.step').hasClass('labelStepTwo')) {
                goStepOne();
            } else {
                goStepTwo();
            }
        }

        //update the name of the fake button
        function updateName(ob) {
            var $input = $(ob).closest(".image").find(".imageInput");
            $input.change(function () {
                var filename = $input.val().replace(/^.*\\/, "");
                $(ob).closest(".image").find(".button").html(filename);
            });
        }


        // Tab moving
        function closeTabs() {
            var tabs = document.getElementsByClassName("tabContent");
            for (i = 0; i < tabs.length; i++) {
                tabs[i].style.display = "none";
            }
        }

        function openTab(id) {
            var tab = document.getElementById(id);
            if (tab.style.display === "none") {
                var open = true
            } else {
                var open = false
            }


            closeTabs();

            if (open == true) {
                tab.style.display = "block";
            }
        }

        function searchExercise() {
            $.ajax({
                url: widgetsList["w_exercises_full"],
                type: "POST",
                data: {search: $("#exercise_search").val()},
                success: function (data, textStatus, jqXHR) {
                    $("#w_exercises_full").html(data);

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

        $(document).ready(function () {
            $(".menu_exercises").addClass("selected");
        });
    </script>

@endsection
