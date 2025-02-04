@extends('layouts.frontEnd')



@section("header")
    {{ Helper::seo("gym") }}
@endsection


@section('content')


<main>

<!-- Jumbotron Block -->
<section id="jumbotron">
    <div class="background base"></div>
    <div class="background img"></div>
    <div class="background top"></div>
    <div class="content">
        <div class="wrapper">
            <h1>{{{ Lang::get("content.Connect with your clients & scale up your personal training business") }}}</h1>
            <p>{{{ Lang::get("content.bestTecno") }}}</p>
            <a href="{{ Lang::get("routes./TrainerSignUp") }}" class="action">{{ Lang::get("content.Get started for free") }}</a>
        </div>        
    </div> 
</section>

<!-- Primary block -->
<section id="primary">
    <div class="wrapper">
        <div class="content">
            <div class="device iPad-portrait">
                <div class="deviceBtn"></div>
                <div class="screen">
                    <img class="header" src="/img/website/iPad-portrait-header.png">
                    <div class="scrollableContainer">
                        <img class="scrollable coreAsset" src="/img/website/iPad-portrait-primary.png">
                    </div>
                    
                </div>
            </div>
            <div class="info">
                <h1>{{ Lang::get("content.Always with you, works on any device, intuitive & easy to use") }}</h1>
                <p>{{ Lang::get("content.mobileFriendlyTW") }}</p>
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
                <h1>{{ Lang::get("content.For you & your clients") }}</h1>
                <p>{{ Lang::get("content.twOffersYou") }}</p>
            </div>
            <div class="mainSectionContent">
                <div class="tabsContainer">
                    <p class="tab branding selected">{{ Lang::get("content.Your branding") }}</p>
                    <p class="tab clientFiles">{{ Lang::get("content.Client files") }}</p>
                    <p class="tab notifications">{{ Lang::get("content.frontEnd/Notifications") }}</p>
                    <p class="tab tracking">{{ Lang::get("content.Tracking & reporting") }}</p>
                </div>
                <div class="selectableTabContainer">
                    <select name="mobileTab" class="selectableTab">
                        <option value="branding">{{ Lang::get("content.Your branding") }}</option>
                        <option value="clientFiles">{{ Lang::get("content.Client files") }}</option>
                        <option value="notifications">{{ Lang::get("content.frontEnd/Notifications") }}</option>
                        <option value="tracking">{{ Lang::get("content.Tracking & reporting") }}</option>
                    </select>
                </div>
                <div class="deviceContainer">
                    <div class="device iPhone7-portrait">
                        <div class="deviceBtn"></div>
                        <div id="branding" class="screen">
                            <img class="header" src="/img/website/iPhone7-portrait-header.png">
                            <div class="scrollableContainer">
                                <img class="scrollable coreAsset" src="/img/website/secondary-branding.png">
                            </div>
                        </div>
                        <div id="clientFiles" class="screen hideMe">
                            <img class="header" src="/img/website/iPad-landscape-header.png">
                            <div class="scrollableContainer">
                                <img class="scrollable coreAsset" src="/img/website/iPad-portrait-primary.png">
                            </div>
                        </div>
                        <div id="notifications" class="screen hideMe">
                            <img class="header" src="/img/website/emailHeader.png">
                            <div class="scrollableContainer">
                                <img class="scrollable coreAsset" src="/img/website/secondary-notification.png">
                            </div>
                        </div>
                        <div id="tracking" class="screen hideMe">
                            <img class="header" src="/img/website/desktop-header.png">
                            <div class="scrollableContainer">
                                <img class="scrollable coreAsset" src="/img/website/secondary-report.png">
                            </div>
                        </div>
                    </div>
                    <div class="message">
                        <p id="brandingMessage">{{ Lang::get("content.Your branding 2") }}</p>
                        <p id="clientFilesMessage" class="hideMe">{{ Lang::get("content.ClientFileEach") }}</p>
                        <p id="notificationsMessage" class="hideMe">{{ Lang::get("content.ReceiveNotification") }}</p>
                        <p id="trackingMessage" class="hideMe">{{ Lang::get("content.GlobalView") }}</p>
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

                <h1>{{ Lang::get("content.A series of tools designed for you") }}</h1>
                <p>{{ Lang::get("content.thirdLongText") }}</p>
            </div>
            <div class="mainSectionContent">
                <div class="selectableTabContainer">
                    <select name="mobileTab" class="selectableTab">
                        <option value="exercises">{{ Lang::get("content.Over 2,500 exercises") }}</option>
                        <option value="builder">{{ Lang::get("content.Workout Builder") }}</option>
                        <option value="share">{{ Lang::get("content.Share your workouts") }}</option>
                        <option value="print">{{ Lang::get("content.Even good old print") }}</option>
                    </select>
                </div>
                <div class="deviceContainer">
                    <div class="device none">
                        <div class="deviceBtn"></div>
                        <div id="exercises" class="screen">
                            <div class="scrollableContainer">
                                <img class="scrollable coreAsset" src="/img/website/third-exercises.png">
                            </div>
                        </div>
                        <div id="builder" class="screen hideMe">
                            <div class="scrollableContainer">
                                <img class="scrollable coreAsset" src="/img/website/third-builder.gif">
                            </div>
                        </div>
                        <div id="share" class="screen hideMe">
                            <div class="scrollableContainer">
                                <img class="scrollable coreAsset" src="/img/website/third-share.gif">
                            </div>
                        </div>
                        <div id="print" class="screen hideMe">
                            <div class="scrollableContainer">
                                <img class="scrollable coreAsset" src="/img/website/third-print.png">
                            </div>
                        </div>
                    </div>
                    <div class="message">
                        <p id="exercisesMessage">{{ Lang::get("content.More exercises added montly.<br> And you can add your own at any time.") }}</p>
                        <p id="builderMessage" class="hideMe">{{ Lang::get("content.Create workouts any way you want them") }}</p>
                        <p id="shareMessage" class="hideMe">{{ Lang::get("content.You can easily share your workout right from the workout builder tool.") }}</p>
                        <p id="printMessage" class="hideMe">{{ Lang::get("content.Yep! We got you covered for your less tech savy clients too.. ;-)") }}</p>
                    </div>
                </div>
                <div class="tabsContainer">
                    <p class="tab exercises selected">{{ Lang::get("content.Over 2,500 exercises") }}</p>
                    <p class="tab builder">{{ Lang::get("content.Workout Builder") }}</p>
                    <p class="tab share">{{ Lang::get("content.Share your workouts") }}</p>
                    <p class="tab print">{{ Lang::get("content.Even good old print") }}</p>
                </div>
            </div>
        </div>
    </div>
</section>  

<!-- Third Block -->

<section class="parentHolderTab" id="forth">
    <div class="background"></div>
    <div class="content">
        <div class="wrapper">
            <div class="topSectionContent">

                <h1>{{ Lang::get("content.A series of tools designed for you") }}</h1>
                <p>{{ Lang::get("content.thirdLongText") }}</p>
            </div>
            <div class="mainSectionContent">
                <div class="selectableTabContainer">
                    <select name="mobileTab" class="selectableTab">
                        <option value="exercises">{{ Lang::get("content.Over 2,500 exercises") }}</option>
                        <option value="builder">{{ Lang::get("content.Workout Builder") }}</option>
                        <option value="share">{{ Lang::get("content.Share your workouts") }}</option>
                        <option value="print">{{ Lang::get("content.Even good old print") }}</option>
                    </select>
                </div>
                <div class="deviceContainer">
                    <div class="device none">
                        <div class="deviceBtn"></div>
                        <div id="exercises" class="screen">
                            <div class="scrollableContainer">
                                <img class="scrollable coreAsset" src="/img/website/third-exercises.png">
                            </div>
                        </div>
                        <div id="builder" class="screen hideMe">
                            <div class="scrollableContainer">
                                <img class="scrollable coreAsset" src="/img/website/third-builder.gif">
                            </div>
                        </div>
                        <div id="share" class="screen hideMe">
                            <div class="scrollableContainer">
                                <img class="scrollable coreAsset" src="/img/website/third-share.gif">
                            </div>
                        </div>
                        <div id="print" class="screen hideMe">
                            <div class="scrollableContainer">
                                <img class="scrollable coreAsset" src="/img/website/third-print.png">
                            </div>
                        </div>
                    </div>
                    <div class="message">
                        <p id="exercisesMessage">{{ Lang::get("content.More exercises added montly.<br> And you can add your own at any time.") }}</p>
                        <p id="builderMessage" class="hideMe">{{ Lang::get("content.Create workouts any way you want them") }}</p>
                        <p id="shareMessage" class="hideMe">{{ Lang::get("content.You can easily share your workout right from the workout builder tool.") }}</p>
                        <p id="printMessage" class="hideMe">{{ Lang::get("content.Yep! We got you covered for your less tech savy clients too.. ;-)") }}</p>
                    </div>
                </div>
                <div class="tabsContainer">
                    <p class="tab exercises selected">{{ Lang::get("content.Over 2,500 exercises") }}</p>
                    <p class="tab builder">{{ Lang::get("content.Workout Builder") }}</p>
                    <p class="tab share">{{ Lang::get("content.Share your workouts") }}</p>
                    <p class="tab print">{{ Lang::get("content.Even good old print") }}</p>
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
                    <h4>{{ Lang::get("content.trying us out") }}</h4>
                    <h2>{{ Lang::get("content.Free") }}</h2>
                    <p>{{ Lang::get("content.3 stored workouts or less") }}</p>
                    <a href="{{ Lang::get("routes./TrainerSignUp") }}">{{ Lang::get("content.Start Free Trial") }}</a>
                </div>
                <div class="plan-a">
                    <h4>{{ Lang::get("content.base plan") }}</h4>
                    <h2>$21.99 USD / {{ Lang::get("content.month") }}</h2>
                    <p>{{ Lang::get("content.Up to 35 connected clients") }}</p>
                    <a href='{{ Lang::get("routes./trainerGetStartedPaid") }}'">{{ Lang::get("content.Get Started") }}</a>
                </div>
                <div class="plan-b">
                    <h4>{{ Lang::get("content.scaling online") }}</h4>
                    <h2>{{ Lang::get("content.Contact Us") }}</h2>
                    <p>{{ Lang::get("content.More than 35 clients") }}</p>
                    <a href="mailto:info@trainerworkout.com">{{ Lang::get("content.Scale Up Online") }}</a>
                </div>
            </div>
        </div>
    </div>
</section>







</main>









@endsection

@section("scripts")
{{ HTML::script('js/tabsSwitcher.js'); }}

<script type="text/javascript">

// INITIATION OF THE ELEMENTS


//cache a reference to the tabs
var sec_tabs = $('#secondary .tab');
var third_tabs = $('#third .tab');
var forth_tabs = $('#forth .tab');

var sec_time = 10000;
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

//auto-rotate Third Section tabs every 12 seconds
var forthdTimer = setInterval(switchForthTab, forth_time);

</script>

@endsection