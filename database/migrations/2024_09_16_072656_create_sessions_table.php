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
        Schema::create('sessions', function (Blueprint $table) {
            $table->uuid('id')->primary(); // UUID as primary key
            $table->string('repetition_status')->default('pending'); // Status of repetition
            $table->integer('repetition_period')->nullable(); // Period for repetition
            $table->string('session_status')->default('pending'); // Status of the session
            $table->uuid('session_request_form_id'); // Foreign key to session_request_forms table
            $table->uuid('student_id'); // Foreign key to users table
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('session_request_form_id')->references('sessionID')->on('session_request_forms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
    }
};
