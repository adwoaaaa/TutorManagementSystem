<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class UpdateReportsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
             // Drop the foreign key constraint for 'student'
             $table->dropForeign(['student']);
            
             // Drop the 'student' field
             $table->dropColumn('student');
             
             
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
             // Add back the 'student' field
             $table->uuid('student')->nullable();
            
             // Re-add the foreign key constraint
             $table->foreign('student')->references('id')->on('users')->onDelete('cascade');
             
        });
    }
};
