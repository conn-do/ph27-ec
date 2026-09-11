<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * total_price は「税込の合計（お会計）」を表す既存カラムのため、
     * その内訳（税抜の小計・消費税・あずけたお金・おつり）を追加する。
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('order_number', 20)->nullable()->unique()->after('id');
            $table->unsignedInteger('subtotal')->default(0)->after('order_number');
            $table->unsignedInteger('tax_total')->default(0)->after('subtotal');
            $table->unsignedInteger('paid_amount')->default(0)->after('total_price');
            $table->unsignedInteger('change_amount')->default(0)->after('paid_amount');
            $table->string('status', 20)->default('paid')->after('change_amount');
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'created_at']);
            $table->dropColumn([
                'order_number',
                'subtotal',
                'tax_total',
                'paid_amount',
                'change_amount',
                'status',
            ]);
        });
    }
};
