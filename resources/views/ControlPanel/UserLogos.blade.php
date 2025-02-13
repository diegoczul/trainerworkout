 @extends('layouts.controlpanel')


@section('content')
                
                <div class-"col-lg-12">
                    <h1 class="page-header">User Logos</h1>
                </div>
                <div class="row add" id="w_logos" style="display:none">
                {{ Form::open(array("url"=>"ControlPanel/UserLogos/AddEdit/")) }}
                <input type="hidden" name="hiddenId" value = "" id="hiddenId" />
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel panel-heading">
                                <div class="form-group" style="margin-bottom:0px;">
                                    Insert / Edit User Logo
                                </div>
                            </div>
                            <div class="panel panel-body">
                                <div class="form-group">
                                    <label>User</label>
                                    {{  FORM::select("userId",$users,"",array("id"=>"userId","placeholder"=>"User", "class"=>"form-control chosen-select")) }}
                                </div>
                                <div class="form-group">
                                    <label>Logo</label>
                                    {{ Form::file('image1','',array('id'=>'image1','class'=>'')) }}
                                     <p class="help-block imageHolder" style="display:none"><img id="imageImage" src="/img/placeholder.png"  style="max-width:600px;"/></p>
                                </div>
                                <div class="form-group">
                                    <label>Active
                                     <input type="checkbox"  name="active" id="active" checked="checked" value="Yes" class="noErase" onclick="this.value = 'Yes'"></label>
                                </div>

                                <button class="btn btn-primary ajaxSave">Save</button>
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
                                    <button onClick="toggleAndClear('w_logos')" type="button" class="btn btn-info">New User Logo</button>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover" id="dtLogos" >
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Name</th>
                                                <th>Active</th>
                                                <th>Updated</th>
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


<script>

$(document).ready(function(){
    List();

});

function List(){
        dtLogos = $('#dtLogos').dataTable( {
                "processing": true,
                "serverSide": false,
                "iDisplayLength": 25,
                "ajax": {
                    "url": "/ControlPanel/UserLogos",
                    "type": "POST",
                },
                 "fnServerParams": function ( aoData ) {
                      aoData.push( 
                                    { "name": "type", "value":  "Data" }
                                );
                },
                "columns": [
                            { "data": "thumb" },
                            { "data": "user" },
                            { "data": "active" },
                            { "data": "updated_at" },
                            { "data": "id" },
                            { "data": "id" }
                        ],
                "columnDefs": [ 
                    { "render": function ( data, type, row ) { return imageRotate(data,row.id); },"targets": 0 }, 
                    { "render": function ( data, type, row ) { return data.firstName+' '+data.lastName },"targets": 1 }, 
                    { "render": function ( data, type, row ) { if(data == 1){ return "Default"; } else return "" },"targets": 2 }, 
                    { "render": function ( data, type, row ) { return echoEdit(data); },"targets": -2 }, 
                    { "render": function ( data, type, row ) { return echoRemoveRow(data); },"targets": -1 }, 

                    { orderable: false, targets: -1 },
                    { orderable: false, targets: -2 }
                ],
                 "aaSorting": []
         });
        arrayDataTables["dtLogos"] = dtLogos;

    }

     function edit(id){
          $.ajax(
                {
                    url : "/ControlPanel/UserLogos/"+id,
                    type: "GET",
                    success:function(data, textStatus, jqXHR) 
                    {
                        $("#userId").val(data.userId);
                        $("#userId").trigger("chosen:updated");
                        $("#hiddenId").val(data.id);
                        $(".imageHolder").show();
                        if(data.image != null){
                            $("#image1").attr("src","/"+data.image);
                        }             
                        if(data.active == 1){
                            $("#active").attr("checked",true);
                        } else {
                             $("#active").attr("checked",false);
                        }                            
                        down('w_logos');
                    },
                    error: function(jqXHR, textStatus, errorThrown) 
                    {
                        errorMessage(jqXHR.responseText +" "+errorThrown);
                    },
                });
    }

    function del(obj,id){
        if(confirm("Are you sure?")){
          $.ajax(
                {
                    url : "/ControlPanel/UserLogos/"+id,
                    type: "DELETE",

                    success:function(data, textStatus, jqXHR) 
                    {
                        successMessage(data);

                        //arrayDataTables["dtExercises"].api().ajax.reload();
                        var table = arrayDataTables["dtLogos"];
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


    function rotateLeft(id){
    $.ajax(
            {
                url :"/UserLogos/Rotate/Left",
                type: "POST",
                data: { id:id },
                success:function(data, textStatus, jqXHR) 
                {
                    successMessage(data);
                    refreshImages("#dtLogos");
                    
                },
                error: function(jqXHR, textStatus, errorThrown) 
                {
                    errorMessage(jqXHR.responseText);
                },
                statusCode: {
                    500: function() {
                        if(jqXHR.responseText != ""){
                            errorMessage(jqXHR.responseText);
                        }else {
                            
                        }
                        
                    }
                }
            });
}

function rotateRight(id){
    $.ajax(
            {
                url :"/UserLogos/Rotate/Right",
                type: "POST",
                data: { id:id },
                success:function(data, textStatus, jqXHR) 
                {
                    successMessage(data);
                    refreshImages("#dtLogos");
                },
                error: function(jqXHR, textStatus, errorThrown) 
                {
                    errorMessage(jqXHR.responseText);
                },
                statusCode: {
                    500: function() {
                        if(jqXHR.responseText != ""){
                            errorMessage(jqXHR.responseText);
                        }else {
                            
                        }
                        
                    }
                }
            });

}

function delRow(obj,id){
        if(confirm("Are you sure?")){
          $.ajax(
                {
                    url : "/ControlPanel/UserLogos/"+id,
                    type: "DELETE",

                    success:function(data, textStatus, jqXHR) 
                    {
                        successMessage(data);

                        //arrayDataTables["dtExercises"].api().ajax.reload();
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