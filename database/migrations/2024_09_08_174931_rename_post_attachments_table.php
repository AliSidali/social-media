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
        Schema::rename('post_attachments', 'attachments');
        DB::statement('ALTER TABLE attachments MODIFY attachable_id bigint(255) null AFTER id');
        DB::statement('ALTER TABLE attachments MODIFY attachable_type varchar(255) null AFTER attachable_id');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('attachments', 'post_attachments');

    }
};
