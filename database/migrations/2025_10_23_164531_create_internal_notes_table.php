<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('internal_notes', function (Blueprint $t) {
            $t->id();
            $t->morphs('noteable'); // noteable_type, noteable_id
            $t->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $t->text('body');
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('internal_notes'); }
};
