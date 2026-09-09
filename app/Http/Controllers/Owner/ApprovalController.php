<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Approval;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    public function index(Request $request)
    {
        $query = Approval::with(['user', 'approver']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $approvals = $query->latest()->paginate(15)->withQueryString();

        return view('owner.approvals.index', compact('approvals'));
    }

    public function show(Approval $approval)
    {
        $approval->load(['user', 'approver']);

        return view('owner.approvals.show', compact('approval'));
    }

    public function approve(Approval $approval, Request $request)
    {
        $approval->update([
            'status' => 'approved',
            'approver_id' => auth()->id(),
            'approval_notes' => $request->notes,
            'approved_at' => now(),
        ]);

        return back()->with('swal_success', 'Permintaan approval berhasil disetujui.');
    }

    public function reject(Approval $approval, Request $request)
    {
        $approval->update([
            'status' => 'rejected',
            'approver_id' => auth()->id(),
            'approval_notes' => $request->notes,
            'approved_at' => now(),
        ]);

        return back()->with('swal_success', 'Permintaan approval berhasil ditolak.');
    }
}
