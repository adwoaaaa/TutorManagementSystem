<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sessions', function (Blueprint $table) {
            $table->uuid('id')->primary(); // UUID as primary key
            $table->text('details'); // Details of the session
            $table->string('repetition_status')->default('pending'); // Status of repetition
            $table->integer('repetition_period')->nullable(); // Period for repetition
            $table->string('session_status')->default('pending'); // Status of the session// Foreign key to paymentable
            $table->uuid('administrator'); // Foreign key to users table
            $table->uuid('student_id'); // Foreign key to users table
            $table->timestamps(); // Timestamps for created_at and updated_at

             // Foreign key constraints
             $table->foreign('administrator')->references('id')->on('users')->onDelete('cascade');
             $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
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
