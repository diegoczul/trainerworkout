@extends("layouts.trainer")


@section("content")
<?php 
    $table = "yes";
 ?>

 <section id="content" class="clearfix">
    <div class="reportHeader">
        <div class="reportSettings">
            <div class="dateContainer">
                <label for="dateStart">Date Start: </label>
                <input type="text" class="datepicker date" name="dateStart" id="dateStart" value="{{ $dateStart }}" />
            </div>
            <div class="dateContainer">
                <label for="dateEnd">Date End:</label>
                <input type="text" class="datepicker date" name="dateEnd" id="dateEnd" value="{{ $dateEnd }}"/>
            </div>
            <input type="button" value="{{ Lang::get("content.Submit") }}" onClick="refreshReport()"/>
        </div>
    </div>
    <div class="reportWrapper">
        <div id="r_workoutsPerformance" class="widgetList">
            <!-- report starts here -->
            <!-- tw/app/views/widgets/reports/workoutsPerformance.blade.php -->
        </div>
    </div>
</section>


@endsection

@section("scripts")

<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script>callWidget("r_workoutsPerformance");</script> 

<script>
function refreshReport(){
        data = {};
        data.dateStart = $("#dateStart").val();
        data.dateEnd =$("#dateEnd").val();
        callWidget("r_workoutsPerformance",null,null,null,data);
        // $('.reportTable').DataTable( {
        //     "scrollX": true
        // } );
    }
$(document).ready(function(){
    $(".menu_clientsReports").addClass("selected");
});

</script>


@endsection