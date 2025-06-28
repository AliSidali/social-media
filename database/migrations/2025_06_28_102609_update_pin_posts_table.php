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
        Schema::table('pin_posts', function (Blueprint $table) {
            $table->dropColumn('isPinned');
            $table->foreignId('post_id')->references('id')->on('posts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pin_posts', function (Blueprint $table) {
            $table->boolean('isPinned')->default(false);
            $table->dropColumn('post_id');
        });
    }
};
