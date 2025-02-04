<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Application Debug Mode
	|--------------------------------------------------------------------------
	|
	| When your application is in debug mode, detailed error messages with
	| stack traces will be shown on every error that occurs within your
	| application. If disabled, a simple generic error page is shown.
	|
	*/

	'debug' => true,
	'chat' => true,
	'url' => 'http://trainerworkout.local', // OR YOU HAVE TO PUT localhost

	/////////////////////////// WHITELABELS ////////////////////////////////////

	/// OPTIONS: [default,ymca]
	'whitelabel' => "default",
	'logo_header' => '/img/whitelabels/ymca/logos/LogoWhite.svg',
	'logo' => '/img/whitelabels/ymca/logos/LogoWhite.svg',
	'logo_over_white' => '/img/whitelabels/ymca/logos/LogoWhite.svg',
	'logo_on_image' => '/img/whitelabels/ymca/logos/LogoLightColored.png',
	'logo_on_print_grid' => '/img/whitelabels/ymca/logos/YMCA_base.png',
	'whitelabel_css' => '/css/whitelabels/ymca/ymca.css',
	'whitelabel_css_trainee' => '/css/whitelabels/ymca/ymca.css',
	'whitelabel_css_trainer' => '/css/whitelabels/ymca/ymca_trainer.css',
	'feedbackEmail' => 'wolfgang.Mercado-Alatrista2@ymcaquebec.org',
	'brand' => 'YMCA',
	'overrideViews' => array(	'login',
								'TraineeSignUp',
								'TrainerSignUp',
								'layouts.frontEnd'
							)

	// 'logo' => '/img/logos/blue_logo.svg',
	// 'logo_over_white' => '/img/logos/LogoWhite.svg',
	// 'logo_on_image' => '/img/logos/d_blue_tw_logo_40px_h.png',
	// 'logo_on_print_grid' => '/img/logos/d_blue_tw_logo_40px_h.png',
	// 'whitelabel_css' => '/css/whitelabels/ymca/ymca.css',
	// 'whitelabel_css_trainee' => '/css/whitelabels/ymca/ymca.css',
	// 'whitelabel_css_trainer' => '/css/whitelabels/ymca/ymca_trainer.css',
	// 'brand' => 'Trainer Workout',
	// 'feedbackEmail' => 'info@trainerworkout.com',
	// 'overrideViews' => array()

);
