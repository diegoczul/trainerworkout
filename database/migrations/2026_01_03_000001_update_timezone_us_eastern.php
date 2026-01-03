<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update all users with deprecated US/Eastern timezone to America/New_York
        DB::table('users')
            ->where('timezone', 'US/Eastern')
            ->update(['timezone' => 'America/New_York']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Optionally revert back (not recommended)
        DB::table('users')
            ->where('timezone', 'America/New_York')
            ->update(['timezone' => 'US/Eastern']);
    }
};
