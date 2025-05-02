@extends('layouts.controlpanel')
@section('content')
    <div class="col-lg-12">
        <h1 class="page-header">Exercises Management</h1>
    </div>
    <div class="row add" id="w_exercises_add" style="display:none">
        {{ Form::open(['url' => 'ControlPanel/Exercises/AddEdit/']) }}
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
                        {{ FORM::text('name', 'NO NAME', ['id' => 'name', 'placeholder' => 'Name', 'class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        <label>Name Engine</label>
                        {{ FORM::text('nameEngine', '', ['id' => 'nameEngine', 'placeholder' => 'Name Engine', 'class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        <label>User</label>
                        {{ FORM::select('userId', $users, '', ['id' => 'userId', 'placeholder' => 'User', 'class' => 'form-control chosen-select']) }}
                    </div>
                    <div class="form-group">
                        <label>Author</label>
                        {{ FORM::select('authorId', $users, '', ['id' => 'authorId', 'placeholder' => 'Author', 'class' => 'form-control chosen-select']) }}
                    </div>
                    <div class="form-group">
                        <label>BodyGroup</label>
                        {{ FORM::select('bodygroupId', ['' => ''] + $bodygroups->toArray(), '', ['id' => 'bodygroupId', 'placeholder' => 'BodyGroup', 'class' => ' chosen-select', 'style' => 'width:200px']) }}
                    </div>
                    <div class="form-group">
                        <label>Extra Bodygroups</label>
                        {{ FORM::select('bodygroupsOptional[]', ['' => ''] + $bodygroups->toArray(), '', ['id' => 'bodygroupsOptional', 'placeholder' => 'Bodygroups', 'class' => 'chosen-select form-control', 'multiple' => '', 'size' => 8]) }}
                    </div>
                    <div class="form-group">
                        <label>Exercise Type</label>
                        {{ FORM::select('exercisesTypesId[]', ['' => ''] + $exercisesTypes->toArray(), '', ['id' => 'exercisesTypesId', 'placeholder' => 'Exercises Types', 'class' => 'chosen-select form-control', 'multiple' => '', 'size' => 8]) }}
                    </div>
                    <div class="form-group">
                        <label>Video</label>
                        {{ FORM::text('video', '', ['id' => 'video', 'placeholder' => 'Video', 'class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        <label>Type</label>
                        {{ FORM::select('type', ['public' => 'public', 'private' => 'private', 'pending' => 'pending'], 'public', ['id' => 'type', 'placeholder' => 'Type', 'class' => ' chosen-select', 'style' => 'width:200px']) }}
                    </div>
                    <div class="form-group">
                        <label>Do not show exercise without Equipment!
                            <input type="checkbox" name="equipmentRequired" id="equipmentRequired" value="Yes"
                                class="noErase" onclick="this.value = 'Yes'"></label>
                    </div>
                    <div class="form-group">
                        <label>Equipment Required</label>
                        {{ FORM::select('equipment[]', $equipments->toArray(), '', ['id' => 'equipment', 'placeholder' => 'Equipment', 'class' => 'chosen-select form-control', 'multiple' => '', 'size' => 8]) }}
                    </div>
                    <div class="form-group">
                        <label>Equipment Optional</label>
                        {{ FORM::select('equipmentOptional[]', ['' => ''] + $equipments->toArray(), '', ['id' => 'equipmentOptional', 'placeholder' => 'Equipment', 'class' => 'chosen-select form-control', 'multiple' => '', 'size' => 8]) }}
                    </div>

                    <div class="form-group">
                        <label>Equipment Hidden</label>
                        {{ FORM::select('equipmentHidden[]', ['' => ''] + $equipments->toArray(), '', ['id' => 'equipmentHidden', 'placeholder' => 'Equipment', 'class' => 'chosen-select form-control', 'multiple' => '', 'size' => 8]) }}
                    </div>
                    <div class="form-group">
                        <label>Views</label>
                        {{ FORM::text('views', '', ['id' => 'views', 'placeholder' => 'Views', 'class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        <label>Used</label>
                        {{ FORM::text('used', '', ['id' => 'used', 'placeholder' => 'Used', 'class' => 'form-control']) }}
                    </div>

                    <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-control" rows="3" id="description" name="description"></textarea>
                    </div>
                    <div class="form-group">
                        <label>You Tube</label>
                        {{ FORM::text('youtube', '', ['id' => 'youtube', 'placeholder' => 'Youtube', 'class' => 'form-control']) }}
                        <iframe id="youtubeVideo" type="text/html" width="200" height="100" src=""> </iframe>
                    </div>
                    <div class="form-group">
                        <label>Image 1</label>
                        {{ Form::file('image1', ['id' => 'image1', 'class' => '']) }}
                        <p class="help-block imageHolder" style="display:none"><img id="imageImage"
                                src="{{ asset('assets/img/placeholder.png') }}" style="max-width:600px;" /></p>
                        <p><a href="javascript:void(0)" onclick="removeImage('imageImage')"> remove image </a></p>
                    </div>
                    <div class="form-group">
                        <label>Image 2</label>
                        {{ Form::file('image2', ['id' => 'image2', 'class' => '']) }}
                        <p class="help-block imageHolder" style="display:none"><img id="image2Image"
                                src="{{ asset('assets/img/placeholder.png') }}" style="max-width:600px;"></p>
                        <p><a href="javascript:void(0)" onclick="removeImage('image2Image')"> remove image </a></p>
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
                        <button onClick="toggleAndClear('w_exercises_add')" type="button" class="btn btn-info">New
                            Exercise</button>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="dtExercises">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>BodyGroup</th>
                                    <th>BG Optional</th>
                                    <th>Type</th>
                                    <th>Name Engine</th>
                                    <th>Translated Name</th>
                                    <th>Translated Name Engine</th>
                                    <th>Equipment</th>
                                    <th>Optional Equipment</th>
                                    <th>Used</th>
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
@section('scripts')
    <script type="text/javascript" src="/fw/flowplayer/flowplayer-3.2.2.min.js"></script>

    <script>
        var dtExercises;
        $(document).ready(function() {

            // Replace the <textarea id="editor1"> with a CKEditor
            // instance, using default configuration.
            CKEDITOR.config.toolbar = [
                ['Styles', 'Font', 'FontSize'],
                ['Bold', 'Italic', 'Underline', 'StrikeThrough', '-', 'Undo', 'Redo', '-', 'Cut', 'Copy',
                    'Paste', 'Find', 'Replace', '-', 'Outdent', 'Indent', '-', 'Print'
                ],
                ['NumberedList', 'BulletedList', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight',
                    'JustifyBlock'
                ],
                ['TextColor', 'BGColor', 'Source']
            ];
            CKEDITOR.config.scayt_autoStartup = true;

            arrayDataTables["dtExercises"] = dtExercises = $("#dtExercises").DataTable({
                processing: true,
                serverSide: true,
                info: true,
                lengthMenu: [
                    [10, 25, 50, -1],
                    ['10 rows', '25 rows', '50 rows', 'All']
                ],
                language: {
                    search: '',
                    searchPlaceholder: "Search Here",
                    processing: "Loading",
                    paginate: {
                        next: '<i class="fa fa-angle-right">',
                        previous: '<i class="fa fa-angle-left">'
                    }
                },
                responsive: {
                    breakpoints: [{
                            name: 'desktop',
                            width: Infinity
                        },
                        {
                            name: 'tablet',
                            width: 1024
                        },
                        {
                            name: 'fablet',
                            width: 768
                        },
                        {
                            name: 'phone',
                            width: 480
                        }
                    ]
                },
                buttons: [
                    'pageLength', 'excel', 'pdf', 'print', 'colvis'
                ],
                ajax: {
                    url: "/ControlPanel/Exercises",
                    type: "POST",
                    dataType: 'json',
                    data: function(f) {
                        f.type = "Data";
                    },
                },
                columns: [{
                        title: "Thumbnail",
                        searchable: false,
                        data: "thumb",
                        class: "text-center",
                        render: function(data, type, row) {
                            return imageRotate1Switch(data, row.id);
                        }
                    },
                    {
                        title: "Thumbnail 2",
                        searchable: false,
                        data: "thumb2",
                        class: "text-center",
                        render: function(data, type, row) {
                            return imageRotate2Switch(data, row.id);
                        }
                    },
                    {
                        title: "ID",
                        data: "id",
                        class: "text-center"
                    },
                    {
                        title: "Name",
                        data: "name"
                    },
                    {
                        title: "Body Group",
                        data: "bodygroup_name"
                    },
                    {
                        title: "Optional Body Groups",
                        searchable: false,
                        data: "bodygroups_optional",
                        render: function(data) {
                            return data ? showNamesB(data) : "";
                        }
                    },
                    {
                        title: "Exercise Types",
                        searchable: false,
                        data: "exercises_types",
                        render: function(data) {
                            return data ? showNamesT(data) : "";
                        }
                    },
                    {
                        title: "Engine Name",
                        data: "nameEngine"
                    },
                    {
                        title: "Translated Name",
                        data: "translated_name"
                    },
                    {
                        title: "Translated Engine Name",
                        data: "translated_name_engine"
                    },
                    {
                        title: "Equipment",
                        data: "equipments",
                        render: function(data) {
                            return data || ""; // just show the raw string
                        }
                    },
                    {
                        title: "Optional Equipment",
                        data: "equipments_optional",
                        render: function(data) {
                            return data || ""; // same here
                        }
                    },

                    {
                        title: "Used",
                        data: "used",
                        class: "text-center"
                    },
                    {
                        title: "User ID",
                        data: "userId",
                        class: "text-center"
                    },
                    {
                        title: "Author ID",
                        data: "authorId",
                        class: "text-center"
                    },
                    {
                        title: "Created At",
                        data: "created_at",
                        class: "text-center"
                    },
                    {
                        title: "Edit",
                        data: "id",
                        class: "text-center",
                        orderable: false,
                        render: function(data) {
                            return echoEdit(data);
                        }
                    },
                    {
                        title: "Delete",
                        data: "id",
                        class: "text-center",
                        orderable: false,
                        render: function(data) {
                            return echoRemoveRow(data);
                        }
                    }
                ],
                order: []
            });
        });

        function edit(id) {
            $.ajax({
                url: "/ControlPanel/Exercises/" + id,
                type: "GET",
                success: function(data, textStatus, jqXHR) {
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
                    //$("#equipment").val(data.equipment);
                    $("#views").val(data.views);
                    $("#used").val(data.used);
                    $("#hiddenId").val(data.id);
                    $(".imageHolder").show();
                    if (data.equipmentRequired == 1) {
                        $("#equipmentRequired").attr("checked", true);
                    } else {
                        $("#equipmentRequired").attr("checked", false);
                    }
                    if (data.youtube != null) {
                        $("#youtubeVideo").attr("src", "https://www.youtube.com/embed/" + data.youtube +
                            "?autoplay=0");
                    }
                    if (data.image != null) {
                        $("#imageImage").attr("src", "/" + data.image);
                    }
                    if (data.image2 != null) {
                        $("#image2Image").attr("src", "/" + data.image2);
                    }
                    if (data.video != null) {
                        $("#videoVideo").attr("src", "/" + data.video);
                    }
                    var array = [];
                    if (data.equipments != null) {

                        for (var x = 0; x < data.equipments.length; x++) {
                            array[x] = data.equipments[x].equipmentId;
                        }
                    }

                    $("#equipment").val(array);
                    $("#equipment").trigger("chosen:updated");
                    var array = [];
                    if (data.equipments_optional != null) {

                        for (var x = 0; x < data.equipments_optional.length; x++) {
                            array[x] = data.equipments_optional[x].equipmentId;
                        }
                    }

                    $("#equipmentOptional").val(array);
                    $("#equipmentOptional").trigger("chosen:updated");

                    var array = [];
                    if (data.equipments_hidden != null) {

                        for (var x = 0; x < data.equipments_hidden.length; x++) {
                            array[x] = data.equipments_hidden[x].equipmentId;
                        }
                    }

                    $("#equipmentHidden").val(array);
                    $("#equipmentHidden").trigger("chosen:updated");

                    if (data.exercises_types != null) {

                        for (var x = 0; x < data.exercises_types.length; x++) {
                            array[x] = data.exercises_types[x].exercisestypesId;
                        }
                    }

                    $("#exercisesTypesId").val(array);
                    $("#exercisesTypesId").trigger("chosen:updated");

                    if (data.bodygroups_optional != null) {

                        for (var x = 0; x < data.bodygroups_optional.length; x++) {
                            array[x] = data.bodygroups_optional[x].bodygroupId;
                        }
                    }

                    $("#bodygroupsOptional").val(array);
                    $("#bodygroupsOptional").trigger("chosen:updated");

                    down('w_exercises_add');

                    flowplayer('player', '/fw/flowplayer/flowplayer-3.2.2.swf', {
                        wmode: "transparent",
                        clip: {
                            autoPlay: false,
                            autoBuffering: true
                        }
                    });

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    errorMessage(jqXHR.responseText + " " + errorThrown);
                },
            });
        }

        function del(obj, id) {
            if (confirm("Are you sure?")) {
                $.ajax({
                    url: "/ControlPanel/Exercises/" + id,
                    type: "DELETE",
                    success: function(data, textStatus, jqXHR) {
                        successMessage(data);
                        dtExercises.ajax.reload();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        errorMessage(jqXHR.responseText + " " + errorThrown);
                    },
                });
            }
        }

        function delRow(obj, id) {
            if (confirm("Are you sure?")) {
                $.ajax({
                    url: "/ControlPanel/Exercises/" + id,
                    type: "DELETE",
                    success: function(data, textStatus, jqXHR) {
                        successMessage(data);
                        dtExercises.ajax.reload();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        errorMessage(jqXHR.responseText + " " + errorThrown);
                    },
                });
            }
        }

        function rotateLeft(id) {
            $.ajax({
                url: "/Exercises/Rotate/Left",
                type: "POST",
                data: {
                    id: id
                },
                success: function(data, textStatus, jqXHR) {
                    successMessage(data);
                    refreshImages("#dtExercises");
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    errorMessage(jqXHR.responseText);
                },
                statusCode: {
                    500: function() {
                        if (jqXHR.responseText != "") {
                            errorMessage(jqXHR.responseText);
                        }
                    }
                }
            });
        }

        function rotateRight(id) {
            $.ajax({
                url: "/Exercises/Rotate/Right",
                type: "POST",
                data: {
                    id: id
                },
                success: function(data, textStatus, jqXHR) {
                    successMessage(data);
                    refreshImages("#dtExercises");
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    errorMessage(jqXHR.responseText);
                },
                statusCode: {
                    500: function() {
                        if (jqXHR.responseText != "") {
                            errorMessage(jqXHR.responseText);
                        }
                    }
                }
            });
        }

        function rotateLeft1(id) {
            $.ajax({
                url: "/Exercises/Rotate1/Left",
                type: "POST",
                data: {
                    id: id
                },
                success: function(data, textStatus, jqXHR) {
                    successMessage(data);
                    refreshImages("#dtExercises");
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    errorMessage(jqXHR.responseText);
                },
                statusCode: {
                    500: function() {
                        if (jqXHR.responseText != "") {
                            errorMessage(jqXHR.responseText);
                        }
                    }
                }
            });
        }

        function rotateRight1(id) {
            $.ajax({
                url: "/Exercises/Rotate1/Right",
                type: "POST",
                data: {
                    id: id
                },
                success: function(data, textStatus, jqXHR) {
                    successMessage(data);
                    refreshImages("#dtExercises");
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    errorMessage(jqXHR.responseText);
                },
                statusCode: {
                    500: function() {
                        if (jqXHR.responseText != "") {
                            errorMessage(jqXHR.responseText);
                        }
                    }
                }
            });
        }

        function rotateLeft2(id) {
            $.ajax({
                url: "/Exercises/Rotate2/Left",
                type: "POST",
                data: {
                    id: id
                },
                success: function(data, textStatus, jqXHR) {
                    successMessage(data);
                    refreshImages("#dtExercises");
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    errorMessage(jqXHR.responseText);
                },
                statusCode: {
                    500: function() {
                        if (jqXHR.responseText != "") {
                            errorMessage(jqXHR.responseText);
                        }
                    }
                }
            });
        }

        function switchPictures(id) {
            $.ajax({
                url: "/Exercises/switchPictures",
                type: "POST",
                data: {
                    id: id
                },
                success: function(data, textStatus, jqXHR) {
                    successMessage(data);
                    refreshImages("#dtExercises");
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    errorMessage(jqXHR.responseText);
                },
                statusCode: {
                    500: function() {
                        if (jqXHR.responseText != "") {
                            errorMessage(jqXHR.responseText);
                        }
                    }
                }
            });
        }

        function rotateRight2(id) {
            $.ajax({
                url: "/Exercises/Rotate2/Right",
                type: "POST",
                data: {
                    id: id
                },
                success: function(data, textStatus, jqXHR) {
                    successMessage(data);
                    refreshImages("#dtExercises");
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    errorMessage(jqXHR.responseText);
                },
                statusCode: {
                    500: function() {
                        if (jqXHR.responseText != "") {
                            errorMessage(jqXHR.responseText);
                        }
                    }
                }
            });
        }

        function removeImage(toClear) {
            if (confirm("Are you sure?")) {
                if ($("#hiddenId").val() != "") {
                    var number = 1;
                    if (toClear == "image2Image") {
                        number = 2;
                    }
                    $.ajax({
                        url: "/Exercises/removeImage",
                        type: "POST",
                        data: {
                            id: $("#hiddenId").val(),
                            image: number
                        },
                        success: function(data, textStatus, jqXHR) {
                            if (number == 1) {
                                $("#imageImage").attr("src", "");
                            } else {
                                $("#image2Image").attr("src", "");
                            }
                            successMessage(data);
                            refreshImages("#dtExercises");
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            errorMessage(jqXHR.responseText);
                        },
                        statusCode: {
                            500: function() {
                                if (jqXHR.responseText != "") {
                                    errorMessage(jqXHR.responseText);
                                }
                            }
                        }
                    });
                }
            }
        }
    </script>
@endsection
