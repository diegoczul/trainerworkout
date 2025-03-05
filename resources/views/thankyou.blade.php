@php
    use App\Http\Libraries\Helper;
@endphp
@extends('layouts.frontEndThankyou')
@section("header")
    {!! Helper::seo("thankYou") !!}
@endsection
@section('content')
    <!-- Header -->
    <header class="header_paypage">
        <div class="container form_block">
            <img class="mobile-logo" src="{{asset('assets/img/logos/icon_logo.png')}}" width="150px">
            <h1 class="save_time_thankyou save_time" style="font-size:15px; opacity: 0.4;">{{ Lang::get("messagesThankYouPayment") }}</h1>
        </div>
    </header>
@endsection
@section('scripts')
    <!-- Twitter conversion pixel -->
    <script src="//platform.twitter.com/oct.js" type="text/javascript"></script>
    <script type="text/javascript">twttr.conversion.trackPid('ntjm9', { tw_sale_amount: 0, tw_order_quantity: 0 });</script>
    <noscript>
        <img height="1" width="1" style="display:none;" alt="" src="https://analytics.twitter.com/i/adsct?txn_id=ntjm9&p_id=Twitter&tw_sale_amount=0&tw_order_quantity=0" />
        <img height="1" width="1" style="display:none;" alt="" src="//t.co/i/adsct?txn_id=ntjm9&p_id=Twitter&tw_sale_amount=0&tw_order_quantity=0" />
    </noscript>


    <!-- Facebook Conversion Code for Thank you Page -->
    <script>(function() {
            var _fbq = window._fbq || (window._fbq = []);
            if (!_fbq.loaded) {
                var fbds = document.createElement('script');
                fbds.async = true;
                fbds.src = '//connect.facebook.net/en_US/fbds.js';
                var s = document.getElementsByTagName('script')[0];
                s.parentNode.insertBefore(fbds, s);
                _fbq.loaded = true;
            }
        })();
        window._fbq = window._fbq || [];
        window._fbq.push(['track', '6038068455680', {'value':'0.00','currency':'CAD'}]);
    </script>
    <noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/tr?ev=6038068455680&amp;cd[value]=0.00&amp;cd[currency]=CAD&amp;noscript=1" /></noscript>

    <script type="text/javascript">
        function showThankYou(){
            $('.save_time').stop().animate({
                scrollTop: 0,
                width: "100%",
                opacity: 1,
                fontSize: "45px",
                margin:"150px"

            }, 1500, 'easeInOutExpo');
        }

        $(document).ready(function(){
            showThankYou();
            window.setTimeout(function(){
                // Move to login page
                window.location.href = "/Trainer/Workouts";
            }, 3000);
        });
    </script>
@endsection
