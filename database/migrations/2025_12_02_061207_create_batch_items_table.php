<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('batch_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('batch_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('in_stock_quantity')->default(0);
            $table->integer('out_stock_quantity')->default(0);
            $table->integer('prev_stock_quantity')->default(0);
            $table->integer('new_stock_quantity')->default(0);
            $table->date('new_expiry_date')->nullable();
            $table->timestamps();

            $table->foreign('batch_id')->references('id')->on('batches')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('batch_items');
    }
};
