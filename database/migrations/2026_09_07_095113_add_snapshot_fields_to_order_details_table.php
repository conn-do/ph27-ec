<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_details', function (Blueprint $table): void {
            $table->string('product_name')->nullable();
            $table->unsignedInteger('unit_price')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('order_details', function (Blueprint $table): void {
            $table->dropColumn(['product_name', 'unit_price']);
        });
    }
};
