<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('guitars', function (Blueprint $table) {
            $table->text('image_url')->nullable()->change(); // change to TEXT
        });
    }

    public function down(): void
    {
        Schema::table('guitars', function (Blueprint $table) {
            $table->string('image_url', 2048)->nullable()->change(); // revert if needed
        });
    }
};

