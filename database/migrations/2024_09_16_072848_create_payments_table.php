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

            Schema::create('payments', function (Blueprint $table) {
                $table->uuid('id')->primary();  // Using UUID for primary key
                $table->decimal('amount', 10, 2);
                $table->string('method');  // E.g., 'credit card', 'bank transfer', etc.
                $table->string('description');
                $table->uuid('session_id');  // Foreign key to sessionRequestForm table
                $table->uuid('student');  // Foreign key to users table
                $table->string('status')->default('pending');
                $table->timestamps();

                $table->foreign('session_id')->references('id')->on('sessions')->onDelete('cascade');
                $table->foreign('student')->references('id')->on('users')->onDelete('cascade');
            });
     
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
