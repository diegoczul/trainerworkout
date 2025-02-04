 @extends('layouts.controlpanel')


@section('content')
                
                <div class-"col-lg-12">
                    <h1 class="page-header">Ratings Management</h1>
                </div>
                <div class="row add" id="w_widget_add" style="display:none">
                {{ Form::open(array("url"=>"ControlPanel/Ratings/AddEdit/")) }}
                <input type="hidden" name="hiddenId" value = "" id="hiddenId" />
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel panel-heading">
                                <div class="form-group" style="margin-bottom:0px;">
                                    Insert / Edit Rating
                                </div>
                            </div>
                            <div class="panel panel-body">
                                <div class="form-group">
                                    <label>Name</label>
                                    {{  FORM::text("name","NO NAME",array("id"=>"name","placeholder"=>"Name", "class"=>"form-control")) }}
                                </div>
                                <div class="form-group">
                                    <label>Trainer</label>
                                    {{  FORM::select("trainer",[null=>'None'] +$trainers,"",array("id"=>"trainer","placeholder"=>"Trainer", "class"=>"form-control chosen-select")) }}
                                </div>
                                
                            


                                <div class="form-group">
                                    <label>Value</label>
                                    {{  FORM::select("value",array_combine(range(0,10),range(0,10)),"1",array("id"=>"value","placeholder"=>"value", "class"=>"form-control chosen-select")) }}
                                  
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
                                    <button onClick="toggleAndClear('w_widget_add')" type="button" class="btn btn-info">New Rating</button>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover" id="dtTable" >
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Trainer</th>
                                                <th>Value</th>
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
        dtTable = $('#dtTable').dataTable( {
                "processing": true,
                "serverSide": false,
                "iDisplayLength": 25,
                "ajax": {
                    "url": "/ControlPanel/Ratings",
                    "type": "POST",
                },
                 "fnServerParams": function ( aoData ) {
                      aoData.push( 
                                    { "name": "type", "value":  "Data" }
                                );
                },
                "columns": [
                            { "data": "name" },
                            { "data": "trainer" },
                            { "data": "value" },
                            { "data": "created_at" },
                            { "data": "id" },
                            { "data": "id" }
                        ],
                "columnDefs": [ 
                    { "render": function ( data, type, row ) { if(data !== undefined && data !== null) { return data.firstName+' '+data.lastName; } else { return ""; } },"targets": 1 }, 
                    { "render": function ( data, type, row ) { return echoEdit(data); },"targets": -2 }, 
                    { "render": function ( data, type, row ) { return echoRemoveRow(data); },"targets": -1 }, 

                    { orderable: false, targets: -1 },
                    { orderable: false, targets: -2 }
                ],
                 "aaSorting": []
         });
        arrayDataTables["dtTable"] = dtTable;

    }

     function edit(id){
          $.ajax(
                {
                    url : "/ControlPanel/Ratings/"+id,
                    type: "GET",
                    success:function(data, textStatus, jqXHR) 
                    {
                        $("#name").val(data.name);
                        $("#trainer").val(data.ownerId);
                        $("#trainer").trigger("chosen:updated");
                        $("#value").val(data.value);
                        $("#value").trigger("chosen:updated");
                        $("#hiddenId").val(data.id);
            
                                            
                        down('w_widget_add');
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
                    url : "/ControlPanel/Ratings/"+id,
                    type: "DELETE",

                    success:function(data, textStatus, jqXHR) 
                    {
                        successMessage(data);

                        //arrayDataTables["dtExercises"].api().ajax.reload();
                        var table = arrayDataTables["dtTable"];
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
                    url : "/ControlPanel/Ratings/"+id,
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