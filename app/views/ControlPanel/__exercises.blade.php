 @extends('layouts.controlpanel')


@section('content')
                
                <div class-"col-lg-12">
                    <h1 class="page-header">Exercises Management</h1>
                </div>
                <div class="row add" id="w_exercises_add" style="display:none">
                {{ Form::open(array("url"=>"ControlPanel/Exercises/AddEdit/")) }}
                <input type="hidden" name="hiddenId" value = "" id="hiddenId" />
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel panel-heading">
                                <div class="form-group" style="margin-bottom:0px;">
                                    Insert / Edit Exercise
                                </div>
                            </div>
                            <div class="panel panel-body">
                                <div class="form-group">
                                    <label>Name</label>
                                    {{  FORM::text("name","",array("id"=>"name","placeholder"=>"Name", "class"=>"form-control")) }}
                                </div>
                                <div class="form-group">
                                    <label>Name Engine</label>
                                    {{  FORM::text("nameEngine","",array("id"=>"nameEngine","placeholder"=>"Name Engine", "class"=>"form-control")) }}
                                </div>
                                <div class="form-group">
                                    <label>User</label>
                                    {{  FORM::select("userId",$users,"",array("id"=>"userId","placeholder"=>"User", "class"=>"form-control chosen-select")) }}
                                </div>
                                <div class="form-group"> 
                                    <label>Author</label>
                                    {{  FORM::select("authorId",$users,"",array("id"=>"authorId","placeholder"=>"Author", "class"=>"form-control chosen-select")) }}
                                </div>
                                <div class="form-group">
                                    <label>BodyGroup</label>
                                    {{  FORM::select("bodygroupId",$bodygroups,null,array("id"=>"bodygroupId","placeholder"=>"BodyGroup", "class"=>" chosen-select", "style"=>"width:200px")) }}
                                </div>
                                <div class="form-group">
                                    <label>Video</label>
                                    {{  FORM::text("video","",array("id"=>"video","placeholder"=>"Video", "class"=>"form-control")) }}
                                </div>
                                <div class="form-group">
                                    <label>Type</label>
                                    {{  FORM::select("type",array("public"=>"public","private"=>"private","pending"=>"pending"),"public",array("id"=>"type","placeholder"=>"Type", "class"=>" chosen-select", "style"=>"width:200px")) }}
                                </div>
                                <div class="form-group">
                                    <label>Equipment</label>
                                    {{  FORM::text("equipment","",array("id"=>"equipment","placeholder"=>"Equipment", "class"=>"form-control")) }}
                                </div>
                                <div class="form-group">
                                    <label>Views</label>
                                    {{  FORM::text("views","",array("id"=>"views","placeholder"=>"Views", "class"=>"form-control")) }}
                                </div>
                                <div class="form-group">
                                    <label>Used</label>
                                    {{  FORM::text("used","",array("id"=>"used","placeholder"=>"Used", "class"=>"form-control")) }}
                                </div>
                               
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea class="form-control" rows="3" id="description" name="description"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>You Tube</label>
                                    {{  FORM::text("youtube","",array("id"=>"youtube","placeholder"=>"Youtube", "class"=>"form-control")) }}
                                     <iframe id="youtubeVideo" type="text/html" width="200" height="100" src=""> </iframe>
                                </div>
                                <div class="form-group">
                                    <label>Image 1</label>
                                    {{ Form::file('image1','',array('id'=>'image1','class'=>'')) }}
                                     <p class="help-block imageHolder" style="display:none"><img id="imageImage" src="/img/placeholder.png" width="200" /></p>
                                </div>
                                <div class="form-group">
                                    <label>Image 2</label>
                                    {{ Form::file('image2','',array('id'=>'image2','class'=>'')) }}
                                     <p class="help-block imageHolder" style="display:none"><img id="image2Image" src="/img/placeholder.png" width="200"/></p>
                                </div>
                                
                                <div class="form-group">
                                    <label>Video</label>
                                    {{ Form::file('video','',array('id'=>'','class'=>'')) }}
                                     <p class="help-block imageHolder" style="display:none"></p>
                                </div>
                                <div class="form-group">
                                    <label>Video Custom</label>
                                   <a href="/" style="display:block;width:200px; height:100px" id="player"> </a>
                                </div>
                                <div class="form-group">
                                    <label>Remove Green Screen</label>
                                     <input type="checkbox"  name="removeGreenScreen" id="removeGreenScreen" value="Yes">Remove Green Screen
                                </div>


                                <button class="btn btn-primary ajaxSaveImage">Save</button>
                            </div>
                        </div>
                    </div>
                {{ Form::close() }}
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel panel-heading">
                                <div class="form-group" style="margin-bottom:0px;">
                                    <button onClick="toggleAndClear('w_exercises_add')" type="button" class="btn btn-info">New Exercise</button>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover" id="dtExercises" >
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Name</th>
                                                <th>BodyGroup</th>
                                                <th>Name Engine</th>
                                                <th>Equipment</th>
                                                <th>Views</th>
                                                <th>User</th>
                                                <th>Author</th>
                                                <th>Created</th>
                                                <th>Edit</th>
                                                <th>Delete</th>
                                            </tr>
                                        </thead>
                                       
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


@endsection

@section("scripts")

<script type="text/javascript" src="/fw/flowplayer/flowplayer-3.2.2.min.js"></script>

<script>

$(document).ready(function(){
    List();
    
    // Replace the <textarea id="editor1"> with a CKEditor
    // instance, using default configuration.
    CKEDITOR.config.toolbar = [
   ['Styles','Font','FontSize'],
   ['Bold','Italic','Underline','StrikeThrough','-','Undo','Redo','-','Cut','Copy','Paste','Find','Replace','-','Outdent','Indent','-','Print'],
   ['NumberedList','BulletedList','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
   ['TextColor','BGColor','Source']];
    CKEDITOR.config.scayt_autoStartup = true;
});

function List(){
        dtExercises = $('#dtExercises').dataTable( {
                "processing": true,
                "serverSide": false,
                "iDisplayLength": 25,
                "ajax": {
                    "url": "/ControlPanel/Exercises",
                    "type": "POST",
                },
                 "fnServerParams": function ( aoData ) {
                      aoData.push( 
                                    { "name": "type", "value":  "Data" }
                                );
                },
                "columns": [
                            { "data": "thumb" },
                            { "data": "name" },
                            { "data": "bodygroup" },
                            { "data": "nameEngine" },
                            { "data": "equipment" },
                            { "data": "views" },
                            { "data": "userId" },
                            { "data": "authorId" },
                            { "data": "created_at" },
                            { "data": "id" },
                            { "data": "id" }
                        ],
                "columnDefs": [ 
                    { "render": function ( data, type, row ) { if(data !== null){ return data.name; } else { return ""; } },"targets": 2 }, 
                    { "render": function ( data, type, row ) { return image(data,100); },"targets": 0 }, 
                    { "render": function ( data, type, row ) { return echoEdit(data); },"targets": -2 }, 
                    { "render": function ( data, type, row ) { return echoRemove(data); },"targets": -1 }, 

                    { orderable: false, targets: -1 },
                    { orderable: false, targets: -2 }
                ],
                 "aaSorting": []
         });
        arrayDataTables["dtExercises"] = dtExercises;

    }

     function edit(id){
          $.ajax(
                {
                    url : "/ControlPanel/Exercises/"+id,
                    type: "GET",
                    success:function(data, textStatus, jqXHR) 
                    {
                        $("#name").val(data.name);
                        $("#nameEngine").val(data.nameEngine);
                        $("#userId").val(data.userId);
                        $("#userId").trigger("chosen:updated");
                        $("#authorId").val(data.authorId);
                        $("#authorId").trigger("chosen:updated");
                        $("#description").val(data.description);
                        $("#video").val(data.video);
                        $("#bodygroupId").val(data.bodygroupId);
                        $("#bodygroupId").trigger("chosen:updated");
                        $("#youtube").val(data.youtube);
                        $("#type").val(data.type);
                        $("#type").trigger("chosen:updated");
                        $("#equipment").val(data.equipment);
                        $("#views").val(data.views);
                        $("#used").val(data.used);
                        $("#hiddenId").val(data.id);
                        $(".imageHolder").show();
                        if(data.youtube != null){
                            $("#youtubeVideo").attr("src","https://www.youtube.com/embed/"+data.youtube+"?autoplay=0");
                        }
                        if(data.image != null){
                            $("#imageImage").attr("src","/"+data.image);
                        }
                        if(data.image2 != null){
                            $("#image2Image").attr("src","/"+data.image2);
                        }
                        if(data.video != null){
                            $("#videoVideo").attr("src","/"+data.video);
                        }

                        flowplayer('player', '/fw/flowplayer/flowplayer-3.2.2.swf', {wmode: "transparent", clip: {
                                autoPlay: false,
                                autoBuffering: true }   
                        });
                                            
                        down('w_exercises_add');
                    },
                    error: function(jqXHR, textStatus, errorThrown) 
                    {
                        errorMessage(jqXHR.responseText +" "+errorThrown);
                    },
                });
    }

    function del(id){
        if(confirm("Are you sure?")){
          $.ajax(
                {
                    url : "/ControlPanel/Exercises/"+id,
                    type: "DELETE",

                    success:function(data, textStatus, jqXHR) 
                    {
                        successMessage(data);

                        //arrayDataTables["dtExercises"].api().ajax.reload();
                        var table = arrayDataTables["dtExercises"];
                        //table.row(obj.closest('tr')).remove().draw(false);
                        obj.closest('tr').remove();

                    },
                    error: function(jqXHR, textStatus, errorThrown) 
                    {
                        errorMessage(jqXHR.responseText +" "+errorThrown);
                    },
                });
      }
    }

</script>


@endsection