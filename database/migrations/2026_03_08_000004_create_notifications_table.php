<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * notifications table menyimpan semua notifikasi untuk:
     * - CEO (new booking)
     * - Customer (payment due, payment received, booking approved)
     * - Admin/Sekretaris (follow-up needed, job upcoming)
     */
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable()->comment('Recipient user ID');
            $table->string('recipient_role', 50)->comment('Nomor WA atau role (CEO, ADMIN, ADMIN2, CUSTOMER, SEKRETARIS)');
            $table->string('phone_number', 20)->nullable()->comment('Nomor WA untuk notifikasi');
            $table->enum('notification_type', [
                'new_booking',
                'booking_approved',
                'booking_rejected',
                'payment_due',
                'payment_reminder',
                'payment_received',
                'follow_up_needed',
                'job_upcoming',
                'late_payment_alert',
                'general'
            ])->comment('Tipe notifikasi');
            $table->string('subject', 255)->comment('Judul notifikasi');
            $table->text('message')->comment('Isi pesan notifikasi');
            $table->enum('channel', ['whatsapp', 'dashboard', 'email'])
                ->default('dashboard')
                ->comment('Channel pengiriman');
            $table->enum('status', ['pending', 'sent', 'failed', 'read'])
                ->default('pending')
                ->comment('Status notifikasi');
            $table->text('metadata')->nullable()->comment('Data tambahan dalam JSON format');
            $table->dateTime('sent_at')->nullable()->comment('Waktu notifikasi terkirim');
            $table->dateTime('read_at')->nullable()->comment('Waktu notifikasi dibaca');
            $table->text('failure_reason')->nullable()->comment('Alasan jika gagal terkirim');
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('booking_id')
                ->references('booking_id')
                ->on('booking')
                ->onDelete('cascade');
            
            $table->foreign('user_id')
                ->references('user_id')
                ->on('users')
                ->onDelete('set null');
            
            // Indexes
            $table->index('booking_id');
            $table->index('user_id');
            $table->index('recipient_role');
            $table->index('notification_type');
            $table->index('status');
            $table->index('channel');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
