<?php

namespace App\Models;

use App\Models\Concerns\HasWorkflow;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rekening extends Model
{
    use HasFactory, HasWorkflow;

    protected $fillable = [
        'nasabah_id',
        'jenis_tabungan',
        'unit_kerja_pembukaan_tabungan',
        'setoran_awal',
        'kartu_atm',
        'nomor_rekening',
        'status',
        'catatan',
        'processed_by',
        'verified_at',
        'rejection_reason',
    ];

    protected $casts = [
        'setoran_awal' => 'decimal:2',
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

