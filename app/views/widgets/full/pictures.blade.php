@if($permissions["view"])  
<?php $counter = 0; ?>
@if ($pictures->count() > 0)
    @foreach($pictures as $picture)
    
            <div class="profielpictures clearfix">
                    <div class="profiledates">
                   {{ Helper::date($picture->recordDate) }}
                    <a class="deleteicon3" href="javascript:void(0)" onClick="deletePictures({{ $picture->id }},$(this))">edit</a>
              
                    </div>
                    <div class="profileimages">

                        <a href="/{{ Helper::image($picture->front) }}"  data-lightbox="front" class="lightbox" title="Front - {{ Helper::date($picture->created_at) }}"><span>Front</span>

                        <img src="/{{ Helper::image($picture->front) }}" alt="body front" class="img-thumb-picture"></a>
                    </div>
                    <div class="profileimages">
                        <a href="/{{ Helper::image($picture->back) }}"  data-lightbox="back" class="lightbox" title="Back - {{ Helper::date($picture->created_at) }}" ><span>Back</span>
                        <img src="/{{Helper::image($picture->back)}}" alt="body back" class="img-thumb-picture"></a>
                    </div>
                    <div class="profileimages">
                        <a href="/{{ Helper::image($picture->left) }}" data-lightbox="left" class="lightbox" title="Left - {{ Helper::date($picture->created_at) }}" ><span>Left</span>
                        <img src="/{{Helper::image($picture->left)}}" alt="body right" class="img-thumb-picture"></a>
                    </div>
                    <div class="profileimages">
                        <a href="/{{ Helper::image($picture->right) }}"  data-lightbox="right" class="lightbox" title="Right - {{ Helper::date($picture->created_at) }}" ><span>Right</span>
                        <img src="/{{ Helper::image($picture->right) }}" alt="body left" class="img-thumb-picture"></a>
                    </div>
                </div>
                <?php $counter++; ?>
    @endforeach
    <script>

    function deletePictures(id,obj){
         $.ajax(
            {
                url : "/widgets/pictures/"+id,
                type: "DELETE",

                success:function(data, textStatus, jqXHR) 
                {
                    successMessage(data);
                    widgetsToReload.push("w_pictures_full");
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
    {{ Messages::showEmptyMessage("PicturesEmpty",$permissions["self"]) }}
@endif

@if($total > $pictures->count())
<div class="clearfix"></div>
    <div class="btmbuttonholder">
                <div class="clearfix"></div>
                    <span class="hrborder"></span>
                    <a href="javascript:void(0)" onclick="callWidget('w_pictures_full',{{ $pictures->count() }},null,$(this))" class="greybtn">More Pictures</a>
                </div>
@endif            
@else
    {{ Messages::showEmptyMessage("NoPermissions") }}
@endif            


