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
        Schema::table('apple_notifications', function (Blueprint $table) {
            $table->integer('is_verified')->default(0)->comment('This is for the Apple In App Purchase ID.')->after('is_auto_renew');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('apple_notifications', function (Blueprint $table) {
            $table->dropColumn('is_verified');
        });
    }
};
