<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('pharmacy_id');
            $table->unsignedBigInteger('transaction_id')->nullable();
            $table->integer('quantity_change')->default(0);
            $table->string('movement_type');
            $table->string('source')->default('admin');
            $table->datetime('movement_date');
            $table->timestamps();

            $table->foreign('product_id')
            ->references('id')
            ->on('products')
            ->onDelete('cascade');
            $table->foreign('pharmacy_id')
            ->references('id')
            ->on('pharmacies')
            ->onDelete('cascade');
            $table->foreign('transaction_id')
            ->references('id')
            ->on('transactions')
            ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_movements');
    }
};
