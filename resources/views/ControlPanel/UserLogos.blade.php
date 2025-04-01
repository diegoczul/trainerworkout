@extends('layouts.controlpanel')
@section('content')
    <div class="col-lg-12">
    <h1 class="page-header">User Logos</h1>
    </div>
    <div class="row add" id="w_logos" style="display:none">
        {{ Form::open(array("url"=>"ControlPanel/UserLogos/AddEdit/")) }}
        <input type="hidden" name="hiddenId" value="" id="hiddenId"/>
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
                        {{ Form::file('image1',array('id'=>'image1','class'=>'')) }}
                        <p class="help-block imageHolder" style="display:none">
                            <img id="imageImage" src="{{asset('assets/img/placeholder.png')}}" style="max-width:600px;"/>
                        </p>
                    </div>
                    <div class="form-group">
                        <label>Active
                            <input type="checkbox" name="active" id="active" checked="checked" value="Yes" class="noErase" onclick="this.value = 'Yes'">
                        </label>
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
                        <button onClick="toggleAndClear('w_logos')" type="button" class="btn btn-info">New User Logo </button>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="dtLogos">
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
        var dtLogos;
        $(document).ready(function () {
            arrayDataTables["dtLogos"] = dtLogos = $("#dtLogos").DataTable({
                processing: true,
                serverSide: true,
                lengthChange: true,
                info: true,
                lengthMenu: [
                    [10, 25, 50, -1],
                    ['10 rows', '25 rows', '50 rows', 'All']
                ],
                buttons: [
                    'pageLength'
                ],
                ajax: {
                    url: "/ControlPanel/UserLogos",
                    type: "POST",
                    dataType: "json",
                    data: function (d) {
                        d.type = "Data";
                    }
                },
                columns: [
                    { title: "Logo", searchable: false, data: "thumb", orderable: false, render: function (data, type, row) { return imageRotate(data, row.id); } },
                    { title: "User", searchable: false, data: "user", render: function (data) { return (data && data.firstName && data.lastName) ? `${data.firstName} ${data.lastName}` : "N/A"; } },
                    { title: "Status", data: "active", render: function (data) { return data == 1 ? "Default" : ""; } },
                    { title: "Updated At", data: "updated_at" },
                    { title: "Edit", data: "id", orderable: false, render: function (data) { return echoEdit(data); } },
                    { title: "Delete", data: "id", orderable: false, render: function (data) { return echoRemoveRow(data); } }
                ],
                order: []
            });
        });

        function edit(id) {
            $.ajax({
                url: "/ControlPanel/UserLogos/" + id,
                type: "GET",
                success: function (data, textStatus, jqXHR) {
                    $("#userId").val(data.userId);
                    $("#userId").trigger("chosen:updated");
                    $("#hiddenId").val(data.id);
                    $(".imageHolder").show();
                    if (data.image != null) {
                        $("#image1").attr("src", "/" + data.image);
                    }
                    if (data.active == 1) {
                        $("#active").attr("checked", true);
                    } else {
                        $("#active").attr("checked", false);
                    }
                    down('w_logos');
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    errorMessage(jqXHR.responseText + " " + errorThrown);
                },
            });
        }

        function del(obj, id) {
            if (confirm("Are you sure?")) {
                $.ajax({
                    url: "/ControlPanel/UserLogos/" + id,
                    type: "DELETE",
                    success: function (data, textStatus, jqXHR) {
                        successMessage(data);
                        dtLogos.ajax.reload();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        errorMessage(jqXHR.responseText + " " + errorThrown);
                    },
                });
            }
        }

        function rotateLeft(id) {
            $.ajax({
                url: "/UserLogos/Rotate/Left",
                type: "POST",
                data: {id: id},
                success: function (data, textStatus, jqXHR) {
                    successMessage(data);
                    refreshImages("#dtLogos");
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    errorMessage(jqXHR.responseText);
                },
                statusCode: {
                    500: function () {
                        if (jqXHR.responseText != "") {
                            errorMessage(jqXHR.responseText);
                        }
                    }
                }
            });
        }

        function rotateRight(id) {
            $.ajax({
                url: "/UserLogos/Rotate/Right",
                type: "POST",
                data: {id: id},
                success: function (data, textStatus, jqXHR) {
                    successMessage(data);
                    refreshImages("#dtLogos");
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    errorMessage(jqXHR.responseText);
                },
                statusCode: {
                    500: function () {
                        if (jqXHR.responseText != "") {
                            errorMessage(jqXHR.responseText);
                        }
                    }
                }
            });
        }

        function delRow(obj, id) {
            if (confirm("Are you sure?")) {
                $.ajax({
                    url: "/ControlPanel/UserLogos/" + id,
                    type: "DELETE",
                    success: function (data, textStatus, jqXHR) {
                        successMessage(data);
                        dtLogos.ajax.reload();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        errorMessage(jqXHR.responseText + " " + errorThrown);
                    },
                });
            }
        }
    </script>
@endsection