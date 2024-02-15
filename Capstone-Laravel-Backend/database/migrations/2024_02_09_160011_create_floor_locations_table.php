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
        Schema::create('floor_locations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('location_id');
            $table->unsignedBigInteger('product_details_id')->nullable();
            $table->string('aisle', 1);
            $table->unsignedTinyInteger('row');
            $table->unsignedTinyInteger('position');
            $table->integer('max_capacity');
            $table->integer('current_capacity');
            $table->unique(['location_id','aisle', 'row', 'position']);
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
            $table->foreign('product_details_id')->references('id')->on('product_details')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('floor_locations');
    }
};
