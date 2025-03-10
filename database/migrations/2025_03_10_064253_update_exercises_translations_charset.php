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
        DB::statement('ALTER TABLE `exercises_translations` CHANGE `name` `name` VARCHAR(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;');
        DB::statement('ALTER TABLE `exercises_translations` CHANGE `description` `description` VARCHAR(5000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;');
        DB::statement('ALTER TABLE `exercises_translations` CHANGE `nameEngine` `nameEngine` VARCHAR(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;');
        DB::statement('ALTER TABLE `exercises_translations` CHANGE `locale` `locale` VARCHAR(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};
