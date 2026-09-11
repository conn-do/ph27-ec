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
            $table->foreignId('category_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->unsignedInteger('stock')->default(0)->after('price');
            $table->unsignedTinyInteger('tax_rate')->default(10)->after('stock');
            $table->boolean('is_published')->default(true)->after('image');
            $table->index(['is_published', 'category_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['is_published', 'category_id']);
            $table->dropConstrainedForeignId('category_id');
            $table->dropColumn(['stock', 'tax_rate', 'is_published']);
        });
    }
};
