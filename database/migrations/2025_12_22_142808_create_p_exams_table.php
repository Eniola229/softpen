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
        Schema::create('p_exams', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('p_class_id');
            $table->string('title');
            $table->string('subject');
            $table->uuid('department_id')->nullable();
            $table->text('description')->nullable();
            $table->integer('duration')->default(60); // minutes
            $table->integer('total_questions');
            $table->integer('passing_score')->default(40);
            $table->string('session')->nullable();
            $table->string('term')->nullable();
            $table->boolean('randomize_questions')->default(false);
            $table->boolean('show_one_question_at_time')->default(false);
            $table->boolean('show_results')->default(true);
            $table->boolean('show_explanations')->default(true); // New field for practice
            $table->text('instructions')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamps();

            $table->foreign('p_class_id')->references('id')->on('p_classes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('p_exams');
    }
};
