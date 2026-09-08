<?php

namespace App\Http\Controllers;

use App\Models\ApprovalHistory;
use App\Models\ApprovalLevel;
use App\Models\WarehouseRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApprovalController extends Controller
{

    public function index(Request $request)
    {
        $user = Auth::user();
        $approvalLevel = ApprovalLevel::where('role_id', $user->role_id)->first();

        abort_unless($approvalLevel, 403, 'User tidak memiliki level approval.');

        $statusFilter = $request->query('status');

        $query = WarehouseRequest::with([
            'requestor',
            'documents',
            'approvalHistories.approvalLevel',
            'approvalHistories.approver.role',
        ]);

        if ($statusFilter === 'approved') {
            $query->whereHas('approvalHistories', function ($q) use ($user, $approvalLevel) {
                $q->where('approval_level_id', $approvalLevel->id)
                    ->where('approver_id', $user->id)
                    ->where('status', 'approved');
            });
        } elseif ($statusFilter === 'rejected') {
            $query->whereHas('approvalHistories', function ($q) use ($user, $approvalLevel) {
                $q->where('approval_level_id', $approvalLevel->id)
                    ->where('approver_id', $user->id)
                    ->where('status', 'rejected');
            });
        } else {
            $query->where('current_approval_level', $approvalLevel->level)
                ->whereIn('status', ['submitted', 'on_review']);
        }

        $requests = $query->latest('submitted_at')->paginate(10);

        $requests->appends($request->query());

        return view(
            'approvals.index',
            compact('requests', 'approvalLevel', 'statusFilter')
        );
    }

    public function show(WarehouseRequest $warehouseRequest)
    {
        $user = Auth::user();

        $approvalLevel = ApprovalLevel::where('role_id', $user->role_id)->first();
        abort_unless($approvalLevel, 403, 'User tidak memiliki level approval.');

        $canApprove = ((int) $warehouseRequest->current_approval_level === (int) $approvalLevel->level &&
            in_array($warehouseRequest->status, ['submitted', 'on_review'], true));

        $warehouseRequest->load([
            'requestor',
            'documents',
            'approvalHistories.approvalLevel',
            'approvalHistories.approver.role',
        ]);

        return view('approvals.show', compact('warehouseRequest', 'approvalLevel', 'canApprove'));
    }

    public function approve(WarehouseRequest $warehouseRequest)
    {
        $user = Auth::user();

        $approvalLevel = ApprovalLevel::where(
            'role_id',
            $user->role_id
        )->first();

        if (!$approvalLevel) {
            return redirect()
                ->back()
                ->with('error', 'User tidak memiliki level approval.');
        }

        if ((int) $warehouseRequest->current_approval_level !=   (int) $approvalLevel->level) {
            return redirect()
                ->back()
                ->with('error', 'Pengajuan ini belum berada pada level approval Anda.');
        }

        if (!in_array($warehouseRequest->status, ['submitted', 'on_review'],  true)) {
            return redirect()
                ->back()
                ->with('error', 'Pengajuan ini tidak dapat diproses.');
        }

        DB::transaction(function () use (
            $warehouseRequest,
            $approvalLevel,
            $user
        ) {

            ApprovalHistory::create([
                'warehouse_request_id' => $warehouseRequest->id,
                'approval_level_id' => $approvalLevel->id,
                'approver_id' => $user->id,
                'status' => 'approved',
                'note' => null,
                'action_at' => now(),
            ]);

            $nextApprovalLevel = ApprovalLevel::where(
                'level',
                '>',
                $approvalLevel->level
            )
                ->where('is_active', true)
                ->orderBy('level')
                ->first();

            if ($nextApprovalLevel) {

                $warehouseRequest->update([
                    'status' => 'on_review',
                    'current_approval_level' => $nextApprovalLevel->level,
                ]);
            } else {

                $warehouseRequest->update([
                    'status' => 'approved',
                    'current_approval_level' => null,
                ]);
            }
        });

        return redirect()
            ->route('approvals.index')
            ->with('success', 'Pengajuan berhasil disetujui.');
    }


    public function reject(Request $request, WarehouseRequest $warehouseRequest)
    {
        $user = Auth::user();

        $approvalLevel = ApprovalLevel::where(
            'role_id',
            $user->role_id
        )->first();

        abort_unless($approvalLevel, 403, 'User tidak memiliki level approval.');

        abort_unless(
            (int) $warehouseRequest->current_approval_level === (int) $approvalLevel->level,
            403,
            'Pengajuan ini belum berada pada level approval Anda.'
        );

        abort_unless(
            in_array($warehouseRequest->status, ['submitted', 'on_review'], true),
            403,
            'Pengajuan ini tidak dapat diproses.'
        );

        $validated = $request->validate([
            'note' => [
                'required',
                'string',
                'min:5',
                'max:1000',
            ],
        ], [
            'note.required' => 'Alasan penolakan wajib diisi.',
            'note.min' => 'Alasan penolakan minimal 5 karakter.',
            'note.max' => 'Alasan penolakan maksimal 1000 karakter.',
        ]);

        DB::transaction(function () use (
            $warehouseRequest,
            $approvalLevel,
            $user,
            $validated
        ) {
            ApprovalHistory::create([
                'warehouse_request_id' => $warehouseRequest->id,
                'approval_level_id' => $approvalLevel->id,
                'approver_id' => $user->id,
                'status' => 'rejected',
                'note' => $validated['note'],
                'action_at' => now(),
            ]);

            $warehouseRequest->update([
                'status' => 'rejected',
                'current_approval_level' => null,
            ]);
        });

        return redirect()
            ->route('approvals.index')
            ->with('success', 'Pengajuan berhasil ditolak.');
    }
}
