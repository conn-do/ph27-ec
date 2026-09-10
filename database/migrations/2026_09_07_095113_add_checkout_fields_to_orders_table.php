<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            $table->string('recipient_name', 100)->nullable();
            $table->string('postal_code', 8)->nullable();
            $table->string('address')->nullable();
            $table->string('phone', 20)->nullable();
            $table->unsignedInteger('shipping_fee')->default(0);
            $table->uuid('checkout_token')->nullable()->unique();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            $table->dropUnique(['checkout_token']);
            $table->dropColumn(['recipient_name', 'postal_code', 'address', 'phone', 'shipping_fee', 'checkout_token']);
        });
    }
};
