<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lead', function (Blueprint $table) {
            $table->timestamp('last_follow_up_sent_at')->nullable()->after('next_follow_up_at');
        });
    }

    public function down(): void
    {
        Schema::table('lead', function (Blueprint $table) {
            $table->dropColumn('last_follow_up_sent_at');
        });
    }
};
