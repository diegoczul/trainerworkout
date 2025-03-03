<?php

use Spatie\Newsletter\Drivers\MailChimpDriver;

return [

    /*
     * The driver to use to interact with MailChimp API.
     */
    'driver' => MailChimpDriver::class,

    /*
     * Mailchimp API settings
     */
    'mailchimp' => [
        'api_key' => env('MAILCHIMP_APIKEY'),
        'default_list_name' => 'trainer',
        'lists' => [
            'trainer' => [
                'id' => env('MAILCHIMP_TRAINERS_LIST_ID', 'ce795f36af'),
            ],
            'trainee' => [
                'id' => env('MAILCHIMP_TRAINEES_LIST_ID', 'f722232f2a'),
            ],
            'newsletter' => [
                'id' => env('MAILCHIMP_NEWSLETTER_LIST_ID', 'ce795f36af'),
            ],
        ],
    ],
];
