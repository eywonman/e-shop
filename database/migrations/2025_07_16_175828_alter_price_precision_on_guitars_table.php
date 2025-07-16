<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('guitars', function (Blueprint $table) {
            // Change to decimal(15, 2) → allows up to ₱999,999,999,999.99
            $table->decimal('price', 15, 2)->change();
        });
    }

    public function down(): void
    {
        Schema::table('guitars', function (Blueprint $table) {
            // Revert to original, e.g., (8,2) if that was the original
            $table->decimal('price', 8, 2)->change();
        });
    }
};

