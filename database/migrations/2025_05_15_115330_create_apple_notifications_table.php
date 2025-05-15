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
        Schema::create('apple_notifications', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('is_active')->default(0);
            $table->string('original_transaction_id')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('transaction_status')->nullable();
            $table->string('transaction_env')->nullable();
            $table->string('transaction_type')->nullable();
            $table->string('notification_type')->nullable();
            $table->string('iap_id')->nullable();
            $table->string('price')->nullable();
            $table->string('price_currency')->nullable();
            $table->datetime('purchase_date')->nullable();
            $table->datetime('expiry_date')->nullable();
            $table->datetime('renewal_date')->nullable();
            $table->tinyInteger('is_auto_renew')->nullable();
            $table->text('transaction_json')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apple_notifications');
    }
};
