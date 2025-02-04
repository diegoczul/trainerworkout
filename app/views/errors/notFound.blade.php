<?php 
    $layoutToLoad = null;
    if(Auth::check()){
        $layoutToLoad = strtolower(Auth::user()->userType); 
    } else{
        $layoutToLoad = "visitor"; 
    }
?>
@extends('layouts.'.$layoutToLoad)


@section('content')


 <!-- content area starts here -->
    <section id="content" class="clearfix">
		<div class="wrapper">
        	
            <div class="widgets threefourthwidget shadow marginleftnone">
            	
            	<h1>{{ Lang::get("content.oups") }}</h1>
            		<p>{{ Lang::get("content.pageNotFound") }} {{{ $page }}}</p>
                <!-- bottom button holder -->
               
            </div>
            <div class="clearfix"></div>
        </div>
    </section>

@endsection


@section("scripts")
<script>
$(document).ready(function(){

    window.setTimeout(function(){
        // Move to login page
        window.location.href = "/";
    }, 1000);
});

</script>
@endsection