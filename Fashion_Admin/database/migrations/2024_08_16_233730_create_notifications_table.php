<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('message');
            $table->tinyInteger('seen')->default(0);    // 0 là chưa xem, 1 là xem rồi
            $table->dateTime('date_received')->nullable();          // ngày nhận
            $table->tinyInteger('type')->default(0);    // 0 : tất cả, 1: nhóm, 2 : cá nhân
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
