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
        Schema::create('issues', function (Blueprint $table) {
            $table->id();
            $table->text('description');
            $table->text('image')->nullable();
            $table->text('solution')->nullable();
            $table->boolean('is_solved')->default(0);
            $table->boolean('is_duplicate')->default(0);
            $table->morphs('userable');
            $table->unsignedBigInteger('issue_type_id');
            $table->foreign('issue_type_id')->references('id')->on('issue_types');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('issues');
    }
};
