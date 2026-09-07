<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApprovalHistory extends Model
{
    protected $fillable = [
        'warehouse_request_id',
        'approval_level_id',
        'approver_id',
        'status',
        'note',
        'action_at',
    ];

    protected function casts(): array
    {
        return [
            'action_at' => 'datetime',
        ];
    }

    /**
     * Pengajuan yang diproses.
     */
    public function warehouseRequest(): BelongsTo
    {
        return $this->belongsTo(
            WarehouseRequest::class,
            'warehouse_request_id'
        );
    }

    /**
     * Level approval.
     */
    public function approvalLevel(): BelongsTo
    {
        return $this->belongsTo(
            ApprovalLevel::class,
            'approval_level_id'
        );
    }

    /**
     * User yang melakukan approval.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'approver_id'
        );
    }
}
