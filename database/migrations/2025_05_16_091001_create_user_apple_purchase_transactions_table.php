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
        Schema::create('user_apple_purchase_transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('ref_user_id')->nullable();
            $table->string('original_transaction_id')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('transaction_type')->nullable();
            $table->datetime('expiry_date')->nullable();
            $table->integer('is_verified')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_apple_purchase_transactions');
    }
};
