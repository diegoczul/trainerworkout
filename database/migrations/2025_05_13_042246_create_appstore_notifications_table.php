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
        Schema::create('appstore_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('original_transaction_id');
            $table->string('transaction_id');
            $table->string('transaction_status');
            $table->string('transaction_env');
            $table->string('transaction_type');
            $table->string('notification_type');
            $table->string('iap_id');
            $table->decimal('price',10,2);
            $table->string('price_currency');
            $table->dateTime('purchase_date');
            $table->dateTime('expiry_date');
            $table->tinyInteger('is_auto_renew')->nullable();
            $table->text('transaction_json')->nullable();
            $table->tinyInteger('is_verified')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appstore_notifications');
    }
};
