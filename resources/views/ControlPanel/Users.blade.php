 @extends('layouts.controlpanel')

@section('content')
                
                <div class="col-lg-12">
                    <h1 class="page-header">Users Management</h1>
                </div>
                <div class="row add" id="w_users_add" style="display:none">
                {{ Form::open(array("url"=>"ControlPanel/Users/AddEdit/")) }}
                <input type="hidden" name="hiddenId" value = "" id="hiddenId" />
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel panel-heading">
                                <div class="form-group" style="margin-bottom:0px;">
                                    Insert / Edit Users
                                </div>
                            </div>
                            <div class="panel panel-body">
                                <div class="form-group">
                                    <label>First Name</label>
                                    {{  FORM::text("firstName","",array("id"=>"firstName","placeholder"=>"First Name", "class"=>"form-control")) }}
                                </div>
                                <div class="form-group">
                                    <label>Last Name</label>
                                    {{  FORM::text("lastName","",array("id"=>"lastName","placeholder"=>"Last Name", "class"=>"form-control")) }}
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    {{  FORM::text("email","",array("id"=>"email","placeholder"=>"Email", "class"=>"form-control")) }}
                                </div>
                                <div class="form-group">
                                    <label>Address</label>
                                    {{  FORM::text("address","",array("id"=>"address","placeholder"=>"Address", "class"=>"form-control")) }}
                                </div>
                                <div class="form-group">
                                    <label>Phone</label>
                                    {{  FORM::text("phone","",array("id"=>"phone","placeholder"=>"Phone", "class"=>"form-control")) }}
                                </div>
                                <div class="form-group">
                                    <label>Street</label>
                                    {{  FORM::text("street","",array("id"=>"street","placeholder"=>"Street", "class"=>"form-control")) }}
                                </div>
                                <div class="form-group">
                                    <label>Suite</label>
                                    {{  FORM::text("suite","",array("id"=>"suite","placeholder"=>"Suite", "class"=>"form-control")) }}
                                </div>
                                <div class="form-group">
                                    <label>City</label>
                                    {{  FORM::text("city","",array("id"=>"city","placeholder"=>"City", "class"=>"form-control")) }}
                                </div>
                                <div class="form-group">
                                    <label>Province</label>
                                    {{  FORM::text("province","",array("id"=>"province","placeholder"=>"Province", "class"=>"form-control")) }}
                                </div>
                                <div class="form-group">
                                    <label>Country</label>
                                    {{  FORM::text("country","",array("id"=>"country","placeholder"=>"Country", "class"=>"form-control")) }}
                                </div>
                                <div class="form-group">
                                    <label>User Type</label>
                                    {{  FORM::select("userType",array("Trainee"=>"Trainee","Trainer"=>"Trainer","Wait_Trainee"=>"Wait_Trainee","Wait_Trainer"=>"Wait_Trainer",),"Trainer",array("id"=>"userType","class"=>"chosen-select", "class"=>"form-control")) }}
                                </div>
                                <div class="form-group">
                                    <label>Password</label>
                                    {{  FORM::text("password","",array("id"=>"password","placeholder"=>"Password", "class"=>"form-control")) }}
                                </div>
                                <div class="form-group">
                                    <label>fbUsername</label>
                                    {{  FORM::text("fbUsername","",array("id"=>"province","placeholder"=>"fbUsername", "class"=>"form-control")) }}
                                </div>
                                <div class="form-group">
                                <label class="checkbox-inline">
                                    <input type="checkbox"  name="appInstalled" id="appInstalled" value="Yes">App Installed
                                </label>
                                <label class="checkbox-inline">
                                    <input type="checkbox"  name="demoApp" id="demoApp" value="Yes">Demo App
                                </label>
                                <label class="checkbox-inline">
                                    <input type="checkbox"  name="demoWeb" id="demoWeb" value="Yes">Onboarding Web
                                </label>
                                </div>
                                <div class="form-group">
                                    <label>Timezone</label>
                                    {{  FORM::text("timezone","",array("id"=>"timezone","placeholder"=>"Timezone", "class"=>"form-control")) }}
                                </div>
                                <div class="form-group">
                                    <label>Birthday</label>
                                    {{  FORM::text("birthday","",array("id"=>"birthday","placeholder"=>"Birthday","class"=>"form-control datepicker")) }}
                                </div>
                                <div class="form-group">
                                    <label>fbUsername</label>
                                    {{  FORM::text("fbUsername","",array("id"=>"province","placeholder"=>"fbUsername")) }}
                                </div>
                                <div class="form-group">
                                    <label>Biography</label>
                                    <textarea class="form-control ckeditor" rows="3" id="biography" name="biography"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Certifications</label>
                                    <textarea class="form-control ckeditor" rows="3" id="certifications" name="certifications"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Specialities</label>
                                    <textarea class="form-control ckeditor" rows="3" id="specialities" name="specialities"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Past Experience</label>
                                    <textarea class="form-control ckeditor" rows="3" id="past_experience" name="past_experience"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Word</label>
                                    <textarea class="form-control ckeditor" rows="3" id="word" name="word"></textarea>
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
                                    <button onClick="toggleAndClear('w_users_add')" type="button" class="btn btn-info">New User</button>
                                </div>
                            </div>
                            <div class="panel-body">
                                <h2>Users</h2>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover" id="dtUsers" >
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>id</th>
                                                <th>First Name</th>
                                                <th>Last Name</th>
                                                <th>Phone</th>
                                                <th>Email</th>
                                                <th>User Type</th>
                                                <th>Created</th>
                                                <th>Personify</th>
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
    let dtUsers = $("#dtUsers").DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        info: true,
        lengthChange: true,
        lengthMenu: [
            [10, 25, 50],
            ['10 rows', '25 rows', '50 rows']
        ],
        buttons: [
            'pageLength'
        ],
        ajax: {
            url: "/ControlPanel/Users",
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
            { title: "Profile Image", data: "thumb", class: "text-center", render: function (data) { return image(data, 100); } },
            { title: "ID", data: "id", class: "text-center" },
            { title: "First Name", data: "firstName" },
            { title: "Last Name", data: "lastName" },
            { title: "Phone", data: "phone", class: "text-center" },
            { title: "Email", data: "email" },
            { title: "User Type", data: "userType" },
            { title: "Created At", data: "created_at", class: "text-center" },
            { title: "Login", data: "id", class: "text-center", orderable: false, render: function (data) { return echoLoginUser(data); } },
            { title: "Edit", data: "id", class: "text-center", orderable: false, render: function (data) { return echoEdit(data); } },
            { title: "Delete", data: "id", class: "text-center", orderable: false, render: function (data) { return echoRemove(data); } }
        ],
        order: []
    });

    arrayDataTables["dtUsers"] = dtUsers;

    }

     function edit(id){
          $.ajax(
                {
                    url : "/ControlPanel/Users/"+id,
                    type: "GET",
                    success:function(data, textStatus, jqXHR) 
                    {
                        $("#firstName").val(data.firstName);
                        $("#lastName").val(data.lastName);
                        $("#email").val(data.email);
                        $("#address").val(data.address);
                        $("#phone").val(data.phone);
                        $("#street").val(data.street);
                        $("#suite").val(data.suite);
                        $("#city").val(data.city);
                        $("#province").val(data.province);
                        $("#country").val(data.country);
                        $("#userType").val(data.userType);
                        $("#userType").trigger("chosen:updated");
                        $("#password").val(data.password);
                        $("#fbUserName").val(data.fbUserName);
                        if(data.appInstalled == "1"){ $('#appInstalled').prop('checked',true); } else { $('#appInstalled').prop('checked',false);  }
                        if(data.demoApp == "1"){ $('#demoApp').prop('checked',true); } else { $('#demoApp').prop('checked',false);  }
                        $("#timezone").val(data.timezone);
                        $("#birthday").val(data.birthday);
                        $("#certifications").val(data.certifications);
                        $("#specialitites").val(data.specialitites);
                        $("#past_experience").val(data.past_experience);
                        $("#word").val(data.word);
                        $("#videoLink").val(data.videoLink);
                        if(data.demoApp == "1"){ $('#demoApp').prop('checked',true); } else { $('#demoApp').prop('checked',false);  }
                        $("#hiddenId").val(data.id);

                        down('w_users_add');
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
                    url : "/ControlPanel/Users/"+id,
                    type: "DELETE",

                    success:function(data, textStatus, jqXHR) 
                    {
                        successMessage(data);
                        arrayDataTables["dtUsers"].api().ajax.reload();
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