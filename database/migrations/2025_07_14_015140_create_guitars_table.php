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
        Schema::create('guitars', function (Blueprint $table) {
            $table->id();
            $table->string('name');               // Guitar name/model
            $table->decimal('price', 10, 2);      // Price with 2 decimal precision
            $table->string('brand');              // Guitar brand (e.g., Fender, Gibson)
            $table->string('image_url')->nullable(); // Optional image path or URL
            $table->integer('stock')->default(0); // Stock quantity
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guitars');
    }
};
