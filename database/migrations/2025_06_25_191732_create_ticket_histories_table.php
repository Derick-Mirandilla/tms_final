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
        Schema::create('ticket_histories', function (Blueprint $table) {
            $table->increments('history_id'); // Primary Key: history_id

            $table->unsignedInteger('ticket_id');
            $table->foreign('ticket_id')->references('ticket_id')->on('tickets')->onDelete('cascade');

            $table->unsignedBigInteger('user_id'); // User who recorded the entry
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');

            $table->unsignedInteger('action_type_id');
            $table->foreign('action_type_id')->references('action_type_id')->on('action_types')->onDelete('restrict');

            $table->string('changed_field')->nullable(); // e.g., 'status_id', 'assigned_agent_id'
            $table->string('old_value')->nullable();
            $table->string('new_value')->nullable();
            $table->timestamp('recorded_at')->useCurrent(); // Automatically set current timestamp

            $table->timestamps(); // For created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_histories');
    }
};
