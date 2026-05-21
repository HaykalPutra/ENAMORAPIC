<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wedding_organizer', function (Blueprint $table) {
            $table->id('wo_id');
            $table->string('nama_wo', 120);
            $table->string('pic_name', 120)->nullable();
            $table->string('no_wa', 20)->default('-');
            $table->string('email', 120)->nullable();
            $table->string('alamat', 200)->nullable();
            $table->string('instagram', 120)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->text('catatan')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('nama_wo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wedding_organizer');
    }
};
