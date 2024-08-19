<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('username')->unique();
            $table->string('avatar')->nullable();
            $table->string('phone', 12)->nullable();
            $table->string('address')->nullable();
            $table->dateTime('birthday')->nullable();
            $table->unsignedBigInteger('role_id')->default(2);
            $table->string('token_email')->nullable();
            $table->string('password');
            $table->string('google_id')->nullable();

            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();

            
            // khóa ngoại : cart

            // role
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('no action');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
        Schema::dropIfExists('users');

    }
};
