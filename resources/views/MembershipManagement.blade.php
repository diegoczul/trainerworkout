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
                            @if (Auth::user()->membership and
                                    Auth::user()->membership->membershipId == 59 and
                                    Memberships::checkMembership(Auth::user()) == '')
                                <div class="currentPlan">
                                    <p>{{ Lang::get('content.CurrentPlan') }}</p>
                                </div>
                            @else
                                @if (Auth::user()->membership->renew == 0)
                                    <p>
                                        {{ Lang::get('content.downgrade_note') }}<strong>{{ \Carbon\Carbon::parse(Auth::user()->membership->expiry)->format('F j, Y') }}</strong>.
                                    </p>
                                @else
                                    <form action="{{ Lang::get('routes./Store/addToCart') }}/59/Membership">
                                        <button>{{ Lang::get('content.Downgrade') }}</button>
                                    </form>
                                @endif
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
                            @if (Auth::user()->membership and
                                    (Auth::user()->membership->membershipId == 63 or Auth::user()->membership->membershipId == 61))
                                @php
                                    $currentMembership = Auth::user()->membership;
                                @endphp
                                <div class="currentPlan">
                                    <p>{{ Lang::get('content.CurrentPlan') }}</p>
                                    @if ($currentMembership && $currentMembership->expiry)
                                        @if ($currentMembership && $currentMembership->renew == 0)
                                            <a class="text-black underline"
                                                href="Store/CancelDowngrade">{{ Lang::get('content.cancel_downgrade') }}</a>
                                        @else
                                            <p>{{ Lang::get('content.next_renewal') }}
                                                <strong>{{ \Carbon\Carbon::parse($currentMembership->expiry)->format('F j, Y') }}</strong>
                                            </p>
                                        @endif
                                    @endif
                                </div>
                            @else
                                @php
                                    $currentMembership = Auth::user()->membership;
                                @endphp
                                {{-- Downgrade from Yearly to Monthly --}}
                                @if ($currentMembership && $currentMembership->membershipId == 64 and $currentMembership->downgrade_to != '')
                                    <p>{{ Lang::get('content.downgrade_note') }}
                                        <strong>{{ \Carbon\Carbon::parse($currentMembership->expiry)->format('F j, Y') }}</strong>.
                                    </p>
                                    <a class="text-black underline"
                                        href="Store/CancelDowngradeYearly">{{ Lang::get('content.cancel_downgrade') }}</a>
                                @else
                                    <form method="POST" action="{{ route('membership.downgrade.monthly') }}">
                                        @csrf
                                        <button type="submit">{{ Lang::get('content.Downgrade') }}</button>
                                    </form>
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
                            @if (Auth::user()->membership and
                                    (Auth::user()->membership->membershipId == 64 or Auth::user()->membership->membershipId == 62) and
                                    Memberships::checkMembership(Auth::user()) == '')
                                <div class="currentPlan">
                                    <p>{{ Lang::get('content.CurrentPlan') }}</p>
                                </div>
                                @if ($currentMembership && $currentMembership->expiry)
                                    @if ($currentMembership && $currentMembership->renew == 0)
                                        <a class="text-black underline"
                                            href="Store/CancelDowngrade">{{ Lang::get('content.cancel_downgrade') }}</a>
                                    @else
                                        @if ($currentMembership->downgrade_to == '')
                                            <p>{{ Lang::get('content.next_renewal') }}
                                                <strong>{{ \Carbon\Carbon::parse($currentMembership->expiry)->format('F j, Y') }}</strong>
                                            </p>
                                        @endif
                                    @endif
                                @endif
                            @else
                                <form action="{{ Lang::get('routes./Store/addToCart') }}/64/Membership">
                                    <button>{{ Lang::get('content.Upgrade') }}</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="plan">

                    <div class="plan--description">
                        <p class="text-sm">{{ Lang::get('content.renewal_note') }}</p>
                    </div>
                </div>

            </div>
            <p class="text-gray-500 text-center mt-5"> <a onclick="deleteAccount();" href="javascript:void(0);"
                    class="text-gray-500">{{ Lang::get('content.DeleteAccount') }}</a>
            </p>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $(".menu_membership").addClass("selected");
        });

        function deleteAccount() {
            if (confirm("Are You Sure You Want To Delete Your Account ?")) {
                $.ajax({
                    url: "{{ Lang::get('routes./delete-account') }}/{{ auth()->user()->id }}",
                    type: "DELETE",
                    success: function(data, textStatus, jqXHR) {
                        successMessage(data);
                        deleteIndexedDatabase();
                        window.location.reload();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        errorMessage(jqXHR.responseText + " " + errorThrown);
                    },
                });
            }
        }
    </script>
@endsection
