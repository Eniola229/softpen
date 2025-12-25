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
        Schema::create('p_student_attempts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('student_id'); 
            $table->uuid('p_exam_id');
            $table->integer('score')->default(0);
            $table->integer('total_marks')->default(0);
            $table->decimal('percentage', 5, 2)->default(0);
            $table->boolean('passed')->default(false);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->integer('time_spent')->nullable(); // in seconds
            $table->boolean('is_completed')->default(false);
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade'); // Changed to users
            $table->foreign('p_exam_id')->references('id')->on('p_exams')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('p_student_attempts');
    }
};
