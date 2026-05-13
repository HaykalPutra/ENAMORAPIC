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
        Schema::table('paket', function (Blueprint $table) {
            $table->text('media_urls')
                ->nullable()
                ->after('gambar')
                ->comment('Daftar URL media (foto/video), pisahkan per baris');

            $table->string('video_url', 500)
                ->nullable()
                ->after('media_urls')
                ->comment('URL video utama opsional untuk preview paket');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('paket', function (Blueprint $table) {
            $table->dropColumn(['media_urls', 'video_url']);
        });
    }
};
