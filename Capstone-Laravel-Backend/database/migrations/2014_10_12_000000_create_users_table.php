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

        
        Schema::create('user_type', function (Blueprint $table) {
            $table->id();
            $table->string('role');
            $table->timestamps();
        });

        Schema::create('employee_type', function (Blueprint $table) {
            $table->id();
            $table->string('role');
            $table->string('permission');
            $table->timestamps();
        });



        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->unsignedBigInteger('user_type_id');
            $table->foreign('user_type_id')->references('id')->on('user_type')->onDelete('restrict');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_type');
        Schema::dropIfExists('employee_type');
        Schema::dropIfExists('users');
    }
};
