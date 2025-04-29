<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Auth;
use App\Models\Users; // Adjust if your User model is elsewhere


class TestEventDispatch extends Command
{
    protected $signature = 'test:event-dispatch';
    protected $description = 'Test event dispatch for debugging Slack notification listener';

    public function handle()
    {
        // Simulate a logged-in user manually
        $user = $user = Users::where('email', "luisczul@gmail.com")->first();


        if (!$user) {
            $this->error('No user found.');
            return;
        }

        Auth::login($user); // Fake login (optional if needed)

        // Example dispatch
        Event::dispatch('shareAWorkout', [$user, 142, "Workout A"]);

        $this->info('Test event dispatched.');
    }
}
