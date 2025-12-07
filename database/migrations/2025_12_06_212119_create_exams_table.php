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
        Schema::create('exams', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('school_id');
            $table->uuid('class_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('subject');
            $table->string('department')->nullable();
            $table->integer('duration'); // in minutes
            $table->integer('total_questions');
            $table->integer('passing_score'); // percentage or points
            $table->text('instructions')->nullable();
            $table->boolean('is_published')->default(false);
            $table->boolean('show_results')->default(true);
            $table->boolean('randomize_questions')->default(false);
            $table->boolean('show_one_question_at_time')->default(false);
            $table->timestamps();
            
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            $table->foreign('class_id')->references('id')->on('sch_classes')->onDelete('cascade');
            $table->index(['school_id', 'class_id', 'is_published']);
        });

        Schema::create('questions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('exam_id');
            $table->longText('question_text');
            $table->string('question_image')->nullable(); // path to image
            $table->enum('question_type', ['multiple_choice', 'true_false', 'short_answer'])->default('multiple_choice');
            $table->integer('order'); // question order in exam
            $table->integer('mark')->default(1); // points for this question
            $table->timestamps();
            
            $table->foreign('exam_id')->references('id')->on('exams')->onDelete('cascade');
            $table->index(['exam_id', 'order']);
        });

        Schema::create('options', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('question_id');
            $table->longText('option_text');
            $table->string('option_image')->nullable(); // path to image for diagram
            $table->boolean('is_correct')->default(false);
            $table->integer('order'); // option order (A, B, C, D)
            $table->timestamps();
            
            $table->foreign('question_id')->references('id')->on('questions')->onDelete('cascade');
            $table->index(['question_id', 'order']);
        });

        Schema::create('exam_results', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('exam_id');
            $table->uuid('student_id');
            $table->timestamp('started_at')->nullable(); 
            $table->timestamp('submitted_at')->nullable();
            $table->integer('total_score')->nullable();
            $table->decimal('percentage', 5, 2)->nullable();
            $table->enum('status', ['in_progress', 'submitted', 'graded'])->default('in_progress');
            $table->timestamps();
            
            $table->foreign('exam_id')->references('id')->on('exams')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->index(['exam_id', 'student_id']);
            $table->index(['status']);
        });

        Schema::create('student_answers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('exam_result_id');
            $table->uuid('question_id');
            $table->uuid('selected_option_id')->nullable(); // for multiple choice
            $table->longText('answer_text')->nullable(); // for short answer
            $table->boolean('is_correct')->nullable();
            $table->integer('marks_obtained')->nullable();
            $table->timestamps();
            
            $table->foreign('exam_result_id')->references('id')->on('exam_results')->onDelete('cascade');
            $table->foreign('question_id')->references('id')->on('questions')->onDelete('cascade');
            $table->foreign('selected_option_id')->references('id')->on('options')->onDelete('set null');
            $table->index(['exam_result_id', 'question_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_answers');
        Schema::dropIfExists('exam_results');
        Schema::dropIfExists('options');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('exams');
    }
};