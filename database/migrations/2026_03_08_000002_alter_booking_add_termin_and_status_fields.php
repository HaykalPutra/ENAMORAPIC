<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('booking', function (Blueprint $table) {
            // Add prewedd date dan location untuk all_in packages (jika belum ada)
            if (!Schema::hasColumn('booking', 'tgl_prewedd')) {
                $table->date('tgl_prewedd')
                    ->nullable()
                    ->after('tgl_acara')
                    ->comment('Tanggal pre-wedding untuk all_in packages');
            }
            
            if (!Schema::hasColumn('booking', 'lokasi_prewedd')) {
                $table->string('lokasi_prewedd', 255)
                    ->nullable()
                    ->after('tgl_prewedd')
                    ->comment('Lokasi pre-wedding untuk all_in packages');
            }
            
            // Add lokasi_wedding jika belum ada
            if (!Schema::hasColumn('booking', 'lokasi_wedding')) {
                $table->string('lokasi_wedding', 255)
                    ->nullable()
                    ->after('lokasi_prewedd')
                    ->comment('Lokasi wedding');
            }
            
            // Total installments - 2 atau 3 (jika belum ada)
            if (!Schema::hasColumn('booking', 'total_termin')) {
                $table->tinyInteger('total_termin')
                    ->default(2)
                    ->after('lokasi_wedding')
                    ->comment('Total jumlah termin pembayaran (2 atau 3)');
            }
            
            // Booking status - pending, booked, completed, cancelled (jika belum ada)
            if (!Schema::hasColumn('booking', 'booking_status')) {
                $table->enum('booking_status', ['pending', 'booked', 'completed', 'cancelled'])
                    ->default('pending')
                    ->after('total_termin')
                    ->comment('Status booking: pending (menunggu approval CEO)');
            }
            
            // Rename status_acara to job_status jika masih status_acara
            if (Schema::hasColumn('booking', 'status_acara') && !Schema::hasColumn('booking', 'job_status')) {
                // Gunakan raw query untuk MariaDB compatibility
                DB::statement(
                    "ALTER TABLE `booking` CHANGE COLUMN `status_acara` `job_status` VARCHAR(50) DEFAULT 'Pending'"
                );
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking', function (Blueprint $table) {
            // Drop columns jika ada
            $columns = ['tgl_prewedd', 'lokasi_prewedd', 'lokasi_wedding', 'total_termin', 'booking_status'];
            foreach ($columns as $col) {
                if (Schema::hasColumn('booking', $col)) {
                    $table->dropColumn($col);
                }
            }
            
            // Rename job_status back to status_acara jika ada
            if (Schema::hasColumn('booking', 'job_status') && !Schema::hasColumn('booking', 'status_acara')) {
                DB::statement(
                    "ALTER TABLE `booking` CHANGE COLUMN `job_status` `status_acara` VARCHAR(50) DEFAULT 'Pending'"
                );
            }
        });
    }
};
