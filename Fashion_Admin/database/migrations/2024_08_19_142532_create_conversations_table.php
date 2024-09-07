<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_taken_over')->default(false);   // Nếu có ng tiếp quản là true
            $table->boolean('is_welcomed')->default(false);     // kiểm tra gửi tn tự động đầu tiên chưa
            $table->unsignedBigInteger('user_id');

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
