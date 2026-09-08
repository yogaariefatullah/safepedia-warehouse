<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Crypt;

class Role extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
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
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function approvalLevels(): HasMany
    {
        return $this->hasMany(ApprovalLevel::class);
    }
}
