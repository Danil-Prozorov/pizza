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
        Schema::create('products', function (Blueprint $table){
            $table->id();
            $table->string('name');
            $table->string('image')->nullable();
            $table->float('price')->default(0);
            $table->text('description')->nullable();
            $table->text('recipe')->nullable();
            $table->string('short_desc')->nullable();
            $table->integer('category')->nullable();
            $table->integer('stock')->default(0);
            $table->boolean('active')->default(0);
            $table->integer('status')->nullable();
        });

        Schema::create('product_statuses', function (Blueprint $table){
            $table->id()->primary();
            $table->string('status_name')->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_statuses');
    }
};
