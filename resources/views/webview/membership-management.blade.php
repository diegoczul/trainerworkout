<!-- This page allows personal trainer to modify their memembership by viewing whihc memembership they have and allowing them to make a switch.  -->
@php
    use App\Http\Libraries\Helper;
    use App\Http\Libraries\Messages;
    use App\Models\Memberships;
@endphp
@extends('layouts.trainer')

@section('header')
    {!! Helper::seo('membershipManagement') !!}
@endsection

@section('content')
    <script type="text/javascript">
        // alternative to DOMContentLoaded
        document.onreadystatechange = function() {
            document.body.style.display = "none";
            document.getElementById("main_header").classList.add("main_header_account");
            document.body.classList.add("trainer_account");
            document.body.style.display = "block";
        }
    </script>
    @php $currentMembership = Auth::user()->membership; @endphp
    <div class="account accountWrapper membership">
        <div class="widget">
            <div class="account--header">
                <h1>{{ Lang::get('content.MembershipSettings') }}</h1>
            </div>
            <div class="account--table">
                <div class="plan base">
                    <div class="plan--header">
                        <h2>{{ Lang::get('content.BaseMembership') }}</h2>
                    </div>
                    <div class="plan--description">
                        <ul>
                            <li>{{ Lang::get('content.memberships1') }}</li>
                            <li>{{ Lang::get('content.memberships2') }}</li>
                            <li>{{ Lang::get('content.memberships3') }}</li>
                        </ul>
                        <div class="plan--description--mgt">
                            <p>{{ Lang::get('content.free') }}</p>
                            @if (Auth::user()->membership && Auth::user()->membership->membershipId == 59 && Memberships::checkMembership(Auth::user()) == '')
                                <div class="currentPlan">
                                    <p>{{ Lang::get('content.CurrentPlan') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="plan pMonthly">
                    <div class="plan--header">
                        <h2>{{ Lang::get('content.PremiumMembershipMonthly') }}</h2>
                    </div>
                    <div class="plan--description">
                        <ul>
                            <li>{{ Lang::get('content.memberships4') }}</li>
                            <li>{{ Lang::get('content.memberships5') }}</li>
                            <li>{{ Lang::get('content.memberships6') }}</li>
                        </ul>
                        <div class="plan--description--mgt">
                            <p>${{ config('constants.price') }} {{ Lang::get('content.monthly') }}</p>
                            @if(isset($currentMembership) && !empty($currentMembership) && \Carbon\Carbon::parse($currentMembership->expiry)->isFuture() && $currentMembership->expiry && $currentMembership->subscriptionStripeKey != null)
                                @if (Auth::user()->membership && (Auth::user()->membership->membershipId == 63 || Auth::user()->membership->membershipId == 61))
                                    <div class="currentPlan">
                                        <p>{{ Lang::get('content.CurrentPlan') }}</p>
                                    </div>
                                @endif
                            @else
                                @if (Auth::user()->membership && (Auth::user()->membership->membershipId == 63 || Auth::user()->membership->membershipId == 61))
                                    <div class="currentPlan">
                                        @if ($currentMembership && \Carbon\Carbon::parse($currentMembership->expiry)->isFuture())
                                            <p>{{ Lang::get('content.CurrentPlan') }}</p>
                                            <p>{{ Lang::get('content.next_renewal') }}
                                                <strong>{{ \Carbon\Carbon::parse($currentMembership->expiry)->format('F j, Y') }}</strong>
                                            </p>
                                        @else
                                            @php
                                                $membership = Memberships::where('id',63)->first();
                                                if ($membership && empty($membership->apple_in_app_purchase_id)){
                                                    $membership = Memberships::where('id',61)->first();
                                                }
                                            @endphp
                                            <form action="javascript:void(0);" >
                                                <button type="button" onclick="logMessage('{{$membership->apple_in_app_purchase_id}}','{{$membership->id}}','PURCHASE')">{{ Lang::get('content.Upgrade') }}</button>
                                            </form>
                                        @endif
                                    </div>
                                @else
                                    @if (isset($currentMembership) && !empty($currentMembership) &&  (\Carbon\Carbon::parse($currentMembership->expiry)->isPast() || $currentMembership->membershipId == 59))
                                        @php
                                            $membership = Memberships::where('id',63)->first();
                                            if ($membership && empty($membership->apple_in_app_purchase_id)){
                                                $membership = Memberships::where('id',61)->first();
                                            }
                                        @endphp
                                        <form action="javascript:void(0);" >
                                            <button type="button" onclick="logMessage('{{$membership->apple_in_app_purchase_id}}','{{$membership->id}}','PURCHASE')">{{ Lang::get('content.Upgrade') }}</button>
                                        </form>
                                    @endif
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
                <div class="plan pYearly">
                    <div class="plan--header">
                        <h2>{{ Lang::get('content.PremiumMembershipYearly') }}</h2>
                    </div>
                    <div class="plan--description">
                        <ul>
                            <li>{{ Lang::get('content.memberships7') }}</li>
                            <li>{{ Lang::get('content.memberships8') }}</li>
                            <li>{{ Lang::get('content.memberships9') }}</li>
                        </ul>
                        <div class="plan--description--mgt">
                            <p>$107.99 {{ Lang::get('content.yearly') }}</p>
                            @if(isset($currentMembership) && !empty($currentMembership) && \Carbon\Carbon::parse($currentMembership->expiry)->isFuture() && $currentMembership->subscriptionStripeKey != null)
                                @if (Auth::user()->membership && (Auth::user()->membership->membershipId == 64 || Auth::user()->membership->membershipId == 62) && Memberships::checkMembership(Auth::user()) == '')
                                    <div class="currentPlan">
                                        <p>{{ Lang::get('content.CurrentPlan') }}</p>
                                        <p>{{ Lang::get('content.next_renewal') }}
                                            <strong>{{ \Carbon\Carbon::parse($currentMembership->expiry)->format('F j, Y') }}</strong>
                                        </p>
                                    </div>
                                @endif
                            @else
                                @if (Auth::user()->membership && (Auth::user()->membership->membershipId == 64 || Auth::user()->membership->membershipId == 62) && Memberships::checkMembership(Auth::user()) == '')
                                    @if (isset($currentMembership) && !empty($currentMembership) &&  (\Carbon\Carbon::parse($currentMembership->expiry)->isFuture()))
                                        <div class="currentPlan">
                                            <p>{{ Lang::get('content.CurrentPlan') }}</p>
                                            <p>{{ Lang::get('content.next_renewal') }}
                                                <strong>{{ \Carbon\Carbon::parse($currentMembership->expiry)->format('F j, Y') }}</strong>
                                            </p>
                                        </div>
                                    @else
                                        @php
                                            $membership = Memberships::where('id',64)->first();
                                            if ($membership && empty($membership->apple_in_app_purchase_id)){
                                                $membership = Memberships::where('id',62)->first();
                                            }
                                        @endphp
                                        <form action="javascript:void(0);" >
                                            <button type="button" onclick="logMessage('{{$membership->apple_in_app_purchase_id}}','{{$membership->id}}','PURCHASE')">{{ Lang::get('content.Upgrade') }}</button>
                                        </form>
                                    @endif
                                @else
                                    @php
                                        $membership = Memberships::where('id',64)->first();
                                        if ($membership && empty($membership->apple_in_app_purchase_id)){
                                            $membership = Memberships::where('id',62)->first();
                                        }
                                    @endphp
                                    <form action="javascript:void(0);" >
                                        <button type="button" onclick="logMessage('{{$membership->apple_in_app_purchase_id}}','{{$membership->id}}','PURCHASE')">{{ Lang::get('content.Upgrade') }}</button>
                                    </form>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
                <div class="plan">
                    <div class="plan--description">
                        <p class="text-sm">{{ Lang::get('content.renewal_note') }}</p>
                    </div>
                </div>
                @if (isset($currentMembership) && !empty($currentMembership) &&  (\Carbon\Carbon::parse($currentMembership->expiry)->isPast() || $currentMembership->membershipId == 59))
                <div class="plan">
                    <form action="javascript:void(0);" >
                        <button type="button" onclick="console.log('RESTORE');console.log('{{config('constants.USER_ID_LOG')}}{{auth()->user()->id}}');">Restore Subscription</button>
                    </form>
                </div>
                @endif
            </div>
            <p class="text-gray-500 text-center mt-5">
                <a onclick="deleteAccount();" href="javascript:void(0);" class="text-gray-500">{{ Lang::get('content.DeleteAccount') }}</a>
            </p>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $(".menu_membership").addClass("selected");
        });

        function logMessage(message,id,purchase_status) {
            console.log('{{config('constants.IN_APP_SUBSCRIPTION_LOG')}}'+message);
            console.log('{{config('constants.SUBSCRIPTION_PLAN_ID_LOG')}}'+id);
            console.log('{{config('constants.SUBSCRIPTION_PURCHASE_STATUS_LOG')}}'+purchase_status);
            console.log('{{config('constants.USER_ID_LOG')}}{{auth()->user()->id}}');
        }
        function deleteAccount() {
            if (confirm("Are You Sure You Want To Delete Your Account ?")) {
                $.ajax({
                    url: "{{ Lang::get('routes./delete-account') }}/{{ auth()->user()->id }}",
                    type: "DELETE",
                    success: function(data, textStatus, jqXHR) {
                        successMessage(data);
                        deleteIndexedDatabase();
                        window.location.href = "{{ route('logout') }}";
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        errorMessage(jqXHR.responseText + " " + errorThrown);
                    },
                });
            }
        }
    </script>
@endsection
