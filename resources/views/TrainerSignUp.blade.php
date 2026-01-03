@extends('layouts.frontEnd')
@php
    use App\Http\Libraries\Helper;
@endphp
@section("header")
    {!! Helper::seo("trainerSignUp") !!}
@endsection
@section('content')
    <main class="accountPages">
        <div class="background"></div>
        <div class="wrapper accountRoot">
            <div class="topBlock">
                <h1>{{ Lang::get("content.frontEnd/welcome") }} </h1>
                <h3>{{ Lang::get("content.frontEnd/thankyouTW") }}</h3>
            </div>
            <div class="accountAction_container">
                {{ Form::open(array('url' => Lang::get("routes./Trainer/SignUp"), "id"=>"login_form")) }}
                @if(!empty(Helper::getDeviceTypeCookie()) && Helper::getDeviceTypeCookie() == 'ios')
                    <a href="javascript:void(0);" onclick="console.log('LOGIN_WITH_APPLE=true');console.log('USER_TYPE=trainer')" class="login-with-apple-btn" style="margin-top: 15px;">Signup In with Apple</a>
                    <p id="loaderGoogleButton"  style="margin:auto;padding: 6px;height: auto;width: 100%;align-items: center;justify-content: center;display: none">
                        <img src="{{ asset('assets/img/tw-gif.gif') }}" style="width: 40px;">
                    </p>
                @endif
                <p id="loaderGoogleButton"  style="margin:auto;padding: 6px;height: auto;width: 100%;align-items: center;justify-content: center;display: none">
                    <img src="{{ asset('assets/img/tw-gif.gif') }}" style="width: 40px;">
                </p>
                <a href="javascript:void(0);" onclick="redirectToGoogleLogin(this);" class="login-with-google-btn" style="margin-top: 15px">Signup In with Google</a>
                <div class="accountOr"><hr><span>or</span><hr></div>
                <label for="firstName">{{ Lang::get("content.First Name") }}</label>
                <input type="text" name="firstName" id="firstName" required placeholder="{{ Lang::get("content.First Name") }}" value="{{ request()->old("firstName") }}"/>
                <label for="lastName">{{ Lang::get("content.Last Name") }}</label>
                <input type="text" name="lastName" id="lastName" required placeholder="{{ Lang::get("content.Last Name") }}" value="{{ request()->old("lastName") }}"/>
                <label for="email">{{ Lang::get("content.Email") }}</label>
                <input type="text" name="email" id="email" required placeholder="{{ Lang::get("content.Email") }}" value="{{ request()->old("email") }}"/>
                <label for="password">{{ Lang::get("content.Password") }}</label>
                <input type="password" id="password" name="password" required placeholder="{{ Lang::get("content.Password") }}" value="{{ request()->old("password") }}"/>
                <input id="timezone" type="hidden" name="timezone" value="{{request()->old("timezone")}}"/>
                <input id="paid" type="hidden" name="paid" value="{{ isset($paid) ? "yes" : "" }}"/>
                <fieldset class="termsAndConditions term-condition-section">
                    <input name="termsAndConditions" id="terms" required type="checkbox" value="Yes">
                    <label for="terms">{{ Lang::get("content.I agree with the") }}
                        <a target="_blank" href="/TermsAndConditions/">{{ Lang::get("content.Terms and Conditions") }}</a>
                    </label>
                </fieldset>
                <button type="submit" class="submit" id="submitBtn">
                    {{ Lang::get("content.Create My Account") }}
                </button>
                <a href="{{ Lang::get("routes./login") }}" class="forgot_password">{{ Lang::get("content.Already have an account, log in") }}</a>
                {{ Form::close() }}
            </div>
        </div>
    </main>
@endsection

@section("scripts")

    <script type="text/javascript">
        function redirectToGoogleLogin(element) {
            $("#loaderGoogleButton").show().css('display', 'flex');
            window.location.href = "{{ route('auth.google',['role' => 'Trainer']) }}";
        }

        $(".button_orange").hide();

        $(document).keypress(function (e) {
            if (e.which == 13) {
                submitForm();
            }
        })

        $(document).on('submit','#login_form', function (){
            $('#submitBtn').html(`<p id="033f09d5-f4f4-3b14-cc0c-aa611221bbd2" style="display: flex; margin:auto;padding: 0;padding-top: 5px;height: auto;width: 100%;align-items: center;justify-content: center;">
                                        <img src="{{asset('/assets/img/logos/LogoWhite.svg')}}" style="width: 40px;">
                                    </p>`);
            })
        function getTimezoneName() {
            tmSummer = new Date(Date.UTC(2005, 6, 30, 0, 0, 0, 0));
            so = -1 * tmSummer.getTimezoneOffset();
            tmWinter = new Date(Date.UTC(2005, 12, 30, 0, 0, 0, 0));
            wo = -1 * tmWinter.getTimezoneOffset();

            if (-660 == so && -660 == wo) return 'Pacific/Midway';
            if (-600 == so && -600 == wo) return 'Pacific/Tahiti';
            if (-570 == so && -570 == wo) return 'Pacific/Marquesas';
            if (-540 == so && -600 == wo) return 'America/Adak';
            if (-540 == so && -540 == wo) return 'Pacific/Gambier';
            if (-480 == so && -540 == wo) return 'US/Alaska';
            if (-480 == so && -480 == wo) return 'Pacific/Pitcairn';
            if (-420 == so && -480 == wo) return 'US/Pacific';
            if (-420 == so && -420 == wo) return 'US/Arizona';
            if (-360 == so && -420 == wo) return 'US/Mountain';
            if (-360 == so && -360 == wo) return 'America/Guatemala';
            if (-360 == so && -300 == wo) return 'Pacific/Easter';
            if (-300 == so && -360 == wo) return 'US/Central';
            if (-300 == so && -300 == wo) return 'America/Bogota';
            if (-240 == so && -300 == wo) return 'America/New_York';
            if (-240 == so && -240 == wo) return 'America/Caracas';
            if (-240 == so && -180 == wo) return 'America/Santiago';
            if (-180 == so && -240 == wo) return 'Canada/Atlantic';
            if (-180 == so && -180 == wo) return 'America/Montevideo';
            if (-180 == so && -120 == wo) return 'America/Sao_Paulo';
            if (-150 == so && -210 == wo) return 'America/St_Johns';
            if (-120 == so && -180 == wo) return 'America/Godthab';
            if (-120 == so && -120 == wo) return 'America/Noronha';
            if (-60 == so && -60 == wo) return 'Atlantic/Cape_Verde';
            if (0 == so && -60 == wo) return 'Atlantic/Azores';
            if (0 == so && 0 == wo) return 'Africa/Casablanca';
            if (60 == so && 0 == wo) return 'Europe/London';
            if (60 == so && 60 == wo) return 'Africa/Algiers';
            if (60 == so && 120 == wo) return 'Africa/Windhoek';
            if (120 == so && 60 == wo) return 'Europe/Amsterdam';
            if (120 == so && 120 == wo) return 'Africa/Harare';
            if (180 == so && 120 == wo) return 'Europe/Athens';
            if (180 == so && 180 == wo) return 'Africa/Nairobi';
            if (240 == so && 180 == wo) return 'Europe/Moscow';
            if (240 == so && 240 == wo) return 'Asia/Dubai';
            if (270 == so && 210 == wo) return 'Asia/Tehran';
            if (270 == so && 270 == wo) return 'Asia/Kabul';
            if (300 == so && 240 == wo) return 'Asia/Baku';
            if (300 == so && 300 == wo) return 'Asia/Karachi';
            if (330 == so && 330 == wo) return 'Asia/Calcutta';
            if (345 == so && 345 == wo) return 'Asia/Katmandu';
            if (360 == so && 300 == wo) return 'Asia/Yekaterinburg';
            if (360 == so && 360 == wo) return 'Asia/Colombo';
            if (390 == so && 390 == wo) return 'Asia/Rangoon';
            if (420 == so && 360 == wo) return 'Asia/Almaty';
            if (420 == so && 420 == wo) return 'Asia/Bangkok';
            if (480 == so && 420 == wo) return 'Asia/Krasnoyarsk';
            if (480 == so && 480 == wo) return 'Australia/Perth';
            if (540 == so && 480 == wo) return 'Asia/Irkutsk';
            if (540 == so && 540 == wo) return 'Asia/Tokyo';
            if (570 == so && 570 == wo) return 'Australia/Darwin';
            if (570 == so && 630 == wo) return 'Australia/Adelaide';
            if (600 == so && 540 == wo) return 'Asia/Yakutsk';
            if (600 == so && 600 == wo) return 'Australia/Brisbane';
            if (600 == so && 660 == wo) return 'Australia/Sydney';
            if (630 == so && 660 == wo) return 'Australia/Lord_Howe';
            if (660 == so && 600 == wo) return 'Asia/Vladivostok';
            if (660 == so && 660 == wo) return 'Pacific/Guadalcanal';
            if (690 == so && 690 == wo) return 'Pacific/Norfolk';
            if (720 == so && 660 == wo) return 'Asia/Magadan';
            if (720 == so && 720 == wo) return 'Pacific/Fiji';
            if (720 == so && 780 == wo) return 'Pacific/Auckland';
            if (765 == so && 825 == wo) return 'Pacific/Chatham';
            if (780 == so && 780 == wo) return 'Pacific/Enderbury'
            if (840 == so && 840 == wo) return 'Pacific/Kiritimati';
            return 'US/Pacific';
        }

        $(document).ready(function () {
            var timezone = getTimezoneName();
            $("#timezone").val(timezone);
        })
    </script>

@endsection
