<?php

namespace App\Http\Controllers;

use App\Models\WarehouseRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\WarehouseDocument;
use Illuminate\Support\Facades\Storage;

class WarehouseRequestController extends Controller
{
    public function index()
    {
        $requests = WarehouseRequest::with('requestor')
            ->where('requestor_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('warehouse-requests.index', compact('requests'));
    }

    public function create()
    {
        return view('warehouse-requests.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'warehouse_name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'area' => ['required', 'numeric', 'min:0'],
            'estimated_budget' => ['required', 'numeric', 'min:0'],
            'description' => ['required', 'string'],
        ]);

        $validated['code'] = 'WR-' . date('YmdHis');
        $validated['requestor_id'] = Auth::id();
        $validated['status'] = 'draft';

        $warehouseRequest = WarehouseRequest::create($validated);

        return redirect()
            ->route('warehouse-requests.show', $warehouseRequest)
            ->with('success', 'Pengajuan berhasil disimpan sebagai draft.');
    }

    public function show(WarehouseRequest $warehouseRequest)
    {
        abort_unless(
            $warehouseRequest->requestor_id === Auth::id(),
            403
        );

        $warehouseRequest->load([
            'requestor',
            'documents',
            'approvalHistories.approvalLevel',
            'approvalHistories.approver.role',
        ]);

        return view(
            'warehouse-requests.show',
            compact('warehouseRequest')
        );
    }

    public function edit(WarehouseRequest $warehouseRequest)
    {
        abort_unless(
            $warehouseRequest->requestor_id === Auth::id(),
            403
        );

        abort_unless(
            $warehouseRequest->status === 'draft',
            403
        );

        return view(
            'warehouse-requests.edit',
            compact('warehouseRequest')
        );
    }

    public function update(Request $request, WarehouseRequest $warehouseRequest)
    {
        abort_unless(
            $warehouseRequest->requestor_id === Auth::id(),
            403
        );

        abort_unless(
            $warehouseRequest->status === 'draft',
            403
        );

        $validated = $request->validate([
            'warehouse_name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'area' => ['required', 'numeric', 'min:0'],
            'estimated_budget' => ['required', 'numeric', 'min:0'],
            'description' => ['required', 'string'],
        ]);

        $warehouseRequest->update($validated);

        return redirect()
            ->route('warehouse-requests.show', $warehouseRequest)
            ->with('success', 'Pengajuan berhasil diperbarui.');
    }

    public function destroy(WarehouseRequest $warehouseRequest)
    {
        abort_unless(
            $warehouseRequest->requestor_id === Auth::id(),
            403
        );

        abort_unless(
            $warehouseRequest->status === 'draft',
            403
        );

        $warehouseRequest->delete();

        return redirect()
            ->route('warehouse-requests.index')
            ->with('success', 'Pengajuan berhasil dihapus.');
    }
    public function uploadDocument(Request $request, WarehouseRequest $warehouseRequest)
    {
        abort_unless(
            $warehouseRequest->requestor_id === Auth::id(),
            403
        );

        abort_unless(
            $warehouseRequest->status === 'draft',
            403
        );

        $request->validate([
            'documents' => ['required', 'array'],
            'documents.*' => [
                'required',
                'file',
                'mimes:pdf,jpg,jpeg,png,doc,docx',
                'max:5120',
            ],
        ]);

        $currentDocumentCount = $warehouseRequest
            ->documents()
            ->count();

        $newDocumentCount = count($request->file('documents'));

        if (($currentDocumentCount + $newDocumentCount) > 10) {
            return back()->withErrors([
                'documents' => 'Maksimal 10 dokumen per pengajuan.',
            ]);
        }

        foreach ($request->file('documents') as $file) {

            $path = $file->store(
                'warehouse-documents/' . $warehouseRequest->id,
                'public'
            );

            WarehouseDocument::create([
                'warehouse_request_id' => $warehouseRequest->id,
                'uploaded_by' => Auth::id(),
                'document_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_type' => $file->getClientMimeType(),
                'file_size' => $file->getSize(),
            ]);
        }

        return redirect()
            ->route('warehouse-requests.show', $warehouseRequest)
            ->with('success', 'Dokumen berhasil diupload.');
    }
    public function submit(WarehouseRequest $warehouseRequest)
    {
        abort_unless(
            $warehouseRequest->requestor_id === Auth::id(),
            403
        );

        abort_unless(
            $warehouseRequest->status === 'draft',
            403
        );

        $documentCount = $warehouseRequest
            ->documents()
            ->count();

        if ($documentCount < 3) {
            return back()->withErrors([
                'documents' => 'Pengajuan harus memiliki minimal 3 dokumen pendukung.',
            ]);
        }

        $warehouseRequest->update([
            'status' => 'submitted',
            'current_approval_level' => 1,
            'submitted_at' => now(),
        ]);

        return redirect()
            ->route('warehouse-requests.show', $warehouseRequest)
            ->with(
                'success',
                'Pengajuan berhasil disubmit dan menunggu review SPV Gudang.'
            );
    }

    public function destroyDocument(WarehouseDocument $document)
    {
        $warehouseRequest = $document->warehouseRequest;

        if ($warehouseRequest->status !== 'draft') {
            return redirect()
                ->back()
                ->with('error', 'Dokumen hanya dapat dihapus saat pengajuan masih berstatus draft.');
        }
        if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        return redirect()
            ->back()
            ->with('success', 'Dokumen berhasil dihapus.');
    }
}
