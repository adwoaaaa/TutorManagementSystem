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
            $table->unsignedBigInteger('session_request_form_id'); // Foreign key to session_request_forms table
            $table->string('session_status');
            $table->timestamps();

            // Foreign key constraints
         //   $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('session_request_form_id')->references('id')->on('session_request_forms')->onDelete('cascade');
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
