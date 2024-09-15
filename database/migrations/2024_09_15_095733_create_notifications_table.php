<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();  // UUID for notification ID
            $table->string('title', 100);  // Notification title
            $table->uuid('sender');  // Foreign key to the user table (sender)
            $table->uuid('recipient');  // Foreign key to the user table (recipient)
            $table->text('content');  // Text for notification content
            $table->string('status')->default('pending');  // Read status (true or false)
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('sender')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('recipient')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
