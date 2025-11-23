<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('status_histories', function (Blueprint $t) {
            $t->id();
            $t->morphs('applicant'); // applicant_type: Rekening/Kredit/Deposito, applicant_id
            $t->enum('from', ['pending','diterima','ditolak'])->nullable();
            $t->enum('to', ['pending','diterima','ditolak']);
            $t->foreignId('changed_by')->constrained('users')->onDelete('cascade');
            $t->text('reason')->nullable();
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('status_histories'); }
};
