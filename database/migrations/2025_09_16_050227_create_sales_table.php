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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('userId'); // seller
            $table->unsignedBigInteger('productId');
            $table->unsignedInteger('quantity');
            $table->decimal('price', 10, 2);

            $table->unsignedInteger('createdAt');
            $table->unsignedInteger('updatedAt');
            $table->unsignedInteger('deletedAt')->nullable();

            $table->foreign('userId')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('productId')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
