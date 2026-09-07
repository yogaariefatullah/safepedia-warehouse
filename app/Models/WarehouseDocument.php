<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function warehouseRequest(): BelongsTo
    {
        return $this->belongsTo(WarehouseRequest::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
