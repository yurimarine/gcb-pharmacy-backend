<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('generic_id')->nullable();
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->unsignedBigInteger('manufacturer_id')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->string('sku')->unique()->nullable();
            $table->string('brand_name');
            $table->string('dosage_form')->nullable();
            $table->string('packaging_type')->nullable();
            $table->decimal('volume_amount',8,2)->nullable();
            $table->string('volume_unit')->nullable();
            $table->decimal('unit_cost',8,2)->default(0);
            $table->string('barcode')->unique()->nullable();
            $table->string('description')->nullable();
            $table->timestamps();

        $table->foreign('generic_id')
        ->references('id')
        ->on('generics')
        ->onDelete('set null');
        $table->foreign('supplier_id')
        ->references('id')
        ->on('suppliers')
        ->onDelete('set null');
        $table->foreign('manufacturer_id')
        ->references('id')
        ->on('manufacturers')
        ->onDelete('set null');
        $table->foreign('category_id')
        ->references('id')
        ->on('categories')
        ->onDelete('set null');
       });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};