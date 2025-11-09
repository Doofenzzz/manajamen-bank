<?php

namespace App\Models\Concerns;

use App\Models\StatusHistory;
use App\Models\InternalNote;
use App\Models\User;

trait HasWorkflow {
    public function statusHistories(){ return $this->morphMany(StatusHistory::class, 'applicant'); }
    public function notes(){ return $this->morphMany(InternalNote::class, 'noteable'); }
    public function processor(){ return $this->belongsTo(User::class, 'processed_by'); }

    public function setStatus(string $to, ?string $reason, int $adminId): void {
        $from = $this->status;
        $this->status = $to;
        if ($to === 'diterima') { $this->verified_at = now(); $this->rejection_reason = null; }
        if ($to === 'ditolak')  { $this->rejection_reason = $reason; $this->verified_at = null; }
        $this->processed_by = $adminId;
        $this->save();

        $this->statusHistories()->create([
            'from'=>$from,'to'=>$to,'changed_by'=>$adminId,'reason'=>$reason,
        ]);
    }
}

