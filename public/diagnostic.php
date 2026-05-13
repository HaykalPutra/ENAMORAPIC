<!DOCTYPE html>
<html>
<head>
    <title>Database Check</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #f5f5f5; }
        pre { background: white; padding: 15px; border-radius: 5px; overflow-x: auto; }
        h2 { color: #333; margin-top: 20px; border-bottom: 2px solid #007bff; padding-bottom: 10px; }
    </style>
</head>
<body>
    <h1>🔍 Database Diagnostic</h1>
    <?php
    try {
        require 'vendor/autoload.php';
        $app = require 'bootstrap/app.php';
        $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
        $kernel->bootstrap();
        
        // Check paket table
        echo "<h2>📦 PAKET TABLE</h2>";
        $count = \Illuminate\Support\Facades\DB::table('paket')->count();
        echo "<p><strong>Total Paket:</strong> $count</p>";
        
        echo "<h3>Table Structure:</h3>";
        $columns = \Illuminate\Support\Facades\DB::getSchemaBuilder()->getColumnListing('paket');
        echo "<pre>";
        foreach ($columns as $col) {
            echo "• $col\n";
        }
        echo "</pre>";
        
        if ($count > 0) {
            echo "<h3>Sample Data:</h3>";
            $sample = \Illuminate\Support\Facades\DB::table('paket')->first();
            echo "<pre>";
            echo json_encode($sample, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            echo "</pre>";
        }
        
        // Check booking table
        echo "<h2>📅 BOOKING TABLE</h2>";
        $bookingCount = \Illuminate\Support\Facades\DB::table('booking')->count();
        echo "<p><strong>Total Bookings:</strong> $bookingCount</p>";
        
        echo "<h3>Table Structure:</h3>";
        $columns = \Illuminate\Support\Facades\DB::getSchemaBuilder()->getColumnListing('booking');
        echo "<pre>";
        foreach ($columns as $col) {
            echo "• $col\n";
        }
        echo "</pre>";
        
        // Check payment_terms
        echo "<h2>💳 PAYMENT_TERMS TABLE</h2>";
        $termCount = \Illuminate\Support\Facades\DB::table('payment_terms')->count();
        echo "<p><strong>Total Payment Terms:</strong> $termCount</p>";
        
        echo "<h2>✅ Status</h2>";
        echo "<pre>";
        echo "✅ Paket Table: OK ($count records)\n";
        echo "✅ Booking Table: OK\n";
        echo "✅ Payment Terms Table: OK\n";
        echo "✅ Database Connection: OK\n";
        echo "</pre>";
        
    } catch (\Exception $e) {
        echo "<h2>❌ Error</h2>";
        echo "<pre style='color: red;'>" . $e->getMessage() . "</pre>";
    }
    ?>
</body>
</html>
