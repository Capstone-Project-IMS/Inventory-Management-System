<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_storages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_details_id')->nullable();
            $table->unsignedBigInteger('storage_location_id');
            $table->foreign('product_details_id')->references('id')->on('product_details')->onDelete('cascade');
            $table->foreign('storage_location_id')->references('id')->on('storage_locations')->onDelete('cascade');
            $table->bigInteger('quantity')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_storage');
    }
};
