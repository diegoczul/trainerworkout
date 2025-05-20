
@php
    use App\Http\Libraries\Helper;
    use App\Http\Libraries\Messages;
    use App\Models\Memberships;
@endphp

@extends('layouts.trainer')

@section('content')

<div class="contentContainer trainer">
    <div class="cancel-subscription">
        <div class="workoutsContainer">
            <div class="widget my-0">
                <div class="subscription-header mb-6 md:mb-8">
                    <h1 class="text-center main-title">Cancellation subscription</h1>
                </div>
                <div class="subscription-content">
                    <div class="cancellation-step mb-6 md:mb-10">
                        <h4 class="mb-6">Open the Setting app on your iPhone or iPad </h4>
                        <div class="flex justify-self-center">
                            <img src="{{asset('assets/img/cancellationflow/s1-setting.png')}}">
                        </div>
                    </div>

                    <div class="cancellation-step mb-6 md:mb-10">
                        <h4 class="text-black pb-2 font-medium">2. Tap on Apple ID (Profile):</h4>
                        <h4 class="mb-6">Scroll down slightly and tap on your Apple ID Profile at the top of the screen (usually contains your name and picture).</h4>
                        <div class="flex justify-self-center">
                            <img src="{{asset('assets/img/cancellationflow/s2-appleID.png')}}">
                        </div>
                    </div>

                    <div class="cancellation-step mb-6 md:mb-10">
                        <h4 class="text-black pb-2 font-medium">3. Go to Subscriptions:</h4>
                        <h4 class="mb-6">Inside the Apple ID screen, there will be an option called Subscriptions.</h4>
                        <div class="flex justify-self-center">
                            <img src="{{asset('assets/img/cancellationflow/s3-subscriptions.png')}}">
                        </div>
                    </div>

                    <div class="cancellation-step mb-6 md:mb-10">
                        <h4 class="text-black pb-2 font-medium">4. Select the Subscription:</h4>
                        <h4 class="mb-6">You will see list of your active subscriptions and select the Trainer Workout to cancel. </h4>
                        <div class="flex justify-self-center">
                            <img src="{{asset('assets/img/cancellationflow/s4-trainerworkout.png')}}">
                        </div>
                    </div>

                    <div class="cancellation-step mb-6 md:mb-10">
                        <h4 class="text-black pb-2 font-medium">5. Tap on Cancel Subscription:</h4>
                        <h4 class="mb-6">After selecting the subscription, there should be an option to Cancel Subscription near the bottom with red color.  </h4>
                        <div class="flex justify-self-center">
                            <img src="{{asset('assets/img/cancellationflow/s5-cancelsubs.png')}}">
                        </div>
                    </div>

                    <div class="cancellation-step mb-6 md:mb-10">
                        <h4 class="text-black pb-2 font-medium">6. Confirm Cancellation: </h4>
                        <h4 class="mb-6">You will be prompted to confirm your decision to cancel.</h4>
                        <div class="flex justify-self-center">
                            <img src="{{asset('assets/img/cancellationflow/s6-confirmcancel.png')}}">
                        </div>      
                    </div>
                </div>
                <div class="w-100 align-middle justify-center flex">
                    <button class="bluebtn flex items-center justify-center"  onclick="showButtonLoader(this);window.location.href='{{route('MembershipManagement')}}'">Back</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
    <script>
        function showButtonLoader(element) {
            $(element).html('<img src="{{ asset('assets/img/tw-gif.gif') }}" style="width: 40px;">')
        }
    </script>
@endsection