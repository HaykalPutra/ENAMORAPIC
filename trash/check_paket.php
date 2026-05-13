<?php
// Quick check script untuk lihat paket data

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$count = \Illuminate\Support\Facades\DB::table('paket')->count();
echo "Total Paket: " . $count . "\n";

if ($count === 0) {
    echo "\n⚠️ Tabel paket kosong! Insert test data...\n\n";
    
    $pakets = [
        ['nama_paket'=>'Couple Session - Photo Only','kategori'=>'Pre-Wedding','deskripsi'=>'1 Day Session (5 Hours), 1 Photographer, 1 Assistant. Output: 100++ Edited Photos (Tone), 2 Prints 12RP w/ Frame, Soft File via Drive. (Weekday Only).','harga'=>2000000,'gambar'=>'prewedd.jpeg'],
        ['nama_paket'=>'Couple Session - Photo Video','kategori'=>'Pre-Wedding','deskripsi'=>'1 Day Session (5 Hours), 1 Photo, 1 Video, 1 Assistant. Output: 100++ Edited Photos, 1-2 Min Cinematic Video, 2 Prints 16RP + 4 Prints 4R w/ Frame. (Weekday Only).','harga'=>3000000,'gambar'=>'prewedd.jpeg'],
        ['nama_paket'=>'Wedding Intimate - Photo Only','kategori'=>'Wedding','deskripsi'=>'Durasi 4 Jam. 1 Photographer, 1 Assistant. Output: 100++ Edited Photos, 2 Prints 12RP w/ Frame, Soft File via Drive.','harga'=>2000000,'gambar'=>'wedding.jpeg'],
        ['nama_paket'=>'Wedding Intimate - Photo Video','kategori'=>'Wedding','deskripsi'=>'Durasi 4 Jam. 1 Photo, 1 Video, 1 Assistant. Output: 100++ Edited Photos, 1-2 Min Cinematic, 30-60 Min Video Documentation, 2 Album Magazine, Flashdisk.','harga'=>3000000,'gambar'=>'wedding.jpeg'],
        ['nama_paket'=>'Wedding Halfday - Photo Only','kategori'=>'Wedding','deskripsi'=>'Durasi 8 Jam. 1 Photographer, 1 Assistant. Output: 100++ Edited Photos, 2 Prints 12RP w/ Frame, Soft File via Drive.','harga'=>2500000,'gambar'=>'wedding.jpeg'],
        ['nama_paket'=>'Wedding Halfday - Photo Video','kategori'=>'Wedding','deskripsi'=>'Durasi 8 Jam. 1 Photo, 1 Video, 1 Assistant. Output: 100++ Edited Photos, 1-2 Min Cinematic, 30-60 Min Video Documentation, 2 Album Magazine, Flashdisk.','harga'=>4000000,'gambar'=>'wedding.jpeg'],
        ['nama_paket'=>'Wedding Fullday - Photo Only','kategori'=>'Wedding','deskripsi'=>'Durasi 16 Jam. 1 Photographer, 1 Assistant. Output: 100++ Edited Photos, 2 Prints 12RP w/ Frame, Soft File via Drive.','harga'=>4000000,'gambar'=>'wedding.jpeg'],
        ['nama_paket'=>'Wedding Fullday - Photo Video','kategori'=>'Wedding','deskripsi'=>'Durasi 16 Jam. 1 Photo, 1 Video, 1 Assistant. Output: 100++ Edited Photos, 1-2 Min Cinematic, 30-60 Min Video Documentation, 2 Album Magazine, Flashdisk.','harga'=>7000000,'gambar'=>'wedding.jpeg'],
        ['nama_paket'=>'Bundling KANIA (Halfday)','kategori'=>'Wedding','paket_type'=>'all_in','bundling_name'=>'KANIA','duration_type'=>'halfday','deskripsi'=>'WEDDING (8 Jam) + PREWEDD. 1 Photo & 1 Video on Wedding & Prewedd. Include: All Files, Cinematic Video, Album Magazine, Prints, Flashdisk.','harga'=>7000000,'gambar'=>'wedding.jpeg'],
        ['nama_paket'=>'Bundling KANIA (Fullday)','kategori'=>'Wedding','paket_type'=>'all_in','bundling_name'=>'KANIA','duration_type'=>'fullday','deskripsi'=>'WEDDING (16 Jam) + PREWEDD. 1 Photo & 1 Video on Wedding & Prewedd. Include: All Files, Cinematic Video, Album Magazine, Prints, Flashdisk.','harga'=>10000000,'gambar'=>'wedding.jpeg'],
        ['nama_paket'=>'Bundling CALIA (Halfday)','kategori'=>'Wedding','paket_type'=>'all_in','bundling_name'=>'CALIA','duration_type'=>'halfday','deskripsi'=>'WEDDING (8 Jam) + PREWEDD. Wedding: 2 Photographer + 1 Videographer. Prewedd: 1 Photo + 1 Video. Include: All Files, Cinematic, Album Magazine, Prints.','harga'=>9000000,'gambar'=>'wedding.jpeg'],
        ['nama_paket'=>'Bundling CALIA (Fullday)','kategori'=>'Wedding','paket_type'=>'all_in','bundling_name'=>'CALIA','duration_type'=>'fullday','deskripsi'=>'WEDDING (16 Jam) + PREWEDD. Wedding: 2 Photographer + 1 Videographer. Prewedd: 1 Photo + 1 Video. Include: All Files, Cinematic, Album Magazine, Prints.','harga'=>12000000,'gambar'=>'wedding.jpeg'],
        ['nama_paket'=>'Special Day - Photo Only','kategori'=>'Engagement','deskripsi'=>'Engagement/Birthday/Aqiqah. Durasi 5 Jam. 1 Photographer, 1 Assistant. Output: 100++ Edited Photos, 2 Prints 12RP, Soft File.','harga'=>1500000,'gambar'=>'engagement.jpeg'],
        ['nama_paket'=>'Special Day - Photo Video','kategori'=>'Engagement','deskripsi'=>'Engagement/Birthday/Aqiqah. Durasi 5 Jam. 1 Photo, 1 Video, 1 Assistant. Output: 100++ Edited Photos, 1-2 Min Cinematic Video.','harga'=>2500000,'gambar'=>'engagement.jpeg'],
    ];
    
    foreach ($pakets as $paket) {
        \Illuminate\Support\Facades\DB::table('paket')->insert($paket);
    }
    
    echo "\n✅ " . count($pakets) . " paket inserted!\n";
} else {
    echo "\n✅ Paket data sudah ada!\n";
    \Illuminate\Support\Facades\DB::table('paket')->get()->each(function($p) {
        echo "- " . $p->paket_id . ". " . $p->nama_paket . " (Rp " . number_format($p->harga, 0, ',', '.') . ")\n";
    });
}
