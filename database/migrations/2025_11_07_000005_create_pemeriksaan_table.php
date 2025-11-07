<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pemeriksaan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peserta_id')->constrained('peserta')->cascadeOnDelete();
            $table->dateTime('tanggal_pemeriksaan');
            $table->decimal('tinggi_badan', 5, 2); // cm
            $table->decimal('berat_badan', 5, 2); // kg
            $table->string('tekanan_darah')->nullable();
            $table->decimal('hemoglobin', 4, 2)->nullable();
            $table->decimal('gula_darah', 6, 2)->nullable();
            $table->decimal('lingkar_lengan', 5, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemeriksaan');
    }
};
