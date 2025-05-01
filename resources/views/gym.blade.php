@extends('layouts.frontEnd')
@php
    use App\Http\Libraries\Helper;
@endphp
@section("header")
    {!! Helper::seo("gym") !!}
@endsection
@section('content')
    <main class="gym">
        <!-- Jumbotron Block -->
        <section id="jumbotron">
            <div class="background base"></div>
            <div class="background img"></div>
            <div class="background top topGym"></div>
            <div class="content">
                <div class="wrapper">
                    <h1>{{{ Lang::get("content.gym/jumbotron1") }}}<!-- <br>{{{ Lang::get("content.gym/jumbotron2") }}}-->
                        <br>
                        <span class="spellingWord"></span>
                    </h1>
                    <div id="typed-strings">
                        <p>{{ Lang::get("content.gym/jumboWord1") }}</p>
                        <p>{{ Lang::get("content.gym/jumboWord2") }}</p>
                        <p>{{ Lang::get("content.gym/jumboWord3") }}</p>
                        <p>{{ Lang::get("content.gym/jumboWord4") }}</p>
                    </div>

                    <!-- <p>{{{ Lang::get("content.bestTecno") }}}</p> -->
                    <a href="{{ Lang::get("routes./TrainerSignUp") }}"
                       class="action">{{ Lang::get("content.gym/cta") }}</a>
                </div>
            </div>
        </section>

        <!-- Primary block -->
        <section class="parentHolderTab" id="primary">
            <div class="wrapper">
                <div class="content">
                    <div class="device iPad-portrait">
                        <div class="deviceBtn"></div>
                        <div class="screen">
                            <img class="header" src="{{asset('assets/img/website/iPad-portrait-header.png')}}">
                            <div class="scrollableContainer">
                                <img class="scrollable coreAsset" src="{{asset('assets/img/website/iPad-portrait-primary.png')}}">
                            </div>

                        </div>
                    </div>
                    <div class="info">
                        <h1>{{ Lang::get("content.gym/alwaysMobile") }}</h1>
                        <p class="info-p">{{ Lang::get("content.gym/alwaysMobileText") }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Seconday block -->
        <section class="parentHolderTab" id="secondary">
            <div class="background"></div>
            <div class="content">
                <div class="wrapper">
                    <div class="topSectionContent">
                        <h1>{{ Lang::get("content.gym/ExperienceClient") }}</h1>
                        <p>{{ Lang::get("content.gym/ExperienceClientText") }}</p>
                    </div>
                    <div class="mainSectionContent">
                        <div class="tabsContainer">
                            <p class="tab gbranding selected">{{ Lang::get("content.gym/clientFeature1") }}</p>
                            <p class="tab acitivtyLog">{{ Lang::get("content.gym/clientFeature2") }}</p>
                            <!-- <p class="tab progress">{{ Lang::get("content.gym/clientFeature3") }}</p> -->
                            <p class="tab workouts">{{ Lang::get("content.gym/clientFeature4") }}</p>
                        </div>
                        <div class="selectableTabContainer">
                            <select name="mobileTab" class="selectableTab">
                                <option value="gbranding">{{ Lang::get("content.gym/clientFeature1") }}</option>
                                <option value="acitivtyLog">{{ Lang::get("content.gym/clientFeature2") }}</option>
                                <!-- <option value="progress">{{ Lang::get("content.gym/clientFeature3") }}</option> -->
                                <option value="workouts">{{ Lang::get("content.gym/clientFeature4") }}</option>
                            </select>
                        </div>
                        <div class="deviceContainer">
                            <div class="device iPhone7-portrait">
                                <div class="deviceBtn"></div>
                                <div id="gbranding" class="screen">
                                    <img class="header" src="{{asset('assets/img/website/Gym_GymClientFeat1_Header_Iphone6_940.gif')}}">
                                    <div class="scrollableContainer">
                                        <img class="scrollable coreAsset" src="{{asset('assets/img/website/Gym_GymClientFeat1_iphone6_2.gif')}}">
                                    </div>
                                </div>
                                <div id="acitivtyLog" class="screen hideMe">
                                    <!-- <img class="header" src="/img/website/iPhone7-portrait-header.png"> -->
                                    <div class="scrollableContainer">
                                        <img class="scrollable coreAsset" src="{{asset('assets/img/website/GymClientFeat2_ipadHorizontal.gif')}}">
                                    </div>
                                </div>
                                <!--  <div id="progress" class="screen hideMe">
                                     <img class="header" src="/img/website/iPhone7-portrait-header.png">
                                     <div class="scrollableContainer">
                                         <img class="scrollable coreAsset" src="/img/website/secondary-notification.png">
                                     </div>
                                 </div> -->
                                <div id="workouts" class="screen hideMe">
                                    <!-- <img class="header" src="/img/website/iPhone7-portrait-header.png"> -->
                                    <div class="scrollableContainer">
                                        <img class="scrollable coreAsset" src="{{asset('assets/img/website/GymClientFeat3_IpadVertical.png')}}">
                                    </div>
                                </div>
                            </div>
                            <div class="message">
                                <p id="gbrandingMessage">{{ Lang::get("content.gym/clientFeature1Message") }}</p>
                                <p id="acitivtyLogMessage" class="hideMe">{{ Lang::get("content.gym/clientFeature2Message") }}</p>
                                <!-- <p id="progressMessage" class="hideMe">{{ Lang::get("content.gym/clientFeature3Message") }}</p> -->
                                <p id="workoutsMessage" class="hideMe">{{ Lang::get("content.gym/clientFeature4Message") }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Third Block -->

        <section class="parentHolderTab" id="third">
            <div class="background"></div>
            <div class="content">
                <div class="wrapper">
                    <div class="topSectionContent">

                        <h1>{{ Lang::get("content.gym/employeeProductive") }}</h1>
                        <p>{{ Lang::get("content.gym/employeeProductiveText") }}</p>
                    </div>
                    <div class="mainSectionContent">
                        <div class="selectableTabContainer">
                            <select name="mobileTab" class="selectableTab">
                                <option value="gexercises">{{ Lang::get("content.gym/employeeFeature1") }}</option>
                                <option value="gbuilder">{{ Lang::get("content.gym/employeeFeature2") }}</option>
                                <option value="gshare">{{ Lang::get("content.gym/employeeFeature3") }}</option>
                                <option value="gprint">{{ Lang::get("content.gym/employeeFeature4") }}</option>
                            </select>
                        </div>
                        <div class="deviceContainer">
                            <div class="device none">
                                <div class="deviceBtn"></div>
                                <div id="gexercises" class="screen">
                                    <div class="scrollableContainer">
                                        <img class="scrollable coreAsset" src="{{asset('assets/img/website/third-exercises.png')}}">
                                    </div>
                                </div>
                                <div id="gbuilder" class="screen hideMe">
                                    <div class="scrollableContainer">
                                        <img class="scrollable coreAsset" src="{{asset('assets/img/website/third-builder.gif')}}">
                                    </div>
                                </div>
                                <div id="gshare" class="screen hideMe">
                                    <div class="scrollableContainer">
                                        <img class="scrollable coreAsset" src="{{asset('assets/img/website/third-share.gif')}}">
                                    </div>
                                </div>
                                <div id="gprint" class="screen hideMe">
                                    <div class="scrollableContainer">
                                        <img class="scrollable coreAsset" src="{{asset('assets/img/website/third-print.png')}}">
                                    </div>
                                </div>
                            </div>
                            <div class="message">
                                <p id="gexercisesMessage">{{ Lang::get("content.gym/employeeFeature1Message") }}</p>
                                <p id="gbuilderMessage" class="hideMe">{{ Lang::get("content.gym/employeeFeature2Message") }}</p>
                                <p id="gshareMessage" class="hideMe">{{ Lang::get("content.gym/employeeFeature3Message") }}</p>
                                <p id="gprintMessage" class="hideMe">{{ Lang::get("content.gym/employeeFeature4Message") }}</p>
                            </div>
                        </div>
                        <div class="tabsContainer">
                            <p class="tab gexercises selected">{{ Lang::get("content.gym/employeeFeature1") }}</p>
                            <p class="tab gbuilder">{{ Lang::get("content.gym/employeeFeature2") }}</p>
                            <p class="tab gshare">{{ Lang::get("content.gym/employeeFeature3") }}</p>
                            <p class="tab gprint">{{ Lang::get("content.gym/employeeFeature4") }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Forth Block -->
        <section class="parentHolderTab" id="forth">
            <div class="background"></div>
            <div class="content">
                <div class="wrapper">
                    <div class="topSectionContent">
                        <h1>{{ Lang::get("content.gym/control") }}</h1>
                        <p>{{ Lang::get("content.gym/controlText") }}</p>
                    </div>
                    <div class="mainSectionContent">
                        <div class="selectableTabContainer">
                            <select name="mobileTab" class="selectableTab">
                                <option value="employee">{{ Lang::get("content.gym/controlFeature1") }}</option>
                                <option value="global">{{ Lang::get("content.gym/controlFeature2") }}</option>
                                <option value="collaboration">{{ Lang::get("content.gym/controlFeature3") }}</option>
                            </select>
                        </div>
                        <div class="tabsContainer">
                            <p class="tab employee selected">{{ Lang::get("content.gym/controlFeature1") }}</p>
                            <p class="tab global">{{ Lang::get("content.gym/controlFeature2") }}</p>
                            <p class="tab collaboration">{{ Lang::get("content.gym/controlFeature3") }}</p>
                        </div>
                        <div class="deviceContainer">
                            <div class="device iPad-landscape">
                                <div class="deviceBtn"></div>
                                <div id="employee" class="screen">
                                    <div class="scrollableContainer">
                                        <img class="scrollable coreAsset" src="{{asset('assets/img/website/GymControlFeat1_IpadHorizontal.gif')}}">
                                    </div>
                                </div>
                                <div id="global" class="screen hideMe">
                                    <div class="scrollableContainer">
                                        <img class="scrollable coreAsset" src="{{asset('assets/img/website/GymControlFeat2_Iphone6.png')}}">
                                    </div>
                                </div>
                                <div id="collaboration" class="screen hideMe">
                                    <div class="scrollableContainer">
                                        <img class="scrollable coreAsset" src="{{asset('assets/img/website/GymControlFeat3_IpadVertical.gif')}}">
                                    </div>
                                </div>
                            </div>
                            <div class="message">
                                <p id="employeeMessage">{{ Lang::get("content.gym/controlFeature1Message") }}</p>
                                <p id="globalMessage" class="hideMe">{{ Lang::get("content.gym/controlFeature2Message") }}</p>
                                <p id="collaborationMessage" class="hideMe">{{ Lang::get("content.gym/controlFeature3Message") }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonial Block -->
        <section id="testimonial">

        </section>

        <!-- Pricing Block -->
        <section id="pricing">
            <div class="background"></div>
            <div class="content">
                <div class="modified-wrapper">
                    <h1>{{ Lang::get("content.Our Plans") }}</h1>
                    <div class="pricingContainer">
                        <div class="plan-b">
                            <h4>{{ Lang::get("content.Number of Employees") }}</h4>
                            <h2>{{ Lang::get("content.Per Trainer Pricing") }}</h2>
                            <p>{!! Lang::get("content.The more trainer the less expensive it becomes") !!}</p>
                            <!-- <a href="{{ Lang::get("routes./TrainerSignUp") }}">{{ Lang::get("content.Start Free Trial") }}</a> -->
                        </div>
                        <div class="plan-a">
                            <h4>{{ Lang::get("content.Do you want control") }}?</h4>
                            <h2>{{ Lang::get("content.Management Module") }}</h2>
                            <p>{!! Lang::get("content.Monthly Fixed Fee<br>(optional)") !!}</p>
                            <!-- <a href='mailto:info@trainer-workout.com'">Contact Us</a> -->
                        </div>
                        <div class="plan-b">
                            <h4>{{ Lang::get("content.Branding Options") }}</h4>
                            <h2>{{ Lang::get("content.Varies based on selected options") }}</h2>
                            <p>{!! Lang::get("content.Options range from simple logo to full on white label<br>(optional)") !!}</p>
                            <!-- <a href="mailto:info@traierworkout.com">Contact Us</a> -->
                        </div>
                    </div>
                    <div class="fixedPricing">
                        <!-- <p>We also can customize pricing for chains</p> -->
                        <a href='mailto:info@trainer-workout.com'>{{ Lang::get("content.Contact Us") }}</a>
                    </div>
                </div>
            </div>
        </section>

    </main>

@endsection

@section("scripts")
    {{ HTML::script(asset('assets/js/tabsSwitcher.js')) }}
    <script src="{{asset('assets/js/typed.min.js')}}"></script>
    <script>
        $(function () {
            $(".spellingWord").typed({
                // strings: [dict["gym/jumboWord1"], dict["gym/jumboWord2"], dict["gym/jumboWord3"], dict["gym/jumboWord4"] ],
                stringsElement: $('#typed-strings'),
                typeSpeed: 80,
                backDelay: 2e3,
                suffle: true,
                loop: true,
                startDelay: 1e3
            });
        });
    </script>
    <script type="text/javascript">

        // INITIATING OF THE TAB SWITCHER FOR THE PAGE

        //cache a reference to the tabs
        var sec_tabs = $('#secondary .tab');
        var third_tabs = $('#third .tab');
        var forth_tabs = $('#forth .tab');

        //time to switch
        var sec_time = 15000;
        var third_time = 12000;
        var forth_time = 10000;


        // switching the tabs in the second section
        function switchSecondTab() {
            switchTab(sec_tabs);
        }

        // switching the tabs in the third section
        function switchThirdTab() {
            switchTab(third_tabs);
        }

        // switching the tabs in the forth section
        function switchForthTab() {
            switchTab(forth_tabs);
        }

        //auto-rotate Second Section tabs every 10 seconds
        var secondTimer = setInterval(switchSecondTab, sec_time);

        //auto-rotate Third Section tabs every 12 seconds
        var thirdTimer = setInterval(switchThirdTab, third_time);

        //auto-rotate Third Section tabs every 10 seconds
        var forthTimer = setInterval(switchForthTab, forth_time);

        $(".nav_item").removeClass("selected");
        $(".nav_gym").addClass("selected");

    </script>

@endsection
