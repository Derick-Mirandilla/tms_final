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
        
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('name', 'first_name');
        });

 
        Schema::table('users', function (Blueprint $table) {
            $table->string('last_name')->after('first_name')->nullable(); 
            $table->string('phone')->after('email')->nullable(); 
            $table->unsignedBigInteger('role_id')->after('password')->nullable(); 
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('set null'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
            $table->dropColumn('phone');
            $table->dropColumn('last_name');
        });

        
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('first_name', 'name');
        });
    }
};
