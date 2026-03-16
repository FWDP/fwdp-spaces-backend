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
        Schema::create('course_analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained();
            $table->integer('enrollments')->default(0);
            $table->integer('completions')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_analytics');
    }
};
