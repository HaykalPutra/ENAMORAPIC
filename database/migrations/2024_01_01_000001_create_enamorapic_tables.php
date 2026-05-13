<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Users
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('username', 50)->unique();
            $table->string('password', 255);
            $table->string('nama_lengkap', 100);
            $table->enum('role', ['CEO', 'ADMIN']);
        });

        // Paket
        Schema::create('paket', function (Blueprint $table) {
            $table->id('paket_id');
            $table->string('nama_paket', 100);
            $table->enum('kategori', ['Wedding', 'Pre-Wedding', 'Engagement', 'Other']);
            $table->text('deskripsi');
            $table->decimal('harga', 15, 0);
            $table->string('gambar', 255);
        });

        // Customer
        Schema::create('customer', function (Blueprint $table) {
            $table->id('customer_id');
            $table->string('nama_client', 100);
            $table->string('no_wa', 20)->default('-');
            $table->integer('alamat')->default(0);
        });

        // Booking
        Schema::create('booking', function (Blueprint $table) {
            $table->id('booking_id');
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('nama_client', 100);
            $table->date('tgl_booking');
            $table->date('tgl_acara');
            $table->double('total_transaksi');
            $table->enum('status_pembayaran', ['Lunas', 'DP']);
            $table->string('status_acara', 50)->default('Pending');
        });

        // Booking Detail
        Schema::create('booking_detail', function (Blueprint $table) {
            $table->id();
            $table->string('booking_id', 50);
            $table->unsignedBigInteger('paket_id');
            $table->decimal('harga_saat_ini', 15, 0);
        });

        // Pegawai
        Schema::create('pegawai', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100)->nullable();
            $table->string('gear', 255)->nullable();
            $table->enum('role', ['Photographer', 'Videographer', 'Admin', 'Editor', 'Assistant'])->nullable();
            $table->decimal('gaji', 15, 2)->nullable();
            $table->string('domisili', 100)->nullable();
            $table->string('no_wa', 20)->nullable();
        });

        // Freelance
        Schema::create('freelance', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100)->nullable();
            $table->string('gear', 255)->nullable();
            $table->enum('role', ['Photographer', 'Videographer', 'Assistant', 'Editor'])->nullable();
            $table->decimal('harga', 15, 2)->nullable();
            $table->string('domisili', 100)->nullable();
            $table->string('no_wa', 20)->nullable();
        });

        // Pesanan Website
        Schema::create('pesanan_website', function (Blueprint $table) {
            $table->id('pesanan_id');
            $table->unsignedBigInteger('paket_id');
            $table->string('nama_pemesan', 100);
            $table->string('no_wa', 20);
            $table->date('tanggal_booking');
            $table->text('lokasi');
            $table->enum('status', ['pending', 'done'])->default('pending');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan_website');
        Schema::dropIfExists('freelance');
        Schema::dropIfExists('pegawai');
        Schema::dropIfExists('booking_detail');
        Schema::dropIfExists('booking');
        Schema::dropIfExists('customer');
        Schema::dropIfExists('paket');
        Schema::dropIfExists('users');
    }
};
