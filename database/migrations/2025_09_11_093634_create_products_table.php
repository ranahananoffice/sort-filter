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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('image');
            $table->string('title');
            $table->string('tag');
            $table->text('description')->nullable();
            $table->decimal('originalPrice', 10, 2);
            $table->decimal('discountPrice', 10, 2)->nullable();
            $table->unsignedBigInteger('totalSales')->default(0);
            $table->boolean('isTopSeller')->default(false);

            $table->unsignedInteger('createdAt');
            $table->unsignedInteger('updatedAt');
            $table->unsignedInteger('deletedAt')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
