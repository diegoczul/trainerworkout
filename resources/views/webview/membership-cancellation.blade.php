
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
                    <h1 class="text-center main-title">{{__('messages.cancellation_subscription')}}</h1>
                </div>
                <div class="subscription-content">
                    <div class="cancellation-step mb-6 md:mb-10">
                        <h4 class="mb-6">{{__('messages.open_settings_app')}}</h4>
                        <div class="flex justify-self-center">
                            <img src="{{asset('assets/img/cancellationflow/s1-setting.png')}}">
                        </div>
                    </div>

                    <div class="cancellation-step mb-6 md:mb-10">
                        <h4 class="text-black pb-2 font-medium">{{__('messages.step_2_title')}}</h4>
                        <h4 class="mb-6">{{__('messages.step_2_description')}}</h4>
                        <div class="flex justify-self-center">
                            <img src="{{asset('assets/img/cancellationflow/s2-appleID.png')}}">
                        </div>
                    </div>

                    <div class="cancellation-step mb-6 md:mb-10">
                        <h4 class="text-black pb-2 font-medium">{{__('messages.step_3_title')}}</h4>
                        <h4 class="mb-6">{{__('messages.step_3_description')}}</h4>
                        <div class="flex justify-self-center">
                            <img src="{{asset('assets/img/cancellationflow/s3-subscriptions.png')}}">
                        </div>
                    </div>

                    <div class="cancellation-step mb-6 md:mb-10">
                        <h4 class="text-black pb-2 font-medium">{{__('messages.step_4_title')}}</h4>
                        <h4 class="mb-6">{{__('messages.step_4_description')}}</h4>
                        <div class="flex justify-self-center">
                            <img src="{{asset('assets/img/cancellationflow/s4-trainerworkout.png')}}">
                        </div>
                    </div>

                    <div class="cancellation-step mb-6 md:mb-10">
                        <h4 class="text-black pb-2 font-medium">{{__('messages.step_5_title')}}</h4>
                        <h4 class="mb-6">{{__('messages.step_5_description')}}</h4>
                        <div class="flex justify-self-center">
                            <img src="{{asset('assets/img/cancellationflow/s5-cancelsubs.png')}}">
                        </div>
                    </div>

                    <div class="cancellation-step mb-6 md:mb-10">
                        <h4 class="text-black pb-2 font-medium">{{__('messages.step_6_title')}}</h4>
                        <h4 class="mb-6">{{__('messages.step_6_description')}}</h4>
                        <div class="flex justify-self-center">
                            <img src="{{asset('assets/img/cancellationflow/s6-confirmcancel.png')}}">
                        </div>      
                    </div>
                </div>
                <div class="w-100 align-middle justify-center flex">
                    <button class="bluebtn flex items-center justify-center"  onclick="showButtonLoader(this);window.location.href='{{route('MembershipManagement')}}'">{{__('messages.back')}}</button>
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