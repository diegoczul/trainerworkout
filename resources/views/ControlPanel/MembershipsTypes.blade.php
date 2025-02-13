 @extends('layouts.controlpanel')


@section('content')
                
                <div class-"col-lg-12">
                    <h1 class="page-header">Memberships Management</h1>
                </div>
                <div class="row add" id="w_widget_add" style="display:none">
                {{ Form::open(array("url"=>"ControlPanel/MembershipsTypes/AddEdit/")) }}
                <input type="hidden" name="hiddenId" value = "" id="hiddenId" />
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel panel-heading">
                                <div class="form-group" style="margin-bottom:0px;">
                                    Insert / Edit Membership
                                </div>
                            </div>
                            <div class="panel panel-body">
                                <div class="form-group">
                                    <label>Name</label>
                                     {{  FORM::text("name","NO NAME",array("id"=>"name","placeholder"=>"Name", "class"=>"form-control")) }}
                                </div>
                                <div class="form-group">
                                    <label>Description</label>
                                   {{  FORM::text("description","NO NAME",array("id"=>"description","placeholder"=>"description", "class"=>"form-control")) }}
                                </div>
                                <div class="form-group">
                                    <label>Features</label>
                                   {{  FORM::text("features","NO NAME",array("id"=>"features","placeholder"=>"features", "class"=>"form-control")) }}
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
                                    <button onClick="toggleAndClear('w_widget_add')" type="button" class="btn btn-info">New Membership</button>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover" id="dtTable" >
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Description</th>
                                                <th>Features</th>
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
                    "url": "/ControlPanel/MembershipsTypes",
                    "type": "POST",
                },
                 "fnServerParams": function ( aoData ) {
                      aoData.push( 
                                    { "name": "type", "value":  "Data" }
                                );
                },
                "columns": [
                            { "data": "name" },
                            { "data": "description" },
                            { "data": "features" },
                            { "data": "created_at" },
                            { "data": "id" },
                            { "data": "id" }
                        ],
                "columnDefs": [ 
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
                    url : "/ControlPanel/MembershipsTypes/"+id,
                    type: "GET",
                    success:function(data, textStatus, jqXHR) 
                    {
                        $("#name").val(data.name);
                        $("#description").val(data.description);
                        $("#features").val(data.features);
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
                    url : "/ControlPanel/MembershipsTypes/"+id,
                    type: "DELETE",

                    success:function(data, textStatus, jqXHR) 
                    {
                        successMessage(data);
                        var table = arrayDataTables["dtTable"];
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
                    url : "/ControlPanel/MembershipsTypes/"+id,
                    type: "DELETE",

                    success:function(data, textStatus, jqXHR) 
                    {
                        successMessage(data);
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