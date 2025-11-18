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
            $table->string('brand_name')->unique();
            $table->string('sku')->unique()->nullable();
            $table->string('dosage_form')->nullable();
            $table->string('dosage_amount')->nullable();
            $table->string('packaging_type')->nullable();
            $table->integer('volume_amount')->nullable();
            $table->string('volume_unit')->nullable();
            $table->integer('unit_cost')->default(0);
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
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
