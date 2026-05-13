<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * payment_logs table menyimpan log dari setiap payment attempt,
     * termasuk response dari payment gateway
     */
    public function up(): void
    {
        Schema::create('payment_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id');
            $table->tinyInteger('term_number')->comment('Termin mana yang dibayar');
            $table->decimal('amount', 15, 0)->comment('Jumlah pembayaran');
            $table->string('payment_method', 50)->comment('Metode pembayaran: transfer, kartu, ewallet, qris');
            $table->enum('payment_status', ['pending', 'processing', 'success', 'failed'])
                ->default('pending')
                ->comment('Status payment attempt');
            $table->string('transaction_id', 100)->nullable()->comment('ID transaksi dari gateway');
            $table->string('gateway_name', 50)->nullable()->comment('Nama payment gateway yang digunakan');
            $table->json('gateway_response')->nullable()->comment('Raw response dari payment gateway');
            $table->text('error_message')->nullable()->comment('Pesan error jika gagal');
            $table->string('qris_url', 255)->nullable()->comment('URL QRIS image jika ada');
            $table->timestamps();
            
            // Foreign key
            $table->foreign('booking_id')
                ->references('booking_id')
                ->on('booking')
                ->onDelete('cascade');
            
            // Indexes
            $table->index('booking_id');
            $table->index('payment_status');
            $table->index('transaction_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_logs');
    }
};
