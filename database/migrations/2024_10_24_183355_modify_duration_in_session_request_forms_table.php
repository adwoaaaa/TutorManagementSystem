<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyDurationInSessionRequestFormsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('session_request_forms', function (Blueprint $table) {
            $table->integer('duration')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('session_request_forms', function (Blueprint $table) {
            $table->string('duration')->change();
        });
    }
};
