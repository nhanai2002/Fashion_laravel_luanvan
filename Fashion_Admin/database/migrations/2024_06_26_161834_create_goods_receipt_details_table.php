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
        Schema::create('goods_receipt_details', function (Blueprint $table) {
            $table->id();
            $table->integer('quantity');
            $table->decimal('base_price', 15, 2);
            $table->unsignedBigInteger('warehouse_item_id')->nullable();
            $table->unsignedBigInteger('goods_receipt_id');

            $table->timestamps();


            $table->foreign('warehouse_item_id')->references('id')->on('warehouse_items')->onDelete('set null');
            $table->foreign('goods_receipt_id')->references('id')->on('goods_receipts')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goods_receipt_details');
    }
};
