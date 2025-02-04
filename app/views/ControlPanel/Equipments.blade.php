 @extends('layouts.controlpanel')


@section('content')
                
                <div class-"col-lg-12">
                    <h1 class="page-header">Equipments Management</h1>
                </div>
                <div class="row add" id="w_equipments_add" style="display:none">
                {{ Form::open(array("url"=>"ControlPanel/Equipments/AddEdit/")) }}
                <input type="hidden" name="hiddenId" value = "" id="hiddenId" />
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel panel-heading">
                                <div class="form-group" style="margin-bottom:0px;">
                                    Insert / Edit Equipment
                                </div>
                            </div>
                            <div class="panel panel-body">
                                <div class="form-group">
                                    <label>Name</label>
                                    {{  FORM::text("name","NO NAME",array("id"=>"name","placeholder"=>"Name", "class"=>"form-control")) }}
                                </div>
                                <div class="form-group">
                                    <label>Name Engine</label>
                                    {{  FORM::text("nameEngine","",array("id"=>"nameEngine","placeholder"=>"Name Engine", "class"=>"form-control")) }}
                                </div>
                                
                                <div class="form-group">
                                    <label>Image 1</label>
                                    {{ Form::file('image1','',array('id'=>'image1','class'=>'')) }}
                                     <p class="help-block imageHolder" style="display:none"><img id="imageImage" src="/img/placeholder.png"  style="max-width:600px;"/></p>
                                </div>
                                <div class="form-group">
                                    <label>Image 2</label>
                                    {{ Form::file('image2','',array('id'=>'image2','class'=>'')) }}
                                     <p class="help-block imageHolder" style="display:none"><img id="image2Image" src="/img/placeholder.png" style="max-width:600px;"></p>
                                </div>
                                
 
                                <div class="form-group">
                                    <label>Remove Green Screen
                                     <input type="checkbox"  name="removeGreenScreen" id="removeGreenScreen" checked="checked" value="Yes" class="noErase" onclick="this.value = 'Yes'"></label>
                                </div>
                                <div class="form-group">
                                    <label>Modulation / Masking</label>
                                     <input type="text"  name="modulation" id="modulation" value="53.3" class="noErase form-control"><p>Default: 53.3</p>
                                </div>
                                <div class="form-group">
                                    <label>Feather</label>
                                     <input type="text"  name="feather" id="feather" value="5" class="noErase form-control"><p>Default: 5</p>
                                </div>
                                <div class="form-group">
                                    <label>Replacer color deviation</label>
                                     <input type="text"  name="replacer" id="replacer" value="8" class="noErase form-control"><p>Default: 8</p>
                                </div>
                                <div class="form-group">
                                    <label>Light</label>
                                     <input type="text"  name="light" id="light" value="130" class="noErase form-control"><p>Default: 130</p>
                                </div>
                                <div class="form-group">
                                    <label>Colors</label>
                                     <input type="text"  name="color1" id="color1" value="509F64,006C1B,149545,078732,5EC590,3D8C40,3AAF6B" class="noErase" style="width:100%">
                                     <p>Default: 509F64,006C1B,149545,078732,5EC590,3D8C40,3AAF6B</p>
                                     <p>Default with MATT: 509F64,149545,078732,5EC590,3D8C40,3AAF6B</p>
                                     
                                </div>
                                <div class="form-group">
                                    <label>Algorithm</label>
                                     <input type="radio" name="algo" value="1" class="noErase" id="algo1" onclick="this.value = 1" /> 1 <input type="radio" name="algo" value="2" checked="checked" class="noErase" id="algo2" onclick="this.value = 2" /> 2
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
                                    <button onclick="toggleAndClear('w_equipments_add')" type="button" class="btn btn-info">New Equipment</button>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover" id="dtEquipments" >
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th>Name</th>
                                                <th>Name Engine</th>
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


<script>

$(document).ready(function(){
    List();

});

function List(){
        dtEquipments = $('#dtEquipments').dataTable( {
                "processing": true,
                "serverSide": false,
                "iDisplayLength": 25,
                "ajax": {
                    "url": "/ControlPanel/Equipments",
                    "type": "POST",
                },
                 "fnServerParams": function ( aoData ) {
                      aoData.push( 
                                    { "name": "type", "value":  "Data" }
                                );
                },
                "columns": [
                            { "data": "thumb" },
                            { "data": "thumb2" },
                            { "data": "name" },
                            { "data": "nameEngine" },
                            { "data": "created_at" },
                            { "data": "id" },
                            { "data": "id" }
                        ],
                "columnDefs": [ 
                    { "render": function ( data, type, row ) { return imageRotate(data,row.id); },"targets": 0 }, 
                    { "render": function ( data, type, row ) { return imageRotate(data,row.id); },"targets": 1 }, 
                    { "render": function ( data, type, row ) { return echoEdit(data); },"targets": -2 }, 
                    { "render": function ( data, type, row ) { return echoRemoveRow(data); },"targets": -1 }, 

                    { orderable: false, targets: -1 },
                    { orderable: false, targets: -2 }
                ],
                 "aaSorting": []
         });
        arrayDataTables["dtEquipments"] = dtEquipments;

    }

     function edit(id){
          $.ajax(
                {
                    url : "/ControlPanel/Equipments/"+id,
                    type: "GET",
                    success:function(data, textStatus, jqXHR) 
                    {
                        $("#name").val(data.name);
                        $("#nameEngine").val(data.nameEngine);
                        $("#hiddenId").val(data.id);
                        $(".imageHolder").show();
                        if(data.image != null){
                            $("#imageImage").attr("src","/"+data.image);
                        }
                        if(data.image2 != null){
                            $("#image2Image").attr("src","/"+data.image2);
                        }
                        

                                            
                        down('w_equipments_add');
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
                    url : "/ControlPanel/Equipments/"+id,
                    type: "DELETE",

                    success:function(data, textStatus, jqXHR) 
                    {
                        successMessage(data);

                        //arrayDataTables["dtExercises"].api().ajax.reload();
                        var table = arrayDataTables["dtEquipments"];
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

     function delRow(obj,id){
        if(confirm("Are you sure?")){
          $.ajax(
                {
                    url : "/ControlPanel/Equipments/"+id,
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

    function rotateLeft(id){
    $.ajax(
            {
                url :"/Equipments/Rotate/Left",
                type: "POST",
                data: { id:id },
                success:function(data, textStatus, jqXHR) 
                {
                    successMessage(data);
                    refreshImages("#dtEquipments");
                    
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
                url :"/Equipments/Rotate/Right",
                type: "POST",
                data: { id:id },
                success:function(data, textStatus, jqXHR) 
                {
                    successMessage(data);
                    refreshImages("#dtEquipments");
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

</script>


@endsection