<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * payment_terms table menyimpan detail setiap termin pembayaran
     * untuk setiap booking. Support baik 2-termin maupun 3-termin.
     */
    public function up(): void
    {
        Schema::create('payment_terms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id');
            $table->tinyInteger('term_number')->comment('Nomor termin (1, 2, 3)');
            $table->decimal('term_percentage', 5, 2)->comment('Persentase pembayaran (30%, 70%, dll)');
            $table->decimal('term_amount', 15, 0)->comment('Jumlah pembayaran dalam Rp');
            $table->date('due_date')->comment('Tanggal jatuh tempo pembayaran');
            $table->enum('payment_status', ['unpaid', 'paid', 'overdue'])
                ->default('unpaid')
                ->comment('Status pembayaran: unpaid, paid, overdue');
            $table->decimal('paid_amount', 15, 0)->default(0)->comment('Jumlah sudah dibayar');
            $table->dateTime('paid_at')->nullable()->comment('Waktu pembayaran diterima');
            $table->string('payment_method', 50)->nullable()->comment('Metode pembayaran (transfer, kartu kredit, e-wallet)');
            $table->string('next_term_reminder_sent_at', 50)->nullable()->comment('Kapan reminder terakhir dikirim');
            $table->timestamps();
            
            // Foreign key
            $table->foreign('booking_id')
                ->references('booking_id')
                ->on('booking')
                ->onDelete('cascade');
            
            // Index untuk query performance
            $table->index('booking_id');
            $table->index('payment_status');
            $table->index('due_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_terms');
    }
};
