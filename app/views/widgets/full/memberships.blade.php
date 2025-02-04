@if($permissions["view"])     
     @if ($exercises->count() > 0)
     <?php $i = 0; ?>
      @foreach ($exercises as $exercise)

                        <div class="exercisesimages {{ ($i % 6 == 0 ? ' marginleftnone':'') }}" >
                        <a href="javascript:void(0)" onClick="deleteExercise({{ $exercise->id }},$(this)); arguments[0].stopPropagation(); return false;" class="deleteicon2"></a>
                             <a href="/Exercise/{{ $exercise->id }}/{{ Helper::formatURLString($exercise->name) }}"><span>{{ $exercise->name }}</span>
                            <img alt="{{$exercise->name}}" width="206" src="/{{ Helper::image($exercise->thumb) }}"/></a>
                        </div>
                        <?php $i++; ?>
                    

                  
                    @endforeach

                        <script>

                        function deleteExercise(id,obj){
                             $.ajax(
                                {
                                    url : "/widgets/exercises/"+id,
                                    type: "DELETE",

                                    success:function(data, textStatus, jqXHR) 
                                    {
                                        successMessage(data);
                                        widgetsToReload.push("exercises_full");
                                        refreshWidgets();
                                    },
                                    error: function(jqXHR, textStatus, errorThrown) 
                                    {
                                        errorMessage(jqXHR.responseText);
                                    },
                                });
                        }

                        </script>


    @else
        {{ Messages::showEmptyMessage("ExercisesEmpty",$permissions["self"]) }}
    @endif

    @if($total > $exercises->count())
    <div class="clearfix"></div>
        <div class="btmbuttonholder">
                    <div class="clearfix"></div>
                        <span class="hrborder"></span>
                        <a href="javascript:void(0)" onclick="callWidget('exercises_full',{{ $exercises->count() }},null,$(this))" class="greybtn">More Exercises</a>
                    </div>
    @endif
@else
    {{ Messages::showEmptyMessage("NoPermissions") }}
@endif
