@extends('layouts.whitelabels.'.strtolower(Config::get("app.whitelabel")).".invitation")

@section("content")

<main class="accountPages">
    <div class="background"></div>
    <div class="wrapper accountRoot">
        <div class="topBlock">
            <h1>{{ Lang::get("content.frontEnd/welcomeTrainee") }} </h1>
            <h3>{{ Lang::get("content.frontEnd/promiseToYou") }}</h3>
        </div>
        <div class="accountAction_container">
            {{ Form::open(array('url' => Lang::get("routes./Trainee/SignUp"), "id"=>"login_form")); }}
                <input type="hidden" name="invite" value="{{ (isset($key)) ? $key : "" }}"/>
                <label for="firstName">{{ Lang::get("content.First Name") }}</label>
                <input type="text" name="firstName" id="firstName" required placeholder="{{ Lang::get("content.First Name") }}" value="{{ (isset($invite) and ($invite)) ? $invite->firstName : Input::old('firstName') }}" />
                <label for="lastName">{{ Lang::get("content.Last Name") }}</label>
                <input type="text" name="lastName" id="lastName" required placeholder="{{ Lang::get("content.Last Name") }}" value="{{ (isset($invite) and ($invite)) ? $invite->lastName : Input::old('lastName') }}" />
                <label for="email">{{ Lang::get("content.Email") }}</label>
                <input type="text" name="email" id="email" required placeholder="{{ Lang::get("content.Email") }}" value="{{ (isset($invite) and ($invite)) ? $invite->email : Input::old('email') }}" />
                <label for="password">{{ Lang::get("content.Password") }}</label>
                <input type="password" id="password" name="password" required placeholder="{{ Lang::get("content.Password") }}" value="{{ Input::old("password") }}" /> 
                <input id="timezone" type="hidden" name="timezone" value="{{Input::old("timezone")}}" />
                <input id="paid" type="hidden" name="paid" value="{{ isset($paid) ? "yes" : "" }}" />
                <fieldset class="termsAndConditions">
                    <input name="termsAndConditions" id="terms" required type="checkbox" value="Yes">
                    <label for="terms">{{ Lang::get("content.I agree with the") }} <a target="_blank" href="/TermsAndConditions/">{{ Lang::get("content.Terms and Conditions") }}</a></label>
                </fieldset>
                <button type="submit" class="submit">{{ Lang::get("content.Create My Account") }}</button>
                <a href="{{ Lang::get("routes./login") }}" class="forgot_password">{{ Lang::get("content.Already have an account, log in") }}</a>
                <a href="{{ Lang::get("routes./login/trainee/facebook") }}{{ (isset($key) and $key != "") ? "/".$key : "" }}" class="facebook">{{ Lang::get("content.frontEnd/facebooklogin") }}</a>
             {{ Form::close() }}
        </div>
    </div>
</main>



@endsection

@section("scripts")
<script type="text/javascript">

function showForm() {
     $(".exp-form").toggle();
     $("#exp-manualBTN").toggle();
}
</script>








@endsection