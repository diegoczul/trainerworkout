<?php
namespace App\Services;

use Exception;
use SendGrid;

class SendGridSubscriptionService
{
    protected $sendGrid;

    public function __construct()
    {
        $this->sendGrid = new SendGrid(config('services.sendgrid.api_key'));
    }

    /**
     * Subscribe a user to a contact list
     *
     * @param array  $userDetails  Array containing 'email' and other fields.
     * @param string $listId       The SendGrid list ID.
     * @return mixed
     */
    public function subscribeToList(array $userDetails, string $listId)
    {
        $data = [
            'list_ids' => [$listId],
            'contacts' => [
                [
                    'email' => $userDetails['email'],
                ]
            ],
        ];

        $request = new SendGrid\Mail\Mail();
        $endpoint = '/marketing/contacts';

        try {
            $response = $this->sendGrid->client->marketing()->contacts()->put($data);
            return $response->statusCode();
        } catch (Exception $e) {
            return 'Caught exception: ' . $e->getMessage();
        }
    }
}