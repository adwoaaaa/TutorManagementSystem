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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();  // Using UUID
            $table->string('lastName');
            $table->string('otherNames');
            $table->string('email')->unique();
            $table->string('phoneNumber');
            $table->string('password');
            $table->enum('role', ['administrator', 'student', 'tutor']); // Enum for roles
            $table->timestamps();  // Timestamps for created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
