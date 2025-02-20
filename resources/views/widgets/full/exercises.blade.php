@php
    use App\Http\Libraries\Helper;
    use App\Http\Libraries\Messages;
@endphp
    <!-- widget/full/exercises.blade.php  THIS IS WIDGET THAT POPULATES THE EXERCISE PAGE  -->

@if($permissions["view"])
    @if (count($exercises) > 0)
            <?php $i = 0; ?>
        @foreach ($exercises as $exercise)
            <div class="exercisesImageContainer">
                <div class="exercisesimages">
                    <a href="{{ Lang::get("routes./Exercise") }}/{{ $exercise->id }}/{{ Helper::formatURLString($exercise->name) }}"><span>{{ $exercise->name }}</span>
                        <img alt="{{$exercise->name}}" width="206" height="206"
                             src="/{{ Helper::image($exercise->thumb,$exercise->video,$exercise->youtube) }}"
                             class="refreshImage exerciseImage"/></a>
                </div>
                <div class="exerciseActionsContainer">
                    <a href="javascript:void(0)"
                       onClick="rotateLeft({{ $exercise->id }},$(this)); arguments[0].stopPropagation(); return false;"
                       class="exerciseOption">
                        <img src="{{asset('assets/img/rotate_left-white.png')}}" class="exerciseOptionImg"/>
                    </a>
                    <a href="javascript:void(0)"
                       onClick="rotateRight({{ $exercise->id }},$(this)); arguments[0].stopPropagation(); return false;"
                       class="exerciseOption">
                        <img src="{{asset('assets/img/rotate_right-white.png')}}" class="exerciseOptionImg"/>
                    </a>
                    <a href="{{ Lang::get("routes./EditExercise") }}/{{ $exercise->id }}" class="exerciseOption"><label
                            class="exerciseOptionImg">edit</label></a>
                    <a href="javascript:void(0)"
                       onClick="deleteExercise({{ $exercise->id }},$(this)); arguments[0].stopPropagation(); return false;"
                       class="exerciseOption">
                        <img src="{{asset('assets/img/svg/closeWhite.svg')}}" class="exerciseOptionImg"/>
                    </a>
                </div>
            </div>

                <?php $i++; ?>
        @endforeach

        <script>

            function deleteExercise(id, obj) {
                $.ajax(
                    {
                        url: "/widgets/exercises/" + id,
                        type: "DELETE",

                        success: function (data, textStatus, jqXHR) {
                            successMessage(data);
                            widgetsToReload.push("w_exercises_full");
                            refreshWidgets();
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            errorMessage(jqXHR.responseText);
                        },
                    });
            }

        </script>

    @else
        {!! Messages::showEmptyMessage("ExercisesEmpty",$permissions["self"]) !!}
    @endif

    @if($total > count($exercises))
        <div class="clearfix"></div>
        <div class="btmbuttonholder">
            <div class="clearfix"></div>
            <span class="hrborder"></span>
            <a href="javascript:void(0)" onclick="callWidget('exercises_full',{{ count($exercises) }},null,$(this))"
               class="greybtn">{{ Lang::get("content.MoreExercises") }}</a>
        </div>
    @endif
@else
    {!! Messages::showEmptyMessage("NoPermissions") !!}
@endif


<script>

    function rotateLeft(id) {
        $.ajax({
            url: "{{ Lang::get("routes./Exercises/Rotate/Left") }}",
            type: "POST",
            data: {id: id},
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

    function rotateRight(id) {
        $.ajax({
            url: "{{ Lang::get("routes./Exercises/Rotate/Right") }}",
            type: "POST",
            data: {id: id},
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
