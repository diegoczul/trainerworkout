 @extends('layouts.controlpanel')


@section('content')
                
                <div class-"col-lg-12">
                    <h1 class="page-header">Fix empty language exercises translations</h1>
                </div>
                
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel panel-heading">
                                   Fix Translations
                            </div>
                            <div class="panel-body" class="fixTranslations">

                             <button onclick="fixTranslations()" type="button" class="btn btn-info">Fix Database for Translations</button>


                                <table id="excelDataTable" class="excelDataTable" border="1">
                                </table>
                               
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel panel-heading">
                                    Completly Remove User
                            </div>
                            <div class="panel-body" >
                               {{  Form::select("userToRemove",$users,"",array("id"=>"userToRemove","placeholder"=>"Remove User", "class"=>"form-control chosen-select")) }}
                               <br/> <br/> <br/>
                                    <button onclick="removeUserFromDatabase()" type="button" class="btn btn-info">Completly Remove User</button>
                               
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel panel-heading">
                                    Restore Deleted Workout
                            </div>
                            <div class="panel-body" >
                            <select id="workoutsToRestore" name="workoutsToRestore" class="form-control chosen-select3">
                                @foreach($workouts as $workout)
                                    <option value="{{ $workout->id }}">{{ $workout->id }} {{ $workout->name }} {{ ($workout->user) ? $workout->user->firstName : "" }} {{ ($workout->user) ? $workout->user->lastName : "" }} {{ ($workout->user) ? $workout->user->email : "" }}  </option>
                                @endforeach
                            </select>
                               <br/> <br/> <br/>
                                    <button onclick="workoutsToRestore()" type="button" class="btn btn-info">Restore</button>
                               
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel panel-heading">
                                    Calculate Used Exercises
                            </div>
                            <div class="panel-body" >
                           <button onclick="fixUsedExercises()" type="button" class="btn btn-info">Recalculate</button>
                               <br/> <br/> <br/>
                               
                            </div>
                        </div>
                    </div>
                </div>


@endsection

@section("scripts")


<script>

$(document).ready(function(){
    $(".chosen-select3").chosen({
        "search_contains":true
    });

});

function fixTranslations(){
    $.ajax(
            {
                url :"/ControlPanel/fixExercisesTranslations",
                type: "POST",
                
                success:function(data, textStatus, jqXHR) 
                {
                    buildHtmlTable(".excelDataTable",data);
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

function fixUsedExercises(){
    $.ajax(
            {
                url :"/ControlPanel/fixUsedExercises",
                type: "POST",
                
                success:function(data, textStatus, jqXHR) 
                {
                    buildHtmlTable(".excelDataTable",data);
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

function removeUserFromDatabase(){
    $.ajax(
            {
                url :"/ControlPanel/removeUserFromDatabase",
                type: "POST",
                data: {userId:$("#userToRemove").val()},
                success:function(data, textStatus, jqXHR) 
                {
                    successMessage(data);
                    location.reload();
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

function workoutsToRestore(){
    $.ajax(
            {
                url :"/ControlPanel/workoutsToRestore",
                type: "POST",
                data: {workoutId:$("#workoutsToRestore").val()},
                success:function(data, textStatus, jqXHR) 
                {
                    successMessage(data);
                    location.reload();
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


function addAllColumnHeaders(myList, selector){
    var columnSet = [];
    var headerTr$ = $('<tr/>');

    for (var i = 0 ; i < myList.length ; i++) {
        var rowHash = myList[i];
        for (var key in rowHash) {
            if ($.inArray(key, columnSet) == -1){
                columnSet.push(key);
                headerTr$.append($('<th/>').html(key));
            }
        }
    }
    $(selector).append(headerTr$);

    return columnSet;
}

// Builds the HTML Table out of myList.
function buildHtmlTable(selector,myList) {
    var columns = addAllColumnHeaders(myList, selector);

    for (var i = 0 ; i < myList.length ; i++) {
        var row$ = $('<tr/>');
        for (var colIndex = 0 ; colIndex < columns.length ; colIndex++) {
            var cellValue = myList[i][columns[colIndex]];

            if (cellValue == null) { cellValue = ""; }

            row$.append($('<td/>').html(cellValue));
        }
        $(selector).append(row$);
    }
}


</script>


@endsection