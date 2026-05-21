<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('layout');           // 'admin' atau 'admin2'
            $table->string('type');             // 'website', 'booking', 'wedding', 'dp', 'approved'
            $table->string('ref_type');         // model class: 'booking', 'pesanan_website'
            $table->unsignedBigInteger('ref_id'); // ID record asal
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->string('time_label')->nullable();
            $table->string('url');
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            $table->index(['layout', 'is_read']);
            $table->index(['ref_type', 'ref_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_notifications');
    }
};