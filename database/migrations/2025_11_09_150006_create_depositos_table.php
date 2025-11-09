<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('depositos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nasabah_id')->constrained()->onDelete('cascade');
            $table->decimal('nominal', 15, 2);
            $table->integer('jangka_waktu'); // bulan
            $table->decimal('bunga', 5, 2)->default(5.00); // default bunga 5%
            $table->string('jenis_deposito')->default('Deposito Berjangka'); // ✅ Tambahan biar fleksibel
            $table->string('bukti_transfer')->nullable(); // ✅ untuk upload bukti setoran opsional
            $table->enum('status', ['pending', 'diterima', 'ditolak'])->default('pending');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('depositos');
    }
};
