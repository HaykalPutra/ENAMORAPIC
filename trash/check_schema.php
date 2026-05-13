<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== PAKET TABLE STRUCTURE ===\n";
$columns = \Illuminate\Support\Facades\DB::getSchemaBuilder()->getColumnListing('paket');
foreach ($columns as $col) {
    echo "- $col\n";
}

echo "\n=== SAMPLE PAKET ===\n";
$paket = \Illuminate\Support\Facades\DB::table('paket')->first();
if ($paket) {
    echo json_encode($paket, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
} else {
    echo "No paket found!\n";
}

echo "\n=== BOOKING TABLE STRUCTURE ===\n";
$columns = \Illuminate\Support\Facades\DB::getSchemaBuilder()->getColumnListing('booking');
foreach ($columns as $col) {
    echo "- $col\n";
}
