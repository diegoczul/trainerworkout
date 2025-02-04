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

<body class="fullWidth upgradeBackground">

 <!-- content area starts here -->
    <section id="content" class="clearfix">
		<div class="wrapper thankYou">
        	
            <div class="widgets">
            	
            	<h2 class="tyMessage">{{ Lang::get("content.Andremember") }}</h2>
            	<div class="thankyou">
                    <p class="tyTransaction">{{ Lang::get("content.transaction",array("orderid"=>$order->id)) }}</p>
                    <div class="ordersummary">
                    	
                        <table width="100%" cellspacing="0" cellpadding="0" border="0" class="tabulardata ordertable">
                          <tbody>
                          <tr>
                          	<td colspan="3"><h3>{{ Lang::get("content.OrderSummary") }}</h3></td>
                          </tr>
                          <tr>
                            <td>{{ Lang::get("content.Subtotal") }}</td>
                            <td>${{ $order->subtotal }}</td>
                          </tr>

                          <tr>
                            <td>{{ Lang::get("content.Total") }}</td>
                            <td>${{ $order->total}}</td>
                          </tr>
                        </tbody></table>
                   
                
                </div>

            <div class="clearfix"></div>
        </div>
    </section>
</body>

@endsection


    @section("scripts")
    <script>//callWidget("w_trendingWorkouts");</script>
    @endsection