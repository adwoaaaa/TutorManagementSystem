<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPublicSubmissionFieldsToReportsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
             // Make the student column nullable for public submissions
             $table->uuid('student')->nullable()->change();

             // Add name, email, and phoneNumber columns for public submissions
             $table->string('name')->nullable();        // Name of the public user
             $table->string('email')->nullable();       // Email of the public user
             $table->string('phoneNumber', 15)->nullable();  // Phone number of the public user 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
             // Reverse the changes: drop the newly added columns
             $table->dropColumn(['name', 'email', 'phoneNumber']);

             // Make the student column non-nullable again
             $table->uuid('student')->nullable(false)->change();
        });
    }
};
