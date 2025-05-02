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
        Schema::create('user_sets_history', function (Blueprint $table) {
            $table->id();
            $table->integer('ref_user_id');
            $table->integer('ref_workout_id');
            $table->integer('ref_exercise_id');
            $table->integer('ref_set_id');
            $table->string('weight');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sets_history');
    }
};
