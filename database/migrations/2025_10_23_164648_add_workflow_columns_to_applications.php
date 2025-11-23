<?php

// database/migrations/2025_10_23_000003_add_workflow_columns_to_applications.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        foreach (['rekenings','kredits','depositos'] as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
                $t->timestamp('verified_at')->nullable();
                $t->text('rejection_reason')->nullable();
            });
        }
    }
    public function down(): void {
        foreach (['rekenings','kredits','depositos'] as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->dropConstrainedForeignId('processed_by');
                $t->dropColumn(['verified_at','rejection_reason']);
            });
        }
    }
};