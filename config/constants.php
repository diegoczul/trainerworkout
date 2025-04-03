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
    "STRIPEsecret_key"      => "sk_live_ag10z5XKQ1O2nfhIEVvSsxIO",
  	"STRIPEpublishable_key" => "pk_live_8rR6dL6VZ8XqohaUGUSBg18Y",
  	"STRIPETestsecret_key"      => "sk_test_PK4J9Bf7l2SjJIORdHF3FI5P",
  	"STRIPETestpublishable_key" => "pk_test_ES8iBSn5YOcWg200bpisoxP0",
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
