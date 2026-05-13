<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // USERS (password = md5 dari original)
        DB::table('users')->insert([
            ['username' => 'ceo',   'password' => '0192023a7bbd73250516f069df18b500', 'nama_lengkap' => 'CEO Enamora',   'role' => 'CEO'],
            ['username' => 'admin', 'password' => '0192023a7bbd73250516f069df18b500', 'nama_lengkap' => 'Admin Staff',   'role' => 'ADMIN'],
        ]);

        // PAKET (14 paket sesuai data asli)
        DB::table('paket')->insert([
            ['nama_paket'=>'Couple Session - Photo Only','kategori'=>'Pre-Wedding','deskripsi'=>'1 Day Session (5 Hours), 1 Photographer, 1 Assistant. Output: 100++ Edited Photos (Tone), 2 Prints 12RP w/ Frame, Soft File via Drive. (Weekday Only).','harga'=>2000000,'gambar'=>'prewedd.jpeg'],
            ['nama_paket'=>'Couple Session - Photo Video','kategori'=>'Pre-Wedding','deskripsi'=>'1 Day Session (5 Hours), 1 Photo, 1 Video, 1 Assistant. Output: 100++ Edited Photos, 1-2 Min Cinematic Video, 2 Prints 16RP + 4 Prints 4R w/ Frame. (Weekday Only).','harga'=>3000000,'gambar'=>'prewedd.jpeg'],
            ['nama_paket'=>'Wedding Intimate - Photo Only','kategori'=>'Wedding','deskripsi'=>'Durasi 4 Jam. 1 Photographer, 1 Assistant. Output: 100++ Edited Photos, 2 Prints 12RP w/ Frame, Soft File via Drive.','harga'=>2000000,'gambar'=>'wedding.jpeg'],
            ['nama_paket'=>'Wedding Intimate - Photo Video','kategori'=>'Wedding','deskripsi'=>'Durasi 4 Jam. 1 Photo, 1 Video, 1 Assistant. Output: 100++ Edited Photos, 1-2 Min Cinematic, 30-60 Min Video Documentation, 2 Album Magazine, Flashdisk.','harga'=>3000000,'gambar'=>'wedding.jpeg'],
            ['nama_paket'=>'Wedding Halfday - Photo Only','kategori'=>'Wedding','deskripsi'=>'Durasi 8 Jam. 1 Photographer, 1 Assistant. Output: 100++ Edited Photos, 2 Prints 12RP w/ Frame, Soft File via Drive.','harga'=>2500000,'gambar'=>'wedding.jpeg'],
            ['nama_paket'=>'Wedding Halfday - Photo Video','kategori'=>'Wedding','deskripsi'=>'Durasi 8 Jam. 1 Photo, 1 Video, 1 Assistant. Output: 100++ Edited Photos, 1-2 Min Cinematic, 30-60 Min Video Documentation, 2 Album Magazine, Flashdisk.','harga'=>4000000,'gambar'=>'wedding.jpeg'],
            ['nama_paket'=>'Wedding Fullday - Photo Only','kategori'=>'Wedding','deskripsi'=>'Durasi 16 Jam. 1 Photographer, 1 Assistant. Output: 100++ Edited Photos, 2 Prints 12RP w/ Frame, Soft File via Drive.','harga'=>4000000,'gambar'=>'wedding.jpeg'],
            ['nama_paket'=>'Wedding Fullday - Photo Video','kategori'=>'Wedding','deskripsi'=>'Durasi 16 Jam. 1 Photo, 1 Video, 1 Assistant. Output: 100++ Edited Photos, 1-2 Min Cinematic, 30-60 Min Video Documentation, 2 Album Magazine, Flashdisk.','harga'=>7000000,'gambar'=>'wedding.jpeg'],
            ['nama_paket'=>'Bundling KANIA (Halfday)','kategori'=>'Wedding','deskripsi'=>'WEDDING (8 Jam) + PREWEDD. 1 Photo & 1 Video on Wedding & Prewedd. Include: All Files, Cinematic Video, Album Magazine, Prints, Flashdisk.','harga'=>7000000,'gambar'=>'wedding.jpeg'],
            ['nama_paket'=>'Bundling KANIA (Fullday)','kategori'=>'Wedding','deskripsi'=>'WEDDING (16 Jam) + PREWEDD. 1 Photo & 1 Video on Wedding & Prewedd. Include: All Files, Cinematic Video, Album Magazine, Prints, Flashdisk.','harga'=>10000000,'gambar'=>'wedding.jpeg'],
            ['nama_paket'=>'Bundling CALIA (Halfday)','kategori'=>'Wedding','deskripsi'=>'WEDDING (8 Jam) + PREWEDD. Wedding: 2 Photographer + 1 Videographer. Prewedd: 1 Photo + 1 Video. Include: All Files, Cinematic, Album Magazine, Prints.','harga'=>9000000,'gambar'=>'wedding.jpeg'],
            ['nama_paket'=>'Bundling CALIA (Fullday)','kategori'=>'Wedding','deskripsi'=>'WEDDING (16 Jam) + PREWEDD. Wedding: 2 Photographer + 1 Videographer. Prewedd: 1 Photo + 1 Video. Include: All Files, Cinematic, Album Magazine, Prints.','harga'=>12000000,'gambar'=>'wedding.jpeg'],
            ['nama_paket'=>'Special Day - Photo Only','kategori'=>'Engagement','deskripsi'=>'Engagement/Birthday/Aqiqah. Durasi 5 Jam. 1 Photographer, 1 Assistant. Output: 100++ Edited Photos, 2 Prints 12RP, Soft File.','harga'=>1500000,'gambar'=>'engagement.jpeg'],
            ['nama_paket'=>'Special Day - Photo Video','kategori'=>'Engagement','deskripsi'=>'Engagement/Birthday/Aqiqah. Durasi 5 Jam. 1 Photo, 1 Video, 1 Assistant. Output: 100++ Edited Photos, 1-2 Min Cinematic Video.','harga'=>2500000,'gambar'=>'engagement.jpeg'],
        ]);

        // PEGAWAI
        DB::table('pegawai')->insert([
            ['nama'=>'Reza Rahardian','gear'=>'PC Editing Ryzen 9 + RTX 3060','role'=>'Editor','gaji'=>5500000,'domisili'=>'Bandung','no_wa'=>'6281122334402'],
            ['nama'=>'Dimas Anggara','gear'=>'Sony A1 + G Master Lens Set','role'=>'Photographer','gaji'=>7500000,'domisili'=>'Jakarta','no_wa'=>'6281122334403'],
            ['nama'=>'Vino Bastian','gear'=>'Red Komodo + Cine Lens Kit','role'=>'Videographer','gaji'=>8000000,'domisili'=>'Jakarta','no_wa'=>'6281122334404'],
            ['nama'=>'Putri Marinoo','gear'=>'Macbook Pro M2 Max','role'=>'Editor','gaji'=>6000000,'domisili'=>'Bandung','no_wa'=>'6281122334405'],
        ]);

        // FREELANCE (sample)
        DB::table('freelance')->insert([
            ['nama'=>'Andi Saputra','gear'=>'Sony A7III + Tamron 28-75mm','role'=>'Photographer','harga'=>1500000,'domisili'=>'Bandung','no_wa'=>'6281234567891'],
            ['nama'=>'Bayu Nugraha','gear'=>'Panasonic GH5 + Gimbal Ronin','role'=>'Videographer','harga'=>2000000,'domisili'=>'Jakarta','no_wa'=>'6281234567892'],
            ['nama'=>'Citra Lestari','gear'=>'Macbook Pro M1','role'=>'Editor','harga'=>750000,'domisili'=>'Bandung','no_wa'=>'6281234567893'],
            ['nama'=>'Dedi Kusnadi','gear'=>'Canon R6 + 50mm f1.2','role'=>'Photographer','harga'=>1800000,'domisili'=>'Cimahi','no_wa'=>'6281234567894'],
            ['nama'=>'Eka Pratiwi','gear'=>'Reflector & Light Stand','role'=>'Assistant','harga'=>300000,'domisili'=>'Bandung','no_wa'=>'6281234567895'],
        ]);

        // Import SQL file data langsung
        $this->call(BookingSeeder::class);
    }
}
