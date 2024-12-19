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
        Schema::create('request_tbl', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('request_refference');
            $table->string('user_id');
            $table->string('request_type_id');
            $table->string('account_type');
            $table->string('fname')->nullable();
            $table->string('lname')->nullable();
            $table->string('email')->nullable();
            $table->string('mobile')->nullable();
            $table->string('department')->nullable();
            $table->string('position')->nullable();
            $table->string('description');
            $table->string('status');
            $table->string('access_state')->nullable();
            $table->string('comment')->nullable();
            $table->string('assigned_username')->nullable();
            $table->string('assigned_role')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_tbl');
    }
};
