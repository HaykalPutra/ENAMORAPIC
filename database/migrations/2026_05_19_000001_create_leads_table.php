<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lead', function (Blueprint $table) {
            $table->id('lead_id');
            $table->string('nama', 100);
            $table->string('no_wa', 20)->default('-');
            $table->string('sumber', 80)->nullable();
            $table->enum('status', ['prospect', 'negotiation', 'booked', 'completed', 'lost'])->default('prospect');
            $table->text('catatan')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('booking_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('last_contact_at')->nullable();
            $table->date('next_follow_up_at')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('customer_id');
            $table->index('booking_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lead');
    }
};
