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
        Schema::table('users', function (Blueprint $table) {
            $table->string('new_email')->nullable()->comment('This is for the email change confirmation flow not being used anywhere else.');
            $table->string('new_email_token')->nullable()->comment('This is for the email change confirmation flow not being used anywhere else.');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->removeColumn('new_email');
            $table->removeColumn('new_email_token');
        });
    }
};
