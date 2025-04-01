{{ Form::open(array('url' => '/widgets/tags/addEdit/',"id"=>"form")) }}
<?php
$selected = explode(",",$selectedTags);
?>
<div class="tags">
    <div class="clientTags">
        <label class="add_tag_header"> {{ Lang::get("content.ClientNametags") }}</label>
        <input type="text" placeholder="{{ Lang::get("content.tagname") }}" id="tagNameClient" name="tagNameClient" class="addTag"  onClick="$('.suggestionBox').hide(); $('.suggestionBoxClients').slideDown()"/>
        <div class="suggestionBox suggestionBoxClients">
            <h1 class="client sizedown">{{ Lang::get("content.ClientsTags") }}</h1>
            <div>
                @foreach ($tagsClient as $tag)
                    @if($tag->type == "user")
                        <div class="badge {{ (in_array($tag->name,$selected) ? 'label-user' : 'selabel-user') }}" onclick="addToTags('{{{ $tag->name }}}','user',$(this))" style="cursor:pointer">
                            <i class="fa fa-user"></i>
                    @else
                        <div class="badge {{ (in_array($tag->name,$selected) ? 'label-tag' : 'selabel-tag') }}" onclick="addToTags('{{{ $tag->name }}}','tag',$(this))" style="cursor:pointer">
                            <i class="fa fa-tag"></i>
                    @endif
                            {{{ $tag->name }}}
                        </div>
                @endforeach
                        </div>
            </div>
        </div>
        <div class="tagsTags">
            <label class="add_tag_header"> {{ Lang::get("content.Tags") }}</label>
            <input type="text" placeholder="{{ Lang::get("content.tagname") }}" id="tagNameTag" name="tagNameTag" class="addTag" onClick="$('.suggestionBox').hide(); $('.suggestionBoxTags').slideDown()"/>
            <div class="suggestionBox suggestionBoxTags">
                <h1 class="keyword sizedown">{{ Lang::get("content.KeywordTags") }}</h1>
                <div>

                    @foreach ($tagsTags as $tag)
                        @if($tag->type == "user")
                            <div class="badge {{ (in_array($tag->name,$selected) ? 'label-user' : 'selabel-user') }}" onclick="addToTags('{{{ $tag->name }}}','user',$(this))" style="cursor:pointer">
                                <i class="fa fa-user"></i>

                                @else
                                    <div class="badge {{ (in_array($tag->name,$selected) ? 'label-tag' : 'selabel-tag') }}" onclick="addToTags('{{{ $tag->name }}}','tag',$(this))" style="cursor:pointer">
                                        <i class="fa fa-tag"></i>
                                        @endif
                                        {{{ $tag->name }}}
                                    </div>
                                    @endforeach


                            </div>
                </div>
            </div>

        </div>
        @if(isset($workoutId))
            <input type="hidden" name="workoutId" id="workoutId" value="{{ $workoutId }}">
        @endif
        <input type="submit" class="bluebtn" value="Save" widget="w_tags">
        {{Form::close() }}
        <script>

            $("#form").bind("keypress", function (e) {
                if (e.keyCode == 13) {
                    e.preventDefault();
                    showTopLoader();
                    addTag();
                }
            });
            $("#form").submit( function (e) {
                e.preventDefault();
                showTopLoader();
                addTag();
            });

            function addToTags(tag,type,obj){
                showTopLoader();
                if(type == "tag"){
                    $("#tagNameTag").val(tag);
                    obj.removeClass("selabel-tag");
                    obj.addClass("label-tag");
                    $(".suggestionBox").hide();
                } else {
                    $("#tagNameClient").val(tag);
                    obj.removeClass("selabel-user");
                    obj.addClass("label-user");
                    $(".suggestionBox").hide();
                }
                $.ajax(
                    {
                        url : "/widgets/tags/addEdit",
                        type: "POST",
                        data:{tagNameClient:$("#tagNameClient").val(),tagNameTag:$("#tagNameTag").val(),workoutId:$("#workoutId").val()},
                        success:function(data, textStatus, jqXHR)
                        {
                            successMessage(data);
                            $(".addTag").val("");
                            callWidget("w_tagsWorkout",null,null,null,{workoutId:$("#workoutId").val()});

                        },
                        error: function(jqXHR, textStatus, errorThrown)
                        {
                            hideTopLoader();
                            errorMessage(jqXHR.responseText);
                        },
                    });
            }

            function addTag(){
                showTopLoader();
                $.ajax(
                    {
                        url : "/widgets/tags/addEdit",
                        type: "POST",
                        data:{tagNameClient:$("#tagNameClient").val(),tagNameTag:$("#tagNameTag").val(),workoutId:$("#workoutId").val()},
                        success:function(data, textStatus, jqXHR)
                        {
                            if($("#tagNameClient").val() != ""){
                                var output = ' <div class="label badge label-user">'+'<i class="fa fa-user"></i>'+$("#tagNameClient").val()+'</div>';
                                $(".usersToAdd").append(output);
                            }
                            if($("#tagNameTag").val() != ""){
                                var output = ' <div class="label badge label-tag">'+'<i class="fa fa-tag"></i>'+$("#tagNameTag").val()+'</div>';
                                $(".tagsToAdd").append(output);
                            }
                            successMessage(data);
                            $(".addTag").val("");
                            callWidget("w_tagsWorkout",null,null,null,{workoutId:$("#workoutId").val()});

                        },
                        error: function(jqXHR, textStatus, errorThrown)
                        {
                            hideTopLoader();
                            errorMessage(jqXHR.responseText);
                        },
                    });
                $(".suggestionBox").hide();
            }
        </script>