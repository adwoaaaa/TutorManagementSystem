<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SessionRequestFormsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('session_request_forms', function (Blueprint $table) {
            $table->id();  // Use an auto-incrementing primary key
            $table->uuid('session_id')->index(); // Alternatively, you can use this as the foreign key
            $table->string('subject');
            $table->string('course');
            $table->string('level_of_education');
            $table->string('session_period');  // E.g., 'morning', 'afternoon', etc.
            $table->string('venue');
            $table->text('additional_information')->nullable();
            $table->string('duration');
            $table->integer('repetition_period')->nullable(); // Period for repetition
            $table->string('session_status')->default('pending'); // Status of the session
            $table->date('date');
            $table->time('time');
            $table->uuid('student');  // Foreign key to users table

            $table->foreign('student')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_request_forms');
    }
};
