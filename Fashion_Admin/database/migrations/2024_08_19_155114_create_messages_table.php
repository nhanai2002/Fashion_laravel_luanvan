<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->text('content');
            $table->unsignedBigInteger('conversation_id');
            $table->unsignedBigInteger('sender_id')->nullable();

            $table->timestamps();

            $table->foreign('sender_id')->references('id')->on('users')->onDelete('set null');

        });
    }


    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
