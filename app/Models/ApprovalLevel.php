<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Crypt;

class ApprovalLevel extends Model
{
    protected $fillable = [
        'role_id',
        'level',
        'name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
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
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function approvalHistories(): HasMany
    {
        return $this->hasMany(ApprovalHistory::class);
    }
}
