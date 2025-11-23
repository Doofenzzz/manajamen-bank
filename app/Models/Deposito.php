<?php

namespace App\Models;

use App\Models\Concerns\HasWorkflow;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposito extends Model
{
    use HasFactory, HasWorkFlow;

    protected $fillable = [
        'nasabah_id',
        'nominal',
        'jangka_waktu',
        'bunga',
        'jenis_deposito',
        'bukti_transfer',
        'status',
        'catatan',
        'processed_by',
        'verified_at',
        'rejection_reason',
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
        'bunga' => 'decimal:2',
        'verified_at' => 'datetime',
    ];

    // Relationships
    public function nasabah()
    {
        return $this->belongsTo(Nasabah::class);
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function statusHistories()
    {
        return $this->morphMany(StatusHistory::class, 'applicant');
    }
}