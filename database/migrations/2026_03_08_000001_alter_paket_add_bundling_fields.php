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
            // Add paket type untuk membedakan wedding_only, prewedd_only, all_in
            $table->enum('paket_type', ['wedding_only', 'prewedd_only', 'all_in'])
                ->default('wedding_only')
                ->after('kategori')
                ->comment('Jenis paket: wedding only, prewedd only, atau all in');
            
            // Add bundling name untuk all_in packages (KANIA, Calia)
            $table->string('bundling_name', 100)
                ->nullable()
                ->after('paket_type')
                ->comment('Nama bundling untuk all_in packages (KANIA, Calia)');
            
            // Add duration type untuk bundling packages (halfday, fullday)
            $table->enum('duration_type', ['halfday', 'fullday'])
                ->nullable()
                ->after('bundling_name')
                ->comment('Durasi layanan untuk bundling packages');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('paket', function (Blueprint $table) {
            $table->dropColumn(['paket_type', 'bundling_name', 'duration_type']);
        });
    }
};
