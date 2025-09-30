<?php
// database/migrations/2025_09_28_add_verbale_fields_to_uploads_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('uploads', function (Blueprint $table) {
            $table->string('verbale_file_path')->nullable()->after('completed_at');
            $table->timestamp('verbale_uploaded_at')->nullable()->after('verbale_file_path');
            $table->timestamp('verbale_sent_at')->nullable()->after('verbale_uploaded_at');
        });
    }

    public function down()
    {
        Schema::table('uploads', function (Blueprint $table) {
            $table->dropColumn(['verbale_file_path', 'verbale_uploaded_at', 'verbale_sent_at']);
        });
    }
};