<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kredits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nasabah_id')->constrained()->onDelete('cascade');
            $table->decimal('jumlah_pinjaman', 15, 2);
            $table->string('jenis_kredit');
            $table->integer('tenor'); // lama pinjaman dalam bulan
            $table->decimal('bunga', 5, 2)->default(5.00); // persen, ditentukan oleh sistem
            $table->string('jaminan_deskripsi'); // deskripsi singkat barang jaminan
            $table->string('alasan_kredit');
            $table->string('jaminan_dokumen')->nullable(); // file jaminan
            $table->string('dokumen_pendukung')->nullable(); // KTP, slip gaji, dsb
            $table->enum('status', ['pending', 'diterima', 'ditolak'])->default('pending');
            $table->text('catatan')->nullable(); // untuk admin menulis komentar
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kredits');
    }
};
