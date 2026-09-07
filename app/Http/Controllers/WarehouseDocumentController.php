<?php

namespace App\Http\Controllers;

use App\Models\WarehouseDocument;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class WarehouseDocumentController extends Controller
{
    public function download(WarehouseDocument $document)
    {
        abort_unless(
            $document->warehouseRequest->requestor_id === Auth::id(),
            403
        );

        abort_unless(
            Storage::disk('public')->exists($document->file_path),
            404
        );

        return Storage::disk('public')->download(
            $document->file_path,
            $document->document_name
        );
    }
}
