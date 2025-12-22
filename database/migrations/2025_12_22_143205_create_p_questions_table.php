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
        Schema::create('p_questions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('p_exam_id');
            $table->text('question_text');
            $table->string('question_image')->nullable();
            $table->enum('question_type', ['multiple_choice', 'true_false', 'short_answer'])->default('multiple_choice');
            $table->integer('mark')->default(1);
            $table->integer('order')->default(1);
            $table->text('explanation')->nullable(); // New field for practice explanations
            $table->text('hint')->nullable(); // Optional hint
            $table->timestamps();

            $table->foreign('p_exam_id')->references('id')->on('p_exams')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('p_questions');
    }
};
