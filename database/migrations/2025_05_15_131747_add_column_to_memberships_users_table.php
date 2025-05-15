<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('memberships_users', function (Blueprint $table) {
            $table->integer('is_verified')->default(0)->comment('This is for the Apple In App Purchase ID.')->after('downgrade_to');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('memberships_users', function (Blueprint $table) {
            //
        });
    }
};
