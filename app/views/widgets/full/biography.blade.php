

@if($permissions["view"])  
@if($user->biography != "" || $user->certifications != "" || $user->past_experience != "" )
@if($user->biography != "")
                        <h1><?php echo ucwords($user->firstName); ?>&CloseCurlyQuote;s Bio </h1>
                       
 <div id="more_bio" style="display:none;"><?php echo ucwords($user->biography); ?></div>
                               <div id="less_word"><?php 
						$no = count(explode(" ",$user->biography ));
						echo $val=substr($user->biography ,0,350);
						 $cut_no=count(explode(" ",$val));
						if($no >$cut_no){?>
						...</div><div id="re_more_word" class="btmbuttonholder re_more_bio"> <span class="hrborder"></span> <a  href="javascript:void(0)" onclick="expand_data_bio('bio');" class="greybtn bluegrehover">Expand</a></div>	
						<?php }else {?> </p> <?php } ?>
@endif

@if($user->certifications != "")
                        <h1>Certifications </h1>
                        <p><?php //echo ucwords($attrUser['word']) ?></p>
 <div id="more_cert" style="display:none;"><?php echo ucwords($user->certifications); ?></div>
                               <div id="less_word"><?php 
						$no = count(explode(" ",$user->certifications));
						echo $val=substr($user->certifications,0,350);
						 $cut_no=count(explode(" ",$val));
						if($no >$cut_no){?>
						...</div><div id="re_more_word" class="btmbuttonholder re_more_cert"> <span class="hrborder"></span> <a  href="javascript:void(0)" onclick="expand_data_bio('cert');" class="greybtn bluegrehover">Expand</a></div>	
						<?php }else {?> </p> <?php } ?>
@endif
@if($user->past_experience != "")
                        <h1>Experience </h1>
                        <p><?php //echo ucwords($attrUser['word']) ?></p>
 <div id="more_exp" style="display:none;"><?php echo ucwords($user->past_experience); ?></div>
                               <div id="less_word"><?php 
						$no = count(explode(" ",$user->past_experience));
						echo $val=substr($user->past_experience,0,350);
						 $cut_no=count(explode(" ",$val));
						if($no >$cut_no){?>
						...</div><div id="re_more_word" class="btmbuttonholder re_more_exp"> <span class="hrborder"></span> <a  href="javascript:void(0)" onclick="expand_data_bio('exp');" class="greybtn bluegrehover">Expand</a></div>	
						<?php }else {?> </p> <?php } ?>
@endif
@else
    {{ Messages::showEmptyMessage("BioEmpty",$permissions["self"]) }}
@endif
@else
    {{ Messages::showEmptyMessage("NoPermissions") }}
@endif


<script type="text/javascript">


    function expand_data_bio(type){
      $('#more_'+type).show();
      $('#less_'+type).hide(); 
      $('.re_more_'+type).hide(); 
    }
  
</script>


