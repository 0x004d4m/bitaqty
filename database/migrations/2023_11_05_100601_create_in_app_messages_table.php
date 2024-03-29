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
        Schema::create('in_app_messages', function (Blueprint $table) {
            $table->id();
            $table->text('type')->default('Client');
            $table->json('title');
            $table->json('description');
            $table->text('image');
            $table->text('action')->nullable();
            $table->boolean('is_important')->default(false);
            $table->boolean('is_active')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('in_app_messages');
    }
};
