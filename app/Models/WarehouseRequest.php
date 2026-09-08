<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Crypt;

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
