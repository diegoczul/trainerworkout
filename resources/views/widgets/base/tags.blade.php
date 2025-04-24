

                      @if ($tags->count() > 0)
                     <!-- SUB MENU TO OPEN WHEN USER CLICK ON SHOW TAGS -->          
                <div class="tag_menu widget">
                    <div class="wrapper_tags client">
                        <h3 class="tags_h3">{{ Lang::get("content.ClientTags") }}</h3>
                        <hr>
                        <div class="container" style="position:relative;">
<!-- CLIENTS TAGS GO HERE -->
                         @foreach ($tags as $tag)
                         @if($tag->type == "user")
                         <div class="badge selabel-user" onclick="addToSearch('{{{ $tag->name }}}')"> {{{ $tag->name }}} @if(isset($workoutId) and $workoutId != "") 
                         <a class="tag_delete"  href="javascript:void(0)" onClick="removeTag({{ $tag->id }},$(this),event)">X</a>
                        @else
                            <a class="tag_delete" onClick="deleteTag({{ $tag->id }},$(this),event)">X</a>
                        @endif
                         </div>
                         @endif

                         @endforeach

                        </div>
                    </div>
                    <div class="wrapper_tags keyword">
                        <h3 class="tags_h3">{{ Lang::get("content.KeywordTags") }}</h3>
                        <hr>
                        <div class="container" style="position:relative;">
<!-- KEYWORDS TAGS GO HERE -->
                         @foreach ($tags as $tag)
                         @if($tag->type != "user")


                         <div class="badge selabel-tag" onclick="addToSearch('{{{ $tag->name }}}')"><i class="fa fa-tag"></i>  {{{ $tag->name }}} @if(isset($workoutId) and $workoutId != "") 

                         <a class="tag_delete"  href="javascript:void(0)" onClick="removeTag({{ $tag->id }},$(this),event)">X</a>
                        @else
                            <a class="tag_delete" onClick="deleteTag({{ $tag->id }},$(this),event)">X</a>
                        @endif
                         </div>
                         @endif

                         @endforeach

                        </div>
                    </div>
                </div>
<!--sub menu ends here -->


    <script>

    function deleteTag(id,obj,event){
        event.stopPropagation();
        if(confirm('{{{ Lang::get("messages.Confirmation") }}}')){
         $.ajax(
            {
                url : "/widgets/tags/"+id,
                type: "DELETE",

                success:function(data, textStatus, jqXHR) 
                {
                    successMessage(data);
                    widgetsToReload.push("w_tags");
                    refreshWidgets();
                    event.stopPropagation();
                },
                error: function(jqXHR, textStatus, errorThrown) 
                {
                    errorMessage(jqXHR.responseText);
                },
            });
         event.stopPropagation();
     }
    }

    </script>

 @else
   
@endif

@if($total > $tags->count())
<div class="clearfix"></div>
    <div class="btmbuttonholder">
                <div class="clearfix"></div>
                    <span class="hrborder"></span>
                    <a href="javascript:void(0)" onclick="callWidget('w_tags',{{ $tags->count() }},null,$(this))" class="greybtn">More Tags</a>
                </div>
@endif

<script>

function addToSearch(value){
    if($("#searchWorkouts").val() != ""){
        var string  = $("#searchWorkouts").val();
        if( string.indexOf(value) == -1 ) $("#searchWorkouts").val($("#searchWorkouts").val()+","+value);
    } else {
         $("#searchWorkouts").val(value);
    }
    searchWorkouts();
}

</script>

