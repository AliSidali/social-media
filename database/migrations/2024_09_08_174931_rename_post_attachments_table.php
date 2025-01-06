<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::rename('post_attachments', 'attachments');
        DB::statement('ALTER TABLE attachments ADD attachable_id bigint(255) null AFTER id');
        DB::statement('ALTER TABLE attachments ADD attachable_type varchar(255) null AFTER attachable_id');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('attachments', 'post_attachments');

    }
};
