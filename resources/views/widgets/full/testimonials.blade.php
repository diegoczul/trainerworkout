 @if ($testimonials->count() > 0)
<?php $count = 0; ?>
 <ul>
 @foreach($testimonials as $testimonial)

                <li class="clearfix removeHover {{ ($count%3 == 0 ? "marginleftnone" : "") }}">
                @if($testimonial->userId == Auth::user()->id or $testimonial->fromUser = Auth::user()->id)
                    <a href="javascript:void(0)" onClick="deleteTestimonial({{ $testimonial->id }},$(this)); arguments[0].stopPropagation(); return false;" class="deleteicon2"></a>
                @endif
                    <div class="imagewrapper fltleft">
                        <a href="#" class="image"><img src="/{{ Helper::image($testimonial->fUser->image) }}" alt="image"></a>
                        <ul class="ratingstar">
                            
                      <?php
    $val='';
                                    for ($k = 1; $k < 6; $k++) { 
                                            $val .= '<li><a href="#"><img ';
                                            if ($k > $testimonial->rating) {
                                                $val .= 'src="/img/non-star.png" alt="star off"';
                                            } else {
                                                $val .= 'src="/img/star-active.png" alt="star active"';
                                            }
                                            $val .= '></a></li>';
                                        }
                                
                                  echo $val;
                                ?>
                          
                        </ul>
                    </div>
                    <div class="imagedetail fltright">
                        
                        <p id="more_{{ $count }}" style="display:none;">{{{ $testimonial->testimonial }}}</p>
                       <p id="less_{{ $count }}">
                       <?php 
				$no = count(explode(" ",$testimonial->testimonial));
				echo $val=substr( $testimonial->testimonial ,0,75);
				 $cut_no=count(explode(" ",$val));
				if($no >$cut_no){?>
				...</p>
				<?php } else {?> </p> <?php } ?>
                <a id="re_more_{{ $count }}" href="javascript:void(0)" onclick="expand(<?php echo $count; ?>);" class="lightgreybtn"><b>Expand</b></a>
                      @if($testimonial->userId == Auth::user()->id)
                            @if($testimonial->approved != "")
                                <a href="javascript:void(0)" onClick="testimonialAction('dissaprove',{{ $testimonial->id }})" class="bluebtn">Dissaprove</a>
                            @else 
                            <a href="javascript:void(0)" onClick="testimonialAction('approve',{{ $testimonial->id }})" class="bluebtn">Approve</a>
                            @endif
                        @endif
                    </div>
                </li>
 <?php $count++; ?>
@endforeach             
</ul>
    @else
        {{ Messages::showEmptyMessage("TestimonialsEmpty") }}
    @endif

    @if($total > $testimonials->count())
    <div class="clearfix"></div>
        <div class="btmbuttonholder">
                    <div class="clearfix"></div>
                        <span class="hrborder"></span>
                        <a href="javascript:void(0)" onclick="callWidget('w_testimonials_full',{{ $testimonials->count() }},null,$(this))" class="greybtn">More Testimonials</a>
                    </div>
    @endif

<script type="text/javascript">
function expand(id){
    $('#more_'+id).show();
    $('#less_'+id).hide(); 
    $('#re_more_'+id).hide(); 
}

function testimonialAction(status,id){
    $.ajax(
                            {
                                url : "/widgets/testimonials/status",
                                type: "POST",
                                data: {id:id,status:status},
                                success:function(data, textStatus, jqXHR) 
                                {
                                    successMessage(data);
                                    widgetsToReload.push("w_testimonials_full");
                                    refreshWidgets();
                                },
                                error: function(jqXHR, textStatus, errorThrown) 
                                {
                                    errorMessage(jqXHR.responseText);
                                },
                            });
}

function deleteTestimonial(id,obj){
                         $.ajax(
                            {
                                url : "/widgets/testimonials/"+id,
                                type: "DELETE",

                                success:function(data, textStatus, jqXHR) 
                                {
                                    successMessage(data);
                                    widgetsToReload.push("w_testimonials_full");
                                    refreshWidgets();
                                },
                                error: function(jqXHR, textStatus, errorThrown) 
                                {
                                    errorMessage(jqXHR.responseText);
                                },
                            });
                    }
</script>  
                  
   