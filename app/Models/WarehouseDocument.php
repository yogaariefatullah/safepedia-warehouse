<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Crypt;

class WarehouseDocument extends Model
{
    protected $fillable = [
        'warehouse_request_id',
        'uploaded_by',
        'document_name',
        'file_path',
        'file_type',
        'file_size',
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
    public function warehouseRequest(): BelongsTo
    {
        return $this->belongsTo(WarehouseRequest::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
