<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('batches', function (Blueprint $table) {
            $table->id();
            $table->string('batch_number');
            $table->unsignedBigInteger('pharmacy_id');
            $table->date('batch_date')->default(now());
            $table->timestamps();

            $table->foreign('pharmacy_id')->references('id')->on('pharmacies');
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('batches');
    }
};