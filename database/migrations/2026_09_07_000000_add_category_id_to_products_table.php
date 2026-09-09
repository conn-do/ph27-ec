<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // The merged July migration now owns category_id; retain this historical migration entry.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // The July migration removes the column when that migration is rolled back.
    }
};
