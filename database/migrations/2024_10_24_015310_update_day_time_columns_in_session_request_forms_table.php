<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateDayTimeColumnsInSessionRequestFormsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    { 
         // Update existing records to convert the day field to JSON format
         DB::table('session_request_forms')->get()->each(function ($item) {
            $dayAsArray = [$item->day]; // Convert the existing day to an array format
            DB::table('session_request_forms')
                ->where('id', $item->id)
                ->update(['day' => json_encode($dayAsArray)]); // Save as JSON
        });

    // Change the columns to JSON type
       Schema::table('session_request_forms', function (Blueprint $table) {
             $table->json('day')->change();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('session_request_forms', function (Blueprint $table) {
              // Revert the 'day' and 'time' columns back to their original types (date and time)
              $table->string('day')->change();
        });
    }
}
