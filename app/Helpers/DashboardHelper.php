<?php

namespace App\Helpers;

use App\Models\ApprovalLevel;
use App\Models\User;
use App\Models\WarehouseRequest;
use Illuminate\Support\Facades\Auth;

class DashboardHelper
{
    public static function getDashboardData(): array
    {
        $user = Auth::user();
        $roleName = $user->role->slug ?? ($user->role->name ?? '');
        $roleId = $user->role_id;

        $totalUsers = 0;
        $recentUsers = collect();
        if ($roleName === 'admin') {
            $totalUsers = User::count();
            $recentUsers = User::with('role')->latest()->take(5)->get();
        }

        $approvalLevel = ApprovalLevel::where('role_id', $roleId)
            ->where('is_active', true)
            ->first();

        $currentApprovalLevel = $approvalLevel?->level;

        $baseQuery = WarehouseRequest::query();

        if ($roleName === 'requestor') {
            $baseQuery->where('requestor_id', $user->id);
        }

        $totalRequests = (clone $baseQuery)->count();
        $approvedRequests = (clone $baseQuery)->where('status', 'approved')->count();
        $rejectedRequests = (clone $baseQuery)->where('status', 'rejected')->count();
        $submittedRequests = (clone $baseQuery)->whereIn('status', ['submitted', 'on_review'])->count();

        $pendingMyReview = 0;
        if ($approvalLevel) {
            $pendingMyReview = (clone $baseQuery)
                ->whereIn('status', ['submitted', 'on_review'])
                ->where('current_approval_level', $currentApprovalLevel)
                ->count();
        }

        return compact(
            'roleName',
            'totalUsers',
            'recentUsers',
            'currentApprovalLevel',
            'totalRequests',
            'approvedRequests',
            'rejectedRequests',
            'submittedRequests',
            'pendingMyReview'
        );
    }
}