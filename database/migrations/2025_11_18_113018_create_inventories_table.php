<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('pharmacy_id');
            $table->integer('stock_quantity')->default(0);
            $table->integer('reorder_quantity')->default(0);
            $table->date('expiry_date')->nullable()->default(null);
            $table->decimal('markup_percentage', 8, 2)->default(0);
            $table->decimal('selling_price', 8, 2)->default(0);
            $table->timestamps();

        $table->foreign('product_id')
        ->references('id')
        ->on('products')
        ->onDelete('cascade');
        $table->foreign('pharmacy_id')
        ->references('id')
        ->on('pharmacies')
        ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory');
    }
};