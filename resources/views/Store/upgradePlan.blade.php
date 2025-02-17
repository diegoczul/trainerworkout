<script type="text/javascript">

// alternative to DOMContentLoaded
document.onreadystatechange = function () {
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
			<h1>{{ Lang::get("content.Upgradingyouraccount") }}</h1>
		</div>
		<div class="account--table">
			<div class="plan">
				<div class="plan--header">
					<h2>{{ Lang::get("content.Monthly") }}</h2>
				</div>
				<div class="plan--description">
					<ul>
						<li>{{ Lang::get("content.personalizeyourbranding") }}</li>
						<li>{{ Lang::get("content.unlimitedstoredworkouts") }}</li>
						<li>{{ Lang::get("content.unlimetedshares") }}</li>
					</ul>
					<h3>$21.99 / {{ Lang::get("content.month") }}</h3>
					<form action="{{ Lang::get("routes./Store/addToCart") }}/63/Membership">
						<button>{{ Lang::get("content.ChoosePlan") }}</button>
					</form>
				</div>
			</div>
			<div class="plan">
				<div class="plan--header">
					<h2>{{ Lang::get("content.Yearly") }}</h2>
				</div>
				<div class="plan--description">
					<ul>
						<li>{{ Lang::get("content.personalizeyourbranding") }}</li>
						<li>{{ Lang::get("content.unlimitedstoredworkouts") }}</li>
						<li>{{ Lang::get("content.unlimetedshares") }}</li>
					</ul>
					<h3>$20.15 / {{ Lang::get("content.month") }}</h3>
					<form action="{{ Lang::get("routes./Store/addToCart") }}/64/Membership">
						<button>{{ Lang::get("content.ChoosePlan") }}</button>
						<img id="upgradeDiscount" src="{{asset('assets/img/tagTenPercentOff.png')}}">
					</form>
				</div>
			</div>
		</div>
	</div>
</div>


@endsection
@section('scripts')

<script>
$(document).ready(function(){
    $(".menu_membership").addClass("selected");
});

</script>



@endsection