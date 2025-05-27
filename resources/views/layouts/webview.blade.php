<!doctype html>
<!--[if lt IE 7 ]>
<html class="ie ie6 no-js" lang="en">
<![endif]-->
<!--[if IE 7 ]>
<html class="ie ie7 no-js" lang="en">
<![endif]-->
<!--[if IE 8 ]>
<html class="ie ie8 no-js" lang="en">
<![endif]-->
<!--[if IE 9 ]>
<html class="ie ie9 no-js" lang="en">
<![endif]-->
<!--[if gt IE 9]><!-->
<html class="no-js" lang="en">
<!--<![endif]-->
@php
    use App\Http\Libraries\Helper;
@endphp
<head>
    <meta charset="utf-8">
    @yield("header")
    <!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <!--  Mobile Viewport Fix
       j.mp/mobileviewport & davidbcalhoun.com/2010/viewport-metatag
       device-width : Occupy full width of the screen in its current orientation
       initial-scale = 1.0 retains dimensions instead of zooming out if page height > device height
       maximum-scale = 1.0 retains dimensions instead of zooming in if page width < device width
       -->
    <meta name="author" content="trainer-workout.com"/>
    <meta name="robots" content="all"/>
    <meta name="distribution" content="global"/>
    <meta name="resource-type" content="document"/>
    <meta name="language" content="en-us"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="copyright" content="trainer-workout.com Copyright 2013. All Rights Reserved.">
    <meta name="viewport" content="initial-scale=1.0, user-scalable = no, width = device-width">
    <link rel="shortcut icon" href="{{asset('assets/img/favicon.ico')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/fw/addtohomescreen/style/addtohomescreen.css')}}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="apple-touch-icon" href="{{asset('assets/img/apple-touch-icon.png')}}">
    {{--
    <link rel="manifest" href="/manifest.json">
    --}}
    <link rel="apple-touch-icon-precomposed" sizes="57x57" href="{{asset('assets/img/apple-touch-icon.png')}}"/>
    <link rel="apple-touch-icon" sizes="57x57" href="{{asset('assets/img/apple-touch-icon.png')}}"/>
    <link rel="apple-touch-icon" sizes="72x72" href="{{asset('assets/img/apple-touch-icon.png')}}"/>
    <link rel="apple-touch-icon" sizes="114x114" href="{{asset('assets/img/apple-touch-icon.png')}}"/>
    <link rel="apple-touch-icon" sizes="144x144" href="{{asset('assets/img/apple-touch-icon.png')}}"/>
    <!-- The is the icon for iOS's web Clip.
       - size: 57x57 for older iPhones, 72x72 for iPads, 114x114 for iPhone4's retina display (IMHO, just go ahead and use the biggest one)
       - To prevent iOS from applying its styles to the icon name it thusly: apple-touch-icon-precomposed.png
       - Transparency is not recommended (iOS will put a black BG behind the icon) -->
    <meta name="google-site-verification" content="">
    <meta name="title" content="">
    <meta name="description" content="">
    <!-- Jquery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <!-- {{ HTML::script(asset('assets/js/jquery.js')) }} -->
    <!-- AJAX SETUP -->
    <script type="text/javascript">
        $.ajaxSetup({
            header: {
                'X-CSRF-Token' : $('meta[name="csrf-token"]').attr('content')
            }
        })
    </script>
    <!-- DataTable -->
    <?php
    if (isset($table)) {
        echo "<link media='all' type='text/css' rel='stylesheet'href='//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css'>";
    }
    ?>
    {{ HTML::style(asset('assets/fw/jquery-ui-1.11.1.custom/jquery-ui.min.css'.ASSET_VERSION)) }}
    {{ HTML::style(asset('assets/fw/datapicker/jquery.ui.timepicker.css'.ASSET_VERSION)) }}
    {{ HTML::style(asset('assets/libs/select2/select2.min.css').ASSET_VERSION) }}
    {{ HTML::script(asset('assets/js/modernizr_touch.js'.ASSET_VERSION)) }}
    @yield("headerScripts")
    <!-- Open Sans Font -->
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,700,800'
          rel='stylesheet' type='text/css'>
    @yield("headerExtra")
    {{ HTML::style(asset('assets/css/Trainer/mobileInnerstyle.css'.ASSET_VERSION)) }}
    {{ HTML::style(asset('assets/css/lang/styles_'.Config::get('app.locale').'.css'.ASSET_VERSION)) }}
    @if(Config::get("app.whitelabel") != "default")
            <?php $whitelabel = "ymca"; ?>
    @endif
    @if(Config::get("app.whitelabel") != "default")
        {!! HTML::style(Config::get("app.whitelabel_css_trainer")) !!}
    @endif
</head>
<body class="trainer">
    <div>
        <div class="systemMessages"></div>
        @yield("content")
    </div>
    <!-- End of O-wrapper -->

    <!-- /c-menu push-left -->
    <nav class="p-menu">
    </nav>

    <div id="c-mask" class="c-mask"></div>
    <!-- /c-mask -->
    <!-- End of Side Mneu -->
    <div class='loader-bg'>
        <img src='{{asset('assets/img/tw-gif.gif')}}'>
        <button onclick="hideTopLoader()" class="btn" style="color: #ffffff;position: absolute;right: 0;top: 0;padding: 10px;font-weight: bold;background: transparent;border: none;">X</button>
    </div>
</body>

<!-- Menu Toggleing JavaScript -->
{{ HTML::script(asset('assets/js/menu.js'.ASSET_VERSION)) }}
<!-- Bootstrap Core JavaScript -->
{{ HTML::script(asset('assets/lang/'.App::getLocale().'/jsWords.js'.ASSET_VERSION)) }}
{{ HTML::script(asset('assets/js/bootstrap.min.js'.ASSET_VERSION)) }}
{{ HTML::script(asset('assets/fw/ckeditor/ckeditor.js'.ASSET_VERSION)) }}
{{ HTML::script(asset('assets/fw/fancybox/source/jquery.fancybox.pack.js?v=2.1.5')) }}
{{ HTML::script(asset('assets/fw/datapicker/jquery.ui.timepicker.js'.ASSET_VERSION)) }}
{{ HTML::script(asset('assets/js/touchpunch.js'.ASSET_VERSION)) }}
{{ HTML::script(asset('assets/js/global.js'.ASSET_VERSION)) }}
{{ HTML::script(asset('assets/js/widgets.js'.ASSET_VERSION)) }}
{{ HTML::script(asset('assets/libs/select2/select2.min.js').ASSET_VERSION) }}
{{ HTML::script(asset('assets/fw/lightbox/js/lightbox.js'.ASSET_VERSION)) }}
{{ HTML::script(asset('assets/fw/addtohomescreen/src/addtohomescreen.js'.ASSET_VERSION)) }}
{{ HTML::script(asset('assets/templates/exerciseList.js'.ASSET_VERSION)) }}
{{ HTML::script(asset('assets/templates/workoutBuilder.js'.ASSET_VERSION)) }}
<!-- CHOSEN SELCT BOX -->
{{ HTML::script(asset('assets/fw/chosen_v1/chosen.jquery.js'.ASSET_VERSION)) }}
{{ HTML::script(asset('assets/fw/chosen_v1/docsupport/prism.js'.ASSET_VERSION)) }}
<script type="text/javascript">
    $(document).ready(function () {
        var config = {
            '.chosen-select': {search_contains: true},
            '.chosen-select-deselect': {allow_single_deselect: true},
            '.chosen-select-no-single': {disable_search_threshold: 10},
            '.chosen-select-no-results': {no_results_text: 'Oops, nothing found!'},
            '.chosen-select-width': {width: "200px;"}
        }
        for (var selector in config) {
            $(selector).chosen(config[selector]);
        }

        $('.select2-select').select2({
            searching: true,
            dropdownParent: $('body')
        })
    });
</script>
<!-- DataTables JavaScript -->
<!-- Page-Level Demo Scripts - Tables - Use for reference -->
<script type="text/javascript">
    /**
     * Push left instantiation and action.
     */
    var pushLeft = new Menu({
        wrapper: '#o-wrapper',
        type: 'push-left',
        menuOpenerClass: '.c-button',
        maskId: '#c-mask'
    });

    var pushLeftBtn = document.querySelector('#c-button--push-left');

    pushLeftBtn.addEventListener('click', function (e) {
        e.preventDefault;
        pushLeft.open();
    });

    function closeMenu() {
        pushLeft.close();
    }

    //Open and Close the feedback Form.
    function moveFeedbackUp() {
        closeMenu();
        $(".fb_overlay").addClass("fb_up");

    }

    function moveFeedbackDown() {
        closeMenu();
        $(".fb_overlay").removeClass("fb_up");
    }

    function deleteAccount() {
        Swal.fire({
            title: "Delete Profile",
            text: "Are You Sure You Want To Delete Your Account",
            icon: "warning",
            showCancelButton: !0,
            confirmButtonText: "Yes",
            cancelButtonText: "No",
            confirmButtonClass: "btn btn-danger mt-2 text-white rounded-pill px-4 fs-16",
            cancelButtonClass: "btn  ms-2 mt-2 border border-theme rounded-pill text-theme px-4 fs-16",
            buttonsStyling: !1,
        }).then(function (t) {
            if (t.value) {
                showTopLoader()
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
            } else {
                hideTopLoader()
            }
        });
    }
</script>
@yield('scripts')
@if($errors->any())
        <?php $message = ""; ?>
    @foreach ($errors->all() as $error)
        <?php $message .= $error . "</br>"; ?>
    @endforeach
    <script>errorMessage("{!! $message !!}")</script>
@endif
@if(Session::has("message"))
    <script>successMessage("{!! Session::get("message") !!}")</script>
    @php session()->forget('message') @endphp
@endif
@if(Session::has("error"))
    <script>errorMessage("{!! Session::get("error") !!}")</script>
@endif
<script>
    @if(Config::get("app.debug"))
        debug = true;
    @else
        debug = false;
    @endif
</script>
<?php
function echoSelectedClassIfRequestMatches($requestUri)
{
    $current_file_name = basename($_SERVER['REQUEST_URI'], ".php");
    if ($current_file_name == $requestUri)
        echo 'selected';
}
?>
@if(!Config::get("app.debug"))
    @php $isDesktop = \Jenssegers\Agent\Facades\Agent::isDesktop(); @endphp
    @if(!$isDesktop)
        <script>
            var Tawk_API = Tawk_API || {};
            Tawk_API.onLoad = function() {
                Tawk_API.hideWidget();
            };
        </script>
    @endif
    <script type="text/javascript">
        //Check if location is on pages we do not want to show the launcher
        var onPage = false;
        if ((window.location.href.indexOf("CreateWorkout") > -1) || (window.location.href.indexOf("CreerWorkout") > -1)) {
            onPage = true;
        } else {
            onPage = false;
        }
    </script>
    <script>
        {{--// window.intercomSettings = {--}}
        {{--//   //The current logged in user's full name--}}
        {{--//   name: "{{{ Auth::user()->getCompleteName() }}}",--}}
        {{--//   //The current logged in user's email address.--}}
        {{--//   email: "{{{ Auth::user()->email }}}",--}}
        {{--//   user_id: "{{{ Auth::user()->id }}}",--}}
        {{--//   //The current logged in user's sign-up date as a Unix timestamp.--}}
        {{--//   created_at: {{ Helper::dateToUnix(Auth::user()->created_at) }},--}}
        {{--//   //UserType--}}
        {{--//   "userType": "trainer",--}}
        {{--//   //Language--}}
        {{--//   "lang": "{{{ Auth::user()->lang }}}",--}}
        {{--//   //Memberhsip type--}}
        {{--//   "membership_plan": "{{{ (Auth::user()->getTrainerWorkoutMembership() and Auth::user()->getTrainerWorkoutMembership()->membership) ? Auth::user()->getTrainerWorkoutMembership()->membership->name : "" }}}",--}}
        {{--//   // Nb of clients--}}
        {{--//   "number_of_clients": "{{{ Auth::user()->getNumberOfClients() }}}",--}}
        {{--//   //Nb of workouts in account--}}
        {{--//   "number_of_workouts": "{{{ Auth::user()->getNumberOfWorkouts() }}}",--}}
        {{--//   //Nb of exercise created--}}
        {{--//   "number_of_exercises": "{{{ Auth::user()->getNumberOfExercises() }}}",--}}

        {{--//   // HIDE INTERCOM CHAT ICON ON Create Workout page--}}
        {{--//   "hide_default_launcher": onPage,--}}

        {{--//   //Where did the user come from--}}
        {{--//   <?php--}}
        {{--         //     $params = explode("&",Auth::user()->marketing);--}}
        {{--         //     foreach($params as $param){--}}
        {{--         //       $pa = explode("=",$param);--}}
        {{--         //       if($pa > 1 and count($pa) > 1){--}}
        {{--         //         echo '"'.$pa[0].'":"'.$pa[1].'",';--}}
        {{--         //       }--}}
        {{--         //     }--}}
        {{--         //     ?>--}}
        {{--//   //Our app ID--}}
        {{--//   app_id: "af0obxyk",--}}
        {{--//   //Class click that launches our intercom messenger--}}
        {{--//   custom_launcher_selector: '#intercomWindow',--}}
        {{--// };--}}

            var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
 (function(){
 var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
 s1.async=true;
 s1.src='https://embed.tawk.to/67c8619b8857401908652631/1iljbqier';
 s1.charset='UTF-8';
 s1.setAttribute('crossorigin','*');
 s0.parentNode.insertBefore(s1,s0);
 })();
    </script>
    <!--End of Tawk.to Script-->
    <script>
        @if(Auth::check())
            Tawk_API.visitor = {
            name: '{{ Auth::user()->getCompleteName() }}',
            email: '{{ Auth::user()->email }}'
        };
        @endif
    </script>
    <script>
        (function () {
            var w = window;
            var ic = w.Intercom;
            if (typeof ic === "function") {
                ic('reattach_activator');
                ic('update', intercomSettings);
            } else {
                var d = document;
                var i = function () {
                    i.c(arguments)
                };
                i.q = [];
                i.c = function (args) {
                    i.q.push(args)
                };
                w.Intercom = i;

                function l() {
                    var s = d.createElement('script');
                    s.type = 'text/javascript';
                    s.async = true;
                    s.src = 'https://widget.intercom.io/widget/af0obxyk';
                    var x = d.getElementsByTagName('script')[0];
                    x.parentNode.insertBefore(s, x);
                }

                if (w.attachEvent) {
                    w.attachEvent('onload', l);
                } else {
                    w.addEventListener('load', l, false);
                }
            }
        })()
    </script>
    <!-- Facebook Pixel Code -->
    <script>
        !function (f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function () {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window,
            document, 'script', 'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '400688060094236', {
            em: '{{{ Auth::user()->email }}}'
        });
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
                   src="https://www.facebook.com/tr?id=400688060094236&ev=PageView&noscript=1"/></noscript>
    <!-- DO NOT MODIFY -->
    <!-- End Facebook Pixel Code -->
    {{ HTML::script(asset('assets/js/thirdParty.js')) }}
@endif
<script>
    $(document).ready(function () {
        addToHomescreen({
            startDelay: 5,
            skipFirstVisit: false,
            maxDisplayCount: 1,
        });
    });

    function openDatabase() {
        return new Promise((resolve, reject) => {
            let request = indexedDB.open("trainer_workout", 1);

            request.onupgradeneeded = function(event) {
                let db = event.target.result;
                if (!db.objectStoreNames.contains("users")) {
                    db.createObjectStore("users", { keyPath: "id", autoIncrement: true });
                }
            };
            request.onsuccess = function(event) {
                resolve(event.target.result);
            };
            request.onerror = function(event) {
                reject("Error opening IndexedDB: " + event.target.errorCode);
            };
        });
    }

    function storeEmail(email) {
        openDatabase().then(db => {
            let transaction = db.transaction("users", "readwrite");
            let store = transaction.objectStore("users");
            let clearRequest = store.clear();
            clearRequest.onsuccess = function() {
                let addRequest = store.add({ email: email });
                addRequest.onsuccess = function() {
                    console.log("Email stored successfully.");
                };
                addRequest.onerror = function(event) {
                    console.error("Error storing email: ", event.target.error);
                };
            };
            clearRequest.onerror = function(event) {
                console.error("Error clearing object store: ", event.target.error);
            };
        }).catch(error => console.error(error));
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

    // Example usage:
    storeEmail("{{auth()->user()->email}}");
</script>
</html>
