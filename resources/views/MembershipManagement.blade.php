<!-- This page allows personal trainer to modify their memembership by viewing whihc memembership they have and allowing them to make a switch.  -->
@php
	use App\Http\Libraries\Helper;
    use App\Http\Libraries\Messages;
    use App\Models\Memberships;
@endphp
@extends('layouts.trainer')

@section("header")
    {{ Helper::seo("membershipManagement") }}
@endsection

@section('content')

<script type="text/javascript">
// alternative to DOMContentLoaded
document.onreadystatechange = function () {
	document.body.style.display = "none";
	document.getElementById("main_header").classList.add("main_header_account");
	document.body.classList.add("trainer_account");
    document.body.style.display = "block";
}
</script>

<div class="account accountWrapper membership">
	<div class="widget">
		<div class="account--header">
			<h1>{{ Lang::get("content.MembershipSettings") }}</h1>
		</div>
		<div class="account--table">
			<div class="plan base">
				<div class="plan--header">
					<h2>{{ Lang::get("content.BaseMembership") }}</h2>
				</div>
				<div class="plan--description">
					<ul>
						<li>{{ Lang::get("content.memberships1") }}</li>
						<li>{{ Lang::get("content.memberships2") }}</li>
						<li>{{ Lang::get("content.memberships3") }}</li>
					</ul>
					<div class="plan--description--mgt">
						<p>{{ Lang::get("content.free") }}</p>
						@if(Auth::user()->membership and Auth::user()->membership->membershipId == 59 and Memberships::checkMembership(Auth::user()) == "")
							<div class="currentPlan"><p>{{ Lang::get("content.CurrentPlan") }}</p></div> 
						@else 
							<form action="{{ Lang::get("routes./Store/addToCart") }}/59/Membership">
								<button>{{ Lang::get("content.Downgrade") }}</button>
							</form>
						@endif
					</div>
				</div>
			</div>
			<div class="plan pMonthly">
				<div class="plan--header">
					<h2>{{ Lang::get("content.PremiumMembershipMonthly") }}</h2>
				</div>
				<div class="plan--description">
					<ul>
						<li>{{ Lang::get("content.memberships4") }}</li>
						<li>{{ Lang::get("content.memberships5") }}</li>
						<li>{{ Lang::get("content.memberships6") }}</li>
					</ul>
					<div class="plan--description--mgt">
						<p>$21.99 {{ Lang::get("content.monthly") }}</p>
						@if(Auth::user()->membership and (Auth::user()->membership->membershipId == 63 or Auth::user()->membership->membershipId == 61))
							<div class="currentPlan"><p>{{ Lang::get("content.CurrentPlan") }}</p></div> 
						@else 
							<form action="{{ Lang::get("routes./Store/addToCart") }}/63/Membership">
								<button>{{ (Auth::user()->membership and Auth::user()->membership->membershipId == 59 and Memberships::checkMembership(Auth::user()) == "") ?  Lang::get("content.Upgrade")  :  Lang::get("content.Downgrade")  }}</button>
							</form>
						@endif
					</div>
				</div>
			</div>
			<div class="plan pYearly">
				<div class="plan--header">
					<h2>{{ Lang::get("content.PremiumMembershipYearly") }}</h2>
				</div>
				<div class="plan--description">
					<ul>
						<li>{{ Lang::get("content.memberships7") }}</li>
						<li>{{ Lang::get("content.memberships8") }}</li>
						<li>{{ Lang::get("content.memberships9") }}</li>
					</ul>
					<div class="plan--description--mgt">
						<p>$219.99 {{ Lang::get("content.yearly") }}</p>
						@if(Auth::user()->membership and (Auth::user()->membership->membershipId == 64 or Auth::user()->membership->membershipId == 62) and Memberships::checkMembership(Auth::user()) == "")
							<div class="currentPlan"><p>{{ Lang::get("content.CurrentPlan") }}</p></div> 
						@else 
							<form action="{{ Lang::get("routes./Store/addToCart") }}/64/Membership">
								<button>{{ Lang::get("content.Upgrade") }}</button>
							</form>
						@endif
					</div>
				</div>
			</div>

		</div>
	</div>
</div>



@endsection

@section("scripts")

<script>
$(document).ready(function(){
    $(".menu_membership").addClass("selected");
});

</script>

@endsection






