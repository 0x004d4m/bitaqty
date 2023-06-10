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
        Schema::create('credits', function (Blueprint $table) {
            $table->id();
            $table->morphs('userable');
            $table->text('image');
            $table->double('amount')->default(0);
            $table->text('notes')->nullable();
            $table->boolean('deposit_or_withdraw');
            $table->double('credit_before')->default(0);
            $table->double('credit_after')->default(0);
            $table->unsignedBigInteger('credit_type_id');
            $table->foreign('credit_type_id')->references('id')->on('credit_types');
            $table->unsignedBigInteger('credit_status_id');
            $table->foreign('credit_status_id')->references('id')->on('credit_statuses');
            $table->unsignedBigInteger('supported_account_id')->nullable();
            $table->foreign('supported_account_id')->references('id')->on('supported_accounts');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credits');
    }
};
