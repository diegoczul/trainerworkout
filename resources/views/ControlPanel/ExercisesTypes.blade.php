@extends('layouts.controlpanel')
@section('content')
    <div class="col-lg-12">
        <h1 class="page-header">Exercises Types Management</h1>
    </div>
    <div class="row add" id="w_widget_add" style="display:none">
        {{ Form::open(array("url"=>"ControlPanel/ExercisesTypes/AddEdit/")) }}
        <input type="hidden" name="hiddenId" value = "" id="hiddenId" />
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel panel-heading">
                    <div class="form-group" style="margin-bottom:0px;">
                        Insert / Edit Exercises Types
                    </div>
                </div>
                <div class="panel panel-body">
                    <div class="form-group">
                        <label>Name</label>
                        {{  FORM::text("name","NO NAME",array("id"=>"name","placeholder"=>"Name", "class"=>"form-control")) }}
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
                        <button onclick="toggleAndClear('w_widget_add')" type="button" class="btn btn-info">New Exercise Type</button>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="dtTable" >
                            <thead>
                            <tr>
                                <th>Name</th>
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
        var dtTable;
        $(document).ready(function(){
            arrayDataTables["dtTable"] = dtTable = $("#dtTable").DataTable({
                processing: true,
                serverSide: true,
                info: true,
                lengthChange: true,
                lengthMenu: [
                    [10, 25, 50, -1],
                    ['10 rows', '25 rows', '50 rows', 'All']
                ],
                buttons: [
                    'pageLength'
                ],
                ajax: {
                    url: "/ControlPanel/ExercisesTypes",
                    type: "POST",
                    dataType: 'json',
                    data: function (f) {
                        f.type = "Data";
                    },
                    error: function () {
                        dataTableError();
                    }
                },
                columns: [
                    { title: "Name", data: "name" },
                    { title: "Created At", data: "created_at", class: "text-center" },
                    { title: "Edit", data: "id", class: "text-center", orderable: false, render: function (data) { return echoEdit(data); } },
                    { title: "Delete", data: "id", class: "text-center", orderable: false, render: function (data) { return echoRemoveRow(data); } }
                ],
                order: []
            });
        });

        function edit(id){
            $.ajax({
                url : "/ControlPanel/ExercisesTypes/"+id,
                type: "GET",
                success:function(data, textStatus, jqXHR){
                    $("#name").val(data.name);
                    $("#hiddenId").val(data.id);
                    down('w_widget_add');
                },
                error: function(jqXHR, textStatus, errorThrown){
                    errorMessage(jqXHR.responseText +" "+errorThrown);
                },
            });
        }

        function del(obj,id){
            if(confirm("Are you sure?")){
                $.ajax({
                    url : "/ControlPanel/ExercisesTypes/"+id,
                    type: "DELETE",
                    success:function(data, textStatus, jqXHR){
                        successMessage(data);
                        dtTable.ajax.reload();
                    },
                    error: function(jqXHR, textStatus, errorThrown){
                        errorMessage(jqXHR.responseText +" "+errorThrown);
                    },
                });
            }
        }

        function delRow(obj,id){
            if(confirm("Are you sure?")){
                $.ajax({
                    url : "/ControlPanel/ExercisesTypes/"+id,
                    type: "DELETE",
                    success:function(data, textStatus, jqXHR){
                        successMessage(data);
                        dtTable.ajax.reload();
                    },
                    error: function(jqXHR, textStatus, errorThrown){
                        errorMessage(jqXHR.responseText +" "+errorThrown);
                    },
                });
            }
        }
    </script>
@endsection