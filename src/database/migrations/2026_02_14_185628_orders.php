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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('status');
            $table->integer('products');
            $table->string('address');
            $table->timestamps('created');
            $table->timestamps('updated');
        });

        Schema::create('order_products', function (Blueprint $table){
           $table->id();
           $table->integer('order_id');
           $table->integer('product_id');
           $table->string('name')->nullable();
           $table->string('image')->nullable();
           $table->string('short_description')->nullable();
           $table->float('price');
           $table->integer('product_amount')->default(1);
        });

        Schema::create('order_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('status_name')->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
        Schema::dropIfExists('order_products');
        Schema::dropIfExists('order_statuses');
    }
};
