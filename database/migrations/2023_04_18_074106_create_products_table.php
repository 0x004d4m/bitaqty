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
            $table->json('name');
            $table->json('description');
            $table->json('unavailable_notes')->nullable();
            $table->json('how_to_use')->nullable();
            $table->text('image');
            $table->double('price');
            $table->double('suggested_price')->default(0);
            $table->double('cost_price')->default(0);
            $table->double('selling_price')->default(0);
            $table->integer('stock')->default(0);
            $table->integer('stock_limit')->default(0);
            $table->boolean('is_active')->default(false);
            $table->boolean('is_vip')->default(false);
            $table->unsignedBigInteger('type_id');
            $table->foreign('type_id')->references('id')->on('types');
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->unsignedBigInteger('subcategory_id');
            $table->foreign('subcategory_id')->references('id')->on('subcategories');
            $table->timestamps();
            $table->softDeletes();
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
