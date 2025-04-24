
 @if ($tags->count() > 0)
                <div class="tagdetails">
                @foreach ($tags as $tag)
                
                    
                    @if($tag->type == "user")
                    <div class="badge label-user" onclick="addToSearch('{{{ $tag->name }}}')" style="cursor:default">
                
                    @else
                    <div class="badge label-tag" onclick="addToSearch('{{{ $tag->name }}}')" style="cursor:default">
                    <i class="fa fa-tag"></i>
                    @endif
                  
                     {{{ $tag->name }}} @if(isset($workoutId) and $workoutId != "") 
                        <a href="javascript:void(0)" onClick="removeTag({{ $tag->id }},this,event)"> &nbsp;&nbsp;&nbsp;&nbsp;X </a> 
                    @else
                        <a href="javascript:void(0)" onClick="deleteTag({{ $tag->id }},this,event)"> &nbsp;&nbsp;&nbsp;&nbsp;X </a> 
                    
                    @endif
                    </div>
                @endforeach
                     </div>


    <script>

    function deleteTag(id,obj,event){
        if(obj !== undefined && obj !== null) obj = $(obj);
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

