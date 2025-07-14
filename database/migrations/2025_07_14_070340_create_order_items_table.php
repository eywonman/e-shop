<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('guitar_id');
            $table->integer('quantity');
            $table->decimal('price', 10, 2); // price at purchase time
            $table->timestamps();

            // Foreign keys
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('guitar_id')->references('id')->on('guitars');
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_items');
    }
};
