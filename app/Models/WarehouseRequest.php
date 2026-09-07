<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WarehouseRequest extends Model
{
    protected $fillable = [
        'code',
        'requestor_id',
        'warehouse_name',
        'address',
        'latitude',
        'longitude',
        'area',
        'estimated_budget',
        'description',
        'status',
        'current_approval_level',
        'requestor_note',
        'submitted_at',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'area' => 'decimal:2',
            'estimated_budget' => 'decimal:2',
            'submitted_at' => 'datetime',
        ];
    }

    /**
     * User yang membuat pengajuan.
     */
    public function requestor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requestor_id');
    }

    /**
     * Dokumen pengajuan.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(WarehouseDocument::class);
    }

    /**
     * Riwayat approval.
     */
    public function approvalHistories(): HasMany
    {
        return $this->hasMany(ApprovalHistory::class);
    }
}
