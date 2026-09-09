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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('shipping_name')->nullable()->after('user_id');
            $table->string('shipping_phone', 30)->nullable()->after('shipping_name');
            $table->string('shipping_postal_code', 8)->nullable()->after('shipping_phone');
            $table->string('shipping_address')->nullable()->after('shipping_postal_code');
            $table->string('payment_method', 30)->nullable()->after('shipping_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'shipping_name',
                'shipping_phone',
                'shipping_postal_code',
                'shipping_address',
                'payment_method',
            ]);
        });
    }
};
