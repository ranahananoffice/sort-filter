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
        Schema::create('top_sellers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('userId');
            $table->unsignedSmallInteger('year');
            $table->unsignedTinyInteger('month');
            $table->unsignedBigInteger('totalSales');

            $table->unsignedInteger('createdAt');
            $table->unsignedInteger('updatedAt');
            $table->unsignedInteger('deletedAt')->nullable();

            $table->unique(['userId', 'year', 'month']);
            $table->foreign('userId')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('top_sellers');
    }
};
