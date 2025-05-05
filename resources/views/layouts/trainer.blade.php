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
    @vite(['resources/css/app.scss', 'resources/js/app.js'])

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
    {{-- Diego Test --}}


    @if(Config::get("app.whitelabel") != "default")
            <?php $whitelabel = "ymca"; ?>
    @endif
    @if(Config::get("app.whitelabel") != "default")
        {!! HTML::style(Config::get("app.whitelabel_css_trainer")) !!}
    @endif
    <style>
        .select2-container{
            width: 100% !important;
        }
    </style>
</head>
<body class="trainer">
    <div id="o-wrapper" class="o-wrapper">
        <div class="systemMessages"></div>
        <div class="main_header" id="main_header">
            <div class="navContentContainer">
                <nav>
                    <div class="navItem navBurger" id="c-button--push-left">
                        <svg width="39" height="35" viewBox="0 0 39 25" xmlns="https://www.w3.org/2000/svg">
                            <title>
                                Mobile Menu Icon
                            </title>
                            <g transform="translate(2)" fill="#FFF" fill-rule="evenodd">
                                <rect width="35" height="3" rx="2"/>
                                <rect y="18" width="35" height="3" rx="2"/>
                                <rect y="9" width="35" height="3" rx="2"/>
                            </g>
                        </svg>
                    </div>
                    <div class="navItem" id="headerLogo">
                        <img src="{{asset('assets/')}}{{ Config::get("app.logo_header") }}"/>
                    </div>
                    <div class="navItem navOptions">
                        @if((Auth::user()->membership && Auth::user()->membership->membershipId == Config::get("constants.defaultMembership")) or (!Auth::user()->membership))
                            <form action="{{ Lang::get("routes./UpgradePlan") }}">
                                <button type="submit" value="Upgrade">{{ Lang::get("content.upgrade") }}</button>
                            </form>
                        @endif
                        <div class="navLang">
                            <a href="/lang/en">{!! (App::getLocale() == "en") ? "<strong>EN</strong>" : "EN" !!}</a>
                            <span>|</span>
                            <a href="/lang/fr">{!! (App::getLocale() == "fr") ? "<strong>FR</strong>" : "FR" !!}</a>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
        @if(Session::has("originalUser"))
            <div class="exitAccess">
                <div class="upgradePlan">
                    <a id="exitAccess" href="{{ Lang::get('routes./Trainer/EmployeeManagement/PersonifyBack') }}">
                        <svg width="16" height="16" viewBox="0 0 16 16" xmlns="https://www.w3.org/2000/svg">
                            <title>
                                Exit Employee View
                            </title>
                            <path
                                d="M11.255 4.235c0 .126.048.25.143.347l2.93 2.928H4.243a.49.49 0 0 0 0 .98h10.083l-2.938 2.938a.49.49 0 0 0 0 .693c.19.193.5.193.692 0l3.775-3.773a.498.498 0 0 0 .106-.16c0-.003.002-.006.003-.008a.485.485 0 0 0 0-.36l-.003-.006a.495.495 0 0 0-.107-.16L12.09 3.888a.49.49 0 0 0-.835.345M8.49 15.51a.49.49 0 0 0-.49-.49H.98V.98H8A.49.49 0 0 0 8 0H.49A.49.49 0 0 0 0 .49v15.02c0 .27.22.49.49.49H8c.27 0 .49-.22.49-.49"
                                fill="#FFF" fill-rule="evenodd"/>
                        </svg>
                        {!! Lang::get("content.exitEmployee") !!}
                    </a>
                </div>
            </div>
        @endif
        @yield("content")
    </div>
    <!-- End of O-wrapper -->
    <!-- SIDE MENU  Needs to stay below FOOTER and outsode of O-wrapper  -->
    <nav id="c-menu--push-left" class="c-menu c-menu--push-left">
        <svg class="c-menu__close" width="15" height="15" viewBox="0 0 15 15" xmlns="https://www.w3.org/2000/svg">
            <title>
                Close Icon
            </title>
            <path class="closeIcon"
                  d="M7.5 4.865L3.536.9a1.874 1.874 0 0 0-2.65 2.65L4.85 7.516.916 11.45a1.874 1.874 0 1 0 2.65 2.65L7.5 10.166l3.934 3.934a1.874 1.874 0 1 0 2.65-2.65L10.15 7.514l3.965-3.964A1.874 1.874 0 0 0 11.465.9L7.5 4.865z"
                  fill-opacity=".52" fill="#FFF" fill-rule="evenodd"/>
        </svg>
        <div class="profile">
            <img src="/{{ Helper::image(Auth::user()->thumb) }}" alt="profile image">
            <h2>{!! Auth::user()->firstName !!}</h2>
            <h4>{!! Auth::user()->lastName !!}</h4>
        </div>
        <ul class="c-menu__items">
            <div class="menu-section">
                <li class="c-menu__item">
                    <a href="{{ Lang::get("routes./Trainer/Workouts")."/".Helper::formatURLString(Auth::user()->firstName.Auth::user()->lastName) }}" class="c-menu__link menu_workouts">{{ Lang::get("content.TWorkouts") }}</a>
                </li>
                <li class="c-menu__item">
                    <a href="{{ Lang::get("routes./Trainer/Exercises") }}" class="c-menu__link menu_exercises">{{ Lang::get("content.TExercises") }}</a>
                </li>
            </div>
            <div class="menu-section">
                <li class="c-menu__item">
                    <a href="{{ Lang::get("routes./Trainer/Clients") }}" class="c-menu__link menu_clients">{{ Lang::get("content.TClients") }}</a>
                </li>
                <li class="c-menu__item">
                    <a href="{{ Lang::get('routes./Trainer/Reports/WorkoutsPerformanceClients') }}" class="c-menu__link menu_clientsReports">{{ Lang::get("content.TClientReport") }}</a>
                </li>
                <li class="c-menu__item">
                    <a href="{{ Lang::get('routes./Trainer/Plans') }}" class="c-menu__link menu_myPlans">{{ Lang::get("content.MyPlans") }}</a>
                </li>
            </div>
            
            @if(Auth::user()->group and (Auth::user()->group->role == "Owner" or Auth::user()->group->role == "Admin"))
            <div class="menu-section">
                <li class="c-menu__item">
                    <a href="{{ Lang::get("routes./employeeManagement") }}" class="c-menu__link menu_employee">{{ Lang::get("content.EmployeeManagement") }}</a>
                </li>
            </div>
            @endif
            <div class="menu-section">
                <li class="c-menu__item">
                    <a href="{{ Lang::get("routes./Trainer/Profile") }}" class="c-menu__link menu_profile">{{ Lang::get("content.TProfile") }}</a>
                </li>
                @if(!(Auth::user()->group and Auth::user()->group->role == "Member"))
                    <li class="c-menu__item">
                        <a href="{{ Lang::get("routes./MembershipManagement") }}" class="c-menu__link menu_membership">{{ Lang::get("content.TMembership") }}</a>
                    </li>
                @endif
            </div>
            <div class="menu-section">
                <li class="c-menu__item">
                    <a href="javascript:void(0);" onclick="moveFeedbackUp();" class="c-menu__link">{{ Lang::get("content.CMSendFeedback") }}</a>
                </li>
              
                <li class="c-menu__item logout">
                    <a onclick="deleteIndexedDatabase();" href="{{ Lang::get("routes./logout") }}" class="c-menu__link">{{ Lang::get("content.Logout") }}</a>
                </li>
            </div>
        </ul>
        <div class="mx-auto flex  gap-8 justify-center mt-4">
            <div>
                <div class="mb-3">
                    <a href="{{ config('constants.PLAYSTORE_URL') }}">
                        <svg width="120" height="40" viewBox="0 0 120 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="0.5" y="0.5" width="119" height="39" rx="5.5" fill="white"/>
                            <rect x="0.5" y="0.5" width="119" height="39" rx="5.5" stroke="black"/>
                            <path d="M17.8048 19.4617L8.0896 30.0059C8.09051 30.0078 8.09051 30.0106 8.09142 30.0125C8.38981 31.1574 9.41179 32 10.6254 32C11.1108 32 11.5662 31.8656 11.9567 31.6305L11.9877 31.6118L22.9229 25.1593L17.8048 19.4617Z" fill="#EA4335"/>
                            <path d="M27.6331 17.6662L27.624 17.6597L22.9028 14.8612L17.5839 19.7013L22.9219 25.1582L27.6176 22.3878C28.4406 21.9324 29 21.045 29 20.0223C29 19.0052 28.4489 18.1225 27.6331 17.6662Z" fill="#FBBC04"/>
                            <path d="M8.08942 9.99331C8.03102 10.2135 8 10.4449 8 10.6838V29.3163C8 29.5552 8.03102 29.7866 8.09034 30.0059L18.1386 19.7313L8.08942 9.99331Z" fill="#4285F4"/>
                            <path d="M17.8766 19.9999L22.9044 14.8594L11.9819 8.38351C11.585 8.13996 11.1214 8 10.626 8C9.41237 8 8.38856 8.84447 8.09018 9.99034C8.09018 9.99127 8.08926 9.9922 8.08926 9.99314L17.8766 19.9999Z" fill="#34A853"/>
                            <path d="M43.61 11.71C43.61 12.71 43.3133 13.5067 42.72 14.1C42.0533 14.8067 41.1767 15.16 40.09 15.16C39.05 15.16 38.17 14.8 37.45 14.08C36.73 13.36 36.37 12.4733 36.37 11.42C36.37 10.3667 36.73 9.48 37.45 8.76C38.17 8.04 39.05 7.68 40.09 7.68C40.6167 7.68 41.1133 7.77333 41.58 7.96C42.0467 8.14667 42.43 8.41 42.73 8.75L42.07 9.41C41.85 9.14333 41.5633 8.93667 41.21 8.79C40.8633 8.63667 40.49 8.56 40.09 8.56C39.31 8.56 38.65 8.83 38.11 9.37C37.5767 9.91667 37.31 10.6 37.31 11.42C37.31 12.24 37.5767 12.9233 38.11 13.47C38.65 14.01 39.31 14.28 40.09 14.28C40.8033 14.28 41.3967 14.08 41.87 13.68C42.3433 13.28 42.6167 12.73 42.69 12.03H40.09V11.17H43.56C43.5933 11.3567 43.61 11.5367 43.61 11.71ZM48.9078 7.84V8.72H45.6478V10.99H48.5878V11.85H45.6478V14.12H48.9078V15H44.7278V7.84H48.9078ZM52.5877 8.72V15H51.6677V8.72H49.6677V7.84H54.5877V8.72H52.5877ZM58.6654 15H57.7454V7.84H58.6654V15ZM62.5487 8.72V15H61.6287V8.72H59.6287V7.84H64.5487V8.72H62.5487ZM74.521 11.42C74.521 12.48 74.1677 13.3667 73.461 14.08C72.7477 14.8 71.8743 15.16 70.841 15.16C69.801 15.16 68.9277 14.8 68.221 14.08C67.5143 13.3667 67.161 12.48 67.161 11.42C67.161 10.36 67.5143 9.47333 68.221 8.76C68.9277 8.04 69.801 7.68 70.841 7.68C71.881 7.68 72.7543 8.04333 73.461 8.77C74.1677 9.48333 74.521 10.3667 74.521 11.42ZM68.101 11.42C68.101 12.2467 68.361 12.93 68.881 13.47C69.4077 14.01 70.061 14.28 70.841 14.28C71.621 14.28 72.271 14.01 72.791 13.47C73.3177 12.9367 73.581 12.2533 73.581 11.42C73.581 10.5867 73.3177 9.90333 72.791 9.37C72.271 8.83 71.621 8.56 70.841 8.56C70.061 8.56 69.4077 8.83 68.881 9.37C68.361 9.91 68.101 10.5933 68.101 11.42ZM76.5267 15H75.6067V7.84H76.7267L80.2067 13.41H80.2467L80.2067 12.03V7.84H81.1267V15H80.1667L76.5267 9.16H76.4867L76.5267 10.54V15Z" fill="black"/>
                            <path d="M93.5181 31.4097H95.1469V20.3981H93.5181V31.4097ZM108.189 24.3646L106.322 29.1388H106.266L104.328 24.3646H102.573L105.479 31.0371L103.823 34.749H105.521L110 24.3646H108.189ZM98.9519 30.1588C98.4176 30.1588 97.6739 29.8902 97.6739 29.2234C97.6739 28.3742 98.6001 28.0483 99.4005 28.0483C100.116 28.0483 100.454 28.2042 100.889 28.4165C100.762 29.4365 99.892 30.1588 98.9519 30.1588ZM99.1483 24.1241C97.969 24.1241 96.7469 24.6482 96.2424 25.8101L97.6879 26.4188C97.9969 25.8101 98.5721 25.611 99.1762 25.611C100.019 25.611 100.875 26.121 100.889 27.0283V27.1411C100.594 26.971 99.9627 26.7165 99.1902 26.7165C97.632 26.7165 96.0451 27.5806 96.0451 29.1952C96.0451 30.6689 97.323 31.6184 98.7546 31.6184C99.8501 31.6184 100.454 31.1225 100.833 30.5411H100.889V31.3912H102.461V27.1692C102.461 25.2146 101.015 24.1241 99.1483 24.1241ZM89.0821 25.7053H86.7655V21.9308H89.0821C90.2998 21.9308 90.9911 22.9482 90.9911 23.8176C90.9911 24.6711 90.2998 25.7053 89.0821 25.7053ZM89.0402 20.3981H85.1375V31.4097H86.7655V27.2379H89.0402C90.8453 27.2379 92.6199 25.9184 92.6199 23.8176C92.6199 21.7168 90.8453 20.3981 89.0402 20.3981ZM67.7583 30.1606C66.6332 30.1606 65.6913 29.2102 65.6913 27.9047C65.6913 26.5852 66.6332 25.6198 67.7583 25.6198C68.8695 25.6198 69.7406 26.5852 69.7406 27.9047C69.7406 29.2102 68.8695 30.1606 67.7583 30.1606ZM69.6289 24.9812H69.5722C69.2064 24.5417 68.5038 24.1444 67.6178 24.1444C65.7611 24.1444 64.0599 25.7898 64.0599 27.9047C64.0599 30.0047 65.7611 31.6369 67.6178 31.6369C68.5038 31.6369 69.2064 31.2396 69.5722 30.7851H69.6289V31.3251C69.6289 32.7582 68.8695 33.5246 67.6457 33.5246C66.6471 33.5246 66.0282 32.8005 65.7751 32.1901L64.3549 32.7864C64.7626 33.78 65.8458 35 67.6457 35C69.5582 35 71.1757 33.8646 71.1757 31.0978V24.3708H69.6289V24.9812ZM72.3008 31.4097H73.9323V20.3973H72.3008V31.4097ZM76.3362 27.777C76.2943 26.3298 77.4474 25.5916 78.2766 25.5916C78.9243 25.5916 79.4725 25.9176 79.6549 26.3862L76.3362 27.777ZM81.3989 26.528C81.0899 25.6912 80.1472 24.1444 78.2208 24.1444C76.3083 24.1444 74.7196 25.6621 74.7196 27.8907C74.7196 29.9906 76.2943 31.6369 78.4032 31.6369C80.1053 31.6369 81.0899 30.5869 81.4976 29.9765L80.2319 29.1247C79.8103 29.7493 79.2333 30.1606 78.4032 30.1606C77.5739 30.1606 76.983 29.7774 76.6033 29.0261L81.5674 26.9534L81.3989 26.528ZM41.8501 25.2939V26.883H45.6184C45.5058 27.777 45.2107 28.4297 44.7612 28.8834C44.2121 29.4374 43.3541 30.0479 41.8501 30.0479C39.5291 30.0479 37.7152 28.1602 37.7152 25.8189C37.7152 23.4767 39.5291 21.5899 41.8501 21.5899C43.1018 21.5899 44.0157 22.0867 44.6905 22.7254L45.8017 21.604C44.8589 20.6959 43.6081 20 41.8501 20C38.6719 20 36 22.6117 36 25.8189C36 29.0261 38.6719 31.6369 41.8501 31.6369C43.5653 31.6369 44.8589 31.0688 45.8715 30.0047C46.9129 28.9547 47.2358 27.4793 47.2358 26.2866C47.2358 25.9176 47.2079 25.5775 47.1512 25.2939H41.8501ZM51.5208 30.1606C50.3957 30.1606 49.425 29.2243 49.425 27.8907C49.425 26.5421 50.3957 25.6198 51.5208 25.6198C52.6451 25.6198 53.6158 26.5421 53.6158 27.8907C53.6158 29.2243 52.6451 30.1606 51.5208 30.1606ZM51.5208 24.1444C49.4669 24.1444 47.7936 25.7194 47.7936 27.8907C47.7936 30.0479 49.4669 31.6369 51.5208 31.6369C53.5739 31.6369 55.2472 30.0479 55.2472 27.8907C55.2472 25.7194 53.5739 24.1444 51.5208 24.1444ZM59.65 30.1606C58.5249 30.1606 57.5542 29.2243 57.5542 27.8907C57.5542 26.5421 58.5249 25.6198 59.65 25.6198C60.7752 25.6198 61.745 26.5421 61.745 27.8907C61.745 29.2243 60.7752 30.1606 59.65 30.1606ZM59.65 24.1444C57.597 24.1444 55.9237 25.7194 55.9237 27.8907C55.9237 30.0479 57.597 31.6369 59.65 31.6369C61.7031 31.6369 63.3764 30.0479 63.3764 27.8907C63.3764 25.7194 61.7031 24.1444 59.65 24.1444Z" fill="black"/>
                        </svg>
                    </a>
                </div>
                <div>
                    <a href="{{ config('constants.APPSTORE_URL') }}">
                        <svg width="120" height="40" viewBox="0 0 120 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="0.5" y="0.5" width="119" height="39" rx="5.5" fill="white"/>
                            <rect x="0.5" y="0.5" width="119" height="39" rx="5.5" stroke="black"/>
                            <path d="M24.7045 20.7631C24.7166 19.8432 24.9669 18.9413 25.4321 18.1412C25.8972 17.3411 26.5621 16.6688 27.3648 16.187C26.8548 15.476 26.1821 14.8908 25.4 14.478C24.6178 14.0652 23.7479 13.8361 22.8592 13.809C20.9635 13.6147 19.1258 14.9164 18.1598 14.9164C17.1751 14.9164 15.6878 13.8283 14.0862 13.8604C13.0502 13.8931 12.0406 14.1872 11.1557 14.7141C10.2708 15.241 9.54075 15.9827 9.03674 16.8669C6.85352 20.5573 8.48201 25.9809 10.5734 28.964C11.6197 30.4247 12.8426 32.0564 14.4428 31.9985C16.0086 31.9351 16.5934 31.0237 18.4835 31.0237C20.3561 31.0237 20.9048 31.9985 22.5374 31.9617C24.2176 31.9351 25.2762 30.4945 26.2859 29.02C27.0377 27.9792 27.6162 26.8288 28 25.6116C27.0238 25.2085 26.1908 24.5338 25.6048 23.6716C25.0187 22.8094 24.7056 21.7979 24.7045 20.7631Z" fill="black"/>
                            <path d="M21.6208 11.8471C22.5369 10.7734 22.9883 9.39335 22.879 8C21.4793 8.14352 20.1865 8.7966 19.258 9.82911C18.804 10.3335 18.4563 10.9203 18.2348 11.556C18.0132 12.1917 17.9222 12.8638 17.9669 13.5338C18.6669 13.5408 19.3595 13.3927 19.9924 13.1005C20.6253 12.8084 21.1821 12.3798 21.6208 11.8471Z" fill="black"/>
                            <path d="M36.791 8.50146H38.8608C40.6494 8.50146 41.5195 9.56494 41.5195 11.4941C41.5195 13.4233 40.6406 14.5 38.8608 14.5H36.791V8.50146ZM37.7886 9.34082V13.6606H38.751C39.9375 13.6606 40.4956 12.9443 40.4956 11.5073C40.4956 10.0615 39.9331 9.34082 38.751 9.34082H37.7886ZM44.6748 9.77588C45.8877 9.77588 46.7358 10.5625 46.7358 11.8677V12.4697C46.7358 13.8188 45.8877 14.5791 44.6748 14.5791C43.4443 14.5791 42.605 13.8276 42.605 12.4741V11.8721C42.605 10.6021 43.4575 9.77588 44.6748 9.77588ZM44.6792 10.5625C43.9849 10.5625 43.5894 11.1426 43.5894 11.9204V12.439C43.5894 13.2168 43.9585 13.7925 44.6792 13.7925C45.3911 13.7925 45.7559 13.2212 45.7559 12.439V11.9204C45.7559 11.1426 45.3735 10.5625 44.6792 10.5625ZM53.8989 9.85498L52.6772 14.5H51.6841L50.7129 11.0723H50.6865L49.7329 14.5H48.7354L47.4609 9.85498H48.5112L49.2583 13.397H49.3022L50.2383 9.85498H51.1567L52.1191 13.397H52.1631L52.9233 9.85498H53.8989ZM54.8657 14.5V9.85498H55.8237V10.6899H55.8721C55.9907 10.3252 56.3291 9.78467 57.2695 9.78467C58.2056 9.78467 58.834 10.3032 58.834 11.3623V14.5H57.8584V11.6479C57.8584 10.9404 57.4893 10.6152 56.9399 10.6152C56.2192 10.6152 55.8413 11.1689 55.8413 11.9204V14.5H54.8657ZM60.3105 14.5V8.18506H61.2861V14.5H60.3105ZM64.6348 9.77588C65.8477 9.77588 66.6958 10.5625 66.6958 11.8677V12.4697C66.6958 13.8188 65.8477 14.5791 64.6348 14.5791C63.4043 14.5791 62.5649 13.8276 62.5649 12.4741V11.8721C62.5649 10.6021 63.4175 9.77588 64.6348 9.77588ZM64.6392 10.5625C63.9448 10.5625 63.5493 11.1426 63.5493 11.9204V12.439C63.5493 13.2168 63.9185 13.7925 64.6392 13.7925C65.3511 13.7925 65.7158 13.2212 65.7158 12.439V11.9204C65.7158 11.1426 65.3335 10.5625 64.6392 10.5625ZM69.2227 14.5703C68.3218 14.5703 67.7021 14.0166 67.7021 13.1509C67.7021 12.3291 68.2734 11.7754 69.3457 11.7754H70.519V11.3403C70.519 10.8086 70.1807 10.5581 69.6445 10.5581C69.1172 10.5581 68.8799 10.7778 68.8052 11.0854H67.8779C67.9351 10.3076 68.5195 9.78467 69.6753 9.78467C70.6685 9.78467 71.4902 10.1978 71.4902 11.3535V14.5H70.563V13.8979H70.519C70.3125 14.2539 69.9082 14.5703 69.2227 14.5703ZM69.5259 13.8145C70.0796 13.8145 70.519 13.4365 70.519 12.9312V12.4302H69.4995C68.9326 12.4302 68.6821 12.7158 68.6821 13.1025C68.6821 13.5859 69.0864 13.8145 69.5259 13.8145ZM74.4961 9.79346C75.1509 9.79346 75.6519 10.0835 75.832 10.5537H75.8804V8.18506H76.856V14.5H75.9067V13.7573H75.8584C75.7178 14.2275 75.1597 14.5615 74.4829 14.5615C73.415 14.5615 72.7207 13.8013 72.7207 12.5752V11.7798C72.7207 10.5537 73.4282 9.79346 74.4961 9.79346ZM74.7686 10.5933C74.1182 10.5933 73.7139 11.0767 73.7139 11.9204V12.4302C73.7139 13.2783 74.1226 13.7617 74.7905 13.7617C75.4497 13.7617 75.8804 13.2827 75.8804 12.4917V11.7886C75.8804 11.0723 75.4102 10.5933 74.7686 10.5933ZM82.2129 9.77588C83.4258 9.77588 84.2739 10.5625 84.2739 11.8677V12.4697C84.2739 13.8188 83.4258 14.5791 82.2129 14.5791C80.9824 14.5791 80.1431 13.8276 80.1431 12.4741V11.8721C80.1431 10.6021 80.9956 9.77588 82.2129 9.77588ZM82.2173 10.5625C81.5229 10.5625 81.1274 11.1426 81.1274 11.9204V12.439C81.1274 13.2168 81.4966 13.7925 82.2173 13.7925C82.9292 13.7925 83.2939 13.2212 83.2939 12.439V11.9204C83.2939 11.1426 82.9116 10.5625 82.2173 10.5625ZM85.5308 14.5V9.85498H86.4888V10.6899H86.5371C86.6558 10.3252 86.9941 9.78467 87.9346 9.78467C88.8706 9.78467 89.499 10.3032 89.499 11.3623V14.5H88.5234V11.6479C88.5234 10.9404 88.1543 10.6152 87.605 10.6152C86.8843 10.6152 86.5063 11.1689 86.5063 11.9204V14.5H85.5308ZM93.2739 9.88574V8.72559H94.2275V9.88574H95.269V10.6504H94.2275V13.1157C94.2275 13.6211 94.4165 13.7617 94.9395 13.7617C95.0713 13.7617 95.2471 13.7529 95.3218 13.7441V14.4912C95.2427 14.5044 94.9175 14.5308 94.6978 14.5308C93.5684 14.5308 93.2607 14.1265 93.2607 13.1948V10.6504H92.5532V9.88574H93.2739ZM96.4995 14.5V8.18506H97.4707V10.6899H97.519C97.6201 10.3604 97.998 9.78467 98.9297 9.78467C99.835 9.78467 100.481 10.3076 100.481 11.3667V14.5H99.5098V11.6523C99.5098 10.9448 99.1187 10.6152 98.5649 10.6152C97.8662 10.6152 97.4707 11.1646 97.4707 11.9204V14.5H96.4995ZM103.755 14.5791C102.489 14.5791 101.703 13.8013 101.703 12.4917V11.8633C101.703 10.5449 102.564 9.77588 103.698 9.77588C104.862 9.77588 105.684 10.5845 105.684 11.8633V12.4082H102.669V12.6367C102.669 13.3047 103.065 13.7969 103.75 13.7969C104.26 13.7969 104.612 13.5552 104.678 13.2651H105.631C105.574 13.8013 105.007 14.5791 103.755 14.5791ZM102.669 11.771H104.73V11.7095C104.73 11.0107 104.322 10.5449 103.702 10.5449C103.083 10.5449 102.669 11.0107 102.669 11.7095V11.771Z" fill="black"/>
                            <path d="M38.2061 30.5H36.1758L40.3066 18.5029H42.5391L46.6611 30.5H44.5518L43.4883 27.1777H39.2783L38.2061 30.5ZM41.4316 20.5771H41.3525L39.7266 25.6484H43.04L41.4316 20.5771ZM52.2644 30.6318C51.0603 30.6318 50.1462 30.0605 49.654 29.208H49.5837V33.585H47.6325V21.21H49.531V22.5723H49.6013C50.1111 21.6846 51.0603 21.0869 52.3083 21.0869C54.3913 21.0869 55.8767 22.6602 55.8767 25.4375V26.2637C55.8767 29.0234 54.4089 30.6318 52.2644 30.6318ZM51.8161 29.0234C53.0554 29.0234 53.8991 28.0303 53.8991 26.1582V25.5078C53.8991 23.7061 53.1081 22.6777 51.781 22.6777C50.4187 22.6777 49.5661 23.7852 49.5661 25.499V26.1582C49.5661 27.916 50.4275 29.0234 51.8161 29.0234ZM62.183 30.6318C60.9789 30.6318 60.0649 30.0605 59.5727 29.208H59.5024V33.585H57.5512V21.21H59.4496V22.5723H59.52C60.0297 21.6846 60.9789 21.0869 62.227 21.0869C64.31 21.0869 65.7954 22.6602 65.7954 25.4375V26.2637C65.7954 29.0234 64.3276 30.6318 62.183 30.6318ZM61.7348 29.0234C62.9741 29.0234 63.8178 28.0303 63.8178 26.1582V25.5078C63.8178 23.7061 63.0268 22.6777 61.6996 22.6777C60.3373 22.6777 59.4848 23.7852 59.4848 25.499V26.1582C59.4848 27.916 60.3461 29.0234 61.7348 29.0234ZM69.8387 27.1689H71.7899C71.8778 28.2061 72.7919 29.0938 74.4882 29.0938C76.0438 29.0938 76.9667 28.3643 76.9667 27.2305C76.9667 26.3164 76.3514 25.8242 75.0682 25.5166L73.0995 25.0244C71.5526 24.6641 70.1639 23.7412 70.1639 21.79C70.1639 19.4961 72.1679 18.2393 74.497 18.2393C76.8261 18.2393 78.7684 19.4961 78.8124 21.7373H76.8964C76.8085 20.7178 76.0262 19.874 74.4706 19.874C73.0995 19.874 72.1679 20.5244 72.1679 21.6406C72.1679 22.4229 72.7128 22.9854 73.829 23.2402L75.7889 23.7236C77.5907 24.1631 78.9618 25.0156 78.9618 27.0547C78.9618 29.4102 77.0546 30.7373 74.3387 30.7373C70.9989 30.7373 69.8827 28.7861 69.8387 27.1689ZM81.3395 21.21V18.9512H83.2555V21.21H85.066V22.7744H83.2555V27.7314C83.2555 28.7422 83.6334 29.0234 84.6793 29.0234C84.8463 29.0234 85.0045 29.0234 85.1188 29.0059V30.5C84.9605 30.5264 84.5914 30.5615 84.1959 30.5615C81.9371 30.5615 81.3131 29.7529 81.3131 27.8896V22.7744H80.0299V21.21H81.3395ZM90.3353 21.0518C93.0071 21.0518 94.4573 22.9326 94.4573 25.4639V26.2109C94.4573 28.8301 93.0159 30.6582 90.3353 30.6582C87.6546 30.6582 86.1956 28.8301 86.1956 26.2109V25.4639C86.1956 22.9414 87.6634 21.0518 90.3353 21.0518ZM90.3353 22.6162C88.8851 22.6162 88.1644 23.8027 88.1644 25.4902V26.2021C88.1644 27.8633 88.8763 29.085 90.3353 29.085C91.7943 29.085 92.4974 27.8721 92.4974 26.2021V25.4902C92.4974 23.7939 91.7855 22.6162 90.3353 22.6162ZM96.1055 30.5V21.21H98.0567V22.4316H98.127C98.3643 21.8516 99.0586 21.0781 100.351 21.0781C100.606 21.0781 100.825 21.0957 101.01 21.1309V22.8535C100.843 22.8096 100.5 22.7832 100.175 22.7832C98.6104 22.7832 98.083 23.75 98.083 24.998V30.5H96.1055ZM105.743 30.6582C103.256 30.6582 101.674 29.0146 101.674 26.2637V25.3232C101.674 22.7305 103.22 21.0518 105.664 21.0518C108.142 21.0518 109.636 22.792 109.636 25.4111V26.2988H103.598V26.5186C103.598 28.083 104.442 29.1201 105.769 29.1201C106.762 29.1201 107.439 28.6279 107.677 27.8281H109.531C109.25 29.3311 108.037 30.6582 105.743 30.6582ZM103.598 24.9365H107.729V24.9189C107.729 23.6006 106.912 22.5635 105.673 22.5635C104.416 22.5635 103.598 23.6006 103.598 24.9189V24.9365Z" fill="black"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        <div class="socialIcons_container">
            <p>{{ Lang::get("content.CMShare") }}</p>
            <div class="socialIcons">
                <a href="https://www.instagram.com/trainerworkout/">
                    <svg width="25" height="25" viewBox="0 0 25 25" xmlns="https://www.w3.org/2000/svg">
                        <title>
                            InstagramSocialIcon
                        </title>
                        <path
                            d="M23.977 1.023C23.352.398 22.603.085 21.73.085H3.27c-.873 0-1.622.313-2.247.938C.397 1.648.085 2.397.085 3.27v18.46c0 .873.312 1.622.938 2.247.625.625 1.374.938 2.247.938h18.46c.873 0 1.622-.313 2.247-.938.625-.625.938-1.374.938-2.247V3.27c0-.873-.313-1.622-.938-2.247zM9 9.033c.976-.943 2.148-1.415 3.516-1.415 1.38 0 2.557.472 3.532 1.415.976.943 1.463 2.082 1.463 3.42 0 1.335-.486 2.475-1.462 3.417-.975.943-2.152 1.415-3.532 1.415-1.368 0-2.54-.472-3.516-1.415-.975-.943-1.463-2.082-1.463-3.418 0-1.337.488-2.476 1.463-3.42zm13.102 12.035c0 .28-.097.514-.29.703a.963.963 0 0 1-.696.284H3.836a.954.954 0 0 1-.704-.283.954.954 0 0 1-.283-.702V10.592h2.28a6.96 6.96 0 0 0-.325 2.118c0 2.07.755 3.834 2.263 5.295 1.51 1.46 3.325 2.19 5.448 2.19a7.77 7.77 0 0 0 3.88-1.002 7.546 7.546 0 0 0 2.813-2.724 7.162 7.162 0 0 0 1.034-3.76 6.96 6.96 0 0 0-.324-2.118h2.183v10.476zm0-14.436c0 .312-.107.577-.323.792-.217.216-.48.323-.793.323h-2.813c-.312 0-.577-.107-.792-.323a1.077 1.077 0 0 1-.323-.792V3.965c0-.302.106-.563.322-.784.215-.22.48-.33.792-.33h2.813c.312 0 .576.11.792.33.215.222.322.483.322.785v2.667z"
                            fill="#FFF" fill-rule="evenodd"/>
                    </svg>
                </a>
{{--                <a href="https://www.facebook.com/tworkout">--}}
{{--                    <svg width="25" height="25" viewBox="0 0 25 25" xmlns="https://www.w3.org/2000/svg">--}}
{{--                        <title>--}}
{{--                            FcaebookSocialIcon--}}
{{--                        </title>--}}
{{--                        <path--}}
{{--                            d="M23.55 1.45C22.637.54 21.54.086 20.26.086H4.74C3.46.085 2.363.54 1.45 1.45.54 2.363.086 3.46.086 4.74v15.52c0 1.282.455 2.378 1.366 3.29.912.91 2.008 1.365 3.29 1.365h15.52c1.282 0 2.378-.455 3.29-1.366.91-.912 1.365-2.008 1.365-3.29V4.74c0-1.282-.456-2.378-1.366-3.29zm-2.337 11.406h-2.83V23.12H14.15V12.856h-2.12v-3.54h2.12V7.197c0-1.52.355-2.668 1.066-3.444.71-.776 1.885-1.164 3.524-1.164h2.83v3.54h-1.78c-.603 0-.988.106-1.155.316-.167.21-.25.58-.25 1.107v1.762h3.2l-.372 3.54z"--}}
{{--                            fill="#FFF" fill-rule="evenodd"/>--}}
{{--                    </svg>--}}
{{--                </a>--}}
                <a href="https://www.twitter.com/trainerworkout">
                    <svg width="25" height="25" viewBox="0 0 25 25" xmlns="https://www.w3.org/2000/svg">
                        <title>
                            TwitterSocialIcon
                        </title>
                        <path
                            d="M23.55 1.45C22.637.54 21.54.086 20.26.086H4.74C3.46.085 2.363.54 1.45 1.45.54 2.363.086 3.46.086 4.74v15.52c0 1.282.455 2.378 1.366 3.29.912.91 2.008 1.365 3.29 1.365h15.52c1.282 0 2.378-.455 3.29-1.366.91-.912 1.365-2.008 1.365-3.29V4.74c0-1.282-.456-2.378-1.366-3.29zm-4.47 8.19c.01.096.015.24.015.435 0 .906-.132 1.814-.396 2.724a9.755 9.755 0 0 1-1.213 2.618 10.193 10.193 0 0 1-1.948 2.215c-.755.64-1.66 1.153-2.716 1.535a9.882 9.882 0 0 1-3.395.574 9.526 9.526 0 0 1-5.206-1.52c.26.033.528.05.808.05 1.563 0 2.97-.486 4.22-1.456a3.223 3.223 0 0 1-1.965-.68 3.45 3.45 0 0 1-1.204-1.68c.28.043.49.064.63.064.237 0 .512-.042.825-.13-.787-.15-1.45-.54-1.99-1.17a3.228 3.228 0 0 1-.807-2.158v-.033c.572.268 1.11.408 1.618.42-.98-.658-1.47-1.6-1.47-2.83 0-.603.155-1.174.467-1.713A9.623 9.623 0 0 0 8.46 9.412a9.61 9.61 0 0 0 3.91 1.05 3.122 3.122 0 0 1-.08-.775c0-.937.33-1.737.994-2.4a3.272 3.272 0 0 1 2.4-.995c.992 0 1.817.356 2.474 1.068a6.753 6.753 0 0 0 2.166-.825c-.27.83-.77 1.46-1.503 1.89.7-.096 1.353-.28 1.957-.548A6.6 6.6 0 0 1 19.08 9.64z"
                            fill="#FFF" fill-rule="evenodd"/>
                    </svg>
                </a>
                <a href="https://www.trainer-workout.com/blog">
                    <svg width="25" height="25" viewBox="0 0 25 25" xmlns="https://www.w3.org/2000/svg">
                        <title>
                            TWSocialIcon
                        </title>
                        <path
                            d="M23.464 1.366C22.554.456 21.457 0 20.174 0H4.656c-1.283 0-2.38.455-3.29 1.366C.456 2.276 0 3.373 0 4.656v15.518c0 1.283.455 2.38 1.366 3.29.91.91 2.007 1.366 3.29 1.366h15.518c1.283 0 2.38-.455 3.29-1.366.91-.91 1.366-2.007 1.366-3.29V4.656c0-1.283-.456-2.38-1.366-3.29zM5.01 11.95c.493 0 .83-.168 1.162-.343-1.49.283-2.96-.757-3.172-2.493l2.467-.037.104-.653L7.23 7h1.426L8.32 9.013s1.13-.035 1.954 0c.506.064 1.1.25 1.345.543.307.294.642.914.803 1.917.063.31.167.89.208 1.298l1.66-3.624c.536-.257 1.744-.39 2.823-.032l.524 4.216 3-4.906 1.06-.362.363 1.052-3.86 8.522c-.945.302-1.93.216-2.497 0l-.71-4.25s-1.85 3.837-2.102 4.25c-.857.302-2.09.183-2.48 0 0-.355 0-4.19-.194-4.793 0 0-.104-1.126-.767-1.236H7.902s-.62 3.37-.548 3.48c.05.152.14.17.228.246.36.072.44.136 1.777-.196v2.074c-.768.498-1.755.96-3.38.728-.902-.164-1.584-.64-1.624-1.847.07-.906.334-2.066.653-4.14z"
                            fill="#FFF" fill-rule="evenodd"/>
                    </svg>
                </a>
                
            </div>
        </div>
    </nav>
    <!-- /c-menu push-left -->
    <nav class="p-menu">
    </nav>
    <div id="c-mask" class="c-mask"></div>
    <!-- /c-mask -->
    <!-- FEEDBACK OVERLAY -->
    <div class="fb_overlay">
        <svg class="c-menu__close" width="15" height="15" viewBox="0 0 15 15" xmlns="https://www.w3.org/2000/svg"
             onclick="moveFeedbackDown();">
            <title>
                Close Icon
            </title>
            <path class="closeIcon"
                  d="M7.5 4.865L3.536.9a1.874 1.874 0 0 0-2.65 2.65L4.85 7.516.916 11.45a1.874 1.874 0 1 0 2.65 2.65L7.5 10.166l3.934 3.934a1.874 1.874 0 1 0 2.65-2.65L10.15 7.514l3.965-3.964A1.874 1.874 0 0 0 11.465.9L7.5 4.865z"
                  fill-opacity=".52" fill="#FFF" fill-rule="evenodd"/>
        </svg>
        <div class="feedbackContainer">
            <h1>{{ Lang::get("content.feedback/ThankYou") }}</h1>
            <h2>{{ Lang::get("content.feedback/1") }}</h2>
            {{ Form::open(array('url' => Lang::get('routes./Feedback'))) }}
            <label for="feedback">{{ Lang::get("content.feedback") }}</label>
            <textarea type="text" placeholder="{{ Lang::get("content.feedback/2") }}" name="feedback"
                      id="feedback"></textarea>
            <div class="btn_container">
                <button>{{{ Lang::get("content.Send") }}}</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
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

?>
@if(!Config::get("app.debug"))
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
