@if($permissions["view"])  
@if ($user->word != "" or $user->videoKey != "")

@if ($user->videoKey != "")
 <div class="videowrapper fltleft">


                   <iframe id="ytplayer" type="text/html" width="560" height="315"
  src="https://www.youtube.com/embed/{{ $user->videoKey }}?autoplay=1"
  frameborder="0"> </iframe>
                    </div>
@endif

                    <div class="videodetail {{ ($user->videoKey != "") ? "fltright" : "fullWidth" }}">
                    @if ($user->videoKey != "")
                        <h1>A word from  {{ $user->firstName }}</h1>
                    @endif
                       
<div id="more_word"  style="display:none;">{{ $user->word }}</div>
<div id="less_word">
<?php 
						$no = count(explode(" ",$user->word));
						echo $val=substr($user->word,0,350);
						 $cut_no=count(explode(" ",$val));
             ?>
						@if($no >$cut_no)
						...</div><div id="re_more_word" class="btmbuttonholder"> <span class="hrborder"></span> <a  href="javascript:void(0)" onclick="expand_data();" class="greybtn bluegrehover">Expand</a></div>	
						@else
            </div>
            @endif
                    </div>
@else
    {{ Messages::showEmptyMessage("WordEmpty",$permissions["self"]) }}
@endif
@else
    {{ Messages::showEmptyMessage("NoPermissions") }}
@endif




<script type="text/javascript">

    function expand_data(){
      $('#more_word').show();
      $('#less_word').hide(); 
      $('#re_more_word').hide(); 
    }

</script>



