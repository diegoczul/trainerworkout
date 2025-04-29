<?php
// app/Console/Commands/ReleaseFrozenEarnings.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ReleaseFrozenEarnings extends Command
{

    protected $signature = 'app:release-frozen-earnings';
    protected $description = 'Convert trainer earnings from frozen to available after 7 days';

    public function handle()
    {
        $affected = DB::table('trainer_earnings')
            ->where('status', 'frozen')
            ->where('created_at', '<=', now()->subDays(7))
            ->update(['status' => 'available']);

        $this->info("Released {$affected} frozen earnings.");
    }
}
