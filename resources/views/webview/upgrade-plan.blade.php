@php use App\Models\Memberships; @endphp
<script type="text/javascript">
    // alternative to DOMContentLoaded
    document.onreadystatechange = function() {
        document.body.style.display = "none";
        document.getElementById("main_header").classList.add("main_header_account");
        document.body.classList.add("trainer_account");
        // accountColors();
        document.body.style.display = "block";
    }

    function accountColors() {
        document.getElementById("main_header").classList.add("main_header_account");
        document.body.classList.add("trainer_account");
        console.log('loaded');
    }
</script>

@extends('layouts.trainer')

@section('content')
    <div class="account accountWrapper">
        <div class="widget">
            <div class="account--header">
                <h1>{{ Lang::get('content.Upgradingyouraccount') }}</h1>
            </div>
            <div class="account--table">
                <div class="plan">
                    <div class="plan--header">
                        <h2>{{ Lang::get('content.Monthly') }}</h2>
                    </div>
                    <div class="plan--description">
                        <ul>
                            <li>{{ Lang::get('content.personalizeyourbranding') }}</li>
                            <li>{{ Lang::get('content.unlimitedstoredworkouts') }}</li>
                            <li>{{ Lang::get('content.unlimetedshares') }}</li>
                        </ul>
                        <h3>${{ config('constants.price') }} / {{ Lang::get('content.month') }}</h3>
                        @php
                            $membership = Memberships::where('id',61)->first();
                            if ($membership && empty($membership->apple_in_app_purchase_id)){
                                $membership = Memberships::where('id',63)->first();
                            }
                        @endphp
                        <form action="javascript:void(0);">
                            <button type="button" onclick="logMessage('{{$membership->apple_in_app_purchase_id}}','{{$membership->id}}','PURCHASE')">{{ Lang::get('content.ChoosePlan') }}</button>
                        </form>
                    </div>
                </div>
                <div class="plan">
                    <div class="plan--header">
                        <h2>{{ Lang::get('content.Yearly') }}</h2>
                    </div>
                    <div class="plan--description">
                        <ul>
                            <li>{{ Lang::get('content.personalizeyourbranding') }}</li>
                            <li>{{ Lang::get('content.unlimitedstoredworkouts') }}</li>
                            <li>{{ Lang::get('content.unlimetedshares') }}</li>
                        </ul>
                        <h3>$20.15 / {{ Lang::get('content.month') }}</h3>
                        @php
                            $membership = Memberships::where('id',64)->first();
                            if ($membership && empty($membership->apple_in_app_purchase_id)){
                                $membership = Memberships::where('id',62)->first();
                            }
                        @endphp
                        <form action="javascript:void(0);">
                            <button type="button" onclick="logMessage('{{$membership->apple_in_app_purchase_id}}','{{$membership->id}}','PURCHASE')">{{ Lang::get('content.ChoosePlan') }}</button>
                            <img id="upgradeDiscount" src="{{ asset('assets/img/tagTenPercentOff.png') }}">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        function logMessage(message,id,purchase_status) {
            console.log('{{config('constants.IN_APP_SUBSCRIPTION_LOG')}}'+message);
            console.log('{{config('constants.SUBSCRIPTION_PLAN_ID_LOG')}}'+id);
            console.log('{{config('constants.SUBSCRIPTION_PURCHASE_STATUS_LOG')}}'+purchase_status);
            console.log('{{config('constants.USER_ID_LOG')}}{{auth()->user()->id}}');
        }
        $(document).ready(function() {
            $(".menu_membership").addClass("selected");
        });
    </script>
@endsection
