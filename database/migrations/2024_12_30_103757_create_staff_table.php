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
        Schema::create('staffs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('school_id');
            $table->string('avatar')->nullable();
            $table->string('image_id')->nullable();
            $table->string('name');
            $table->string('address');
            $table->string('class');
            $table->string('department')->nullable();
            $table->string('mobile');
            $table->string('subject');
            $table->string('email')->nullable()->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->enum('status', ['ACTIVE', 'DISACTIVATE'])->default('ACTIVE');
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
