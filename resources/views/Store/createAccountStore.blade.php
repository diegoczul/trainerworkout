@extends('layouts.frontEnd')

@section('content')
<!--......................... Create Account Expandable ............................-->
            <div class="fullWidth exp-Background" id="freeTrialSignup">
                <h1 id="headerFrPricing">{{ Lang::get("content.index11") }}</h1>
                <h3 class="freeTrialSignup_h3">{{ Lang::get("content.index12") }}</h3>
                <div class="exp-Widget">
                    <h1>{{ Lang::get("content.createAccounth1") }}</h1>
                    <div class="exp-SignUp">
                        <div class="exp-facebook">
                            {{ Form::open(array('url' => Lang::get("routes./Trainer/SignUp"), "id"=>"login_form")); }}
                            <a href="/login/facebook" class="exp-Btn facebook">{{ Lang::get("content.index13") }}</a>
                            {{ Form::close() }}
                        </div>
                        <div class="exp-circle">{{ Lang::get("content.or") }}</div>
                        <div class="exp-manual">
                            <a class="exp-Btn" href="javascript:void(0)" id="exp-manualBTN" onclick="showForm()">{{ Lang::get("content.createAccountManually") }}</a>

                            <div class="exp-form">
                                {{ Form::open(array('url' => Lang::get("routes./Trainer/SignUp"), "id"=>"login_form")); }}
                                <fieldset>

                                    <label for="firstName">{{ Lang::get("content.firstname") }}*</label>
                                    <input type="text" placeholder="{{ Lang::get("content.firstname") }}" value="{{ Input::old('firstName') }}" required name="firstName" id="firstName" />

                                    <label for="lastName">{{ Lang::get("content.lastname") }}*</label>
                                    <input type="text" placeholder="{{ Lang::get("content.lastname") }}" value="{{ Input::old('lastName') }}" required name="lastName" id="lastName" />

                                    <label for="phoneNumber">{{ Lang::get("content.createAccountPhone") }}</label>
                                    <input type="text" placeholder="{{ Lang::get("content.createAccountPhone") }}" value="{{ Input::old('phoneNumber') }}" name="phoneNumber" id="phoneNumber" />

                                    <label for="email">{{ Lang::get("content.email") }}*</label>
                                    <input type="text" placeholder="{{ Lang::get("content.email") }}" value="{{ Input::old('email') }}" required name="email" id="email" />

                                    <label for="password">{{ Lang::get("content.password") }}*</label>
                                    <input placeholder="{{ Lang::get("content.password") }}" required name="password" type="password" id="password"/>

                                    <input name="termsAndConditions" id="checkbox" type="checkbox" value="Yes" class="validate[required]">
                                    <label class="terms-services" id="exp-termsServices" for="checkbox">{{ Lang::get("content.index00") }}</label>
                                    
                                    <button id="submit_freetrial" class="exp-Btn" type="submit">{{ Lang::get("content.createAccounth1") }}</button>

                                    <input id="timezone" type="hidden" name="timezone" class="inputboxmid" value="{{Input::old("timezone")}}" />

                                </fieldset>
                                 {{ Form::close() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

<!--.........................End Create Account Expandable ............................-->


<script>
    function showForm() {
     $(".exp-form").toggle();
     $("#exp-manualBTN").toggle();
 }

 </script>

 @endsection





