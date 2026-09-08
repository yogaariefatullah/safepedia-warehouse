<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Crypt;

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
    
    public function getRouteKey(): string
    {
        return Crypt::encryptString($this->getKey());
    }


    public function resolveRouteBinding($value, $field = null)
    {
        try {
            $decryptedId = Crypt::decryptString($value);

            return $this->where($field ?? $this->getKeyName(), $decryptedId)->firstOrFail();
        } catch (DecryptException $e) {
            abort(404);
        }
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

    public function approvalLevel(): BelongsTo
    {
        return $this->belongsTo(
            ApprovalLevel::class,
            'approval_level_id'
        );
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'approver_id'
        );
    }
}
