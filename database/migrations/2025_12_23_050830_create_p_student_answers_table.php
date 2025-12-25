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
        Schema::create('p_student_answers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('p_student_attempt_id');
            $table->uuid('p_question_id');
            $table->uuid('p_question_option_id')->nullable();
            $table->text('answer_text')->nullable();
            $table->boolean('is_correct')->default(false);
            $table->integer('marks_obtained')->default(0);
            $table->timestamps();

            $table->foreign('p_student_attempt_id')->references('id')->on('p_student_attempts')->onDelete('cascade');
            $table->foreign('p_question_id')->references('id')->on('p_questions')->onDelete('cascade');
            $table->foreign('p_question_option_id')->references('id')->on('p_question_options')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('p_student_answers');
    }
};
