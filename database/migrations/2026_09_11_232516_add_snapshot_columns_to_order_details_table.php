<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * 商品名や値段はあとから変わるため、注文したときの値を明細に残しておく。
     */
    public function up(): void
    {
        Schema::table('order_details', function (Blueprint $table) {
            $table->string('product_name')->after('product_id');
            $table->unsignedInteger('unit_price')->default(0)->after('product_name');
            $table->unsignedTinyInteger('tax_rate')->default(10)->after('unit_price');
            $table->unsignedInteger('subtotal')->default(0)->after('quantity');
            $table->unsignedInteger('tax_amount')->default(0)->after('subtotal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_details', function (Blueprint $table) {
            $table->dropColumn([
                'product_name',
                'unit_price',
                'tax_rate',
                'subtotal',
                'tax_amount',
            ]);
        });
    }
};
