{{ Form::open(array('url' => '/widgets/testimonials/addEdit/')); }}
<div id="dv-rate" class=" clearfix ">
<h6>Give Your Review</h6>
        <ul class="ratingstars">
<?php
                $html = '';
                for ($i = 1; $i < 6; $i++) {
                  $act = '0'; 
                    $html .= '<li><a href="javascript:void(0)"'; $html .= ' onclick="rateExercise( '.$i.', $(this)); return false;"';  $html .= ' active="'.$act.'" star="'.$i.'"><img ';
                   
                        $html .= 'src="/img/star-off-big.png" alt="star off"';
                    $html .= 'star="'.$i.'" /></a></li>';
                }
                echo $html;
            ?>
</ul>
</div >
<div id="comments">
 <textarea id="Comment" name="testimonial" class="descriptionObjectives border-radius" placeholder="Comment"></textarea>
 
                                     	
                           <input type="hidden" name="rating" id="rating" value="">  
                           <input type="submit" class="bluebtn ajaxSave{{ Helper::getTypeOfCall($user->id) }}" value="Save" widget="w_testimonials_full">
                      
                           {{ Form::hidden("userId",$user->id) }}
                        </div> 
                    
{{Form::close() }}
<script>
    function rateExercise(rate, el) {
    var preLoad = 0;
    if (rate <= 6 && rate >= 1) {
       
                                        $('#dv-rate').find('a').each(function() {

                                            if ($(this).attr('star') == rate) {
                                                $(this).attr('active', 1);
                                            } else {
                                                $(this).attr('active', 0);
                                            }
                                        });
             updateGlobalRating(1, 1, rate);
                                      
    } else {
         error_mess("Error");
    }
    return false;
}
function updateGlobalRating(rc, rw, rate) {
    $('#dv-rate img').unbind('hover', null);
    $('#dv-rate img').unbind('click');
    $('#dv-rate').find("img").each(function(i) {

        if (i < rate) {

            $(this).attr('src', '/img/star-active-big.png');
        } else {
            $(this).attr('src', '/img/star-off-big.png');
        }
    });
   $('input[name=rating]').val(rate);
   $('#comments').show();
}

</script>