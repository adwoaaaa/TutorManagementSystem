<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropTimeColumnFromSessionRequestFormsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('session_request_forms', function (Blueprint $table) {
            $table->dropColumn('time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('session_request_forms', function (Blueprint $table) {
            $table->time('time');  
        });
    }
};
