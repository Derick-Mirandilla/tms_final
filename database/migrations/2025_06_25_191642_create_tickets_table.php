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
            $table->increments('ticket_id'); // Primary Key: ticket_id
            $table->string('reference_number')->unique();
            $table->string('subject');
            $table->text('description')->nullable();
            $table->timestamp('resolved_at')->nullable();

            // Foreign Keys
            $table->unsignedInteger('status_id');
            $table->foreign('status_id')->references('status_id')->on('ticket_statuses')->onDelete('restrict');

            $table->unsignedInteger('priority_id');
            $table->foreign('priority_id')->references('priority_id')->on('priority_levels')->onDelete('restrict');

            $table->unsignedInteger('customer_id');
            $table->foreign('customer_id')->references('customer_id')->on('customers')->onDelete('restrict');

            $table->unsignedBigInteger('created_by_user_id'); // User who created the ticket
            $table->foreign('created_by_user_id')->references('id')->on('users')->onDelete('restrict'); // Refers to 'id' of users table

            $table->unsignedBigInteger('assigned_agent_id')->nullable(); // User assigned to the ticket
            $table->foreign('assigned_agent_id')->references('id')->on('users')->onDelete('set null');

            $table->unsignedInteger('category_id');
            $table->foreign('category_id')->references('category_id')->on('categories')->onDelete('restrict');

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
