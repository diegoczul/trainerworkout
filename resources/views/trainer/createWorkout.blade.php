+@php
    use App\Http\Libraries\Helper;
@endphp
@extends('layouts.trainer')

@section("header")
    {!! Helper::seo("createWorkout") !!}
@endsection

@section('content')


<!-- *************************************************** -->

<!--                       Header                        -->

<!-- *************************************************** -->

<div class="cw wrapper">
    <!-- This div contains all the info about the workout header -->
    <div class="widget cw-header">
        <div class="ptLogoPlaceholder" style="cursor:pointer; @if(Auth::user()->activeLogo) background-image: none; @endif" onclick="window.location='{{ Lang::get("routes./Trainer/Profile") }}'">
            @if(Auth::user()->activeLogo)
                <img src="/{{ Helper::image(Auth::user()->activeLogo->thumb) }}">
            @endif
        </div>
        <div class="cw-header-description">
            <input type="text" placeholder="{{ Lang::get("content.createWorkout/name") }}" id="workout_name" name="workout_name" onkeyup="updateWorkoutName()" value="{{ $workout->name }}" required />
            <input type="hidden" id="client" name="client" value="{{ (isset($client) ? $client : "" ) }}" />
            <h5>By: {{ Auth::user()->firstName }} {{ Auth::user()->lastName }}</h5>
        </div>
        <!-- The add note links to a pop-up that allow the user to add a note to the workout -->
        <div class="ptAddNote">
            <div class="spanContainer" onclick="workoutNote(this);">
                <span >{{ Lang::get("content.add") }}<br>{{ Lang::get("content.note") }}</span>
            </div>
            <div class="note">
                <img class="exitNote" src="{{asset('assets/img/exitPopup.svg')}}" onclick="workoutNote(this);">
                <h3>{{ Lang::get("content.Adding a note to this workout") }}</h3>

                    <textarea name="noteToWorkout" id="noteToWorkout">{{ $workout->notes }}</textarea>
                    <button onclick="saveNoteWorkout(this)">{{ Lang::get("content.Save Note") }}</button>
                    <input type="hidden" name="exercise" id="noteWorkout"/>
            </div>
        </div>
    </div>
</div>
@if($workout->notes != "")
<script>
    $(".ptAddNote").find("span").html("{{ Lang::get("content.note") }}" + "<br>" + "{{ Lang::get("content.added") }}");
    $(".ptAddNote").find(".spanContainer").css("padding", "3px 0px");
</script>
@endif
<!-- *************************************************** -->

<!--                    Adding BAR                       -->

<!-- *************************************************** -->



<!-- DIEGO!! see below the comment. -->
<!-- Once an exercise has been added to the workout the dive below needs the class cw-adding to be added in it.  -->
<div id="addingExercise" class="wrapper cw-create">
    <!-- This div is only shown when there are no exercise added to the workout just yet -->
    <div class="widget">
        <div class="innerContainer">
            <button onclick="showSearch('regular')">
                <svg width="14" height="14" viewBox="0 0 14 14" xmlns="https://www.w3.org/2000/svg">
                    <title>
                        ADD ICON
                    </title>
                    <path d="M7.5 6.5V0h-1v6.5H0v1h6.5V14h1V7.5H14v-1H7.5z" fill="#FFF" fill-rule="evenodd"/></path>
                </svg>
                {{ Lang::get("content.Add Exercise") }}
            </button>
            <button id="completeWorkout" onclick="createWorkout()">{{ Lang::get("content.done") }}</button>
            <!-- Diego!!! Upon clicking the button below the option box of the circuit is opened Search for exercise happens after-->
            <button  onclick="addCircuit()">
                <svg width="14" height="14" viewBox="0 0 14 14" xmlns="https://www.w3.org/2000/svg">
                    <title>
                        ADD ICON
                    </title>
                    <path d="M7.5 6.5V0h-1v6.5H0v1h6.5V14h1V7.5H14v-1H7.5z" fill="#FFF" fill-rule="evenodd"/></path>
                </svg>
                {{ Lang::get("content.Add Circuit") }}
            </button>
            <!-- *********** -->
            <button class="add-modal-btns" onclick="exercisemodal()">Add</button>
            
        </div>
    </div>
</div>

<!-- *************************************************** -->

<!--                   SEARCH POP-UP                     -->

<!-- *************************************************** -->


<!-- The searchPOP is shown everytime the users adds or modify an exercise -->
<div class="searchPop">
    <div class="overlay">
        <img class="exitSearch" src="{{asset('assets/img/exitPopup.svg')}}" onclick="hideSearch()">
        <div class="searchContainer">
            <div class="searchWrapper">
                <h4>{{ Lang::get("content.Search Exercises") }}</h4>
                <div class="searchField search-exercise">
                    <div class="input-section">
                    <input id="exercise_search" name="exercise_search" placeholder="{{ Lang::get('content.searchPlaceholder') }}">
                        <a href="javascript:void(0);" class="cancel-btn" onclick="clearSearch();" style=" position: absolute; top: 8px; left: auto; bottom: auto; right: 10px;">
                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="25" height="25" x="0" y="0" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path d="m292.2 256 109.9-109.9c10-10 10-26.2 0-36.2s-26.2-10-36.2 0L256 219.8 146.1 109.9c-10-10-26.2-10-36.2 0s-10 26.2 0 36.2L219.8 256 109.9 365.9c-10 10-10 26.2 0 36.2 5 5 11.55 7.5 18.1 7.5s13.1-2.5 18.1-7.5L256 292.2l109.9 109.9c5 5 11.55 7.5 18.1 7.5s13.1-2.5 18.1-7.5c10-10 10-26.2 0-36.2z" fill="#808080" opacity="1" data-original="#000000"></path></g></svg>
                        </a>
                    </div>


                    <button type="button" onClick="searchExercise();">{{ Lang::get('content.Search') }}</button>
                    <select id="langSelector" style="height: 41px;">
                        <option value="en" {{ (app()->getLocale()  == "en") ? "selected" : "" }}>EN</option>
                        <option value="fr" {{ (app()->getLocale()  == "fr") ? "selected" : "" }}>FR</option>
                    </select>
                </div>
                <div id="selectedFilters" class="selectedFilters"></div>
                <div class="tagContainer">
                    <ul class="tabs">
                        <li class="tab active" onclick="openTab('tags-muscle');setActiveTab(this);">{{ Lang::get("content.Muscle Group") }}</li>
                        <li class="tab" onclick="openTab('tags-equipment');setActiveTab(this);">{{ Lang::get("content.Equipment") }}</li>
                        <li class="tab" onclick="openTab('tags-exercise');setActiveTab(this);">{{ Lang::get("content.Exercise Type") }}</li>
                        <li class="tab" onclick="closeTabs();setActiveTab(this);searchExercise();" id="myExerciseTab">{{ Lang::get("content.myExercises") }}</li>
                    </ul>
                    <div id="tags-muscle" class="tabContent" style="display: block;">
                        @foreach($bodygroups as $bodyGroup)
                            <div class="searchTag" onclick='addToFilter("{{ $bodyGroup->name }}","bodygroup",{{ $bodyGroup->id }},this)'>{{{ $bodyGroup->name }}}</div>
                        @endforeach
                    </div>
                    <div id="tags-equipment" class="tabContent" style="display: none;">
                        @foreach($equipments as $equipment)
                            <div class="searchTag" onclick='addToFilter("{{ $equipment->name }}","equipment",{{ $equipment->id }},this)'>{{{ $equipment->name }}}</div>
                        @endforeach
                    </div>
                    <div id="tags-exercise" class="tabContent" style="display: none;">
                        @foreach($exercisesTypes as $exercisesType)
                            <div class="searchTag" onclick='addToFilter("{{ $exercisesType->name }}","type",{{ $exercisesType->id }},this)'>{{{ $exercisesType->name }}}</div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
        <div class="addExercise">
            <a href="javascript:void(0)" onclick="addExercise()">{{ Lang::get("content.Add your own personal exercises to your personal collection") }}</a>
        </div>


        <!-- search results goes here below.  -->
        <div id="search_results">
            <ul class="searchResultsContainer">

            </ul>
        </div>
    </div>
</div>




<!-- *************************************************** -->

<!--                   MUSCLE EXERCISE                   -->

<!-- *************************************************** -->

<div class="cw-exercise muscle templateMuscle exerciseTarget mainExerciseBlock" style="display:none">
    <div class="exerciseHeader">
        <h1></h1>
        <div class="exerciseOptions">
            <svg class="edit" width="22px" height="24px" viewBox="139 28 22 24" version="1.1" xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink">
                <!-- Edit that-->
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" transform="translate(150.000000, 40.000000) rotate(43.000000) translate(-150.000000, -40.000000) translate(148.000000, 28.500000)">
                    <title>{{ Lang::get("content.Edit") }}</title>
                    <rect fill="#FFFFFF" x="-2.27373675e-13" y="-2.13162821e-13" width="4" height="17.3333333" rx="0.506903261">
                    </rect>
                    <path d="M1.3671875,18.7874958 C1.71668019,17.7390178 2.28230926,17.7359861 2.6328125,18.7874958 L4,22.8890583 L1.080247e-12,22.8890583 L1.3671875,18.7874958 Z" fill="#FFFFFF" transform="translate(2.000000, 20.444529) scale(1, -1) translate(-2.000000, -20.444529) "></path>
                </g>
            </svg>
            <svg  class="duplicate" width="18px" height="22px" viewBox="174 29 18 22" version="1.1" xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink">
                <!-- Duplicate -->
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" transform="translate(174.694311, 29.225436)">
                    <title>{{ Lang::get("content.Duplicate") }}</title>
                    <path d="M14.4237037,17.9598222 L16.7796296,17.9598222 L16.7796296,0 L3.35592593,0 L3.35592593,2 L14.4237037,2 L14.4237037,17.9598222 Z" fill="#FFFFFF"></path>
                    <rect fill="#FFFFFF" x="0.5" y="2.5" width="13.4237037" height="17.9598222"></rect>
                </g>
            </svg>
            <svg class="move-up" width="21px" height="22px" viewBox="-1 -2 21 22" version="1.1" xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink">
                <!-- Move-up -->
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" transform="translate(9.500000, 9.500000) scale(1, -1) translate(-9.500000, -9.500000) translate(0.000000, -0.000000)">
                    <title>{{ Lang::get("content.moveup") }}</title>
                    <ellipse stroke="#FFFFFF" cx="9.5" cy="9.5" rx="9.5" ry="9.5"></ellipse>
                    <polyline stroke="#FFFFFF" stroke-width="2" stroke-linecap="square" points="5 7 9.26999998 12.2658068 13.54 7"></polyline>
                </g>
            </svg>
            <svg class="move-down" width="21px" height="22px" viewBox="33 -2 21 22" version="1.1" xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink">
                <!-- Move Down -->
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" transform="translate(34.000000, -0.000000)">
                    <title>{{ Lang::get("content.movedown") }}</title>
                    <ellipse stroke="#FFFFFF" cx="9.5" cy="9.5" rx="9.5" ry="9.5"></ellipse>
                    <polyline stroke="#FFFFFF" stroke-width="2" stroke-linecap="square" points="5 7 9.26999998 12.2658068 13.54 7"></polyline>
                </g>
            </svg>
            <svg class="delete" width="17px" height="17px" viewBox="273 32 17 17" version="1.1" xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink">

                <!-- Delete -->
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" transform="translate(275.000000, 34.000000)" stroke-linecap="round">
                    <title>{{ Lang::get("content.Delete") }}</title>
                    <path d="M0.342105263,0.295454545 L12.6578947,12.7045455" stroke="#FFFFFF" stroke-width="2.25"></path>
                    <path d="M0.342105263,0.295454545 L12.6578947,12.7045455" stroke="#FFFFFF" stroke-width="2.25" transform="translate(6.500000, 6.500000) scale(-1, 1) translate(-6.500000, -6.500000) "></path>
                </g>
            </svg>
        </div>
    </div> <!-- End of Exercise Header -->
    <div class="containerExercise">
        <div class="exerciseNote">
            <div class="spanContainer" onclick="exerciseNote(this);">
                <span>{{ Lang::get("content.add") }}<br>{{ Lang::get("content.note") }}</span>
            </div>
            <div class="note">
                <img class="exitNote" src="{{asset('assets/img/exitPopup.svg')}}" onclick="exerciseNote(this);">
                <h3>{{ Lang::get("content.Adding a note to this exercise") }}</h3>
                    <textarea name="noteToExercise" class="noteToExercise"></textarea>
                    <button onclick="saveNote(this)">{{ Lang::get("content.Save Note") }}</button>
            </div>
        </div>
        <div class="exerciseImageContainer">

            <div class="exeDescription" onclick="exeDescriptionToggle(this);">
                <div class="exeDescription_Exp">
                    <svg width="9" height="9" viewBox="0 0 9 9" xmlns="https://www.w3.org/2000/svg">
                        <title>
                            {{ Lang::get("content.See Exercise Description") }}
                        </title>
                        <g stroke-linecap="square" stroke="#2C3E50" fill="none" fill-rule="evenodd">
                            <path d="M.5 4.5 h8"></path>
                            <path d="M4.5 .5 v8"></path>
                        </g>
                    </svg>
                </div>
                <p>{{ Lang::get("content.exercise description") }}</p>
                <div class="exeDescription_full">
                    <p>{{ Lang::get("content.A description for this exercise will be added shortly by Trainer Workout.") }}</p>
                </div>
            </div>
            <div class="exerciseImages">
                <img src="{{asset('assets/img/transparent.png')}}"  onClick="editExercise(this)" style="cursor: pointer">
                <img src="{{asset('assets/img/transparent.png')}}" onClick="editExercise(this)" style="cursor: pointer">
            </div>
        </div>

        <div class="exerciseDetails">
            <div class="exerciseDetails-topContainer">
                <div class="tempoContainer">
                    <button onclick="showTempo(this)" class="addTempoButton">+ {{ Lang::get("content.add tempo") }}</button>
                    <div class="tempo-active hide">
                        <div>{{ Lang::get("content.Tempo") }}</div>
                        <input type="number" oninput="this.value = this.value.replace(/[^0-9.]/g, '')" inputmode="decimal" class="tempo1" onChange="updateTempo(this)" value=""/>
                        <input type="number" oninput="this.value = this.value.replace(/[^0-9.]/g, '')" inputmode="decimal" class="tempo2" onChange="updateTempo(this)" value=""/>
                        <input type="number" oninput="this.value = this.value.replace(/[^0-9.]/g, '')" inputmode="decimal" class="tempo3" onChange="updateTempo(this)" value=""/>
                        <input type="number" oninput="this.value = this.value.replace(/[^0-9.]/g, '')" inputmode="decimal" class="tempo4" onChange="updateTempo(this)" value=""/>
                        <div onclick="hideTempo(this)" style="align-items: center;justify-content: center;display: flex;">
                            <svg class="delete" width="17px" height="17px" viewBox="273 32 17 17" version="1.1" xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink">
                                <!-- Delete -->
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" transform="translate(275.000000, 34.000000)" stroke-linecap="round">
                                    <title>Delete</title>
                                    <path d="M0.342105263,0.295454545 L12.6578947,12.7045455" stroke="#FFFFFF" stroke-width="2.25"></path>
                                    <path d="M0.342105263,0.295454545 L12.6578947,12.7045455" stroke="#FFFFFF" stroke-width="2.25" transform="translate(6.500000, 6.500000) scale(-1, 1) translate(-6.500000, -6.500000) "></path>
                                </g>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="unitSwitcherContainer">
                    <p>{{ Lang::get("content.lbs") }}</p>
                    <label onclick="weightUnitToggle(this)" class="unitToggleLabel">
                        <input type="checkbox" class="unitToggleInput">
                        <div class="unitToggleControl"></div>
                    </label>
                    <p>{{{ Lang::get("content.kg") }}}</p>
                </div>
            </div>
            <div class="exercise-table">
                <table>

                    <caption>{{ Lang::get("content.MUSLCE EXERCISE") }}</caption>
                    <thead>
                        <tr>
                            <th class="tbSet" scope="col"><p>{{ Lang::get("content.Set") }}</p></th>
                            <th class="tbWeight" scope="col"><p>{{ Lang::get("content.Weight") }}</p></th>
                            <!-- <th class="bound time" scope="col">Time</th> -->
                            <!-- <th class="bound rep" scope="col">Repetition</th> -->
                            <!-- <th class="bound range" scope="col">Range</th> -->
                            <th class="bound tbRepType" onclick="muscleBoundMenu(this,null)" scope="col"><p>{{ Lang::get("content.Repetition") }}</p></th>
                            <th class="tbMode" scope="col"><p>{{ Lang::get("content.Mode") }}</p></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="set muscleSet" setNumber="1">
                            <th scope="row"><p>1</p></th>
                            <td class="weight lbs"><input type="number" oninput="this.value = this.value.replace(/[^0-9.]/g, '')" inputmode="decimal" onChange="updateField('weight',this)"></td>
                            <!-- <td class="time bound sec"><input></td> -->
                            <td class="rep bound"><input value="8" onChange="updateField('rep',this)"></td>
                            <!-- <td class="range bound"><input> - <input></td> -->
                            <!-- <td class="maxRep bound repPointer"><input value='maximum'></td> -->
                            <td class="repStyleChoice" onclick="muscleBoundMenu(this,0)"><p>{{ Lang::get("content.rep") }}</p></td>

                        </tr>
                    </tbody>

                </table>
                <div class="setManagement">
                   <button onclick="addingSet(this)" class="addSet">+ {{ Lang::get("content.Add Set") }}</button>
                   <button onclick="removeSet(this)" class="removeSet">- {{ Lang::get("content.Remove Set") }}</button>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- *************************************************** -->

<!--        REST BETWEEN EXERCISE in Circuit             -->

<!-- *************************************************** -->


<div class="btwExerciseRest templateRestBetweenExercises" style="display:none">
    <button onclick="restBetweenExercise(this)" class="addRest">+ {{ Lang::get("content.Add rest between exercises") }}</button>
    <div class="btwExerciseRest-active widget hide">
        <svg width="50" height="50" viewBox="0 0 27 27" xmlns="https://www.w3.org/2000/svg" style="position: relative;right: 15px;>
            <title>
                {{ Lang::get("content.Pause Icon") }}
            </title>
            <g transform="translate(1 1)" fill="none" fill-rule="evenodd">
                <circle stroke="#2C3E50" fill="#FFF" cx="12.5" cy="12.5" r="12.5"/>
                <g fill="#2C3E50">
                    <path d="M8.523 7.955h2.84v9.66h-2.84zM13.068 7.955h2.84v9.66h-2.84z"/>
                </g>
            </g>
        </svg>
        <div class="btwExerciseRest-time">
            <label for="restTime">{{ Lang::get("content.rest time") }}</label>
            <input id="" class="restTime" type="number" oninput="this.value = this.value.replace(/[^0-9.]/g, '')" onChange="saveRestTime(this)" inputmode="decimal">
            <label for="restTime">{{ Lang::get("content.sec") }}</label>
        </div>
        <svg onclick="closeRestBetweenExercise(this)" width="15" height="15" viewBox="0 0 15 15" xmlns="https://www.w3.org/2000/svg">
            <title>
                {{ Lang::get("content.Close Button Rest Time") }}
            </title>
            <g stroke="#2C3E50" stroke-width="2.25" fill="none" fill-rule="evenodd" stroke-linecap="round">
                <path d="M1.342 1.295l12.316 12.41M13.658 1.295L1.342 13.705"/>
            </g>
        </svg>
    </div>
</div>

<!-- *************************************************** -->

<!--                REST BETWEEN GROUPS                -->

<!-- *************************************************** -->


<div class="btwExerciseRest templateRestBetweenGroups" style="display:none">
    <button onclick="restBetweenExercise(this)" class="addRest">+ {{ Lang::get("content.Add rest between exercises") }}</button>
    <div class="btwExerciseRest-active widget hide">
        <svg width="27" height="27" viewBox="0 0 27 27" xmlns="https://www.w3.org/2000/svg">
            <title>
                {{ Lang::get("content.Pause Icon") }}
            </title>
            <g transform="translate(1 1)" fill="none" fill-rule="evenodd">
                <circle stroke="#369AD8" fill="#FFF" cx="12.5" cy="12.5" r="12.5"/>
                <g fill="#369AD8">
                    <path d="M8.523 7.955h2.84v9.66h-2.84zM13.068 7.955h2.84v9.66h-2.84z"/>
                </g>
            </g>
        </svg>
        <div class="btwExerciseRest-time">
            <label for="restTime">{{ Lang::get("content.rest time") }}</label>
            <input id="" class="restTime" type="number" oninput="this.value = this.value.replace(/[^0-9.]/g, '')" onChange="saveRestTimeBetweenGroups(this)" inputmode="decimal">
            <label for="restTime">{{ Lang::get("content.sec") }}</label>
        </div>
        <svg onclick="closeRestBetweenExercise(this)" width="15" height="15" viewBox="0 0 15 15" xmlns="https://www.w3.org/2000/svg">
            <title>
                {{ Lang::get("content.Close Button Rest Time") }}
            </title>
            <g stroke="#369AD8" stroke-width="2.25" fill="none" fill-rule="evenodd" stroke-linecap="round">
                <path d="M1.342 1.295l12.316 12.41M13.658 1.295L1.342 13.705"/>
            </g>
        </svg>
    </div>
</div>




<!-- *************************************************** -->

<!--                   CARDIO EXERCISE                   -->

<!-- *************************************************** -->


<div class="cw-exercise cardio templateCardio exerciseTarget mainExerciseBlock" id="" style="display:none">
    <div class="exerciseHeader">
        <h1></h1>
        <div class="exerciseOptions">
            <svg class="edit" width="22px" height="24px" viewBox="139 28 22 24" version="1.1" xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink">
                <!-- Edit that-->
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" transform="translate(150.000000, 40.000000) rotate(43.000000) translate(-150.000000, -40.000000) translate(148.000000, 28.500000)">
                    <title>{{ Lang::get("content.Edit") }}</title>
                    <rect fill="#FFFFFF" x="-2.27373675e-13" y="-2.13162821e-13" width="4" height="17.3333333" rx="0.506903261">
                    </rect>
                    <path d="M1.3671875,18.7874958 C1.71668019,17.7390178 2.28230926,17.7359861 2.6328125,18.7874958 L4,22.8890583 L1.080247e-12,22.8890583 L1.3671875,18.7874958 Z" fill="#FFFFFF" transform="translate(2.000000, 20.444529) scale(1, -1) translate(-2.000000, -20.444529) "></path>
                </g>
            </svg>
            <svg  class="duplicate" width="18px" height="22px" viewBox="174 29 18 22" version="1.1" xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink">
                <!-- Duplicate -->
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" transform="translate(174.694311, 29.225436)">
                    <title>{{ Lang::get("content.Duplicate") }}</title>
                    <path d="M14.4237037,17.9598222 L16.7796296,17.9598222 L16.7796296,0 L3.35592593,0 L3.35592593,2 L14.4237037,2 L14.4237037,17.9598222 Z" fill="#FFFFFF"></path>
                    <rect fill="#FFFFFF" x="0.5" y="2.5" width="13.4237037" height="17.9598222"></rect>
                </g>
            </svg>
            <svg class="move-up" width="21px" height="22px" viewBox="-1 -2 21 22" version="1.1" xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink">
                <!-- Move-up -->
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" transform="translate(9.500000, 9.500000) scale(1, -1) translate(-9.500000, -9.500000) translate(0.000000, -0.000000)">
                    <title>{{ Lang::get("content.moveup") }}</title>
                    <ellipse stroke="#FFFFFF" cx="9.5" cy="9.5" rx="9.5" ry="9.5"></ellipse>
                    <polyline stroke="#FFFFFF" stroke-width="2" stroke-linecap="square" points="5 7 9.26999998 12.2658068 13.54 7"></polyline>
                </g>
            </svg>
            <svg class="move-down" width="21px" height="22px" viewBox="33 -2 21 22" version="1.1" xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink">
                <!-- Move Down -->
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" transform="translate(34.000000, -0.000000)">
                    <title>{{ Lang::get("content.movedown") }}</title>
                    <ellipse stroke="#FFFFFF" cx="9.5" cy="9.5" rx="9.5" ry="9.5"></ellipse>
                    <polyline stroke="#FFFFFF" stroke-width="2" stroke-linecap="square" points="5 7 9.26999998 12.2658068 13.54 7"></polyline>
                </g>
            </svg>
            <svg class="delete" width="17px" height="17px" viewBox="273 32 17 17" version="1.1" xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink">

                <!-- Delete -->
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" transform="translate(275.000000, 34.000000)" stroke-linecap="round">
                    <title>{{ Lang::get("content.Delete") }}</title>
                    <path d="M0.342105263,0.295454545 L12.6578947,12.7045455" stroke="#FFFFFF" stroke-width="2.25"></path>
                    <path d="M0.342105263,0.295454545 L12.6578947,12.7045455" stroke="#FFFFFF" stroke-width="2.25" transform="translate(6.500000, 6.500000) scale(-1, 1) translate(-6.500000, -6.500000) "></path>
                </g>
            </svg>
        </div>
    </div> <!-- End of Exercise Header -->
    <div class="containerExercise">
        <div class="exerciseNote">
            <div class="spanContainer" onclick="exerciseNote(this);">
                <span>{{ Lang::get("content.add") }}<br>{{ Lang::get("content.note") }}</span>
            </div>
            <div class="note">
                <img class="exitNote" src="{{asset('assets/img/exitPopup.svg')}}" onclick="exerciseNote(this);">
                <h3>{{ Lang::get("content.Adding a note to this exercise") }}</h3>
                    <textarea name="noteToExercise" class="noteToExercise"></textarea>

                    <button onclick="saveNote(this)">{{ Lang::get("content.Save Note") }}</button>
            </div>
        </div>
        <div class="exerciseImageContainer">

            <div class="exeDescription" onclick="exeDescriptionToggle(this);">
                <div class="exeDescription_Exp">
                    <svg width="9" height="9" viewBox="0 0 9 9" xmlns="https://www.w3.org/2000/svg">
                        <title>
                            {{ Lang::get("content.See Exercise Description") }}
                        </title>
                        <g stroke-linecap="square" stroke="#2C3E50" fill="none" fill-rule="evenodd">
                            <path d="M.5 4.5 h8"></path>
                            <path d="M4.5 .5 v8"></path>
                        </g>
                    </svg>
                </div>
                <p>{{ Lang::get("content.exercise description") }}</p>
                <div class="exeDescription_full">
                    <p>{{ Lang::get("content.A description for this exercise will be added shortly by Trainer Workout.") }}</p>
                </div>
            </div>
            <div class="exerciseImages">
                <img src="{{asset('assets/img/transparent.png')}}" onClick="editExercise(this)" style="cursor: pointer">
            </div>
        </div>

        <div class="exerciseDetails">
            <div class="exerciseDetails-topContainer">
                <div class="tempoContainer">
                </div>

                <div class="unitSwitcherContainer">
                    <p>{{ Lang::get("content.mi") }}</p>
                    <label onclick="cardioUnitToggle(this)" class="unitToggleLabel">
                        <input type="checkbox" class="unitToggleInput">
                        <div class="unitToggleControl"></div>
                    </label>
                    <p>{{ Lang::get("content.km") }}</p>
                </div>
            </div>
            <div class="exercise-table">

                    <table>
                        <caption>{{ Lang::get("content.CARDIO EXERCISE") }}</caption>
                        <thead>
                            <tr>
                                <th class="tbInt" scope="col"><p>{{ Lang::get("content.Interval") }}</p></th>
                                <th onclick="cardioRaiseMenu(this)" class="tbHr tbRepType" scope="col"><p>{{ Lang::get("content.HeartRate") }}</p></th>

                                <th class="tbSpeed" scope="col"><p>{{ Lang::get("content.Speed") }}</p></th>
                                <th class="tbDist" scope="col"><p>{{ Lang::get("content.Distance") }}</p></th>
                                <th class="tbTime" scope="col"><p>{{ Lang::get("content.Time") }}</p></th>
                                <th class="tbMode" scope="col"><p>{{ Lang::get("content.Mode") }}</p></th>

                            </tr>
                        </thead>
                        <tbody>
                            <tr class="set cardioSet" setnumber="1">
                                <th scope="row"><p>1</p></th>
                                <td class="bound hrtemplate hr"><input value="20" onChange="updateField('hr',this)"/></td>

                                <td class="bound speed mih"><input onChange="updateField('speed',this)"/></td>
                                <td class="bound distance mi"><input onChange="updateField('distance',this)"/></td>
                                <td class="bound time min"><input onChange="updateField('time',this)" value="30" /></td>
                                <td class="repStyleChoice" onclick="cardioRaiseMenu(this,0)"><p>{{ Lang::get("content.HR") }}</p></td>
                            </tr>
                        </tbody>
                    </table>

                <div class="setManagement">
                   <button onclick="addingSet(this)" class="addSet">+ {{ Lang::get("content.Add Interval") }}</button>
                   <button onclick="removeSet(this)" class="removeSet">- {{ Lang::get("content.Remove Interval") }}</button>
                </div>
            </div>
        </div>
    </div>
</div>




<!-- *************************************************** -->

<!--             CIRCUIT Muscle EXERCISE                 -->

<!-- *************************************************** -->


<div class="containerExercise circuit-muscle templateMuscleCircuit exerciseTarget" style="display:none">
    <div class="exerciseNote">
        <div class="spanContainer" onclick="exerciseNote(this);">
            <span>{{ Lang::get("content.add") }}<br>{{ Lang::get("content.note") }}</span>
        </div>
        <div class="note">
            <img class="exitNote" src="{{asset('assets/img/exitPopup.svg')}}" onclick="exerciseNote(this);">
            <h3>{{ Lang::get("content.Adding a note to this exercise") }}</h3>
                <textarea name="noteToExercise" class="noteToExercise"></textarea>
                <button onclick="saveNote(this)">{{ Lang::get("content.Save Note") }}</button>
        </div>
    </div>
    <div class="exerciseImageContainer">
        <div class="cExercise_header">
            <div class="cExercise_header_top">
                <div class="cExercise_header_icon">
                    <svg width="27" height="27" viewBox="0 0 27 27" xmlns="https://www.w3.org/2000/svg">
                        <title>
                            {{ Lang::get("content.Play Icon") }}
                        </title>
                        <g transform="translate(1 1)" fill="none" fill-rule="evenodd">
                            <circle stroke="#2C3E50" fill="#FFF" cx="12.5" cy="12.5" r="12.5"/></circle>
                            <path fill="#2C3E50" d="M19 12.5L8 20V5z"/></path>
                        </g>
                    </svg>

                </div>
                <div class="cExercise_header_info">
<!-- Diego !!! here below in the p the number of this exercise in the span the total number of exercise in this circuit -->
                    <h5></h5>
                </div>
            </div>
            <div class="cExercise_header_bottom">
                <div class="exerciseOptions">
                    <svg class="edit" width="22px" height="24px" viewBox="139 28 22 24" version="1.1" xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink">
                        <!-- Edit that-->
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" transform="translate(150.000000, 40.000000) rotate(43.000000) translate(-150.000000, -40.000000) translate(148.000000, 28.500000)">
                            <title>{{ Lang::get("content.Edit") }}</title>
                            <rect fill="#FFFFFF" x="-2.27373675e-13" y="-2.13162821e-13" width="4" height="17.3333333" rx="0.506903261">
                            </rect>
                            <path d="M1.3671875,18.7874958 C1.71668019,17.7390178 2.28230926,17.7359861 2.6328125,18.7874958 L4,22.8890583 L1.080247e-12,22.8890583 L1.3671875,18.7874958 Z" fill="#FFFFFF" transform="translate(2.000000, 20.444529) scale(1, -1) translate(-2.000000, -20.444529) "></path>
                        </g>
                    </svg>
                    <svg  class="duplicate" width="18px" height="22px" viewBox="174 29 18 22" version="1.1" xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink">
                        <!-- Duplicate -->
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" transform="translate(174.694311, 29.225436)">
                            <title>{{ Lang::get("content.Duplicate") }}</title>
                            <path d="M14.4237037,17.9598222 L16.7796296,17.9598222 L16.7796296,0 L3.35592593,0 L3.35592593,2 L14.4237037,2 L14.4237037,17.9598222 Z" fill="#FFFFFF"></path>
                            <rect fill="#FFFFFF" x="0.5" y="2.5" width="13.4237037" height="17.9598222"></rect>
                        </g>
                    </svg>
                    <svg class="move-up" width="21px" height="22px" viewBox="-1 -2 21 22" version="1.1" xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink">
                        <!-- Move-up -->
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" transform="translate(9.500000, 9.500000) scale(1, -1) translate(-9.500000, -9.500000) translate(0.000000, -0.000000)">
                            <title>{{ Lang::get("content.moveup") }}</title>
                            <ellipse stroke="#FFFFFF" cx="9.5" cy="9.5" rx="9.5" ry="9.5"></ellipse>
                            <polyline stroke="#FFFFFF" stroke-width="2" stroke-linecap="square" points="5 7 9.26999998 12.2658068 13.54 7"></polyline>
                        </g>
                    </svg>
                    <svg class="move-down" width="21px" height="22px" viewBox="33 -2 21 22" version="1.1" xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink">
                        <!-- Move Down -->
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" transform="translate(34.000000, -0.000000)">
                            <title>{{ Lang::get("content.movedown") }}</title>
                            <ellipse stroke="#FFFFFF" cx="9.5" cy="9.5" rx="9.5" ry="9.5"></ellipse>
                            <polyline stroke="#FFFFFF" stroke-width="2" stroke-linecap="square" points="5 7 9.26999998 12.2658068 13.54 7"></polyline>
                        </g>
                    </svg>
                    <svg class="delete" width="17px" height="17px" viewBox="273 32 17 17" version="1.1" xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink">

                        <!-- Delete -->
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" transform="translate(275.000000, 34.000000)" stroke-linecap="round">
                            <title>{{ Lang::get("content.Delete") }}</title>
                            <path d="M0.342105263,0.295454545 L12.6578947,12.7045455" stroke="#FFFFFF" stroke-width="2.25"></path>
                            <path d="M0.342105263,0.295454545 L12.6578947,12.7045455" stroke="#FFFFFF" stroke-width="2.25" transform="translate(6.500000, 6.500000) scale(-1, 1) translate(-6.500000, -6.500000) "></path>
                        </g>
                    </svg>
                </div>
            </div>
        </div>
        <div class="exerciseImages">
            <img src="{{asset('assets/img/transparent.png')}}" onClick="editExercise(this)" style="cursor: pointer">
            <img src="{{asset('assets/img/transparent.png')}}" onClick="editExercise(this)" style="cursor: pointer">

        </div>
    </div>

    <div class="exerciseDetails">
        <div class="exerciseDetails-topContainer">
            <div class="tempoContainer">
                <button onclick="showTempo(this)" class="addTempoButton">+ {{ Lang::get("content.add tempo") }}</button>
                <div class="tempo-active hide">
                    <div>{{ Lang::get("content.Tempo") }}</div>
                    <input type="number" oninput="this.value = this.value.replace(/[^0-9.]/g, '')" inputmode="decimal" class="tempo1" onChange="updateTempo(this)" value=""/>
                    <input type="number" oninput="this.value = this.value.replace(/[^0-9.]/g, '')" inputmode="decimal" class="tempo2" onChange="updateTempo(this)" value=""/>
                    <input type="number" oninput="this.value = this.value.replace(/[^0-9.]/g, '')" inputmode="decimal" class="tempo3" onChange="updateTempo(this)" value=""/>
                    <input type="number" oninput="this.value = this.value.replace(/[^0-9.]/g, '')" inputmode="decimal" class="tempo4" onChange="updateTempo(this)" value=""/>
                    <div onclick="hideTempo(this)" style="align-items: center;justify-content: center;display: flex;">
                        <svg class="delete" width="17px" height="17px" viewBox="273 32 17 17" version="1.1" xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink">
                            <!-- Delete -->
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" transform="translate(275.000000, 34.000000)" stroke-linecap="round">
                                <title>Delete</title>
                                <path d="M0.342105263,0.295454545 L12.6578947,12.7045455" stroke="#FFFFFF" stroke-width="2.25"></path>
                                <path d="M0.342105263,0.295454545 L12.6578947,12.7045455" stroke="#FFFFFF" stroke-width="2.25" transform="translate(6.500000, 6.500000) scale(-1, 1) translate(-6.500000, -6.500000) "></path>
                            </g>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="unitSwitcherContainer">
                <p>{{ Lang::get("content.lbs") }}</p>
                <label onclick="weightUnitToggle(this)" class="unitToggleLabel">
                    <input type="checkbox" class="unitToggleInput">
                    <div class="unitToggleControl"></div>
                </label>
                <p>{{ Lang::get("content.kg") }}</p>
            </div>
        </div>
        <div class="exercise-table">
            <table>
                <form>
                    <caption>{{ Lang::get("content.CIRCUIT MUSLCE EXERCISE") }}</caption>
                    <thead>
                        <tr>
                            <th class="tbSet" scope="col"><p>{{ Lang::get("content.set") }}</p></th>
                            <th class="tbWeight" scope="col"><p>{{ Lang::get("content.Weight") }}</p></th>
                            <th class="bound tbRepType" onclick="muscleBoundMenu(this, null)" scope="col"><p>{{ Lang::get("content.Repetition") }}</p></th>
                            <th class="tbMode" scope="col"><p>{{ Lang::get("content.Mode") }}</p></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="set muscleSet" setnumber="1">
                            <th scope="row"><p>1</p></th>
                            <td class="weight lbs"><input type="number" oninput="this.value = this.value.replace(/[^0-9.]/g, '')" inputmode="decimal" onChange="updateField('weight',this)"/></td>
                            <td class="rep bound"><input  onChange="updateField('rep',this)" value="8"/></td>
                            <td class="repStyleChoice" onclick="muscleBoundMenu(this,0)"><p>{{ Lang::get("content.rep") }}</p></td>
                        </tr>
                    </tbody>
                </form>
            </table>
            <div class="setManagement">
               <button onclick="addingSet(this)" class="addSet">+ {{ Lang::get("content.Add Set") }}</button>
               <button onclick="removeSet(this)" class="removeSet">- {{ Lang::get("content.Remove Set") }}</button>
            </div>
        </div>
    </div>
</div>


<!-- *************************************************** -->

<!--             CIRCUIT CARDIO EXERCISE                 -->

<!-- *************************************************** -->


<div class="containerExercise circuit-cardio templateCardioCircuit exerciseTarget"  style="display:none">
    <div class="exerciseNote">
        <div class="spanContainer" onclick="exerciseNote(this);">
            <span>{{ Lang::get("content.add") }}<br>{{ Lang::get("content.note") }}</span>
        </div>
        <div class="note">
            <img class="exitNote" src="{{asset('assets/img/exitPopup.svg')}}" onclick="exerciseNote(this);">
            <h3>{{ Lang::get("content.Adding a note to this exercise") }}</h3>
                <textarea name="noteToExercise" class="noteToExercise"></textarea>
                <button onclick="saveNote(this)">{{ Lang::get("content.Save Note") }}</button>
        </div>
    </div>
    <div class="exerciseImageContainer">
        <div class="cExercise_header">
            <div class="cExercise_header_top">
                <div class="cExercise_header_icon">
                    <svg width="27" height="27" viewBox="0 0 27 27" xmlns="https://www.w3.org/2000/svg">
                        <title>
                            {{ Lang::get("content.Play Icon") }}
                        </title>
                        <g transform="translate(1 1)" fill="none" fill-rule="evenodd">
                            <circle stroke="#2C3E50" fill="#FFF" cx="12.5" cy="12.5" r="12.5"/></circle>
                            <path fill="#2C3E50" d="M19 12.5L8 20V5z"/></path>
                        </g>
                    </svg>

                </div>
                <div class="cExercise_header_info">
                    <h5></h5>
                </div>
            </div>
            <div  class="cExercise_header_bottom">
                <div class="exerciseOptions">
                    <svg class="edit" width="22px" height="24px" viewBox="139 28 22 24" version="1.1" xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink">
                        <!-- Edit that-->
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" transform="translate(150.000000, 40.000000) rotate(43.000000) translate(-150.000000, -40.000000) translate(148.000000, 28.500000)">
                            <title>{{ Lang::get("content.Edit") }}</title>
                            <rect fill="#FFFFFF" x="-2.27373675e-13" y="-2.13162821e-13" width="4" height="17.3333333" rx="0.506903261">
                            </rect>
                            <path d="M1.3671875,18.7874958 C1.71668019,17.7390178 2.28230926,17.7359861 2.6328125,18.7874958 L4,22.8890583 L1.080247e-12,22.8890583 L1.3671875,18.7874958 Z" fill="#FFFFFF" transform="translate(2.000000, 20.444529) scale(1, -1) translate(-2.000000, -20.444529) "></path>
                        </g>
                    </svg>
                    <svg  class="duplicate" width="18px" height="22px" viewBox="174 29 18 22" version="1.1" xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink">
                        <!-- Duplicate -->
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" transform="translate(174.694311, 29.225436)">
                            <title>{{ Lang::get("content.Duplicate") }}</title>
                            <path d="M14.4237037,17.9598222 L16.7796296,17.9598222 L16.7796296,0 L3.35592593,0 L3.35592593,2 L14.4237037,2 L14.4237037,17.9598222 Z" fill="#FFFFFF"></path>
                            <rect fill="#FFFFFF" x="0.5" y="2.5" width="13.4237037" height="17.9598222"></rect>
                        </g>
                    </svg>
                    <svg class="move-up" width="21px" height="22px" viewBox="-1 -2 21 22" version="1.1" xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink">
                        <!-- Move-up -->
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" transform="translate(9.500000, 9.500000) scale(1, -1) translate(-9.500000, -9.500000) translate(0.000000, -0.000000)">
                            <title>{{ Lang::get("content.moveup") }}</title>
                            <ellipse stroke="#FFFFFF" cx="9.5" cy="9.5" rx="9.5" ry="9.5"></ellipse>
                            <polyline stroke="#FFFFFF" stroke-width="2" stroke-linecap="square" points="5 7 9.26999998 12.2658068 13.54 7"></polyline>
                        </g>
                    </svg>
                    <svg class="move-down" width="21px" height="22px" viewBox="33 -2 21 22" version="1.1" xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink">
                        <!-- Move Down -->
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" transform="translate(34.000000, -0.000000)">
                            <title>{{ Lang::get("content.movedown") }}</title>
                            <ellipse stroke="#FFFFFF" cx="9.5" cy="9.5" rx="9.5" ry="9.5"></ellipse>
                            <polyline stroke="#FFFFFF" stroke-width="2" stroke-linecap="square" points="5 7 9.26999998 12.2658068 13.54 7"></polyline>
                        </g>
                    </svg>
                    <svg class="delete" width="17px" height="17px" viewBox="273 32 17 17" version="1.1" xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink">

                        <!-- Delete -->
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" transform="translate(275.000000, 34.000000)" stroke-linecap="round">
                            <title>{{ Lang::get("content.Delete") }}</title>
                            <path d="M0.342105263,0.295454545 L12.6578947,12.7045455" stroke="#FFFFFF" stroke-width="2.25"></path>
                            <path d="M0.342105263,0.295454545 L12.6578947,12.7045455" stroke="#FFFFFF" stroke-width="2.25" transform="translate(6.500000, 6.500000) scale(-1, 1) translate(-6.500000, -6.500000) "></path>
                        </g>
                    </svg>
                </div>
            </div>
        </div>
        <div class="exerciseImages">
            <img src="{{asset('assets/img/transparent.png')}}" onClick="editExercise(this)" style="cursor: pointer">
        </div>
    </div>

    <div class="exerciseDetails">
        <div class="exerciseDetails-topContainer">
            <div class="tempoContainer">
               <!--  <button onclick="showTempo(this)">+ add tempo</button>
                <div class="tempo-active hide">
                    <div>{{ Lang::get("content.Tempo") }}</div>
                    <input class="tempo1" onChange="updateTempo(this)" value=""/>
                    <input class="tempo2" onChange="updateTempo(this)" value=""/>
                    <input class="tempo3" onChange="updateTempo(this)" value=""/>
                    <input class="tempo4" onChange="updateTempo(this)" value=""/>
                    <div onclick="hideTempo(this)"></div>
                </div> -->
            </div>

            <div class="unitSwitcherContainer">
                <p>{{ Lang::get("content.mi") }}</p>
                <label onclick="cardioUnitToggle(this)" class="unitToggleLabel">
                    <input type="checkbox" class="unitToggleInput">
                    <div class="unitToggleControl"></div>
                </label>
                <p>{{ Lang::get("content.km") }}</p>
            </div>
        </div>
        <div class="exercise-table">
            <table>
                <form>
                    <caption>{{ Lang::get("content.CIRCUIT CARDIO EXERCISE") }}</caption>
                    <thead>
                        <tr>
                            <th class="tbInt" scope="col"><p>{{ Lang::get("content.Interval") }}</p></th>
                            <th onclick="cardioRaiseMenu(this)" class="tbRepType tbHr" scope="col"><p>{{ Lang::get("content.HeartRate") }}</p></th>
                            <th class="tbSpeed" scope="col"><p>{{ Lang::get("content.Speed") }}</p></th>
                            <th class="tbDist" scope="col"><p>{{ Lang::get("content.Distance") }}</p></th>
                            <th class="tbTime" scope="col"><p>{{ Lang::get("content.Time") }}</p></th>
                            <th class="tbMode" scope="col"><p>{{ Lang::get("content.Mode") }}</p></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="set cardioSet" setnumber="1">
                            <th scope="row">1</th>
                            <td class="bound hrtemplate hr"><input onChange="updateField('hr',this)"></td>
                            <td class="bound speed mih"><input onChange="updateField('speed',this)"></td>
                            <td class="bound distance mi"><input onChange="updateField('distance',this)"></td>
                            <td class="bound time min"><input onChange="updateField('time',this)" value="30"/></td>
                            <td class="repStyleChoice" onclick="cardioRaiseMenu(this,0)"><p>{{ Lang::get("content.HR") }}</p></td>
                        </tr>
                    </tbody>
                </form>
            </table>
            <div class="setManagement">
                <button class="addSet" onclick="addingSet(this)">+ {{ Lang::get("content.Add Interval") }}</button>
                <button class="removeSet" onclick="removeSet(this)">- {{ Lang::get("content.Remove Interval") }}</button>
            </div>
        </div>
    </div>
</div>



<!-- *************************************************** -->

<!--                 CIRCUIT HEADER                      -->

<!-- *************************************************** -->

<div class="circuitContainer templateCircuit mainExerciseBlock" style="display:none">
<div class="cw-exercise circuit circuitEditing" id="templateCircuit2">
    <div class="exerciseHeader">
         <svg title="Delete" class="delete circuitDelete" onclick="deleteCircuitAdding(this)" width="17px" height="17px" viewBox="273 32 17 17" version="1.1" xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink">
                    <!-- Delete -->
            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" transform="translate(275.000000, 34.000000)" stroke-linecap="round">
                <path d="M0.342105263,0.295454545 L12.6578947,12.7045455" stroke="#FFFFFF" stroke-width="2.25"></path>
                <path d="M0.342105263,0.295454545 L12.6578947,12.7045455" stroke="#FFFFFF" stroke-width="2.25" transform="translate(6.500000, 6.500000) scale(-1, 1) translate(-6.500000, -6.500000) "></path>
            </g>
        </svg>
        <h1>{{ Lang::get("content.Circuit") }} #1</h1>

        <div class="circuitExerciseOptions">
            <div class="circuitSetUp" style="display: block;">
                <!-- <form> -->
                    <fieldset class="ciruitStyleChoice">
                        <span class="optionId">1</span>
                        <div class="option">
                            <input name="circuitStyle" value="rounds" id="optNbRounds" type="radio"  onclick="ciruitStyleChoice(this)">
                            <label for="optNbRounds" onclick="ciruitStyleChoice(this)"><span></span>{{ Lang::get("content.Number of rounds") }}</label>
                        </div>
                        <div class="option">
                            <input name="circuitStyle" value="amrap" id="optAmrap" type="radio" onclick="ciruitStyleChoice(this)">
                            <label for="optAmrap" onclick="ciruitStyleChoice(this)"><span></span>{{ Lang::get("content.AMRAP (as many round as possible)") }}</label>
                        </div>
                        <div class="option">
                            <input name="circuitStyle" value="emom" id="optEmom" type="radio" onclick="ciruitStyleChoice(this)">
                            <label for="optEmom" onclick="ciruitStyleChoice(this)"><span></span>{{ Lang::get("content.EMOM (every minute on the minute)") }}</label>
                        </div>
                    </fieldset>
                    <fieldset class="circuitStyle-rounds">
                        <span class="optionId">2</span>
                        <div class="option">
                            <input type="number" oninput="this.value = this.value.replace(/[^0-9.]/g, '')" inputmode="decimal" name="nbRounds" id="nbRounds" class="numberOfRounds likeNumber" value="1">
                            <label for="nbRounds">{{ Lang::get("content.nb of Rounds") }}</label>
                        </div>
                        <span class="optionId">3</span>
                        <div class="option">
                            <input type="number" oninput="this.value = this.value.replace(/[^0-9.]/g, '')" inputmode="decimal" name="restBtwRounds" id="restBtwRounds" class="numberOfRoundsRest likeNumber">
                            <label for="restBtwRounds">{{ Lang::get("content.sec rest between rounds (optional)") }}</label>
                        </div>
                    </fieldset>
                    <fieldset class="circuitStyle-amrap">
                        <span class="optionId">2</span>
                        <div class="option">

                            <input type="number" oninput="this.value = this.value.replace(/[^0-9.]/g, '')" inputmode="decimal" name="amrap" id="amrap" class="amrapValue likeNumber">
                            <label for="amrap">{{ Lang::get("content.Total time (optional)") }}</label>
                        </div>
                        <span class="optionId">3</span>
                        <div class="option">
                            <input type="number" oninput="this.value = this.value.replace(/[^0-9.]/g, '')" inputmode="decimal" name="restBtwRoundsAm" id="restBtwRoundsAm" class="likeNumber numberOfRoundsRestAm">
                            <label for="restBtwRoundsAm">{{ Lang::get("content.sec rest between rounds (optional)") }}</label>
                        </div>
                    </fieldset>
                    <fieldset class="circuitStyle-emom">
                        <span class="optionId">2</span>
                        <div class="option">
                            <input type="number" oninput="this.value = this.value.replace(/[^0-9.]/g, '')" inputmode="decimal" name="emom" id="emom" class="emomValue likeNumber numberOfRoundsRestEmom" value="1">
                            <label for="emom">{{ Lang::get("content.nb of minutes") }}</label>
                        </div>
                    </fieldset>
                    <fieldset class="circuitStyleSubmit">
                        <button onclick="saveCircuitInfo(this)">{{ Lang::get("content.Save") }}</button>
                    </fieldset>
                <!-- </form> -->
            </div>

            <div class="circuitInfo">
                <div class="nbrounds">
                    <div class="circleInstruction"><p class="roundsMeasure" style="color: #ffffff">4</p><span>{{ Lang::get("content.rounds") }}</span></div>
                </div>
                <div class="amrap">
                    <div class="circleInstruction"><p class="amrapMeasure" style="color: #ffffff">4</p><span>{{ Lang::get("content.minutes") }}</span></div>
                    <span>{{ Lang::get("content.AMRAP") }}</span>
                </div>
                <div class="emom">
                    <div class="circleInstruction"><p class="emomMeasure" style="color: #ffffff">4</p><span>{{ Lang::get("content.minutes") }}</span></div>
                    <span>{{ Lang::get("content.EMOM") }}</span>
                </div>
            </div>
            <div class="exerciseOptions">
                <svg class="edit" width="22px" height="24px" viewBox="139 28 22 24" version="1.1" xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink">
                    <!-- Edit that-->
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" transform="translate(150.000000, 40.000000) rotate(43.000000) translate(-150.000000, -40.000000) translate(148.000000, 28.500000)">
                        <title>{{ Lang::get("content.Edit") }}</title>
                        <rect fill="#FFFFFF" x="-2.27373675e-13" y="-2.13162821e-13" width="4" height="17.3333333" rx="0.506903261">
                        </rect>
                        <!-- <title>{{ Lang::get("content.Edit") }}</title> -->
                        <path d="M1.3671875,18.7874958 C1.71668019,17.7390178 2.28230926,17.7359861 2.6328125,18.7874958 L4,22.8890583 L1.080247e-12,22.8890583 L1.3671875,18.7874958 Z" fill="#FFFFFF" transform="translate(2.000000, 20.444529) scale(1, -1) translate(-2.000000, -20.444529) "></path>
                    </g>
                </svg>
                <svg  class="duplicate" width="18px" height="22px" viewBox="174 29 18 22" version="1.1" xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink">
                    <!-- Duplicate -->
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" transform="translate(174.694311, 29.225436)">
                        <title>{{ Lang::get("content.Duplicate") }}</title>
                        <path d="M14.4237037,17.9598222 L16.7796296,17.9598222 L16.7796296,0 L3.35592593,0 L3.35592593,2 L14.4237037,2 L14.4237037,17.9598222 Z" fill="#FFFFFF"></path>
                        <rect fill="#FFFFFF" x="0.5" y="2.5" width="13.4237037" height="17.9598222"></rect>
                    </g>
                </svg>
                <svg class="move-up" width="21px" height="22px" viewBox="-1 -2 21 22" version="1.1" xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink">
                    <!-- Move-up -->
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" transform="translate(9.500000, 9.500000) scale(1, -1) translate(-9.500000, -9.500000) translate(0.000000, -0.000000)">
                        <title>{{ Lang::get("content.moveup") }}</title>
                        <ellipse stroke="#FFFFFF" cx="9.5" cy="9.5" rx="9.5" ry="9.5"></ellipse>
                        <polyline stroke="#FFFFFF" stroke-width="2" stroke-linecap="square" points="5 7 9.26999998 12.2658068 13.54 7"></polyline>
                    </g>
                </svg>
                <svg class="move-down" width="21px" height="22px" viewBox="33 -2 21 22" version="1.1" xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink">
                    <!-- Move Down -->
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" transform="translate(34.000000, -0.000000)">
                        <title>{{ Lang::get("content.movedown") }}</title>
                        <ellipse stroke="#FFFFFF" cx="9.5" cy="9.5" rx="9.5" ry="9.5"></ellipse>
                        <polyline stroke="#FFFFFF" stroke-width="2" stroke-linecap="square" points="5 7 9.26999998 12.2658068 13.54 7"></polyline>
                    </g>
                </svg>
                <svg class="delete" width="17px" height="17px" viewBox="273 32 17 17" version="1.1" xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink">

                    <!-- Delete -->
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" transform="translate(275.000000, 34.000000)" stroke-linecap="round">
                        <title>{{ Lang::get("content.Delete") }}</title>
                        <path d="M0.342105263,0.295454545 L12.6578947,12.7045455" stroke="#FFFFFF" stroke-width="2.25"></path>
                        <path d="M0.342105263,0.295454545 L12.6578947,12.7045455" stroke="#FFFFFF" stroke-width="2.25" transform="translate(6.500000, 6.500000) scale(-1, 1) translate(-6.500000, -6.500000) "></path>
                    </g>
                </svg>
            </div>
        </div>
    </div> <!-- End of Exercise Header -->

    <div class="containerExerciseCircuit">


    </div>


    <div class="addExerciseToCircuit">
        <button onclick="showSearch('circuit',this)">
            <svg width="14" height="14" viewBox="0 0 14 14" xmlns="https://www.w3.org/2000/svg">
                <title>
                    {{ Lang::get("content.ADD ICON") }}
                </title>
                <path d="M7.5 6.5V0h-1v6.5H0v1h6.5V14h1V7.5H14v-1H7.5z" fill="#FFF" fill-rule="evenodd"/></path>
            </svg>
            {{ Lang::get("content.Add Exercise") }}
        </button>
    </div>
    <div class="repeatCircuit">
        <svg width="37" height="37" viewBox="0 0 37 37" xmlns="https://www.w3.org/2000/svg">
            <title>
                {{ Lang::get("content.Rewind Icon") }}
            </title>
            <g transform="translate(1 1)" fill="none" fill-rule="evenodd">
                <circle stroke="#2C3E50" fill="#FFF" cx="17.5" cy="17.5" r="17.5"/>
                <path d="M26.79 18.148a8.69 8.69 0 0 0-.623-3.238c-1.366-3.413-4.828-5.836-8.883-5.836a9.73 9.73 0 0 0-6.308 2.285m-3.198 6.788c0 5.012 4.256 9.074 9.506 9.074 1.865 0 5.073-1.398 5.073-1.398" stroke="#2C3E50" stroke-width="2"/>
                <path fill="#2C3E50" d="M28.144 23.455l1.343-7.904-7.076 2.3zM8.896 11.366L4.453 18.04l7.4.778z"/>
            </g>
        </svg>
        <div class="restTimeBtwRounds">
            <p><span class="restText"></span> {{ Lang::get("content.sec rest before next rounds") }}</p>
        </div>
        <div class="restTimeBtwRoundsEmom" style="display:none">
            <p>{{ Lang::get("content.Rest for the remainder of the minute") }}</p>
        </div>
    </div>

</div>
</div>  <!-- End of Circuit -->



<div id="workout" class="cw">

</div>

<div class="saveStatusContainer">
<div class="saveStatus">
    <div class="saving">
        <p>{{ Lang::get("content.Saving...") }}</p>
        <svg class="svglogo" viewBox="48 624 75 44" version="1.1" xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink">
            <path d="M55.9050083,643.473922 C57.8502224,643.473922 59.170563,642.81821 60.4821906,642.12919 C54.6213723,643.241017 48.8374848,639.147914 48,632.317903 L57.7090724,632.172095 L58.1157489,629.602946 L64.6499275,624 L70.2555738,624 L68.9349218,631.922143 C68.9349218,631.922143 73.3778641,631.781882 76.6215725,631.922143 C78.6129746,632.172095 80.9450904,632.904572 81.91466,634.057342 C83.1282805,635.214434 84.4438017,637.655031 85.0789869,641.60202 C85.3276399,642.814798 85.7363868,645.105949 85.8959429,646.707094 L92.4232046,632.445835 C94.5327116,631.433663 99.2883965,630.909341 103.533025,632.317903 L105.594012,648.908635 L117.397987,629.602946 L121.573913,628.180221 L123,632.317903 L107.813837,665.852105 C104.090005,667.040586 100.215946,666.701015 97.9855533,665.852105 L95.1934367,649.133937 C95.1934367,649.133937 87.9149943,664.224692 86.9186269,665.852105 C83.5448588,667.040586 78.6916295,666.57257 77.1547718,665.852105 C77.1547718,664.454168 77.1547718,649.364085 76.3928232,646.992663 C76.3928232,646.992663 75.9838585,642.561557 73.3778641,642.12919 L67.2919378,642.12919 C67.2919378,642.12919 64.8505806,655.392393 65.137753,655.817958 C65.3324231,656.417611 65.6840762,656.494944 66.0346147,656.790324 C67.4484767,657.072533 67.7616652,657.324687 73.0273256,656.018891 L73.0273256,664.179093 C70.002481,666.135873 66.1212457,667.954418 59.7300492,667.040586 C56.175271,666.393653 53.4914869,664.518152 53.3366213,659.771025 C53.615023,656.204743 54.651364,651.640089 55.9050083,643.473922 Z" id="Logo-TW" stroke="none" fill="#FFFFFF" fill-rule="evenodd"></path>
        </svg>
    </div>
    <div class="saved">
        <p>{{ Lang::get("content.saved") }}</p>
        <svg class="svglogo" viewBox="259 636 19 17" version="1.1" xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink">
            <polyline id="Line" stroke="#FFFFFF" stroke-width="1.5" stroke-linecap="square" fill="none" points="261 646.84 266.948276 651 276 638"></polyline>
        </svg>
    </div>
    <div class="failed">
        <p>{{ Lang::get("content.Saving failed, check your connection and please try again") }}</p>
    </div>
</div>
</div>





<div class="hide">
<form method="post" enctype="multipart/encode" action="{{ Lang::get("routes./Trainer/CreateWorkout") }}" id="createform" style="display:none">
<input type="hidden" name="exercises" id="exercises" value="">
<input type="hidden" name="exerciseGroup" id="exerciseGroup" value="">
<input type="hidden" name="exerciseGroupsRest" id="exerciseGroupsRest" value="">
<input type="hidden" name="workoutName" id="workoutName" value="">
<input type="hidden" name="clientId" id="clientId" value="">
<input type="hidden" name="notes" id="noteToWorkoutForm" value="">
<input type='hidden' name='id' value="{{ $workout->id }}" id="id" />
<input type="submit" value="submit">
</form>
</div>


@include('popups.addExerciseInWorkout', array("bodygroups" => $bodygroups, "equipments"=>$equipments))

@include('popups.suggested-exercise')


@endsection

@section('scripts')
{{ HTML::script(asset('assets/js/templates.js')) }}
{{ HTML::script(asset('assets/js/verify.notify.js')) }}
<script src="{{asset('assets/js/jquery.bpopup.min.js')}}"></script>
 <!-- CHOSEN SELCT BOX -->
<script>
    $(document).on('keyup','#exercise_search', function (e){
        if(e.keyCode == 13) {
            searchExercise();
        }
    });

    function clearSearch(){
        $("#exercise_search").val("");
    }
document.addEventListener("keyup", function(event) {
    if (event.key === "Enter"|| e.keyCode === 13) {
        const activeElement = document.activeElement;
        if (activeElement && (activeElement.tagName === "INPUT" || activeElement.tagName === "TEXTAREA")) {
            activeElement.blur(); // Closes the keyboard
        }
    }
});
function numberOnly(input){
    // Accepts numbers only in input
    input.addEventListener('keypress', function(e) {
        var charCode = (e.which) ? e.which : event.keyCode;
        var inputValue = $(this).val();

        // Check if the pressed key is a digit or a single dot
        if ((charCode >= 48 && charCode <= 57) || charCode === 46) {
            if (inputValue.indexOf('.') !== -1 && charCode === 46) {
                return false;
            }
            return true;
        } else {
            return false;
        }
    });
}

var exerciseGroups = [];
var exerciseGroupsRest = [];
var workout_name_empty    = "{{ Lang::get("content.createWorkout/validation1") }}";
var no_exercises_selected = "{{ Lang::get("content.createWorkout/validation2") }}";
var no_exercises_dragged  = "{{ Lang::get("content.createWorkout/validation2") }}";
var globalExName = '';
var exercises = [];
var filters = [];
var addMode = "muscle";
var addModeObject = "muscle";
var response = [];
var firstLoadData = "";
var activeGroup = 0;
var rangeConstant = "8-10";
var maxRepConstant = "maximum";
var repConstant = "8";
var speedConstant = "";
var timeConstant = "20";
var hrConstant = "150";
var distanceConstant = "";
var timeConstant = "30";
var maxRepCardioConstant = "20";
var vo2Constant = "70";
var reserverConstant = "40";
var rangeCardioConstant = "120-150";
var maxCardioConstant = "Max";
var workoutName = "";
var weightConstant = 0;
var editId = "";
var addToCircuit = "";
var autoSaveExercise = "";
var totalExDisplayed = 0;



$(document).on("keydown", function (e) {
    if (e.which === 8 && !$(e.target).is("input, textarea")) {
        e.preventDefault();
    }
});


$(document).ready(function(){

    // $('#exercise_search').on('keypress', function(e) {
    //     if (e.keyCode == 13) {
    //         searchExercise();
    //     }
    // });


    $(".exerciseOptions svg title").each(function(){
        var title = $(this).html();
        var div = '<div class="hoverTxt"><p>' + title + '</p></div>';
        // $(this).closest("svg").after(div);
    })

});

// ================
//    Alain Code
// ================

var numberExercises = 0;

function addingExerciseMenu () {
    var $nbExercise = $("#workout").find(".cw-exercise").length;
    if($nbExercise > 0) {
        $("#addingExercise").addClass("cw-adding");
    } else {
        $("#addingExercise").removeClass("cw-adding");
    }
}

addingExerciseMenu();

//close exercise pop up
function closeExercise() {
   $(".overlayKillParent").removeClass("overlayKillParent-active");
    $("#o-wrapper *").removeClass("gone");
}


// open exercsie adding
function addExercise() {
  $(".overlayKillParent").addClass("overlayKillParent-active");
  $("#o-wrapper *").not(".exerciseOverlay, .exerciseOverlay *").addClass("gone");
  $(".overlayKillChild").click(function() {
    closeExercise();
  });

  document.getElementById("exercise_form").reset();
  detectFile("img1");
  detectFile("img2");
  detectFile("video");
}

function suggestExercise() {
    $(".overlayKillParent").addClass("overlayKillParent-active");
    $("#o-wrapper *").not(".exerciseOverlay, .exerciseOverlay *").addClass("gone");
    $(".overlayKillChild").click(function() {
        closeExercise();
    });

    document.getElementById("suggest_form").reset();
}

//Adding a note to your workout POP UP
function workoutNote(object) {
    var HaddingNote = $(object).closest(".ptAddNote");
    var HaddNote = $(object).closest(".ptAddNote").find("span");
    var Hnote = $(object).closest(".ptAddNote").find(".note");
    var Hspan = $(object).closest(".ptAddNote").find(".spanContainer");

    Hspan.toggleClass("spanOpen");
    HaddNote.toggle();
    HaddingNote.toggleClass("ptAddingNote");
    Hnote.toggle();
}

//Adding a note to the exercise
function exerciseNote(object) {
    var EaddingNote = $(object).closest(".exerciseNote");
    var EaddNote = $(object).closest(".exerciseNote").find("span");
    var Enote = $(object).closest(".exerciseNote").find(".note");
    var EnoteChild = $(object).closest(".exerciseNote").find(".note").children();
    var Espan = $(object).closest(".exerciseNote").find(".spanContainer");

    Espan.toggleClass("spanOpen");
    EaddNote.toggle();
    EaddingNote.toggleClass("ptAddingNote");
    if (Enote.css("display") == "none") {
        EnoteChild.show();
        EnoteChild.css("opacity", "1");
    } else {
        EnoteChild.hide();
        EnoteChild.css("opacity", "0");
    }
    Enote.toggle();
}

function saveNote(object){
    id = $(object).closest(".exerciseTarget").attr("id");
    idParts = explodeId(id);
    group = idParts[0];
    exNumber = idParts[1];
    notes = $(object).closest(".exerciseNote").find(".noteToExercise").val();

    exerciseGroups[group][exNumber].notes = notes;

    exerciseNote(object);

    $(object).closest(".exerciseNote").find("span").html(dict["note"] + "<br>" + dict["added"]);
    $(object).closest(".exerciseNote").find(".spanContainer").css("padding", "8px 0px");
}

function saveNoteWorkout(object){
    // How to add the notes to this workout, how to find this workout id?
    notes = $("#noteToWorkout").val();

    workoutNote(object);
    $(object).closest(".ptAddNote").find("span").html(dict["note"] + "<br>" + dict["added"]);
}


// opening or closing the tags buckets
$("div[class^='tag']").find("h4").click(function () {
    $("div[class^='tag']").find(".selctableTagContainer").slideUp();
    if ($(this).parent().hasClass("active")) {
        $(this).parent().toggleClass("active");
    } else {
        $("div[class^='tag']").removeClass("active");
        $(this).parent().toggleClass("active");
        $(this).parent().find(".selctableTagContainer").slideDown();
    }
});

// chaning the selcted status
$(".searchTag").click(function() {
    $(this).toggleClass("selected");
});

//Opening and closing the search box
function showSearch(addModeVar,object) {
    addMode = addModeVar;
    addModeObject = object;
    if(addMode == "circuit"){
        addToCircuit = $(object).closest(".circuitContainer").attr("group");
    }

    $(".searchPop").show();

    triggerFirstLoad();
}

function hideSearch() {
    $(".searchPop").hide();
    emptyFilters();
     $("#search_results ul").html("");

}

function closeTabs() {
    var tabs = document.getElementsByClassName("tabContent");
    for (i = 0; i < tabs.length; i++) {
        tabs[i].style.display = "none";
    }
}

function openTab(id) {
    var tab = document.getElementById(id);
    if(tab.style.display === "none") {var open = true} else {var open = false};

    closeTabs();

    open = true;

    if(open == true) {
        tab.style.display = "block";
    }
}



//Showing and hiding of exercise description
function exeDescriptionToggle(el) {
    if ($(el).find(".exeDescription_full").is(":visible")) {
        $(el).find(".exeDescription_full").slideUp(300);
        $(el).find(".exeDescription_Exp path:nth-child(2)").css("opacity", "1");
    } else {
        $(el).find(".exeDescription_full").slideDown(300);
        $(el).find(".exeDescription_Exp path:nth-child(2)").css("opacity", "0");
    }
};

function saveCircuitInfo(object){
    circuit = parseInt($(object).closest(".circuitContainer").attr("group"));
    if(debug) console.log("Group Number: "+circuit);
    var cir = {};
    cir["circuitStyle"] = "";
    if(debug) console.log($(object).closest(".circuitSetUp").find(".numberOfRounds").val());
    if(debug) console.log($(object).closest(".circuitSetUp").find(".numberOfRoundsRest").val());


    circuitType = $('input[name=circuitStyle]:checked').val();
    if(debug) console.log(circuitType);

    $(object).closest(".exerciseHeader").find(".nbrounds").hide();
    $(object).closest(".exerciseHeader").find(".amrap").hide();
    $(object).closest(".exerciseHeader").find(".emom").hide();




    cir["circuitStyle"] = circuitType;
    cir["type"] = "circuit";
    cir["restBetweenCircuitExercises"] = [];


    exerciseGroupsRest[circuit] = cir;

    console.log(exerciseGroupsRest[circuit]);
    console.log(circuit);

    if(exerciseGroupsRest[circuit] == undefined){
        if(circuit >= 1){
            newElementRest = $(".templateRestBetweenGroups").clone().removeClass("templateRestBetweenGroups").removeAttr("style");
            $("#workout").find(".mainExerciseBlock").last().before(newElementRest);
        }

    }

    if(circuitType == "rounds"){
      circuitType = "rounds";
      exerciseGroupsRest[circuit]["circuitRound"] = $(object).closest(".circuitSetUp").find(".numberOfRounds").val();
      $(object).closest(".exerciseHeader").find(".nbrounds").show();
      $(object).closest(".exerciseHeader").find(".roundsMeasure").html(exerciseGroupsRest[circuit]["circuitRound"]);
      exerciseGroupsRest[circuit]["circuitRest"] = $(object).closest(".circuitSetUp").find(".numberOfRoundsRest").val();

    }else if(circuitType == "amrap"){
      circuitType = "amrap";
      exerciseGroupsRest[circuit]["circuitMaxTime"] = $(object).closest(".circuitSetUp").find(".amrapValue").val();
      $(object).closest(".exerciseHeader").find(".amrap").show();
      $(object).closest(".exerciseHeader").find(".amrapMeasure").html(exerciseGroupsRest[circuit]["circuitMaxTime"]);
      exerciseGroupsRest[circuit]["circuitRest"] = $(object).closest(".circuitSetUp").find(".numberOfRoundsRestAm").val();

    } else if(circuitType == "emom") {
      circuitType = "emom";
      exerciseGroupsRest[circuit]["circuitEmom"] = $(object).closest(".circuitSetUp").find(".emomValue").val();
      $(object).closest(".exerciseHeader").find(".emom").show();
      $(object).closest(".exerciseHeader").find(".emomMeasure").html(exerciseGroupsRest[circuit]["circuitEmom"]);
      exerciseGroupsRest[circuit]["circuitRest"] = $(object).closest(".circuitSetUp").find(".numberOfRoundsRestEmom").val();
    }
    // if it is emom, don't show the restTimeBtwRounds div
    if(circuitType == 'emom'){
        $(object).closest(".circuitContainer").find('.restTimeBtwRounds').css('display','none');
        $(object).closest(".circuitContainer").find('.restTimeBtwRoundsEmom').css('display','block');
    } else {
        $(object).closest(".circuitContainer").find('.restTimeBtwRoundsEmom').css('display','none');
    }
    $(object).closest(".circuitContainer").find(".restText").text(exerciseGroupsRest[circuit]["circuitRest"]);

    $(object).closest(".circuitEditing").removeClass("circuitEditing");
    $(object).closest(".circuit").find(".circuitExerciseOptions").addClass("rowStyle");
    $(object).closest(".circuit").find(".circuitSetUp").hide();
    $(object).closest(".circuit").find(".circuitDelete").hide();


    fillUpCircuit(circuit);

    addingExerciseMenu();

}

//Showing Tempo editing
function showTempo(div){
    $(div).toggleClass("hide");
    $(div).closest(".tempoContainer").find(".tempo-active").toggleClass("hide");

}

function hideTempo(div){
    $(div).closest(".tempoContainer").find(".tempo-active").toggleClass("hide");
    $(div).closest(".tempoContainer").find("button").toggleClass("hide");
    id = $(div).closest("template").attr("id");
    idParts = explodeId(id);
    group = idParts[0];
    exNumber = idParts[1];
    exerciseGroups[group][exNumber].tempo1 = null;
    exerciseGroups[group][exNumber].tempo2 = null;
    exerciseGroups[group][exNumber].tempo3 = null;
    exerciseGroups[group][exNumber].tempo4 = null;
}

function setExerciseMetric(object){
    id = $(div).closest(".exerciseTarget").attr("id");
    idParts = explodeId(id);
    group = idParts[0];
    exNumber = idParts[1];
    if($(object).attr("checked") == "checked"){
        exerciseGroups[group][exNumber].metric = "metric";
    } else {
        exerciseGroups[group][exNumber].metric = "imperial";
    }
}


//Hiding Tempo editing
$(".tempo-active").children("div:last-child").click(function() {
    $(this).parent(".tempo-active").toggleClass("hide");
    $(".tempoContainer").children("button").toggleClass("hide");
});

//Exercise input styling
$(".exerciseTarget").find("input").focusout(function(){
    if ($(this).val() != "") {
        $(this).addClass("fill");
    } else {
        $(this).removeClass("fill");
    }
});


//add the remove set button to the window
function setButtons(object) {
    //selectig the number of existing sets.
    var set = $(object).closest(".exercise-table").find(".set");
    //Find out how many set there are in the exercise
    var nbSet = set.length;
    //test for number of set
    if (nbSet > 1) {
        //add the remove set button
        $(object).closest(".setManagement").addClass("setManagement-active");
    } else {
        //remove the remove set button
        $(object).closest(".setManagement").removeClass("setManagement-active");
    }
}

function saveRestTime(object){
    id = $(object).closest(".btwExerciseRest").prev().attr("id");
    idParts = explodeId(id);
    group = idParts[0];
    exNumber = idParts[1];
    if(debug) console.log(group);
    if(debug) console.log(exerciseGroupsRest[group]);
    if(exerciseGroupsRest[group] === undefined) exerciseGroupsRest[group] = {};
    if(exerciseGroupsRest[group].restBetweenCircuitExercises === undefined) exerciseGroupsRest[group].restBetweenCircuitExercises = [];
    exerciseGroupsRest[group].restBetweenCircuitExercises[exNumber] = $(object).val();

}

function saveRestTimeBetweenGroups(object){
    id = $(object).closest(".btwExerciseRest").prev().attr("group");
    idParts = explodeId(id);
    group = idParts[0];
    if(debug) console.log(group);
    if(debug) console.log(exerciseGroupsRest[group]);
    if(exerciseGroupsRest[group] === undefined) exerciseGroupsRest[group] = {};
    exerciseGroupsRest[group].restTime = $(object).val();

}



//Adding Set
function addingSet(object) {

    //selectig the number of existing sets.
    var set = $(object).closest(".exercise-table").find(".set");

    //Find out how many set there are in the exercise
    var nbSet = set.length;

    // adjust the number of set to be added in the new row header
    nbSet += 1;

    // get the number of td in the table
    var nbTd = set.children("td").length;

    //Make a rest set
    var muscleRest = '<tr class="restBtwSet">';
    muscleRest += '<th scope="row"><button onclick="addRestBetweenSets(this,'+nbSet+')">+</button><span>'+dict["rest"]+'</span></th>';
    muscleRest += `<td colspan="${nbTd}" class="secRest"><p style="text-align:center;">${dict["add rest between set"]}</p><input type="number" oninput="this.value = this.value.replace(/[^0-9.]/g, '')" inputmode="decimal" class="restBetweenSets" onChange="addRestBetweenSetsInfo(this)" setNumber="${nbSet}" /></td>'`;
    muscleRest += '</tr>';

    //Add rest time to the table
    $(object).closest(".exercise-table").find("tbody").append(muscleRest);

    //Clone a set
    var setClone = set.first().clone();

    setClone.attr("setNumber",nbSet);

    //Change the number of interval to display in the th
    setClone.children("th").text(nbSet);

    id = $(object).closest(".exerciseTarget").attr("id");
    idParts = explodeId(id);
    group = idParts[0];
    exNumber = idParts[1];

    if(exerciseGroups[group][exNumber].exercise.bodygroupId == 18){
        if(debug) console.log("Cardio Adding a Set"+nbSet);
        setClone.find(".repStyleChoice").attr("onclick","cardioRaiseMenu(this,"+(nbSet-1)+")");
    } else {
        if(debug) console.log("Muscle Adding a Set"+nbSet);
        setClone.find(".repStyleChoice").attr("onclick","muscleBoundMenu(this,"+(nbSet-1)+")");
    }

    //Add a class to Set clone to seperate the following sets.
    setClone.addClass("followingSet");

    //Add the new set to the table
    $(object).closest(".exercise-table").find("tbody").append(setClone);

    //fix the set buttons
    setButtons(object);

    //bind new td to the focus out function
    $(".exerciseTarget").find("input").focusout(function(){
        if ($(object).val() != "") {
            $(object).addClass("fill");
        } else {
            $(object).removeClass("fill");
        }
    }); //End binding focusout event

    //bind new rest time to the add rest time functions
     $(".restBtwSet").find("button").click(function (event){
        event.preventDefault();
        var input = "<input></input>";
        //$(object).hide();
        $(object).closest(".restBtwSet").addClass("restBtwSet-active");
        $(object).parent("th").next("td").addClass("secRest");
    }); // end new rest time function



    //DIEGO
    var sets = [];
    var speeds = [];
    var distances = [];
    var times = [];
    var hrs = [];
    var sets = [];
    var weights = [];
    var repsType = [];
    $(object).closest(".exercise-table").find(".set").each(function(index){
        weight = $(this).find(".weight").find("input").val();
        reps = $(this).find(".bound").find("input").val();
        hr = $(this).find(".hrtemplate").find("input").val();
        speed = $(this).find(".speed").find("input").val();
        distance = $(this).find(".distance").find("input").val();
        time = $(this).find(".time").find("input").val();
        //if(debug) console.log("---");
        //if(debug) console.log(exerciseGroups[group][exNumber].repsType);
        //if(debug) console.log(index);
        if(exerciseGroups[group][exNumber].repsType[index] !== undefined){
            repType = exerciseGroups[group][exNumber].repsType[index];
        } else {
            if(index > 0){
                repType = exerciseGroups[group][exNumber].repsType[index-1]
            } else {
                repType = exerciseGroups[group][exNumber].repType;
            }
        }

        sets.push(reps);
        speeds.push(speed);
        distances.push(distance);
        times.push(time);
        hrs.push(hr);
        weights.push(weight);
        repsType.push(repType);

    });

    id = $(object).closest(".exerciseTarget").attr("id");
    idParts = explodeId(id);
    group = idParts[0];
    exNumber = idParts[1];
    exerciseGroups[group][exNumber].times = times;
    exerciseGroups[group][exNumber].hrs = hrs;
    exerciseGroups[group][exNumber].distances = distances;
    exerciseGroups[group][exNumber].speeds = speeds;
    exerciseGroups[group][exNumber].repArray = sets;
    exerciseGroups[group][exNumber].weights = weights;
    exerciseGroups[group][exNumber].repsType = repsType;

} //End adding a Set


function addRestBetweenSetsInfo(object){
    id = $(object).closest(".exerciseTarget").attr("id");
    idParts = explodeId(id);
    group = idParts[0];
    exNumber = idParts[1];
    arrayRest = [];
    noFirst = true;
    $(object).closest(".exerciseTarget").find(".set").each(function(index){
        if(noFirst){
            noFirst = false;
        } else{
            arrayRest.push("");
        }
    });
    //console.log(arrayRest);
    $(object).closest(".exerciseTarget").find(".restBetweenSets").each(function(index){
        setNumber = $(this).attr("setNumber");
        arrayRest[(setNumber-2)] = $(this).val();
    });
    exerciseGroups[group][exNumber].restBetweenSets = arrayRest;
}

function updateField(type,object){
    id = $(object).closest(".exerciseTarget").attr("id");
    idParts = explodeId(id);
    group = idParts[0];
    exNumber = idParts[1];
    setNumber = $(object).closest("tr").attr("setNumber");
    setNumber = setNumber - 1;
    if(debug) console.log(type);
    if(debug) console.log(object);
    if(debug) console.log(group);
    if(debug) console.log(exNumber);
    if(type == "weight"){
        exerciseGroups[group][exNumber].weights[setNumber] = $(object).val();
    } else if(type == "rep"){
        exerciseGroups[group][exNumber].repArray[setNumber] = $(object).val();
    } else if(type == "hr"){
        exerciseGroups[group][exNumber].hrs[setNumber] = $(object).val();
    } else if(type == "speed"){
        exerciseGroups[group][exNumber].speeds[setNumber] = $(object).val();
    } else if(type == "distance"){
        exerciseGroups[group][exNumber].distances[setNumber] = $(object).val();
    } else if(type == "time"){
        exerciseGroups[group][exNumber].times[setNumber] = $(object).val();
    }
    if(debug) console.log(exerciseGroups[group][exNumber].weights);
    if(debug) console.log(exerciseGroups[group][exNumber].repArray);


}


function addRestBetweenSets(object,setNumber){
    id = $(object).closest(".exerciseTarget").attr("id");
    idParts = explodeId(id);
    group = idParts[0];
    exNumber = idParts[1];
    // change the button sign and row content on click
    if($(object).html()=='+'){
        $(object).html('x');
        $(object).closest(".restBtwSet").find("p").hide();
        $(object).closest(".restBtwSet").find("input").after("<p class='secBtwSet' style='display:inline-block'>{{ Lang::get("content.secbetweenset") }}</p>");
        $(object).closest(".restBtwSet").find("input").show();
        $(object).closest(".restBtwSet").find("td").addClass("secRest");
    } else {
        exerciseGroups[group][exNumber].restBetweenSets[setNumber-2] = "";
        $(object).html('+');
        $(object).closest(".restBtwSet").find("p").show();
        $(object).closest(".restBtwSet").find(".secBtwSet").remove();
        $(object).closest(".restBtwSet").find("input").hide();
        $(object).closest(".restBtwSet").find("td").removeClass("secRest");

    }
}

//Removing a Set
function removeSet(object){
    //remove the last set
    removeSetInfo(object);
    $(object).closest(".exercise-table").find("tr.followingSet:last").remove();
    //remove the last rest
    $(object).closest(".exercise-table").find("tr.restBtwSet:last").remove();
    // check for button status
    setButtons(object);
} // End removing a set


function removeSetInfo(object){
    id = $(object).closest(".exerciseTarget").attr("id");
    idParts = explodeId(id);
    group = idParts[0];
    exNumber = idParts[1];
    exerciseGroups[group][exNumber].repArray.pop();
    exerciseGroups[group][exNumber].times.pop();
    exerciseGroups[group][exNumber].speeds.pop();
    exerciseGroups[group][exNumber].distances.pop();
    exerciseGroups[group][exNumber].hrs.pop();
    exerciseGroups[group][exNumber].weights.pop();
    exerciseGroups[group][exNumber].repsType.pop();
}

//Unit Switcher Cardio
function cardioUnitToggle(object) {
    metric = "imperial";
    var speedTd = $(object).closest(".exerciseDetails").find(".speed");
    var distanceTd = $(object).closest(".exerciseDetails").find(".distance");
    if ($(object).find("input").is(':checked')) {
        //switch to mi.
        speedTd.removeClass("mih");
        distanceTd.removeClass("mi");
        speedTd.addClass("kmh");
        distanceTd.addClass("km");
        metric = "metric";
    } else {
        //switch to km
        speedTd.removeClass("kmh");
        distanceTd.removeClass("km");
        speedTd.addClass("mih");
        distanceTd.addClass("mi");
        metric = "imperial";
    }

    id = $(object).closest(".exerciseTarget").attr("id");
    idParts = explodeId(id);
    group = idParts[0];
    exNumber = idParts[1];
    exerciseGroups[group][exNumber].metric = metric;
}


//Unit Switcher Muscle
function weightUnitToggle(object)  {
    metric = "imperial";
    var weightTd = $(object).closest(".exerciseDetails").find(".weight");
    if ($(object).find("input").is(':checked')) {
        //switch to KG
        weightTd.removeClass("lbs");
        weightTd.addClass("kg");
        metric = "metric";
    } else {
        //switch to lbs
        weightTd.removeClass("kg");
        weightTd.addClass("lbs");
        metric = "imperial";
    }
    id = $(object).closest(".exerciseTarget").attr("id");
    idParts = explodeId(id);
    group = idParts[0];
    exNumber = idParts[1];
    exerciseGroups[group][exNumber].metric = metric;
};


//Appending a menu to an exercise & making it slowly come up from the bottom
function appendToExercise(menu,object) {
    //adding menu
    $(object).closest(".exerciseTarget").append(menu);
}

function buildExerciseSets(id){
    idParts = explodeId(id);
    group = idParts[0];
    exNumber = idParts[1];
    multipleModes = false;
    mainMode = "";
    // sets = exerciseGroups[group][exNumber].repArray;
    if(exerciseGroups[group][exNumber].exercise.bodygroupId == 18){
        sets = exerciseGroups[group][exNumber].hrs;
        if (debug) console.log('checkcheck');
            var heartcallback = "onclick='updateField(\"heart\",this)'";
            var speedcallback = "onclick='updateField(\"speed\",this)'";
            var distancecallback = "onclick='updateField(\"distance\",this)'";
            var timecallback = "onclick='updateField(\"time\",this)'";
            var html = "<table>"+
                        '<caption>'+dict["CARDIO EXERCISE"]+'</caption>'+
                        '<thead>'+
                            '<tr>'+
                                '<th scope="col" class="tbInt"><p>Interval</p></th>';
                                // '<th scope="col" class="tbRepType tbHr" onclick="cardioRaiseMenu(this)">Heart Rate</th>';
                                var repTypeCallback = "onclick='cardioRaiseMenu(this,null)'";
                                mainMode = exerciseGroups[group][exNumber].repType;
                                if(exerciseGroups[group][exNumber].repType == "effort"){
                                    html += "<th scope='col' class='tbHr tbRepType "+exerciseGroups[group][exNumber].repsType[x]+"' "+repTypeCallback+">"+dict["effort"]+"</th>";
                                } else if(exerciseGroups[group][exNumber].repType == "hr" || exerciseGroups[group][exNumber].repType == "rep"){
                                    html += "<th scope='col' class='tbHr tbRepType "+exerciseGroups[group][exNumber].repsType[x]+"' "+repTypeCallback+">"+dict["hr"]+"</th>";
                                } else if(exerciseGroups[group][exNumber].repType == "Vo2Max"){
                                    html += "<th scope='col' class='tbHr tbRepType "+exerciseGroups[group][exNumber].repsType[x]+"' "+repTypeCallback+">"+dict["hrVo2Max"]+"</th>";
                                } else if(exerciseGroups[group][exNumber].repType == "reserve"){
                                    html += "<th scope='col' class='tbHr tbRepType "+exerciseGroups[group][exNumber].repsType[x]+"' "+repTypeCallback+">"+dict["reserve"]+"</th>";
                                } else if(exerciseGroups[group][exNumber].repType == "range"){
                                    html += "<th scope='col' class='tbHr tbRepType "+exerciseGroups[group][exNumber].repsType[x]+"' "+repTypeCallback+">"+dict["HRrange"]+"</th>";
                                } else if(exerciseGroups[group][exNumber].repType == "max"){
                                    html += "<th scope='col' class='tbHr tbRepType "+exerciseGroups[group][exNumber].repsType[x]+"' "+repTypeCallback+">"+dict["max"]+"</th>";
                                } else {
                                    html += "<th scope='col' class='tbHr tbRepType "+exerciseGroups[group][exNumber].repsType[x]+"' "+repTypeCallback+">"+dict["exerciseMode"]+"</th>";
                                }
                                html +='<th scope="col" class="tbSpeed">'+dict["Speed"]+'</th>'+
                                '<th scope="col" class="tbDist">'+dict["Distance"]+'</th>'+
                                '<th scope="col" class="tbTime">'+dict["Time"]+'</th>';
                                html += "<th class='tbMode' scope='col'>"+dict["Mode"]+"</th>" +
                            '</tr>'+
                        '</thead>'+
                        '<tbody>';
                        for(t = 0; t < sets.length; t++){
                            if(exerciseGroups[group][exNumber].repsType[t] != mainMode) multipleModes = true;
                            if(t > 0){
                                html += '<tr class="restBtwSet">';
                                html += '<th scope="row"><button onclick="addRestBetweenSets(this,'+(t+1)+')">';
                                if(exerciseGroups[group][exNumber].restBetweenSets[t-1] != undefined && exerciseGroups[group][exNumber].restBetweenSets[t-1] !== ""){
                                    html += '-';
                                } else {
                                    html += '+';
                                }
                                html += '</button><span>'+dict["rest"]+'</span></th>';

                                if(exerciseGroups[group][exNumber].restBetweenSets[t-1] != undefined && exerciseGroups[group][exNumber].restBetweenSets[t-1] !== ""){
                                    html += '<td colspan="4" class="secRest">';
                                    html += '<p style=" text-align: center;display: none;">'+dict["add rest between set"]+'</p><input setnumber="'+(t+1)+'" onchange="addRestBetweenSetsInfo(this)" class="restBetweenSets" style="display: inline;" value="'+exerciseGroups[group][exNumber].restBetweenSets[t-1]+'">';
                                    html += '<p class="secBtwSet" style="display:inline-block">'+dict["seconds between set"]+'</p></td></tr>';
                                } else {
                                    html += '<td colspan="4">';
                                    html += '<p style=" text-align: center;display: block;">'+dict["add rest between set"]+'</p><input setnumber="'+(t+1)+'" onchange="addRestBetweenSetsInfo(this)" class="restBetweenSets" style="display: none;"></td></tr>';
                                }
                            }
                            if (t<1){
                                html += '<tr class="set cardioSet" setnumber="'+(t+1)+'">'+
                                '<th scope="row">'+(t+1)+'</th>';
                            }
                            else{
                            html += '<tr class="set followingSet cardioSet" setnumber="'+(t+1)+'">'+
                                '<th scope="row">'+(t+1)+'</th>';
                            }
                                var value=0;
                                var cardiotype = 'reps';
                                if(exerciseGroups[group][exNumber].repsType[t] == "effort"){
                                    value = exerciseGroups[group][exNumber].hrs[t];
                                    cardiotype = '% of effort';
                                } else if(exerciseGroups[group][exNumber].repsType[t] == "hr" || exerciseGroups[group][exNumber].repsType[t] == "rep"){
                                    value = exerciseGroups[group][exNumber].hrs[t];
                                    cardiotype = 'Heart Rate';
                                } else if(exerciseGroups[group][exNumber].repsType[t] == "Vo2Max"){
                                    value = exerciseGroups[group][exNumber].hrs[t];
                                    cardiotype = 'Vo2Max';
                                } else if(exerciseGroups[group][exNumber].repsType[t] == "reserve"){
                                    value = exerciseGroups[group][exNumber].hrs[t];
                                    cardiotype = 'Reserve';
                                } else if(exerciseGroups[group][exNumber].repsType[t] == "range"){
                                    value = exerciseGroups[group][exNumber].hrs[t]
                                    cardiotype = 'HR Range';
                                } else if(exerciseGroups[group][exNumber].repsType[t] == "max"){
                                    value = "maximum";
                                    cardiotype = 'Max';
                                }
                                metric = "mih";
                                metric2 = "mi";
                                if(exerciseGroups[group][exNumber].metric == "metric"){
                                    metric = 'kmh';
                                    metric2 = "km";
                                }
                                html += '<td class="bound hrtemplate hr"><input onChange="updateField(\'hr\',this)" value="'+value+'"></td>'+
                                '<td class="bound speed '+metric+'"><input onchange="updateField(\'speed\',this)" value="'+exerciseGroups[group][exNumber].speeds[t]+'"></td>'+
                                '<td class="bound distance '+metric2+'"><input onchange="updateField(\'distance\',this)" value="'+exerciseGroups[group][exNumber].distances[t]+'"></td>'+
                                '<td class="bound time min"><input onchange="updateField(\'time\',this)" value="'+exerciseGroups[group][exNumber].times[t]+'"></td>';
                                html +=  '<td class="repStyleChoice" onclick="cardioRaiseMenu(this,'+t+')">'+dict[cardiotype]+'</td>';
                            '</tr>';
                        }
                        html += '</tbody>'+
                    '</table>';
                    if(sets.length > 1){
                         html += '<div class="setManagement setManagement-active">';
                    } else{
                        html += '<div class="setManagement">';
                    };
                    html += '<button class="addSet" onclick="addingSet(this)">+ '+dict["Add Interval"]+'</button>'+
                    '<button class="removeSet" onclick="removeSet(this)">- '+dict["Remove Interval"]+'</button>'+
                    '</div>';
                    $("#"+id).find(".exercise-table").html(html);

                    if(multipleModes){
                        $("#"+id).find(".exercise-table").find(".tbRepType").html(dict["exerciseMode"]);
                    }

    } else {
            sets = exerciseGroups[group][exNumber].repArray;
            var html = "<table><thead>";
            html += "<tr>";
            html += "<th class='tbSet'>"+dict["Set"]+"</th>";
            html += "<th class='tbWeight'>"+dict["Weight"]+"</th>";
            var weightCallback = "onclick='updateField(\"weight\",this)'";
            var repCallback = "onclick='updateField(\"rep\",this)'";
            var repTypeCallback = "onclick='muscleBoundMenu(this,null)'";

            if(exerciseGroups[group][exNumber].repType == "rep"){
                html += "<th class='bound tbRepType "+exerciseGroups[group][exNumber].repsType[x]+"' "+repTypeCallback+">"+dict["rep"]+"</th>";
            } else if(exerciseGroups[group][exNumber].repType == "maxRep"){
                html += "<th class='bound tbRepType "+exerciseGroups[group][exNumber].repsType[x]+"' "+repTypeCallback+">"+dict["MaxRep"]+"</th>";
            } else if(exerciseGroups[group][exNumber].repType == "time"){
                html += "<th class='bound tbRepType "+exerciseGroups[group][exNumber].repsType[x]+"' "+repTypeCallback+">"+dict["Time"]+"</th>";
            } else if(exerciseGroups[group][exNumber].repType == "range"){
                html += "<th class='bound tbRepType "+exerciseGroups[group][exNumber].repsType[x]+"' "+repTypeCallback+">"+dict["Range"]+"</th>";
            } else{
                html += "<th class='bound tbRepType "+exerciseGroups[group][exNumber].repsType[x]+"' "+repTypeCallback+">"+dict["exerciseMode"]+"</th>";
            }
            html += "<th class='tbMode' scope='col'>"+dict["Mode"]+"</th>";
            html += "</tr></thead><tbody>";
            setNumber = 1;

            for(t = 0; t < sets.length; t++){
                if(exerciseGroups[group][exNumber].repsType[t] != mainMode) multipleModes = true;
                if(t > 0){
                    html += '<tr class="restBtwSet">';
                    html += '<th scope="row"><button onclick="addRestBetweenSets(this,'+(t+1)+')">';
                    if(exerciseGroups[group][exNumber].restBetweenSets[t-1] != undefined && exerciseGroups[group][exNumber].restBetweenSets[t-1] !== ""){
                        html += '-';
                    } else {
                        html += '+';
                    }
                    html += '</button><span>'+dict["Mode"]+'</span></th>';

                    if(exerciseGroups[group][exNumber].restBetweenSets[t-1] != undefined && exerciseGroups[group][exNumber].restBetweenSets[t-1] !== ""){
                        html += '<td colspan="3" class="secRest">';
                        html += '<p style="text-align: center;display: none;">'+dict["add rest between set"]+'</p><input setnumber="'+(t+1)+'" onchange="addRestBetweenSetsInfo(this)" class="restBetweenSets" style="display: inline;" value="'+exerciseGroups[group][exNumber].restBetweenSets[t-1]+'">';
                        html += '<p class="secBtwSet" style="display:inline-block">'+dict["seconds between set"]+'</p></td></tr>';
                    } else {
                        html += '<td colspan="3">';
                        html += '<p style="text-align: center;display: block;">'+dict["add rest between set"]+'</p><input setnumber="'+(t+1)+'" onchange="addRestBetweenSetsInfo(this)" class="restBetweenSets" style="display: none;"></td></tr>';
                    }

                }
                if (t < 1){
                    html += "<tr  class='set muscleSet' setnumber=\""+setNumber+"\">";
                } else{
                    html += "<tr  class='set followingSet muscleSet' setnumber=\""+setNumber+"\">";
                }
                // html += "<tr  class='set muscleSet' setnumber=\""+setNumber+"\">";
                html += "<th scope='row'>"+setNumber+"</th>";
                metric = "lbs";
                if(exerciseGroups[group][exNumber].metric == "metric"){
                    metric = 'kg';
                }

                html += `<td class='weight ${metric}'>
                            <input type="number" oninput="this.value = this.value.replace(/[^0-9.]/g, ')" inputmode="decimal" onchange="updateField(\'weight\',this)" value="${exerciseGroups[group][exNumber].weights[t]}"/>
                        </td>`;

                var value=0;
                if(exerciseGroups[group][exNumber].repType == "rep"){
                    value = exerciseGroups[group][exNumber].repArray[t];
                } else if(exerciseGroups[group][exNumber].repType == "maxRep"){
                    value = exerciseGroups[group][exNumber].repArray[t];
                } else if(exerciseGroups[group][exNumber].repType == "time"){
                    value = exerciseGroups[group][exNumber].repArray[t];
                } else if(exerciseGroups[group][exNumber].repType == "range"){
                    value = exerciseGroups[group][exNumber].repArray[t]
                }

                html += "<td class='bound "+exerciseGroups[group][exNumber].repsType[t]+"'>"+
                       '<input onchange="updateField(\'rep\',this)" value="'+value+'"/>'+
                       '</td>';

                html +=  '<td class="tbSet repStyleChoice" onclick="muscleBoundMenu(this,'+t+')">'+dict[exerciseGroups[group][exNumber].repsType[t]]+'</td>';
                html += "</tr>";
                setNumber++;
            }
            html += "</tbdoy></table>";
            if(sets.length > 1){
                 html += '<div class="setManagement setManagement-active">';
             } else{
                html += '<div class="setManagement">';
            };
            html +='<button class="addSet" onclick="addingSet(this)">+ '+dict["Add Set"]+'</button>'+
                    '<button class="removeSet" onclick="removeSet(this)">- '+dict["Remove Set"]+'</button>'+
                    '</div>';
            $("#"+id).find(".exercise-table").html(html);

            if(multipleModes){
                $("#"+id).find(".exercise-table").find(".tbRepType").html(dict["exerciseMode"]);
            }
        }
}


function recreateWorkoutFromJson(){
    $("#workout").html("");



    cleanUpArrays();



    mainCounter = 0;
    if(exerciseGroupsRest.length >=  exerciseGroups.length){
        mainCounter = exerciseGroupsRest.length;
    } else {
        mainCounter = exerciseGroups.length;
    }



    for(u = 0; u < mainCounter; u++){
        circuit = false;


        if(u > 0){
            newElementRest = $(".templateRestBetweenGroups").clone().removeClass("templateRestBetweenGroups").removeAttr("style");

            if(exerciseGroupsRest[u-1] !== undefined && exerciseGroupsRest[u-1] !== null && exerciseGroupsRest[u-1].restTime !== undefined  && exerciseGroupsRest[u-1].restTime !== null){
                if(debug) console.log(exerciseGroupsRest[u].restTime);
                newElementRest.find(".restTime").val(exerciseGroupsRest[u-1].restTime);
                newElementRest.find(".addRest").hide();
                newElementRest.find(".btwExerciseRest-active").removeClass("hide");
            }
            $("#workout").append(newElementRest);
            fillUpExerciseRest();
        }

        if( exerciseGroupsRest[u] === undefined ||  exerciseGroupsRest[u] === null){
                exerciseGroupsRest[parseInt(u)] = {};
                exerciseGroupsRest[parseInt(u)].type = "regular";
                exerciseGroupsRest[parseInt(u)].restTime = undefined;
        }


        if(exerciseGroupsRest[u] && exerciseGroupsRest[u]["circuitStyle"] !== undefined){
            circuit = true;
        }
        if(circuit){
          newElement = $(".templateCircuit").clone().removeClass("templateCircuit").removeAttr("style").attr("group",u);
          newElement.find(".exerciseHeader h1").text("Circuit");
          newElement.find(".nbrounds p").text(exerciseGroupsRest[u]["circuitRound"]);
          circuitType = exerciseGroupsRest[u]["circuitStyle"];
          var cir = {};
          cir["circuitStyle"] = "";

          newElement.find(".exerciseHeader").find(".nbrounds").hide();
          newElement.find(".exerciseHeader").find(".amrap").hide();
          newElement.find(".exerciseHeader").find(".emom").hide();

          cir = exerciseGroupsRest[u];
          if(circuitType == "rounds"){
            circuitType = "rounds";
            newElement.find(".exerciseHeader").find(".nbrounds").show();
            newElement.find(".exerciseHeader").find(".roundsMeasure").html(cir["circuitRound"]);

          }else if(circuitType == "amrap"){
            circuitType = "amrap";
            newElement.find(".exerciseHeader").find(".amrap").show();
            newElement.find(".exerciseHeader").find(".amrapMeasure").html(cir["circuitMaxTime"]);

          } else if(circuitType == "emom") {
            circuitType = "emom";
            newElement.find(".exerciseHeader").find(".emom").show();
            newElement.find(".exerciseHeader").find(".emomMeasure").html(cir["circuitEmom"]);

          }

            newElement.find(".repeatCircuit").find(".restText").html(exerciseGroupsRest[u]["circuitRest"]);
            console.log(newElement.find(".circuitContainer").find(".restText").text());
            console.log(exerciseGroupsRest[u]["circuitRest"]);

            newElement.find(".circuitEditing").removeClass("circuitEditing");
            newElement.find(".circuit").find(".circuitExerciseOptions").addClass("rowStyle");
            newElement.find(".circuit").find(".circuitSetUp").hide();
            newElement.find(".circuit").find(".circuitDelete").hide();





            $("#workout").append(newElement);

            fillUpCircuit(u);




        }
        if( exerciseGroups[u] !== undefined){
            for(o = 0; o < exerciseGroups[u].length; o++){

                if(circuit){
                    if(o > 0) {
                            var cssStyle = "";
                            if(exerciseGroupsRest[u].circuitStyle == "emom") cssStyle = "display:none";

                            if(debug) console.log("add rest between exercises");
                            otherElement = $(".templateRestBetweenExercises").clone().removeClass("templateRestBetweenExercises").removeAttr("style").attr("style",cssStyle);

                            if(exerciseGroupsRest[u].restBetweenCircuitExercises === undefined) exerciseGroupsRest[u].restBetweenCircuitExercises = [];

                            if(exerciseGroupsRest[u].restBetweenCircuitExercises[o-1] !== undefined){
                                console.log("h22ere");
                                otherElement.find(".restTime").val(exerciseGroupsRest[u].restBetweenCircuitExercises[o-1]);
                                $(otherElement).find(".addRest").hide();
                                $(otherElement).find(".btwExerciseRest-active").removeClass("hide");
                            } else {

                            }

                            /*otherElement.find(".addRest").hide();
                            otherElement.find(".btwExerciseRest-active").removeClass("hide");*/




                            $(newElement).closest(".circuitContainer").find(".containerExerciseCircuit").append(otherElement);



                    }

                  if(debug) console.log("X: "+u+" "+"Y: "+o);
                  var id = u+"_"+o+"_"+exerciseGroups[u][o].exercise.id+"_"+(exerciseGroups[u][o].exercise.equipmentId ? exerciseGroups[u][o].exercise.equipmentId : "" );
                  var bodygroupId = exerciseGroups[u][o].exercise.bodygroupId;
                  if(bodygroupId == 18){
                      newElement = $(".templateCardioCircuit").clone().removeClass("templateCardioCircuit").removeAttr("style").attr("id",id);
                      $("div[group='"+u+"']").find(".containerExerciseCircuit").append(newElement);
                  } else {
                      newElement = $(".templateMuscleCircuit").clone().removeClass("templateMuscleCircuit").removeAttr("style").attr("id",id);
                      $("div[group='"+u+"']").find(".containerExerciseCircuit").append(newElement);
                  }

                  fillUpExerciseCircuit(id,exerciseGroups[u][o]);
                  buildExerciseSets(id);
                }
                else {

                  if(debug) console.log("X: "+u+" "+"Y: "+o);
                  console.log(exerciseGroups[u]);
                  var id = u+"_"+o+"_"+exerciseGroups[u][o].exercise.id+"_"+(exerciseGroups[u][o].exercise.equipmentId ? exerciseGroups[u][o].exercise.equipmentId : "" );
                  var bodygroupId = exerciseGroups[u][o].exercise.bodygroupId;
                  if(bodygroupId == 18){
                      newElement = $(".templateCardio").clone().removeClass("templateCardio").removeAttr("style").attr("id",id).attr("group",u);
                      $("#workout").append(newElement);
                  } else {
                      newElement = $(".templateMuscle").clone().removeClass("templateMuscle").removeAttr("style").attr("id",id).attr("group",u);
                      $("#workout").append(newElement);
                  }

                  fillUpExercise(id,exerciseGroups[u][o]);
                  buildExerciseSets(id);
                }
            }
        }

    }
}

function fillUpExerciseRest(group){

}

function fillUpExerciseRestCircuit(number){

}

function cleanUpArrays(){
    var arrayToDelete = [];
    for(x = 0; x < exerciseGroups.length; x++){
        if(exerciseGroups[x] !== undefined && exerciseGroups[x] !== null){
            for(y = 0; y < exerciseGroups[x].length; y++){
                if(debug) console.log("X: "+x+" "+"Y: "+y);
                console.log(exerciseGroups[x]);
                if(exerciseGroups[x][y] == undefined || exerciseGroups[x][y] == null){
                    arrayToDelete.push(y);
                }
            }
            for(t = 0; t < arrayToDelete.length; t++){
                exerciseGroups[x][y] = undefined;
            }
            exerciseGroups[x] = exerciseGroups[x].filter(function( element ) { return !!element; });
        }
    }
    for(x = 0; x < exerciseGroups.length; x++){
        if(exerciseGroups[x] !== undefined && exerciseGroups[x] !== null){ if(exerciseGroups[x].length == 0) exerciseGroups[x] = undefined; }
    }

    if(exerciseGroups.length > 0){
        exerciseGroups = exerciseGroups.filter(function( element ) { return !!element; });
    }
}


function muscleBoundMenu(object,setNumber) {
    id = $(object).closest(".exerciseTarget").attr("id");
    idParts = explodeId(id);
    if(debug) console.log(id);
    group = idParts[0];
    exNumber = idParts[1];


    var raiseMenu = '<div class="overlay">';
    raiseMenu += '<div class="leftOverBound" onclick="closeOverlay(event)"><img class="exitOverlay" onclick="closeOverlay(event)" src="assets/img/exitPopup.svg"></div>';
    raiseMenu += '<div class="chooseBound"><ul>';
    raiseMenu += '<li class="exeName">' + exerciseGroups[group][exNumber].exercise.name + '</li>';
    raiseMenu += '<li onclick="setRepType(\''+id+'\',\'rep\','+setNumber+',this)">'+dict["Number of Repetition"]+'</li>';
    raiseMenu += '<li onclick="setRepType(\''+id+'\',\'range\','+setNumber+',this)">'+dict["Range of Repetition"]+'</li>';
    raiseMenu += '<li onclick="setRepType(\''+id+'\',\'maxRep\','+setNumber+',this)">'+dict["Max Repetition / AMRAP"]+'</li>';
    raiseMenu += '<li onclick="setRepType(\''+id+'\',\'time\','+setNumber+',this)">'+dict["Time Bound"]+'</li>';
    raiseMenu += '</ul></div></div>';

    appendToExercise(raiseMenu,object);
}

function cardioRaiseMenu(object,setNumber) {
    id = $(object).closest(".exerciseTarget").attr("id");
    idParts = explodeId(id);
    group = idParts[0];
    exNumber = idParts[1];

    var cardioMenu = '<div class="overlay">';
    cardioMenu += '<div class="leftOverBound" onclick="closeOverlay(event)"></div>';
    cardioMenu += '<div class="chooseBound"><ul>';
    cardioMenu += '<li class="exeName">' + exerciseGroups[group][exNumber].exercise.name + '</li>';
    cardioMenu += '<li onclick="setCardioRepType(\''+id+'\',\'Heart Rate\','+setNumber+',this)">'+dict["Heart Rate"]+'</li>';
    cardioMenu += '<li onclick="setCardioRepType(\''+id+'\',\'% of effort\','+setNumber+',this)">'+dict["% of effort"]+'</li>';
    cardioMenu += '<li onclick="setCardioRepType(\''+id+'\',\'Vo2Max\','+setNumber+',this)">'+dict["% Vo2 Max"]+'</li>';
    cardioMenu += '<li onclick="setCardioRepType(\''+id+'\',\'HR Reserve\','+setNumber+',this)">'+dict["% Heart Rate Reserve"]+'</li>';
    cardioMenu += '<li onclick="setCardioRepType(\''+id+'\',\'HR Range\','+setNumber+',this)">'+dict["Heart Rate Range"]+'</li>';
    cardioMenu += '<li onclick="setCardioRepType(\''+id+'\',\'Max\','+setNumber+',this)">'+dict["Max"]+'</li>';
    cardioMenu += '</ul></div></div>';

    appendToExercise(cardioMenu,object);
};

function setRepType(id,type,setNumber,object){
    idParts = explodeId(id);
    group = idParts[0];
    exNumber = idParts[1];


    if(setNumber === null || setNumber === undefined){
        var counter = 0;
        exerciseGroups[group][exNumber].repType = type;
        $("#"+id).find(".exercise-table").find(".set").each(function(index){
            setRepTypeAux(id,type,counter);
            counter = counter + 1;
        });
         $("#"+id).find(".exercise-table").find(".tbRepType").html(dict[type]);

    } else {
        setRepTypeAux(id,type,setNumber,object);
        $("#"+id).find(".exercise-table").find(".tbRepType").html(dict["Exercise Mode"]);
    }

    closeMenu(object);
}

function setRepTypeAux(id,type,setNumber,object){
    console.log(setNumber);
    $("#"+id).find(".exercise-table .set").eq(setNumber).find("td").eq(2).html(dict[type]);
    $("#"+id).find(".exercise-table .set").eq(setNumber).find("td").eq(1).removeClass("maxRep").removeClass("range").removeClass("time").removeClass("rep").addClass(type);
    inputValue = 0;
    inputunit = 'sec';
    if(type == "maxRep") {
        inputValue = maxRepConstant;
    } else if(type == "range" || type == "intervalle") {
        inputValue = rangeConstant;
    } else if(type == "time" || type == "temps") {
        inputValue = timeConstant;
    } else if(type == "rep") {
        inputValue = repConstant;
    }
    $("#"+id).find(".exercise-table .set").eq(setNumber).find("td").eq(1).find("input").val(inputValue);

    idParts = explodeId(id);
    group = idParts[0];
    exNumber = idParts[1];

    exerciseGroups[group][exNumber].repsType[setNumber] = type;
    exerciseGroups[group][exNumber].repArray[setNumber] = inputValue;
}

function setCardioRepType(id,type,setNumber,object){
    idParts = explodeId(id);
    group = idParts[0];
    exNumber = idParts[1];

    if(debug) console.log("Translated: "+type);
    if(debug) console.log("Translated: "+dict[type]);
    if(setNumber === null || setNumber === undefined){
        var counter = 0;
        $("#"+id).find(".exercise-table").find(".set").each(
            function(index){
                $("#"+id).find(".exercise-table .set").eq(counter).find("td").eq(4).html(dict[type]);
                counter +=1;
        });
        $("#"+id).find(".exercise-table").find(".tbRepType").html(dict[type]);
        if (type == "% of effort"){
            type = 'effort';
        } else if (type == "Heart Rate"){
            type = 'hr'
        } else if(type == "HR Range"){
            type = 'range'
        } else if (type == 'HR Reserve'){
            type = 'reserve'
        } else if(type == "Max"){
            type = 'max'
        }
        exerciseGroups[group][exNumber].repType = type;
        counter = 0;
        $("#"+id).find(".exercise-table").find(".set").each(function(index){
            setCardioRepTypeAux(id,type,counter);
            counter = counter + 1;
        });
        // type = "tb"+type;
    } else {
        $("#"+id).find(".exercise-table .set").eq(setNumber).find("td").eq(4).html(dict[type]);
        if (type == "% of effort"){
            type = 'effort';
        } else if (type == "Heart Rate"){
            type = 'hr'
        } else if(type == "HR Range"){
            type = 'range'
        } else if (type == 'HR Reserve'){
            type = 'reserve'
        } else if(type == "Max"){
            type = 'max'
        }
        setCardioRepTypeAux(id,type,setNumber);
    }

    closeMenu(object);
}

function setCardioRepTypeAux(id,type,setNumber){

    $("#"+id).find(".exercise-table .set").eq(setNumber).find("td").eq(0).removeClass("hr").removeClass("Vo2Max").removeClass("reserve").removeClass("range").removeClass("effort").removeClass('max').addClass(type);
    inputValue = 0;
    $("#"+id).find(".exercise-table .set").eq(setNumber).find("td").eq(0).find("input").prop('disabled', false);
    if(type == "effort") {
       inputValue = maxRepCardioConstant;
       $("#"+id).find(".exercise-table .set").eq(setNumber).find("td").eq(0).find("input").val(inputValue);
    } else if(type == "hr") {
        inputValue = hrConstant;
        $("#"+id).find(".exercise-table .set").eq(setNumber).find("td").eq(0).find("input").val(inputValue);
    } else if(type == "Vo2Max") {
        inputValue = vo2Constant;
        $("#"+id).find(".exercise-table .set").eq(setNumber).find("td").eq(0).find("input").val(inputValue);
    } else if(type == "reserve") {
        inputValue = reserverConstant;
        $("#"+id).find(".exercise-table .set").eq(setNumber).find("td").eq(0).find("input").val(inputValue);
    } else if(type == "range") {
        inputValue = rangeCardioConstant;
        $("#"+id).find(".exercise-table .set").eq(setNumber).find("td").eq(0).find("input").val(inputValue);
    } else if(type == "max") {
        inputValue = maxCardioConstant;
        $("#"+id).find(".exercise-table .set").eq(setNumber).find("td").eq(0).find("input").val(inputValue);
        $("#"+id).find(".exercise-table .set").eq(setNumber).find("td").eq(0).find("input").prop('disabled', true);
    }

    idParts = explodeId(id);
    group = idParts[0];
    exNumber = idParts[1];
    exerciseGroups[group][exNumber].repsType[setNumber] = type;
    exerciseGroups[group][exNumber].hrs[setNumber] = inputValue;
}

function closeOverlay(e){
    console.log(e.target);
    if (e.target == $('.leftOverBound')[0] || $('.exitOverlay')[0]){
        document.querySelector('.cw-exercise .overlay').remove();
    }
}

function closeMenu(object) {
    $(object).closest(".overlay").remove();
}

//Setting the right style of circuit for a circuit
function ciruitStyleChoice(object) {
    //Get value of the input checked
    var typeValue = $(object).closest(".option").find("input").val();

    //Key of the value selcted into appropriate css class
    var circuitStyle = {
        rounds: ".circuitStyle-rounds",
        amrap: ".circuitStyle-amrap",
        emom: ".circuitStyle-emom",
        submit: ".circuitStyleSubmit"
    };

    // get the appropriate css class
    var circuitStyleChosen = circuitStyle[typeValue];

    //close all options that are open
    $(object).closest(".circuitSetUp").find('fieldset[class^="circuitStyle"]').css("display", "none");

    //open the right option
    $(object).closest(".circuitSetUp").find(circuitStyleChosen).css("display", "block");

    //show the submit button
    $(object).closest(".circuitSetUp").find(circuitStyle.submit).css("display", "block");
    $('html, body').animate({
        scrollTop: $(object).offset().top
    }, 500);
}


// // Show the rest between exercise
function restBetweenExercise(object) {
    $(object).hide();
    $(object).closest(".btwExerciseRest").find(".btwExerciseRest-active").removeClass("hide");

}

// Hide and clear the rest between exercise
function closeRestBetweenExercise(object) {
    $(object).closest(".btwExerciseRest-active").find("input").val("");
    $(object).closest(".btwExerciseRest-active").addClass("hide");
    $(object).closest(".btwExerciseRest").find("button").show();

    id = $(object).closest(".btwExerciseRest").prev().attr("id");
    idParts = explodeId(id);
    group = idParts[0];
    exNumber = idParts[1];
    if(debug) console.log(group);
    if(debug) console.log(exerciseGroupsRest[group]);
    exerciseGroupsRest[group] = undefined;
}

//  ========================
//    End of Alain's Code
//  ========================

function saveWorkoutName(){
    $("#workout_name").val();
}

 function updateWorkoutName(){
  workoutName = $("#workout_name").val();
 }

function setActiveTab(element){
    $(".tab").removeClass("active");
    $(element).addClass("active");
}

function searchExercise(el, event, page, more) {
    $('.add_ex').remove();
    // Show loader
    //This means that user just wants to see more.

    if(page === undefined ) page = 15;

    if($("#exercise_search").val() == "" && filters.length == 0 && more != true){

      triggerFirstLoad(page);
    }else {
    typewatch(function() {


        var handler = el;
        var preload;
        // if ( ($("#exercise_search").val() != globalExName || filters.length > 0) || (more !== undefined && more !== null && more == true)) {
            showTopLoader();

            if($("#exercise_search").val() != globalExName) totalExDisplayed = 0;


            callForEvent('workouts-search-exercise',{"workout-name":workoutName,"exercise-name":$("#exercise_search").val(),"user_id":{{ Auth::user()->id }},"email":'{{ Auth::user()->email }}'});
            $("#search_loader").show();
            var myExercises = false;
            if($('.tab.active').attr('id') == 'myExerciseTab'){
                myExercises = true;
            }
            $.ajax({
                'async': true,
                'url': '{{ Lang::get("routes./Exercises/Search") }}',
                'dataType': 'html',
                'type': 'post',
                'data': {
                    search: $("#exercise_search").val(),
                    filters: filters,
                    pageSize: page,
                    myExercises: myExercises,
                    lang: $("#langSelector").val(),
                },
                'success': function(data) {
                    allExercisesDictionnary = {};
                    displayResults(data);
                    hideTopLoader();
                },
                error: function(jqXHR, textStatus, errorThrown)
                {
                  errorMessage(jqXHR.responseText);
                  hideTopLoader();
                },
                statusCode: {
                  500: function() {
                    if(jqXHR.responseText != ""){
                      errorMessage(jqXHR.responseText);
                      hideTopLoader();
                    }else {
                      hideTopLoader();
                    }

                  }
              }
            });
        // }
        globalExName = $("#exercise_search").val();;
    }, 800);
  }
}


function triggerFirstLoad(page) {
    // Show loader
    //     if(firstLoadData == ""){
        showTopLoader();
        var myExercises = false;
        if($('.tab.active').attr('id') == 'myExerciseTab'){
            myExercises = true;
        }
        $("#search_results").show();
            $("#search_loader").show();
            //console.log(filters);
            $.ajax({
                'async': true,
                'url': '{{ Lang::get("routes./Exercises/Search") }}',
                'dataType': 'html',
                'type': 'post',
                'data': {
                    search: $("#exercise_search").val(),
                    filters: filters,
                    pageSize: page,
                    myExercises: myExercises,
                    lang: $("#langSelector").val(),
                },
                'success': function(data) {
                    allExercisesDictionnary = {};
                    displayResults(data);
                }
            });
        // } else {
        //     allExercisesDictionnary = {};
        //     displayResults(firstLoadData);
        // }

}

/*
 * @EFFECT  Displays the exercises in the list from the POST method above.
 */
function displayResults(results) {
    firstLoadData = results;
    var json = JSON.parse(results);
    response = json.data;
    var html = "";

    // Clear previous
    $("#search_results ul").html("");

    for (var x = 0; x < response.length; x++) {
        // Make sure there are no duplicates
         // $("#search_results").data(response[x].elementID, response[x]);
          addToSearchResultView(response[x],x);
    }
    //if (response.length < json.total ) {
    $(".moreExercisesButton").remove();


    if(totalExDisplayed != response.length || $("#exercise_search").val() != globalExName){

         html = "<div class=\"clearfix\"></div><button class='moreExercisesButton' onclick='searchExercise($(this),null," + response.length + ",true); arguments[0].stopPropagation(); return false;' class='greybtn more_ex'>{{ Lang::get("content.MoreExercises") }}</button></div>";
        $("#search_results").append(html);

        totalExDisplayed = response.length;
    }else{
        if($('.add_ex').length <= 0){
            html = `<div class="clearfix"><button onclick='addExercise();' style="margin-right: 10px;" class='bluebtn add_ex more_ex'>{{ Lang::get("content.Add_your_own_exercises") }}</button><button onclick='suggestExercise();' class='bluebtn add_ex more_ex'>Suggest an exercise</button></div>`;
            $("#search_results").append(html);
        }
    }

    $("#search_loader").hide();
    hideTopLoader();
    $("#search_results").show();
}

function  removeMoreButton(){

}

function addToSearchResultView(exercise,index){
  var html = '';
  if(exercise.image !== undefined && exercise.image !== null){
  html = '<li class="elementSearch" id="search_'+exercise.elementID+'">'+
                '<div class="addToWorkout" onclick="addToWorkout(\''+exercise.id+'\','+exercise.bodygroupId+','+index+')">'+dict["add to workout"]+'</div>' +
                '<img onclick="addToWorkout(\''+exercise.id+'\','+exercise.bodygroupId+','+index+')" src="/'+exercise.thumb+'"/><div class="exeName"><span>'+exercise.name+'</span></div>'+
                 (exercise.favorite ? '<div onClick="addToFavorite('+exercise.id+','+exercise.equipmentId+',this)" class=" '+(exercise.video != "" ? "videoIcon" : "")+' favorite removeFavorite">'+dict["remove from favorites"]+'</div>' : '<div class="favorite" onClick="addToFavorite('+exercise.id+','+exercise.equipmentId+',this)">'+dict["add to favorites"]+'</div>')+
            '</li>';
  } else {
  html = '<li class="elementSearch" id="search_'+exercise.elementID+'">'+
                  '<div class="addToWorkout" onclick="addToWorkout(\''+exercise.id+'\','+exercise.bodygroupId+','+index+')">'+dict["add to workout"]+'</div>' +
                '<img  onclick="addToWorkout(\''+exercise.id+'\','+exercise.bodygroupId+','+index+')" src="/'+placeholder+'"/><div class=" '+(exercise.video != "" ? "videoIcon" : "")+' exeName"><span>'+exercise.name+'</span></div>'+
                (exercise.favorite ? '<div onClick="addToFavorite('+exercise.id+','+exercise.equipmentId+',this)" class="favorite removeFavorite">'+dict["remove from favorites"]+'</div>' : '<div class="favorite" onClick="addToFavorite('+exercise.id+','+exercise.equipmentId+',this)">'+dict["add to favorites"]+'</div>')+
            '</li>';
  }
  $(html).hide().appendTo("#search_results ul").fadeIn(200);
}


function addToFavorite(exId,equipmentId,object){
    if ($(object).closest(".elementSearch").find(".removeFavorite").length > 0){
        $(object).closest(".elementSearch").find(".removeFavorite").text(dict["add to favorites"]);
        $(object).closest(".elementSearch").find(".removeFavorite").removeClass("removeFavorite");
    } else {
        $(object).closest(".elementSearch").find(".favorite").text(dict["remove from favorites"]);
        $(object).closest(".elementSearch").find(".favorite").addClass("removeFavorite");
    }

    $.ajax({
        'async': true,
        'url': '{{ Lang::get("routes./Exercises/AddToFavorite") }}',
        'dataType': 'html',
        'type': 'post',
        'data': {
            id: exId,
            equipmentId: equipmentId
        },
        'success': function(data) {
            successMessage(data);
        }
    });
}


function generateExerciseID(exercise,exerciseGroup) {
    if(exerciseGroups[exerciseGroup] === undefined) exerciseGroups[exerciseGroup] = [];
    exerciseNumber = exerciseGroups[exerciseGroup].length;
  return (exerciseGroup+"_"+exerciseNumber+"_"+exercise.id+"_"+(exercise.equipmentId ? exercise.equipmentId : "" ));
}

var typewatch = (function(){
  var timer = 0;
  return function(callback, ms){
    clearTimeout (timer);
    timer = setTimeout(callback, ms);
  };
})();

function imgError(image) {
    image.onerror = "";
    image.src = "../img/default.gif";
    return true;
}








function createWorkout(){


  var form = $('#createform');
  $("#exercises").val(JSON.stringify(exercises));
  $("#exerciseGroup").val(JSON.stringify(exerciseGroups));
  $("#exerciseGroupsRest").val(JSON.stringify(exerciseGroupsRest));
  //$("#tags").val(JSON.stringify(tags));
  //alert($("#workout_name").val());
  $("#workoutName").val($("#workout_name").val());
  $("#clientId").val($("#client").val());
  $("#noteToWorkoutForm").val($("#noteToWorkout").val());
    if($("#workout_name").val() == ""){
        errorMessage("{{ Lang::get("content.createWorkout/validation1") }}")
    }else{
      var $clone = $("#ptLogo").clone();
      form.append($clone);


      form.submit();
      lightBoxLoadingTwSpinner();
    }

}

function addToFilter(name,type,id,object){
    callForEvent('workouts-search-exercise-filter',{"workout-name":workoutName,"filter-name":name,"user_id":{{ Auth::user()->id }},"email":'{{ Auth::user()->email }}'});
    var newFilter = {name:name,type:type,id:id};
    var flag = true;
    filters.forEach(function(filter){
        if(filter.name == newFilter.name && filter.type == newFilter.type) flag = false;
    });

    if(flag) filters.push(newFilter);

    refreshFilters(true);

    // object.addClass('secletedTag');

}

function refreshFilters(search){
    var output = "";
    filters.forEach(function(filter){
        output += '<div class="searchTag">'+filter.name+' <i onclick="removeFilter(\''+filter.name+'\',\''+filter.type+'\')">X</i></div>';
    });
    $("#selectedFilters").empty().append(output);
    totalExDisplayed = 0;
    if(search) searchExercise();
}

function removeFilter(name,type){
    var index = 0;
    for(x =0; x < filters.length; x++){
        var filter = filters[x];
        if(filter.name == name && filter.type == type) index = x;
    }
    $(".searchTag").filter(function() {
        return $(this).text() === name;
    }).removeClass("selected");
    filters.splice(index,1);
    refreshFilters(true);
}

function emptyFilters(){
    var index = 0;
    for(x =0; x < filters.length; x++){
        var filter = filters[x];
        if(filter.name == name && filter.type == type) index = x;
    }
    filters = [];
    // refreshFilters(false);
    refreshFilters(true);
}


function savingVisual(status) {
    if (status == "saving") {
        $(".saveStatus").removeClass("saveStatus_failed");
        $(".saveStatus").addClass("saveStatus_active");
    } else if (status == "saved") {
        $(".saveStatus").removeClass("saveStatus_failed");
        $(".saveStatus").removeClass("saveStatus_active");
    } else if (status == "failed") {
        $(".saveStatus").addClass("saveStatus_failed");
        $(".saveStatus").removeClass("saveStatus_active");
    }
}


function autoSaveWorkout(){


  var form = $('#createform');
  $("#exercises").val(JSON.stringify(exercises));
  $("#exerciseGroup").val(JSON.stringify(exerciseGroups));
  $("#exerciseGroupsRest").val(JSON.stringify(exerciseGroupsRest));
  //$("#tags").val(JSON.stringify(tags));
  //alert($("#workout_name").val());
  $("#workoutName").val($("#workout_name").val());
  $("#noteToWorkoutForm").val($("#noteToWorkout").val());

  var $clone = $("#ptLogo").clone();
  form.append($clone);

  if(autoSaveExercise != JSON.stringify(exerciseGroups)){
    savingVisual('saving');
    autoSaveExercise = JSON.stringify(exerciseGroups);

      $.ajax({
        url: "{{ Lang::get("routes./Trainer/autoSaveWorkout") }}",
        type: "POST",
        data: $('#createform').serialize(),
        success: function(data)
        {
            savingVisual('saved');
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            savingVisual('failed');
        },
      });

  }


}

@if($workout->status != "Released")


 setInterval(function(){
      autoSaveWorkout();
    },20000);

@endif


$(document).ready(function(){

@if($workout->name != "")
$("#workout_name").val('{{ addslashes($workout->name) }}');
@endif

@if(isset($workout->exerciseGroup) && !empty($workout->exerciseGroup))
    exercises = JSON.parse(`{!! addslashes(json_encode(json_decode($workout->exercises, true))) !!}`);
    exerciseGroups = JSON.parse(`{!! addslashes(json_encode(json_decode($workout->exerciseGroup, true))) !!}`);
    @if($workout->exerciseGroupRest != "")
    exerciseGroupsRest = JSON.parse(`{!! addslashes(json_encode(json_decode($workout->exerciseGroupRest, true))) !!}`);
    @endif

    recreateWorkoutFromJson();
    addingExerciseMenu();
@endif
});


function addCircuit(){


        if(exerciseGroupsRest.length > 0){
            var cssStyle = "";
            otherElement = $(".templateRestBetweenGroups").clone().removeClass("templateRestBetweenGroups").removeAttr("style").attr("style",cssStyle);
            $("#workout").append(otherElement);
        }


        newElement = $(".templateCircuit").clone().removeClass("templateCircuit").removeAttr("style").attr("group",exerciseGroupsRest.length);
        newElement.find(".exerciseHeader h1").text("Circuit");
        newElement.find(".nbrounds p").text("1");
        $("#workout").append(newElement);
        $('html, body').animate({
            scrollTop: newElement.offset().top
        }, 500);


        addingExerciseMenu();
}


function addToWorkout(exerciseId, bodgroupId,index){
    closeTabs();
    activeGroup = exerciseGroupsRest.length;
    if(addMode == "circuit"){

            activeGroup = addToCircuit;
    } else {
        // exerciseGroupsRest[activeGroup] = [];
        // exerciseGroupsRest[activeGroup]["circuitRound"] = 1;
        // exerciseGroupsRest[activeGroup]["circuitRest"] = 0;
    }
    addToCircuit = "";

    var id = generateExerciseID(response[index],activeGroup);
    if(debug) console.log("Edit Mode: "+editId);
    if(editId != ""){
        id = editId;
        idParts = explodeId(id);
        group = idParts[0];
        exNumber = idParts[1];
        ex = response[index];
        if(debug) console.log("EDITING GROUP: "+group);
        if(debug) console.log("EDITING exNumber: "+exNumber);
        exerciseGroups[group][exNumber].exercise = ex;
        //if(isCircuit(group)){
        //        if(debug) console.log("Editing a Circuit ex "+id);
        //        fillUpExerciseCircuit(id,exerciseGroups[group][exNumber]);
        //} else {
        //        if(debug) console.log("Editing a normal "+id);
        //        fillUpExercise(id,exerciseGroups[group][exNumber]);
        //}
        recreateWorkoutFromJson();
        hideSearch();

    }
    else {

        ex = response[index];
        if(debug) console.log(addMode);
        if(debug) console.log(activeGroup);
        if(addMode == "regular"){
            if(exerciseGroupsRest.length > 0){
                var cssStyle = "";
                otherElement = $(".templateRestBetweenGroups").clone().removeClass("templateRestBetweenGroups").removeAttr("style").attr("style",cssStyle);
                $("#workout").append(otherElement);
            }

            exerciseGroupsRest[activeGroup] = {};
            exerciseGroupsRest[activeGroup].type = "regular";
            exerciseGroupsRest[activeGroup].restTime = undefined;

            if(debug) console.log(ex);
            if(ex.bodygroupId == 18){
                newElement = $(".templateCardio").clone().removeClass("templateCardio").removeAttr("style").attr("id",id).attr("group",activeGroup);
                $("#workout").append(newElement);
                $('html, body').animate({
                    scrollTop: newElement.offset().top
                }, 500);
            } else {
                newElement = $(".templateMuscle").clone().removeClass("templateMuscle").removeAttr("style").attr("id",id).attr("group",activeGroup);
                $("#workout").append(newElement);
                $('html, body').animate({
                    scrollTop: newElement.offset().top
                }, 500);
            }

            //ex = {exercise:response[index]};
            exerciseGroups[activeGroup] = [];
            exerciseGroups[activeGroup][0] = {};
            exerciseGroups[activeGroup][0].exercise = response[index];
            exerciseGroups[activeGroup][0].repType = "rep";
            exerciseGroups[activeGroup][0].repsType = ["rep"];
            exerciseGroups[activeGroup][0].weights = [weightConstant];
            exerciseGroups[activeGroup][0].hrs = [hrConstant];
            exerciseGroups[activeGroup][0].speeds = [speedConstant];
            exerciseGroups[activeGroup][0].distances = [distanceConstant];
            exerciseGroups[activeGroup][0].times = [timeConstant];
            exerciseGroups[activeGroup][0].repArray = [repConstant];
            exerciseGroups[activeGroup][0].metric = "imperial";
            exerciseGroups[activeGroup][0].notes = "";
            exerciseGroups[activeGroup][0].tempo1 = "";
            exerciseGroups[activeGroup][0].tempo2 = "";
            exerciseGroups[activeGroup][0].tempo3 = "";
            exerciseGroups[activeGroup][0].tempo4 = "";
            exerciseGroups[activeGroup][0].restBetweenSets = [];
            if(debug) console.log(id);
            if(debug) console.log(activeGroup);
            fillUpExercise(id,exerciseGroups[activeGroup][0]);

        } else {
            if(debug) console.log(ex);


            if(ex.bodygroupId == 18){
                newElement = $(".templateCardioCircuit").clone().removeClass("templateCardioCircuit").removeAttr("style").attr("id",id);
              ex = response[index];
              if(debug) console.log(newElement);
                $(addModeObject).closest(".circuitContainer").find(".containerExerciseCircuit").append(newElement);
                $('html, body').animate({
                    scrollTop: newElement.offset().top
                }, 500);
            } else {
                newElement = $(".templateMuscleCircuit").clone().removeClass("templateMuscleCircuit").removeAttr("style").attr("id",id);
                if(debug) console.log(newElement);

                $(addModeObject).closest(".circuitContainer").find(".containerExerciseCircuit").append(newElement);
                $('html, body').animate({
                    scrollTop: newElement.offset().top
                }, 500);

            }

            var cssStyle = "";
            if(exerciseGroupsRest[activeGroup].circuitStyle == "emom") cssStyle = "display:none";

            if(exerciseGroups[activeGroup].length >= 0) {
                    if(debug) console.log("add rest between exercises");
                    otherElement = $(".templateRestBetweenExercises").clone().removeClass("templateRestBetweenExercises").removeAttr("style").attr("style",cssStyle);
                    $(addModeObject).closest(".circuitContainer").find(".containerExerciseCircuit").append(otherElement);
            }

            //ex = {exercise:response[index]};
            counter = exerciseGroups[activeGroup].length;
            exerciseGroups[activeGroup][counter] = {};
            exerciseGroups[activeGroup][counter].exercise = response[index];
            exerciseGroups[activeGroup][counter].repType = "rep";
            exerciseGroups[activeGroup][counter].repsType = ["rep"];
            exerciseGroups[activeGroup][counter].weights = [weightConstant];
            exerciseGroups[activeGroup][counter].hrs = [hrConstant];
            exerciseGroups[activeGroup][counter].speeds = [speedConstant];
            exerciseGroups[activeGroup][counter].distances = [distanceConstant];
            exerciseGroups[activeGroup][counter].times = [timeConstant];
            exerciseGroups[activeGroup][counter].repArray = [repConstant];
            exerciseGroups[activeGroup][counter].metric = "imperial";
            exerciseGroups[activeGroup][counter].notes = "";
            exerciseGroups[activeGroup][counter].tempo1 = "";
            exerciseGroups[activeGroup][counter].tempo2 = "";
            exerciseGroups[activeGroup][counter].tempo3 = "";
            exerciseGroups[activeGroup][counter].tempo4 = "";
            exerciseGroups[activeGroup][counter].restBetweenSets = [];

            fillUpExerciseCircuit(id,exerciseGroups[activeGroup][counter]);
        }
    }
    editId = "";
    hideSearch();

    //Refresh add positioning
    addingExerciseMenu();



}

function updateTempo(object){

    id = $(object).closest(".exerciseTarget").attr("id");
    idParts = explodeId(id);
    group = idParts[0];
    exNumber = idParts[1];
    exerciseGroups[group][exNumber].tempo = [];
    tempo1 = $(object).closest(".tempoContainer").find(".tempo1").val();
    console.log(tempo1);
    tempo2 = $(object).closest(".tempoContainer").find(".tempo2").val();
    tempo3 = $(object).closest(".tempoContainer").find(".tempo3").val();
    tempo4 = $(object).closest(".tempoContainer").find(".tempo4").val();

    exerciseGroups[group][exNumber].tempo1 = tempo1;
    exerciseGroups[group][exNumber].tempo2 = tempo2;
    exerciseGroups[group][exNumber].tempo3 = tempo3;
    exerciseGroups[group][exNumber].tempo4 = tempo4;
}

function explodeId(id){
    results = [];
    results = id.split("_");
    return results;
}

function fillUpExercise(div,exercise){
    console.log(exercise);
    var counter = 0;
    $("#"+div).find(".exerciseImages").children().each(function(image){
        if(counter == 0){
            $(this).attr("src","/"+imageFilter(exercise.exercise.image,exercise.exercise.video,exercise.exercise.youtube));
        } else {
            $(this).attr("src","/"+imageFilter(exercise.exercise.image2,exercise.exercise.video,exercise.exercise.youtube));
            if(exercise.exercise.image2 == undefined || exercise.exercise.image2 == null){
                $(this).hide();
            } else {
                $(this).show();
            }
        }
        counter = counter + 1;
    });
    if(exercise.notes != ""){
       $("#"+div).find(".exerciseNote").find("span").html(dict["note"] + "<br>" + dict["added"]);
       $("#"+div).find(".exerciseNote").find(".note textarea").val(exercise.notes );
    } else {
       $("#"+div).find(".exerciseNote").find("span").html(dict["add"] + "<br>" + dict["note"]);
       $("#"+div).find(".exerciseNote").find(".note textarea").val("");
    }

    if(exercise.metric != "metric"){
       $("#"+div).find(".unitToggleInput").prop('checked', false);
    } else {
       $("#"+div).find(".unitToggleInput").prop('checked', true);
    }

    if(exercise.tempo1 != "" || exercise.tempo2 != "" || exercise.tempo3 != "" || exercise.tempo4 != ""){
        $("#"+div).find(".tempoContainer").find(".addTempoButton").toggleClass("hide");
        $("#"+div).find(".tempoContainer").find(".tempo-active").toggleClass("hide");

        $("#"+div).find(".tempoContainer").find(".tempo-active").find(".tempo1").val(exercise.tempo1);
        $("#"+div).find(".tempoContainer").find(".tempo-active").find(".tempo2").val(exercise.tempo2);
        $("#"+div).find(".tempoContainer").find(".tempo-active").find(".tempo3").val(exercise.tempo3);
        $("#"+div).find(".tempoContainer").find(".tempo-active").find(".tempo4").val(exercise.tempo4);

    }
    $("#"+div).find("h1").text(exercise.exercise.name);
    $("#"+div).find(".exerciseOptions").find(".edit").attr("onclick",'editExercise(this)');
    $("#"+div).find(".exerciseOptions").find(".duplicate").attr("onclick",'duplicateExercise(this)');
    $("#"+div).find(".exerciseOptions").find(".move-up").attr("onclick",'moveUp(this)');
    $("#"+div).find(".exerciseOptions").find(".move-down").attr("onclick",'moveDown(this)');
    $("#"+div).find(".exerciseOptions").find(".delete").attr("onclick",'deleteExericse(this)');
    $("#"+div).find(".exerciseNote").find(".spanContainer").attr("onclick",'exerciseNote(this)');
}

function fillUpCircuit(group){
     $("div[group='"+group+"']").find(".exerciseOptions").find(".edit").attr("onclick",'editCircuit(this)');
     $("div[group='"+group+"']").find(".exerciseOptions").find(".duplicate").attr("onclick",'duplicateCircuit(this)');
     $("div[group='"+group+"']").find(".exerciseOptions").find(".move-up").attr("onclick",'moveUp(this)');
     $("div[group='"+group+"']").find(".exerciseOptions").find(".move-down").attr("onclick",'moveDown(this)');
     $("div[group='"+group+"']").find(".exerciseOptions").find(".delete").attr("onclick",'deleteCircuit(this)');
}


function fillUpExerciseCircuit(div,exercise){
    //console.log(exercise);
    var counter = 0;
    $("#"+div).find(".exerciseImages").children().each(function(image){
        if(counter == 0){
            $(this).attr("src","/"+imageFilter(exercise.exercise.image,exercise.exercise.video,exercise.exercise.youtube));
        } else {
            $(this).attr("src","/"+imageFilter(exercise.exercise.image2,exercise.exercise.video,exercise.exercise.youtube));
            if(exercise.exercise.image2 == undefined || exercise.exercise.image2 == null){
                $(this).hide();
            } else {
                $(this).show();
            }
        }
        counter = counter + 1;
    });
    if(exercise.notes != ""){
       $("#"+div).find(".exerciseNote").find("span").html(dict["note"] + "<br>" + dict["added"]);
       $("#"+div).find(".exerciseNote").find(".note textarea").val(exercise.notes );
    } else {
       $("#"+div).find(".exerciseNote").find("span").html(dict["add"] + "<br>" + dict["note"]);
       $("#"+div).find(".exerciseNote").find(".note textarea").val("");
    }

    if(exercise.metric != "metric"){
       $("#"+div).find(".unitToggleInput").prop('checked', false);
    } else {
       $("#"+div).find(".unitToggleInput").prop('checked', true);
    }

    if(exercise.tempo1 != "" || exercise.tempo2 != "" || exercise.tempo3 != "" || exercise.tempo4 != ""){
        $("#"+div).find(".tempoContainer").find(".addTempoButton").toggleClass("hide");
        $("#"+div).find(".tempoContainer").find(".tempo-active").toggleClass("hide");

        $("#"+div).find(".tempoContainer").find(".tempo-active").find(".tempo1").val(exercise.tempo1);
        $("#"+div).find(".tempoContainer").find(".tempo-active").find(".tempo2").val(exercise.tempo2);
        $("#"+div).find(".tempoContainer").find(".tempo-active").find(".tempo3").val(exercise.tempo3);
        $("#"+div).find(".tempoContainer").find(".tempo-active").find(".tempo4").val(exercise.tempo4);

    }


    $("#"+div).find("h5").text(exercise.exercise.name);
    $("#"+div).find(".exerciseOptions").find(".edit").attr("onclick",'editExercise(this)');
    $("#"+div).find(".exerciseOptions").find(".duplicate").attr("onclick",'duplicateExercise(this)');
    $("#"+div).find(".exerciseOptions").find(".move-up").attr("onclick",'moveUp(this)');
    $("#"+div).find(".exerciseOptions").find(".move-down").attr("onclick",'moveDown(this)');
    $("#"+div).find(".exerciseOptions").find(".delete").attr("onclick",'deleteExericse(this)');
    $("#"+div).find(".exerciseNote").find(".spanContainer").attr("onclick",'exerciseNote(this)');
}


function moveUp(object){
    id = $(object).closest(".exerciseTarget").attr("id");
    if(id === undefined){
        if(debug) console.log("entered undefined");

        group = $(object).closest(".mainExerciseBlock").attr("group");
        if(debug) console.log(group);

        if(group > 0){
            console.log(exerciseGroups);
            idSwapped = "";
            swapWith = exerciseGroups[group-1];
            exerciseGroups[group-1] = exerciseGroups[group];
            exerciseGroups[group] = swapWith;

            swapWith = exerciseGroupsRest[group-1];
            swapWithRestTime = swapWith.restTime;
            exerciseGroupsRest[group-1] = exerciseGroupsRest[group];
            swapWith.restTime = exerciseGroupsRest[group].restTime;
            exerciseGroupsRest[group-1].restTime = swapWithRestTime;
            exerciseGroupsRest[group] = swapWith;


            if(debug) console.log(exerciseGroups);
            recreateWorkoutFromJson();
        }
    } else {


    if(debug) console.log(id);
    idParts = explodeId(id);
    group = idParts[0];
    exNumber = idParts[1];
    if(debug) console.log(exNumber);
    if(exerciseGroups[group].length == 1){
        if(group > 0){
            idSwapped = "";
            swapWith = exerciseGroups[group-1];
            exerciseGroups[group-1] = exerciseGroups[group];
            exerciseGroups[group] = swapWith;

            swapWith = exerciseGroupsRest[group-1];
            swapWithRestTime = swapWith.restTime;
            exerciseGroupsRest[group-1] = exerciseGroupsRest[group];
            swapWith.restTime = exerciseGroupsRest[group].restTime;
            exerciseGroupsRest[group-1].restTime = swapWithRestTime;
            exerciseGroupsRest[group] = swapWith;


            if(debug) console.log(exerciseGroups);
            recreateWorkoutFromJson();
        }
    } else {
        if(exNumber > 0){
            idSwapped = "";
            swapWith = exerciseGroups[group][exNumber-1];
            exerciseGroups[group][exNumber-1] = exerciseGroups[group][exNumber];
            exerciseGroups[group][exNumber] = swapWith;
            recreateWorkoutFromJson();
        }
    }
    }
}


function moveDown(object){
    id = $(object).closest(".exerciseTarget").attr("id");
    if(debug) console.log(id);
    if(id === undefined){
        if(debug) console.log("entered undefined");
        group = $(object).closest(".mainExerciseBlock").attr("group");
        if(debug) console.log(group);
        if(group < exerciseGroups.length-1){
            idSwapped = "";
            swapWith = exerciseGroups[parseInt(group)+1];
            exerciseGroups[parseInt(group)+1] = exerciseGroups[group];
            exerciseGroups[group] = swapWith;

            if( exerciseGroupsRest[parseInt(group)+1] === undefined ||  exerciseGroupsRest[parseInt(group)+1] === null){
                exerciseGroupsRest[parseInt(group)+1] = {};
                exerciseGroupsRest[parseInt(group)+1].type = "regular";
                exerciseGroupsRest[parseInt(group)+1].restTime = undefined;
            }
            swapWith = exerciseGroupsRest[parseInt(group)+1];
            swapWithRestTime = swapWith.restTime;
            exerciseGroupsRest[parseInt(group)+1] = exerciseGroupsRest[group];
            swapWith.restTime = exerciseGroupsRest[group].restTime;
            exerciseGroupsRest[parseInt(group)+1].restTime = swapWithRestTime;
            exerciseGroupsRest[group] = swapWith;



            if(debug) console.log(exerciseGroups);
            recreateWorkoutFromJson();
        }
    } else {

        idParts = explodeId(id);
        group = idParts[0];
        exNumber = idParts[1];
        if(debug) console.log("Group: "+group);
        if(debug) console.log(exNumber);
        if(exerciseGroups[group].length == 1){
            if(group < exerciseGroups.length-1){
                if(debug) console.log("here");
                idSwapped = "";
                swapWith = exerciseGroups[parseInt(group)+1];
                exerciseGroups[parseInt(group)+1] = exerciseGroups[group];
                exerciseGroups[group] = swapWith;

                if( exerciseGroupsRest[parseInt(group)+1] === undefined ||  exerciseGroupsRest[parseInt(group)+1] === null){
                    exerciseGroupsRest[parseInt(group)+1] = {};
                    exerciseGroupsRest[parseInt(group)+1].type = "regular";
                    exerciseGroupsRest[parseInt(group)+1].restTime = undefined;
                }
                swapWith = exerciseGroupsRest[parseInt(group)+1];
                swapWithRestTime = swapWith.restTime;
                exerciseGroupsRest[parseInt(group)+1] = exerciseGroupsRest[group];
                swapWith.restTime = exerciseGroupsRest[group].restTime;
                exerciseGroupsRest[parseInt(group)+1].restTime = swapWithRestTime;
                exerciseGroupsRest[group] = swapWith;


                if(debug) console.log(exerciseGroups);
                recreateWorkoutFromJson();
            }
        } else {
            if(exNumber < exerciseGroups[group].length){
                idSwapped = "";
                swapWith = exerciseGroups[group][parseInt(exNumber)+1];
                exerciseGroups[group][parseInt(exNumber)+1] = exerciseGroups[group][exNumber];
                exerciseGroups[group][exNumber] = swapWith;
                recreateWorkoutFromJson();
            }
        }
    }
}


function isCircuit(group){
    if(exerciseGroupsRest[group] && exerciseGroupsRest[group]["circuitStyle"] !== undefined){
        if(debug) console.log("It is a circuit");
        return true;
    } else{
        if(debug) console.log("It is a NOT circuit");

        return false;
    }
}
function duplicateExercise(object){
    id = $(object).closest(".exerciseTarget").attr("id");
    if(debug) console.log(id);

    idParts = explodeId(id);
    group = idParts[0];
    exNumber = idParts[1];
    if(isCircuit(group)){
            duplicate = exerciseGroups[group][exNumber];
            duplicate = JSON.parse(JSON.stringify(duplicate));
            exerciseGroups[group][exerciseGroups[group].length] = duplicate;
            recreateWorkoutFromJson();
    } else {
            duplicate = exerciseGroups[group];
            duplicate = JSON.parse(JSON.stringify(duplicate));
            duplicate2 = exerciseGroupsRest[group];
            duplicate2 = JSON.parse(JSON.stringify(duplicate2));
            exerciseGroups[exerciseGroups.length] = duplicate;
            exerciseGroupsRest[exerciseGroupsRest.length] = duplicate2;
            recreateWorkoutFromJson();
    }

    addingExerciseMenu();
}


function duplicateCircuit(object){
    group = $(object).closest(".circuitContainer").attr("group");

    duplicate = exerciseGroups[group];
    exerciseGroups[exerciseGroups.length] = JSON.parse(JSON.stringify(duplicate));;

    duplicate = exerciseGroupsRest[group];
    exerciseGroupsRest[exerciseGroupsRest.length] = JSON.parse(JSON.stringify(duplicate));;

    recreateWorkoutFromJson();
    addingExerciseMenu();
}


function deleteExericse(object){
    id = $(object).closest(".exerciseTarget").attr("id");
    if(debug) console.log(id);
    idParts = explodeId(id);
    group = idParts[0];
    exNumber = idParts[1];

    if(exerciseGroups[group].length == 1){
         exerciseGroupsRest[group] = undefined;
        exerciseGroupsRest = exerciseGroupsRest.filter(function( element ) { return !!element; });
    }

    exerciseGroups[group][exNumber] = undefined;
    exerciseGroups[group] = exerciseGroups[group].filter(function( element ) { return !!element; });



    recreateWorkoutFromJson();
    addingExerciseMenu();
}

function deleteCircuitAdding(object){
    $(object).closest(".circuitContainer").remove();
}


function deleteCircuit(object){
    group = $(object).closest(".circuitContainer").attr("group");
    if(debug) console.log(group);
    if(debug) console.log(exerciseGroups);
    exerciseGroups[group] = undefined;
    exerciseGroups = exerciseGroups.filter(function( element ) { return !!element; });

    exerciseGroupsRest[group] = undefined;
    exerciseGroupsRest = exerciseGroupsRest.filter(function( element ) { return !!element; });

    recreateWorkoutFromJson();
    addingExerciseMenu();
}


function editExercise(object){
    id = $(object).closest(".exerciseTarget").attr("id");
    editId = id;
    showSearch("regular");
}

function editCircuit(object){
    id = $(object).closest(".exerciseTarget").attr("id");


    $(object).closest(".circuitEditing").addClass("circuitEditing");
    $(object).closest(".circuit").find(".circuitExerciseOptions").removeClass("rowStyle");
    $(object).closest(".circuit").find(".circuitSetUp").show();

    group = $(object).closest(".circuitContainer").attr("group");

    circuitType = exerciseGroupsRest[group]["circuitStyle"];

    if(circuitType == "rounds"){
        $(object).closest(".circuitExerciseOptions").find(".numberOfRounds").val(exerciseGroupsRest[group]["circuitRound"]);
    }else if(circuitType == "amrap"){
        $(object).closest(".circuitExerciseOptions").find(".amrapValue").val(exerciseGroupsRest[group]["circuitMaxTime"]);
    } else if(circuitType == "emom") {
        $(object).closest(".circuitExerciseOptions").find(".emomValue").val(exerciseGroupsRest[group]["circuitEmom"]);
    }
    $(object).closest(".circuitExerciseOptions").find(".restText").text(exerciseGroupsRest[group]["circuitRest"]);


}

function imageFilter(image,video,youtube){

    if(image !== undefined && image !== null){
        return image;
    }else if( (video !== undefined && video !== null) || (youtube !== undefined && youtube !== null)){
        return "{{ Config::get("constants.videoPlaceholder") }}";
    } else  {
        return "{{ Helper::image(null) }}";
    }
}


function showExercisePopUp(){
    $("#addExerciseForm").show();
}


</script>

<script>
    
    function exercisemodal(){
        $(".lightBox").addClass("lightBox-activated");
        $(".popup_container").addClass("popup_container-activated");
        $(".lightbox_mask").addClass("lightbox_mask-activated");
        $("body").addClass('no_scroll_overlay');

    }

    function hideexerclosemodal(e) {
        if (e.target == $('.lightBox')[0]){
            resetTypeWorkout();
            $(".lightBox").removeClass("lightBox-activated");
            $(".popup_container").removeClass("popup_container-activated");
            $(".lightbox_mask").removeClass("lightbox_mask-activated");
            $("body").removeClass('no_scroll_overlay');
        }
    }

    function hideexerclosemodalWithoutE() {
        $(".lightBox").removeClass("lightBox-activated");
        $(".popup_container").removeClass("popup_container-activated");
        $(".lightbox_mask").removeClass("lightbox_mask-activated");
        $("body").removeClass('no_scroll_overlay');
    }

    
</script>

@endsection
