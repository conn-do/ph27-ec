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
        Schema::table('products', function (Blueprint $table) {
            // セール価格（Null許可）とセール中フラグを追加
            $table->integer('sale_price')->nullable()->after('price');
            $table->boolean('is_sale')->default(false)->after('sale_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // ロールバック時に追加したカラムを削除
            $table->dropColumn(['sale_price', 'is_sale']);
        });
    }
};