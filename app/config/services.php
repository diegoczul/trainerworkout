<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Third Party Services
	|--------------------------------------------------------------------------
	|
	| This file is for storing the credentials for third party services such
	| as Stripe, Mailgun, Mandrill, and others. This file provides a sane
	| default location for this type of information, allowing packages
	| to have a conventional place to find your various credentials.
	|
	*/

	'mailgun' => array(
		'domain' => 'trainerworkout.com',
        'secret' => 'key-a146a870cb2dc561962cfdaea19a0237',
	),

	'mandrill' => array(
		'secret' => 'sAJJs12OxJG4B4enaw4y6g',
	),

	'stripe' => array(
		'model'  => 'User',
		'secret' => '',
	),

);
