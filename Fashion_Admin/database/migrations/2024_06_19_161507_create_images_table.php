<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->string('url');
            $table->unsignedBigInteger('product_id');
            $table->timestamps();


            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
