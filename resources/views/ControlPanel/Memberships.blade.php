@extends('layouts.controlpanel')
@section('content')
    <div class="col-lg-12">
    <h1 class="page-header">Memberships Management</h1>
    </div>
    <div class="row add" id="w_widget_add" style="display:none">
        {{ Form::open(array("url"=>"ControlPanel/Memberships/AddEdit/")) }}
        <input type="hidden" name="hiddenId" value = "" id="hiddenId" />
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel panel-heading">
                    <div class="form-group" style="margin-bottom: 0px;">
                        Insert / Edit Membership
                    </div>
                </div>
                <div class="panel panel-body">
                    <div class="form-group">
                        <label>User</label>
                        {{  FORM::select("userId",$users,"",array("id"=>"userId","placeholder"=>"User", "class"=>"form-control chosen-select")) }}
                    </div>
                    <div class="form-group">
                        <label>Membership</label>
                        {{  FORM::select("membershipId",$memberships,"",array("id"=>"membershipId","placeholder"=>"Membership", "class"=>"form-control chosen-select")) }}
                    </div>
                    <div class="form-group">
                        <label>Expiry</label>
                        {{  FORM::text("expiry","",array("id"=>"expiry","placeholder"=>"Expiry", "class"=>"form-control datepicker date")) }}
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
                        <button onClick="toggleAndClear('w_widget_add')" type="button" class="btn btn-info">New User Membership</button>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="dtTable" >
                            <thead>
                                <tr>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Email</th>
                                    <th>Membership</th>
                                    <th>Expiry</th>
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
                buttons: ['pageLength'],
                ajax: {
                    url: "/ControlPanel/Memberships",
                    type: "POST",
                    dataType: "json",
                    data: function (f) {
                        f.type = "Data";
                    }
                },
                columns: [
                    { title: "First Name", data: "users", render: function (data) { return data ? data.firstName : ""; } },
                    { title: "Last Name", data: "users", render: function (data) { return data ? data.lastName : ""; } },
                    { title: "Email", data: "users", render: function (data) { return data ? data.email : ""; } },
                    { title: "Membership", data: "membership", render: function (data) { return data ? data.name : ""; } },
                    { title: "Expiry Date", data: "expiry", render: function (data) { return echoColorIfDatePast(data); } },
                    { title: "Created At", data: "created_at" },
                    { title: "Edit", data: "id", orderable: false, render: function (data) { return echoEdit(data); } },
                    { title: "Delete", data: "id", orderable: false, render: function (data) { return echoRemoveRow(data); } }
                ],
                order: []
            });
        });

        function edit(id){
            $.ajax({
                url : "/ControlPanel/Memberships/"+id,
                type: "GET",
                success:function(data, textStatus, jqXHR) {
                    $("#userId").val(data.userId);
                    $("#membershipId").val(data.membershipId);
                    $("#membershipId").trigger("chosen:updated");
                    $("#userId").trigger("chosen:updated");
                    $("#expiry").val(data.expiry);
                    $("#hiddenId").val(data.id);
                    down('w_widget_add');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    errorMessage(jqXHR.responseText +" "+errorThrown);
                },
            });
        }

        function del(obj,id){
            if(confirm("Are you sure?")){
                $.ajax({
                    url : "/ControlPanel/Memberships/"+id,
                    type: "DELETE",
                    success:function(data, textStatus, jqXHR) {
                        successMessage(data);
                        dtTable.ajax.reload();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        errorMessage(jqXHR.responseText +" "+errorThrown);
                    },
                });
            }
        }

        function delRow(obj,id){
            if(confirm("Are you sure?")){
                $.ajax({
                    url : "/ControlPanel/Memberships/"+id,
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