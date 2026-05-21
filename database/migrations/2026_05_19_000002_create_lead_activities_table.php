<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lead_activity', function (Blueprint $table) {
            $table->id('lead_activity_id');
            $table->unsignedBigInteger('lead_id');
            $table->enum('activity_type', ['wa', 'call', 'meeting', 'note'])->default('note');
            $table->text('activity_note');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->index('lead_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lead_activity');
    }
};
