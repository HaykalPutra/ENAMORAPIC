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
        Schema::table('pesanan_website', function (Blueprint $table) {
            $table->unsignedBigInteger('booking_id')->nullable()->after('pesanan_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pesanan_website', function (Blueprint $table) {
            $table->dropColumn('booking_id');
        });
    }
};
