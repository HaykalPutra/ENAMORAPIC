<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * BookingSeeder
 * 
 * Seeder ini akan mengisi tabel customer, booking, booking_detail, dan pesanan_website
 * dengan data asli dari db_enamorapic.sql.
 * 
 * Cara alternatif (lebih cepat):
 * Import langsung file SQL: database/db_enamorapic.sql ke MySQL
 * mysql -u root db_enamorapic < database/db_enamorapic.sql
 */
class BookingSeeder extends Seeder
{
    public function run(): void
    {
        // Sample customer data (dari data asli)
        $customers = [
            'Wedding prewedd diaz wo', 'Prewedd sore', 'wedding mahligai selasih',
            'wedding Kadedeuh wo', 'Ptewedd Junaidi', 'wedding cinta',
            'prewedd pagi', 'Siraman uyung', 'Wedding uyung', 'Nawrah subang',
        ];

        foreach ($customers as $nama) {
            DB::table('customer')->insert(['nama_client' => $nama, 'no_wa' => '-', 'alamat' => 0]);
        }

        // Sample booking data
        $bookings = [
            [1, 'Wedding prewedd diaz wo', '2025-01-03', '2025-01-03', 4000000, 'Lunas', 'Selesai', 6],
            [2, 'Prewedd sore',            '2025-01-11', '2025-01-11', 2000000, 'Lunas', 'Selesai', 1],
            [3, 'wedding mahligai selasih','2025-01-12', '2025-01-12', 4000000, 'Lunas', 'Selesai', 6],
            [4, 'wedding Kadedeuh wo',     '2025-01-26', '2025-01-26', 4000000, 'Lunas', 'Selesai', 6],
            [5, 'Ptewedd Junaidi',         '2025-01-27', '2025-01-27', 2000000, 'Lunas', 'Selesai', 1],
        ];

        foreach ($bookings as [$custId, $nama, $tglBook, $tglAcara, $total, $bayar, $status, $paketId]) {
            $bId = DB::table('booking')->insertGetId([
                'customer_id'       => $custId,
                'nama_client'       => $nama,
                'tgl_booking'       => $tglBook,
                'tgl_acara'         => $tglAcara,
                'total_transaksi'   => $total,
                'status_pembayaran' => $bayar,
                'job_status'        => $status, // Updated column name
                'total_termin'      => 2, // Default 2 termin
                'booking_status'    => 'booked', // Set to booked since these are old bookings
            ]);
            DB::table('booking_detail')->insert([
                'booking_id'     => $bId,
                'paket_id'       => $paketId,
                'harga_saat_ini' => $total,
            ]);
        }

        // Pesanan website sample
        DB::table('pesanan_website')->insert([
            ['paket_id'=>3,'nama_pemesan'=>'Haykal','no_wa'=>'08980564584','tanggal_booking'=>'2025-12-31','lokasi'=>'Jl. Sari Indah','status'=>'done','created_at'=>now()],
        ]);

        $this->command->info('✅ Sample data loaded. Untuk data lengkap, import: database/db_enamorapic.sql');
    }
}
