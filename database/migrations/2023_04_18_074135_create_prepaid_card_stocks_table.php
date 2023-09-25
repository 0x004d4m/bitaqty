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
        Schema::create('prepaid_card_stocks', function (Blueprint $table) {
            $table->id();
            $table->string('serial1');
            $table->string('serial2')->nullable();
            $table->integer('number1');
            $table->integer('number2')->nullable();
            $table->string('cvc')->nullable();
            $table->date('expiration_date');
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prepaid_card_stocks');
    }
};
