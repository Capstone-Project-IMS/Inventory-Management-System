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
        Schema::create('product_priorities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_details_id')->unique();
            $table->integer('when_to_order');
            $table->integer('order_amount');
            $table->foreign('product_details_id')->references('id')->on('product_details')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_priorities');
    }
};
