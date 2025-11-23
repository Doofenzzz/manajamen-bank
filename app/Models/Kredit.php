<?php

namespace App\Models;

use App\Models\Concerns\HasWorkflow;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kredit extends Model
{
    use HasFactory, HasWorkflow;

    protected $fillable = [
        'nasabah_id',
        'jumlah_pinjaman',
        'jenis_kredit',
        'tenor',
        'bunga',
        'jaminan_deskripsi',
        'alasan_kredit',
        'jaminan_dokumen',
        'dokumen_pendukung',
        'status',
        'catatan',
        'processed_by',
        'verified_at',
        'rejection_reason',
    ];

    protected $casts = [
        'jumlah_pinjaman' => 'decimal:2',
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