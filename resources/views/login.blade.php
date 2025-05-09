@extends('layouts.frontEnd')
@php
    use App\Http\Libraries\Helper;
@endphp
@section("header")
    {!! Helper::seo("login") !!}
@endsection
@section('content')
    <!-- Main -->
    <main class="accountPages">
        <div class="background"></div>
        <div class="wrapper accountRoot">
            <div class="topBlock">
                <h1>{{ __("content.frontEnd/welcomeBack") }} </h1>
                <h3>{{ __("content.frontEnd/thankyouTW") }}</h3>
            </div>
            <div class="accountAction_container">
                <form action="{{ __("routes./login") }}" method="post" id="login_form">
                    @csrf
{{--                    <a href="{{ __('routes./login/facebook') }}" class="facebook">{{ __("content.frontEnd/facebooklogin") }}</a>--}}
                    <a href="{{ route('auth.google',['role' => 'Trainer']) }}" class="login-with-google-btn" style="margin-top: 15px">Log In with Google</a>
                    <div class="accountOr">
                        <hr><span>or</span><hr>
                    </div>
                    <label for="email">{{ __("content.email") }}</label>
                    <input type="text" placeholder="{{ __('content.email') }}" value="{{ request()->old('email') }}" required name="email" id="email"/>
                    <label for="password">{{ __("content.password") }}</label>
                    <input placeholder="{{ __('content.password') }}" required name="password" type="password" id="password"/>
                    <a href="javascript:void(0)" onclick="submitForm()" class="submit login-btn" id="submitBtn">{{ __("content.Login") }}</a>
                    <a href="{{ __("/password/reset") }}" class="forgot_password">{{ __("content.forgot") }}</a>
                </form>
            </div>
        </div>
    </main>
@endsection

@section('scripts')
    <script type="text/javascript">
        function getEmail() {
            let request = indexedDB.open("trainer_workout", 1);

            request.onsuccess = function(event) {
                let db = event.target.result;

                // Check if the "users" object store exists
                if (!db.objectStoreNames.contains("users")) {
                    console.log("Object store 'users' does not exist. Deleting database...");
                    db.close(); // Close the database before deleting
                    let deleteRequest = indexedDB.deleteDatabase("trainer_workout");

                    deleteRequest.onsuccess = function() {
                        console.log("Database deleted successfully.");
                    };

                    deleteRequest.onerror = function(event) {
                        console.error("Error deleting database: ", event.target.error);
                    };
                    return; // Exit function since the object store doesn't exist
                }

                let transaction = db.transaction("users", "readonly");
                let store = transaction.objectStore("users");
                let getAllRequest = store.getAll();

                getAllRequest.onsuccess = function() {
                    if (getAllRequest.result.length > 0) {
                        let email = getAllRequest.result[getAllRequest.result.length - 1].email;
                        window.location.href = "{{ route('login-with-email') }}?email=" + email;
                    }
                };

                getAllRequest.onerror = function(event) {
                    console.error("Error retrieving data: ", event.target.error);
                };
            };

            request.onerror = function(event) {
                console.error("Error opening IndexedDB: ", event.target.error);
            };
        }

        function deleteIndexedDatabase() {
            let request = indexedDB.deleteDatabase("trainer_workout");
            request.onsuccess = function() {
                console.log("Database deleted successfully.");
            };
            request.onerror = function(event) {
                console.error("Error deleting database: ", event.target.error);
            };
            request.onblocked = function() {
                console.error("Database deletion blocked. Close all connections and try again.");
            };
        }

        // Call the function to get and alert the email
        @if(session()->has('clear_db'))
            deleteIndexedDatabase();
        @else
            getEmail();
        @endif

        function submitForm() {
            var valid = true;
            var password = $("#password").val();
            var email = $("#email").val();
            if (!validateEmail(email)) {
                valid = false;
                $("#email").addClass('error');
                $("#email_icon").css("color", "rgba(255,0,0,0.5)");
            } else {
                $("#email").removeClass('error');
                $("#email_icon").css("color", "#38AFDF");
            }
            if (password.length < 1) {
                valid = false;
                $("#password").addClass('error');
                $("#password_icon").css("color", "rgba(255,0,0,0.5)");
            } else {
                $("#password").removeClass('error');
                $("#password_icon").css("color", "#38AFDF");
            }
            if (valid) {
                document.getElementById('login_form').submit();
                $('#submitBtn').html(`<p id="033f09d5-f4f4-3b14-cc0c-aa611221bbd2" style="display: flex; margin:auto;padding: 0;padding-top: 5px;height: auto;width: 100%;align-items: center;justify-content: center;">
                                        <img src="{{asset('/assets/img/logos/LogoWhite.svg')}}" style="width: 40px;">
                                    </p>`);
            }
        }

        function validateEmail(email) {
            var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
            return re.test(email);
        }

        $(document).keypress(function (e) {
            if (e.which == 13) {
                submitForm();
            }
        });
    </script>
@endsection

<script async src="https://www.googletagmanager.com/gtag/js?id=G-ZZ4SCN3MPG"></script>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'G-ZZ4SCN3MPG');
</script>

<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-8026299207830445" crossorigin="anonymous"></script>
<script>
    function initializeAds() {
        return new Promise((resolve) => {
            if (window.afg) {
                if (window.afg.ready) {
                    resolve(true)
                } else {
                    resolve(false)
                }
                return;
            }

            window.adsbygoogle = window.adsbygoogle || [];
            var afg = {};

            afg.adBreak = window.adConfig = function (o) {
                adsbygoogle.push(o);
            };
            afg.ready = false;
            window.afg = afg;
            const onAdsReady = () => {
                window.afg.ready = true
                resolve(true)
            }
            try{
                if (!window.adConfigCalled) {
                    window.adConfigCalled = true;
                    adConfig({
                        preloadAdBreaks: 'on',
                        onReady: onAdsReady,
                    });
                } else {
                    console.log('Already set.');
                    onAdsReady();
                }
            }catch(e){
                console.log('>> Ad config already initilized')
                resolve(false);
            }
        })
    }

    initializeAds().then((isAdsReady) => {
        if (isAdsReady) {
            window.afg.adBreak({
                type: 'start',
                name: 'gamesdonut-ads',
                beforeAd: () => {
                    console.log('>> Before AD')
                },
                adBreakDone: () => {
                    console.log('>> After AD')
                }
            });
        }
    });
</script>
