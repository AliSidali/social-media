<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('follower_users', function (Blueprint $table) {

            $table->foreignId('follower_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('followed_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['follower_id', 'followed_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
