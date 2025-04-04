<?php

use Illuminate\Support\Facades\Config;
if (!defined("ASSET_VERSION"))
	define('ASSET_VERSION','?v1.0.1');

if (!defined("MAIL_HOST"))
	define('MAIL_HOST', "smtp.sendgrid.net");
if (!defined("MAIL_PORT"))
	define('MAIL_PORT',587);
if (!defined("MAIL_ENCRYPTION"))
	define('MAIL_ENCRYPTION', "apikey");
if (!defined("MAIL_PASSWORD"))
	define('MAIL_PASSWORD', "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");
if (!defined("MAIL_USERNAME"))
	define('MAIL_USERNAME', "tls");
if (!defined("MAIL_FROM_ADDRESS"))
	define('MAIL_FROM_ADDRESS', "info@trainer-workout.com");

return array(
    'thumbSize' 	=> '400',
    'displaySize'	=> '800',
    'userPath'	=> 'public/users',
    'picturesPath'	=> '/pictures',
    'profilePath'	=> '/profile',
    'exercisesPath'	=> '/exercises',
    'exercisesCustomPath'	=> '/exercisesCustom',
    'moreExercises'	=> 'images/exercises',
    'videosExercisesPath'	=> '/videosExercises',
  	"STRIPEpublishable_key" => "pk_live_51QkHq3RrMsQ7lFic7BWflWWhVxuUargBlqdsIVGc4Ql7SV2KBFuF6G6kzWwII9jE30kSXO1J4oYRtam81RmH5PcZ006R9SuVIF",
    "STRIPEsecret_key"      => "***REMOVED***",
  	"STRIPETestpublishable_key" => "pk_test_51QkHq3RrMsQ7lFickqRTum0yz0lcQADjzvxv13KszdUxQ2lFHVBVh9QvqkmvBivY3WlkDlBG7HNmqCLDXv2k1Rcb00gCRN4QCr",
  	"STRIPETestsecret_key"      => "***REMOVED***",
    "mixPanelKey" => "ac325f08772f60d01b6478c33c2c47db",
    "onboardingClient" => "24",
    "onboardingUser" => "15",
    'intercom_app_id' => 'af0obxyk',
    'intercom_api_key' => '***REMOVED***',
    'mailChimpGetEarlyAccessListTrainer' => 'ce795f36af',
    'mailChimpGetEarlyAccessListTrainee' => 'f722232f2a',
    'mailChimpNewsletter' => 'ce795f36af',
    'mailChimpTrainees' => 'f722232f2a',
    'mailChimpTrainers' => 'ce795f36af',
    'defaultMembership' => '59',
    'defaultMembershipExpiry' => '+2 weeks',
//    'filePrefix' => Config::get("app.brand").' - ',
    'maxFreeWorkouts' => 3,
    'freeTrialMembershipId' => 59,
    'version' => "2.0.8",
    'activityEmail' => "activity@trainerworkout.com",
    'accountDomain' => "trainerworkout.com",
    'videoPlaceholder' => "assets/img/video.jpg",
    'gridPDF' => "grid.pdf",

    'constantsDesktopSizeVideo_w' => "100%",
    'constantsTabletSizeVideo_w' => "100%",
    'constantsMobileSizeVideo_w' => "100%",

    'constantsDesktopSizeVideo_h' => "250px",
    'constantsTabletSizeVideo_h' => "250px",
    'constantsMobileSizeVideo_h' => "150px",
);
?>
