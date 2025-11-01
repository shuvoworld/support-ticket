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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('subject')->nullable()->default(null);
            $table->longText('content')->nullable()->default(null);
            $table->enum('priority', ['Low', 'Medium', 'High', 'Urgent'])->default('Medium')->index();

            // Relationships
            $table->foreignId('user_id')->constrained('users')->comment('The requester/client.');
            $table->foreignId('agent_id')->nullable()->constrained('users')->comment('The assigned support staff.');
            $table->foreignId('category_id')->constrained('categories');

            // New foreign key to the statuses table
            $table->foreignId('status_id')->constrained('statuses');

            $table->timestamp('closed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
