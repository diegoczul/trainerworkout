<?php 

return array( 
	
	/*
	|--------------------------------------------------------------------------
	| oAuth Config
	|--------------------------------------------------------------------------
	*/

	/**
	 * Storage
	 */
	'storage' => 'Session', 

	/**
	 * Consumers
	 */
	'consumers' => array(

		/**
		 * Facebook
		 */
        'Facebook' => array(
            'client_id'     => '430853867021763',
            'client_secret' => '3f8d4530282a97ca54fcb2e8a091d2d2',
            'appId'     => '430853867021763',
            'secret' => '3f8d4530282a97ca54fcb2e8a091d2d2',
            'scope'         => array('email','user_friends'),
        ),		

	)

);