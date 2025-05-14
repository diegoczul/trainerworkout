<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        $events = [
            'login',
            'signUp',
            'sendInviteToClient',
            'addedAnExercise',
            'deletedAnExercise',
            'addedWorkoutMarket',
            'createTag',
            'patchCreateTag',
            'destroyTag',
            'removeTagWorkout',
            'messageClient',
            'messagePersonalTrainer',
            'messageNoneClient',
            'confirmEmail',
            'editProfileInformation',
            'loginWithFacebook',
            'signUpWithFacebook',
            'loginWithGoogle',
            'signUpWithGoogle',
            'apiSignUp',
            'apiLogin',
            'printWorkout',
            'printWorkouts',
            'userNewWorkout',
            'editAWorkout',
            'createAWorkout',
            'duplicateWorkout',
            'archiveWorkout',
            'addClient',
            'updateClient',
            'deleteClient',
            'unArchiveWorkout',
            'deleteAWorkout',
            'shareAWorkout',
            'pdfWorkout',
            'notifyActivity',
            'jsTriggeredEvent',
            'logout'
        ];

        foreach ($events as $eventName) {
            Event::listen($eventName, function (...$payload) use ($eventName) {
                $user = $payload[0] ?? null;
                $params = array_slice($payload, 1);

                app(\App\Listeners\SendSlackNotification::class)->handle($eventName, $user, ...$params);
            });
        }
    }


    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
