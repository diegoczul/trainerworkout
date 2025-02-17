@extends("layouts.visitor")

@section("content")


 <section id="content" class="clearfix">
 <div class="wrapper">
<div class="widgets fullwidthwidget shadow marginleftnone" style="min-height:10px;"> <a class="bluebtn" href='/'>Sign Up to Trainer Workout</a></div>
            <div class="widgets fullwidthwidget shadow marginleftnone">
                <h1>Exercise {{ $exercise->name }}</h1>
                <!-- add exercises -->
                <div class="add-exercises clearfix">
                  <!-- add exercises form -->

                  <div class="fltleft exercisesblockleft marginleftnone">

                    <input name="action" value="addexercise" type="hidden" />
                    <p> <strong>Bodygroup: {{ ($exercise->bodygroup) ? $exercise->bodygroup->name : "" }}</strong> </p>
                    <p>{{ $exercise->description }}</p>
                   <?php
                      if($exercise->video != ""){
                     ?>


                                        <a

                                            href="/{{ $exercise->video}}"

                                            style="display:block;width:500px; height:400px"

                                            id="player">

                                        </a>
                     <?php
                      }
                    ?>
                     @if($exercise->youtube != "")
                   <iframe id="ytplayer" type="text/html" width="500" height="315"
  src="https://www.youtube.com/embed/{{$exercise->youtube }}"">?autoplay=1"
  frameborder="0"> </iframe>
                    @endif
                  </div>

                  <div class="fltright exercisesblockright">

                    <div id="image1" style="width:220px; margin-right:10px; float:left"><img width="220" src="/{{ Helper::image($exercise->image) }}" /></div>
                    <div id="image2" style="width:220px; float:left"><img width="220" src="/{{ Helper::image($exercise->image2) }}" /></div>

                    <fieldset>
                     <!-- <input class="title linkblock border-radius" type="text" placeholder="https://www.youtube.com/watch?" name="video">-->

                      <input style="margin-top:10px;" class="title linkblock border-radius" type="text" placeholder="Equipment" name="equipment" disabled="disabled" value="{{ ( $exercise->equipment != ''? $exercise->equipment : 'Equipment NA')}}">
                    </fieldset>

                  </div>

                </div>
                </div>
                    </section>

@endsection


@section("scripts")

                      <script type="text/javascript" src="/fw/flowplayer/flowplayer-3.2.2.min.js"></script>

                                        <script>

                                            jQuery(document).ready(function() {

                                                flowplayer('player', '/fw/flowplayer/flowplayer-3.2.2.swf', {wmode: "transparent", clip: {

                                                        autoPlay: true,

                                                        autoBuffering: true }

                                                });
                                            });


                                        </script>


<script type="text/javascript">
$(document).ready(function(){ $("#m_exercises").addClass('current'); });
</script>



@endsection