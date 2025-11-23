<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rekenings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nasabah_id')->constrained()->onDelete('cascade');
            $table->string('jenis_tabungan');
            $table->string('unit_kerja_pembukaan_tabungan');
            $table->decimal('setoran_awal', 15, 2);
            $table->enum('kartu_atm', ['ya', 'tidak'])->default('ya');
            $table->string('nomor_rekening')->nullable()->unique();
            $table->enum('status', ['pending', 'diterima', 'ditolak'])->default('pending');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rekenings');
    }
};
