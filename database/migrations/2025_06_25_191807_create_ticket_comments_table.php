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
        Schema::create('ticket_comments', function (Blueprint $table) {
            $table->increments('comment_id'); // Primary Key: comment_id
            $table->unsignedInteger('ticket_id');
            $table->foreign('ticket_id')->references('ticket_id')->on('tickets')->onDelete('cascade');

            $table->unsignedBigInteger('user_id'); // User who wrote the comment
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');

            $table->text('comment_text');
            $table->boolean('is_internal')->default(false);
            $table->boolean('send_email')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_comments');
    }
};
