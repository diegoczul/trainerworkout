 @extends('layouts.controlpanel')


@section('content')
                
                <div class-"col-lg-12">
                    <h1 class="page-header">Groups</h1>
                </div>
                <div class="row add" id="w_add" style="display:none">
                {{ Form::open(array("url"=>"ControlPanel/Groups/AddEdit/")) }}
                <input type="hidden" name="hiddenId" value = "" id="hiddenId" />
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel panel-heading">
                                <div class="form-group" style="margin-bottom:0px;">
                                    Insert / Edit User Group
                                </div>
                            </div>
                            <div class="panel panel-body">
                                <div class="form-group">
                                    <label>Name</label>
                                    {{  FORM::text("name","",array("id"=>"name","placeholder"=>"Name", "class"=>"form-control")) }}
                                </div>

                                <button class="btn btn-primary ajaxSave">Save</button>
                            </div>
                        </div>
                    </div>
                {{ Form::close() }}
                </div>
                <div class="row add" id="w_addUser" style="display:none">
                {{ Form::open(array("url"=>"ControlPanel/UserGroups/AddEdit/")) }}
                <input type="hidden" name="hiddenId" value = "" id="hiddenUserId" />
                <input type="hidden" name="hiddenGroupId" value = "" id="hiddenGroupId" class="noErase" />
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel panel-heading">
                                <div class="form-group" style="margin-bottom:0px;">
                                    Insert / Edit User
                                </div>
                            </div>
                            <div class="panel panel-body">
                                <div class="form-group">
                                    <label>Name</label>
                                    {{  FORM::select("userId",$users,"",array("id"=>"userId","placeholder"=>"User", "class"=>"form-control chosen-select")) }}
                                </div>
                                <div class="form-group">
                                    <label>Role</label>
                                    {{  FORM::select("role",array("Owner"=>"Owner","Admin"=>"Admin","Member"=>"Member"),"",array("id"=>"role","placeholder"=>"Role", "class"=>"form-control chosen-select")) }}
                                </div>

                                <button class="btn btn-primary ajaxSave">Save</button>
                            </div>
                        </div>
                    </div>
                {{ Form::close() }}
                </div>
                <div class="row" id="w_userGroups" style="display:none">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel panel-heading">
                                <div class="form-group" style="margin-bottom:0px;">
                                    <button onclick="toggleAndClear('w_addUser')" type="button" class="btn btn-info">New User</button>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover" id="dtTable2" >
                                        <thead>
                                            <tr>
                                                <th>First Name</th>
                                                <th>Last Name</th>
                                                <th>Email</th>
                                                <th>Role</th>
                                                <th>User Id</th>
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
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel panel-heading">
                                <div class="form-group" style="margin-bottom:0px;">
                                    <button onClick="toggleAndClear('w_add')" type="button" class="btn btn-info">New Group</button>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover" id="dtTable1" >
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Updated</th>
                                                <th>Manage Group</th>
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

var generalGroup = 0;

$(document).ready(function(){
    List();
    List2(generalGroup);

});

function List(){
        dtTable1 = $('#dtTable1').dataTable( {
                "processing": true,
                "serverSide": false,
                "iDisplayLength": 25,
                "ajax": {
                    "url": "/ControlPanel/Groups",
                    "type": "POST",
                },
                 "fnServerParams": function ( aoData ) {
                      aoData.push( 
                                    { "name": "type", "value":  "Data" }
                                );
                },
                "columns": [
                            { "data": "name" },
                            { "data": "updated_at" },
                            { "data": "id" },
                            { "data": "id" },
                            { "data": "id" }
                        ],
                "columnDefs": [  
                    { "render": function ( data, type, row ) { return echoCustomFunction(data,"manageGroup","Manage Group"); },"targets": -3 }, 
                    { "render": function ( data, type, row ) { return echoEdit(data); },"targets": -2 }, 
                    { "render": function ( data, type, row ) { return echoRemoveRow(data); },"targets": -1 }, 

                    { orderable: false, targets: -1 },
                    { orderable: false, targets: -2 }
                ],
                 "aaSorting": []
         });
        arrayDataTables["dtTable1"] = dtTable1;

    }

function List2(groupId){
        dtTable2 = $('#dtTable2').dataTable( {
                "processing": true,
                "serverSide": false,
                "iDisplayLength": 25,
                "ajax": {
                    "url": "/ControlPanel/UserGroups",
                    "type": "POST",
                },
                 "fnServerParams": function ( aoData ) {
                      aoData.push( 
                                    { "name": "groupId", "value":  generalGroup  }
                                );
                },
                "columns": [
                            { "data": "user.firstName" },
                            { "data": "user.lastName" },
                            { "data": "user.email" },
                            { "data": "role" },
                            { "data": "id" },
                            { "data": "updated_at" },
                            { "data": "id" },
                            { "data": "id" }
                        ],
                "columnDefs": [  
                    { "render": function ( data, type, row ) { return echoEdit(data,"editUser"); },"targets": -2 }, 
                    { "render": function ( data, type, row ) { return echoRemoveRow(data,"delUser"); },"targets": -1 }, 

                    { orderable: false, targets: -1 },
                    { orderable: false, targets: -2 }
                ],
                 "aaSorting": []
         });
        arrayDataTables["dtTable2"] = dtTable2;

    }

    function manageGroup(id,button){
        generalGroup = id;
        $.ajax(
                {
                    url : "/ControlPanel/UserGroups/"+id,
                    type: "GET",
                    success:function(data, textStatus, jqXHR) 
                    {
                        
                        $("#name").val(data.name);
                        $("#hiddenId").val(data.id);                         
                        $("#hiddenGroupId").val(id); 
                         arrayDataTables["dtTable2"].api().ajax.reload();                        
                        down('w_userGroups');
                    },
                    error: function(jqXHR, textStatus, errorThrown) 
                    {
                        errorMessage(jqXHR.responseText +" "+errorThrown);
                    },
                });
    }

     function edit(id){
          $.ajax(
                {
                    url : "/ControlPanel/Groups/"+id,
                    type: "GET",
                    success:function(data, textStatus, jqXHR) 
                    {
                        $("#name").val(data.name);
                        $("#hiddenId").val(data.id);                         
                        down('w_add');
                    },
                    error: function(jqXHR, textStatus, errorThrown) 
                    {
                        errorMessage(jqXHR.responseText +" "+errorThrown);
                    },
                });
    }

     function editUser(id){
          $.ajax(
                {
                    url : "/ControlPanel/UserGroups/"+id,
                    type: "GET",
                    success:function(data, textStatus, jqXHR) 
                    {
                        $("#name").val(data.name);
                        $("#hiddenUserId").val(data.id);   
                        $("#userId").val(data.userId);   
                        $("#role").val(data.role);   
                        $("#userId").trigger("chosen:updated");                      
                        $("#role").trigger("chosen:updated");                      
                        down('w_addUser');
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
                    url : "/ControlPanel/Groups/"+id,
                    type: "DELETE",

                    success:function(data, textStatus, jqXHR) 
                    {
                        successMessage(data);

                        //arrayDataTables["dtExercises"].api().ajax.reload();
                        var table = arrayDataTables["dtTable1"];
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


    function delUser(obj,id){
        if(confirm("Are you sure?")){
          $.ajax(
                {
                    url : "/ControlPanel/UserGroups/"+id,
                    type: "DELETE",

                    success:function(data, textStatus, jqXHR) 
                    {
                        successMessage(data);

                        //arrayDataTables["dtExercises"].api().ajax.reload();
                        var table = arrayDataTables["dtTable2"];
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
                    url : "/ControlPanel/Groups/"+id,
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