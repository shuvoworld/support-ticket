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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->longText('content')->default(null)->nullable()->index();
            $table->foreignId('ticket_id')->constrained('tickets')->onDelete('cascade'); // Link to the ticket
            $table->foreignId('user_id')->constrained('users'); // The person who left the comment (Requester or Agent)
            // Internal Notes Feature
            $table->boolean('is_internal')->default(false)->comment('If true, this is a private note visible only to agents.');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
