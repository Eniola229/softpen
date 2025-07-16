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
        Schema::create('results', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(Str::uuid());

            $table->uuid('student_id');
            $table->uuid('school_id');
            
            $table->string('class');
            $table->string('term');
            $table->string('session');

            $table->json('scores')->nullable(); // JSON field to store all subject scores

            $table->timestamps();

            // Foreign Keys (optional but recommended)
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');

            // Ensure one result per student/session/term
            $table->unique(['student_id', 'session', 'term']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
