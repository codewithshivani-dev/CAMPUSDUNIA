<?php

namespace App\Http\Controllers;

use App\Models\OutPassApproval;
use App\Models\OutPassMigration;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OutPassApprovalController extends Controller
{
    /**
     * Display a listing of approvals.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = OutPassApproval::with(['outPass', 'approver'])
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($request->filled('out_pass_id')) {
            $query->where('out_pass_id', $request->out_pass_id);
        }

        if ($request->filled('approver_id')) {
            $query->where('approver_id', $request->approver_id);
        }

        if ($request->filled('approval_level')) {
            $query->where('approval_level', $request->approval_level);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('institute_id')) {
            $query->where('institute_id', $request->institute_id);
        }

        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('pending_only')) {
            $query->where('status', 'pending');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('comments', 'LIKE', "%{$search}%")
                  ->orWhereHas('outPass', function ($oq) use ($search) {
                      $oq->where('pass_code', 'LIKE', "%{$search}%")
                         ->orWhere('purpose', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('approver', function ($uq) use ($search) {
                      $uq->where('name', 'LIKE', "%{$search}%")
                         ->orWhere('email', 'LIKE', "%{$search}%");
                  });
            });
        }

        $approvals = $query->paginate(15)->withQueryString();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $approvals
            ]);
        }

        return view('out-pass-approvals.index', compact('approvals'));
    }

    /**
     * Show the form for creating a new approval.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $outPassId = $request->get('out_pass_id');
        $outPass = null;
        $approvers = User::whereIn('role', ['admin', 'institute_admin', 'security'])->get();
        
        if ($outPassId) {
            $outPass = OutPassMigration::find($outPassId);
        }

        return view('out-pass-approvals.create', compact('outPass', 'outPassId', 'approvers'));
    }

    /**
     * Store a newly created approval in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'institute_id' => 'nullable|string',
            'branch_id' => 'nullable|string',
            'out_pass_id' => 'required|integer|exists:out_pass_migrations,id',
            'approver_id' => 'required|integer|exists:users,id',
            'approval_level' => 'required|in:primary,secondary,final',
            'status' => 'required|in:pending,approved,rejected',
            'comments' => 'nullable|string|max:1000',
            'action_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Check if approval already exists for this level
            $existingApproval = OutPassApproval::where('out_pass_id', $request->out_pass_id)
                ->where('approval_level', $request->approval_level)
                ->first();

            if ($existingApproval && $request->isMethod('post')) {
                return redirect()->back()
                    ->with('error', 'Approval for this level already exists.')
                    ->withInput();
            }

            // Create approval
            $approval = new OutPassApproval();
            $approval->institute_id = $request->institute_id;
            $approval->branch_id = $request->branch_id;
            $approval->out_pass_id = $request->out_pass_id;
            $approval->approver_id = $request->approver_id;
            $approval->approval_level = $request->approval_level;
            $approval->status = $request->status;
            $approval->comments = $request->comments;
            $approval->action_at = $request->action_at ?? ($request->status !== 'pending' ? now() : null);
            
            $approval->save();

            // Update out pass status based on approval
            $this->updateOutPassStatus($request->out_pass_id);

            DB::commit();

            $message = 'Approval record created successfully.';

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'data' => $approval->load(['outPass', 'approver'])
                ], 201);
            }

            return redirect()->route('out-pass-approvals.show', $approval->id)
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            
            $error = 'Failed to create approval record: ' . $e->getMessage();
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $error
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', $error)
                ->withInput();
        }
    }

    /**
     * Display the specified approval.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $approval = OutPassApproval::with(['outPass', 'approver'])
            ->findOrFail($id);

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $approval
            ]);
        }

        return view('out-pass-approvals.show', compact('approval'));
    }

    /**
     * Show the form for editing the specified approval.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $approval = OutPassApproval::findOrFail($id);
        $approvers = User::whereIn('role', ['admin', 'institute_admin', 'security'])->get();
        
        return view('out-pass-approvals.edit', compact('approval', 'approvers'));
    }

    /**
     * Update the specified approval in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $approval = OutPassApproval::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'approver_id' => 'sometimes|integer|exists:users,id',
            'approval_level' => 'sometimes|in:primary,secondary,final',
            'status' => 'sometimes|in:pending,approved,rejected',
            'comments' => 'nullable|string|max:1000',
            'action_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $oldStatus = $approval->status;

            if ($request->has('approver_id')) {
                $approval->approver_id = $request->approver_id;
            }

            if ($request->has('approval_level')) {
                $approval->approval_level = $request->approval_level;
            }

            if ($request->has('status')) {
                $approval->status = $request->status;
                if ($request->status !== 'pending' && !$approval->action_at) {
                    $approval->action_at = now();
                }
            }

            if ($request->has('comments')) {
                $approval->comments = $request->comments;
            }

            $approval->save();

            // Update out pass status if approval status changed
            if ($oldStatus !== $approval->status) {
                $this->updateOutPassStatus($approval->out_pass_id);
            }

            DB::commit();

            $message = 'Approval record updated successfully.';

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'data' => $approval
                ]);
            }

            return redirect()->route('out-pass-approvals.show', $id)
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            
            $error = 'Failed to update approval record: ' . $e->getMessage();
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $error
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', $error)
                ->withInput();
        }
    }

    /**
     * Remove the specified approval from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $approval = OutPassApproval::findOrFail($id);
        
        $outPassId = $approval->out_pass_id;

        try {
            DB::beginTransaction();

            $approval->delete();

            // Update out pass status
            $this->updateOutPassStatus($outPassId);

            DB::commit();

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Approval record deleted successfully.'
                ]);
            }
            return redirect()->route('out-pass-approvals.index')
                ->with('success', 'Approval record deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            $error = 'Failed to delete approval record: ' . $e->getMessage();
            
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $error
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', $error);
        }
    }

    /**
     * Approve an out pass approval.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function approve(Request $request, $id)
    {
        $approval = OutPassApproval::findOrFail($id);

        if ($approval->status !== 'pending') {
            return $this->sendErrorResponse('This approval is not pending.', $request);
        }

        $validator = Validator::make($request->all(), [
            'comments' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $approval->status = 'approved';
            $approval->comments = $request->comments ?? $approval->comments;
            $approval->action_at = now();
            $approval->save();

            // Update out pass status
            $this->updateOutPassStatus($approval->out_pass_id);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Out pass approved successfully.',
                'data' => $approval->load(['outPass', 'approver'])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject an out pass approval.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function reject(Request $request, $id)
    {
        $approval = OutPassApproval::findOrFail($id);

        if ($approval->status !== 'pending') {
            return $this->sendErrorResponse('This approval is not pending.', $request);
        }

        $validator = Validator::make($request->all(), [
            'comments' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $approval->status = 'rejected';
            $approval->comments = $request->comments;
            $approval->action_at = now();
            $approval->save();

            // Update out pass status
            $this->updateOutPassStatus($approval->out_pass_id);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Out pass rejected successfully.',
                'data' => $approval->load(['outPass', 'approver'])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to reject: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get approvals by out pass ID.
     *
     * @param  int  $outPassId
     * @return \Illuminate\Http\Response
     */
    public function getByOutPass($outPassId)
    {
        $approvals = OutPassApproval::where('out_pass_id', $outPassId)
            ->with(['approver'])
            ->orderBy('approval_level')
            ->orderBy('created_at')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $approvals
        ]);
    }

    /**
     * Get approvals by approver ID.
     *
     * @param  int  $approverId
     * @return \Illuminate\Http\Response
     */
    public function getByApprover($approverId)
    {
        $approvals = OutPassApproval::where('approver_id', $approverId)
            ->with(['outPass'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $approvals
        ]);
    }

    /**
     * Get pending approvals for current user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getPendingForCurrentUser(Request $request)
    {
        $userId = auth()->id();

        $query = OutPassApproval::where('approver_id', $userId)
            ->where('status', 'pending')
            ->with(['outPass', 'outPass.requester'])
            ->orderBy('created_at', 'asc');

        if ($request->filled('approval_level')) {
            $query->where('approval_level', $request->approval_level);
        }

        $pendingApprovals = $query->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $pendingApprovals,
            'total_pending' => $query->count()
        ]);
    }

    /**
     * Get approval hierarchy for an out pass.
     *
     * @param  int  $outPassId
     * @return \Illuminate\Http\Response
     */
    public function getHierarchy($outPassId)
    {
        $approvals = OutPassApproval::where('out_pass_id', $outPassId)
            ->with(['approver'])
            ->orderByRaw("FIELD(approval_level, 'primary', 'secondary', 'final')")
            ->get()
            ->map(function ($item) {
                return [
                    'level' => $item->approval_level,
                    'level_display' => ucfirst($item->approval_level),
                    'approver_name' => $item->approver->name,
                    'approver_email' => $item->approver->email,
                    'status' => $item->status,
                    'status_color' => $item->status_color,
                    'comments' => $item->comments,
                    'action_at' => $item->action_at ? $item->action_at->format('Y-m-d H:i:s') : null,
                    'action_at_diff' => $item->action_at ? $item->action_at->diffForHumans() : null,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $approvals
        ]);
    }

    /**
     * Get approval statistics.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function statistics(Request $request)
    {
        $query = OutPassApproval::query();

        if ($request->filled('institute_id')) {
            $query->where('institute_id', $request->institute_id);
        }

        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('created_at', [$request->date_from, $request->date_to]);
        }

        $stats = [
            'total' => $query->count(),
            'by_status' => (clone $query)
                ->select('status', DB::raw('count(*) as total'))
                ->groupBy('status')
                ->get()
                ->keyBy('status'),
            'by_level' => (clone $query)
                ->select('approval_level', DB::raw('count(*) as total'))
                ->groupBy('approval_level')
                ->get()
                ->keyBy('approval_level'),
            'by_approver' => (clone $query)
                ->select('approver_id', DB::raw('count(*) as total'))
                ->with('approver:id,name')
                ->groupBy('approver_id')
                ->get()
                ->map(function ($item) {
                    return [
                        'approver_id' => $item->approver_id,
                        'approver_name' => $item->approver->name ?? 'Unknown',
                        'total' => $item->total
                    ];
                }),
            'pending' => (clone $query)->where('status', 'pending')->count(),
            'approved' => (clone $query)->where('status', 'approved')->count(),
            'rejected' => (clone $query)->where('status', 'rejected')->count(),
            'avg_approval_time' => $this->getAverageApprovalTime(clone $query),
            'today' => (clone $query)->whereDate('created_at', Carbon::today())->count(),
            'this_week' => (clone $query)->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count(),
        ];

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        }

        return view('out-pass-approvals.statistics', compact('stats'));
    }

    /**
     * Get average approval time in hours.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return float
     */
    private function getAverageApprovalTime($query)
    {
        $approved = (clone $query)->where('status', 'approved')
            ->whereNotNull('action_at')
            ->get();

        if ($approved->isEmpty()) {
            return 0;
        }

        $totalHours = $approved->sum(function ($item) {
            return $item->created_at->diffInHours($item->action_at);
        });

        return round($totalHours / $approved->count(), 2);
    }

    /**
     * Update out pass status based on approvals.
     *
     * @param  int  $outPassId
     * @return void
     */
    private function updateOutPassStatus($outPassId)
    {
        $outPass = OutPassMigration::find($outPassId);
        
        if (!$outPass) {
            return;
        }

        $approvals = OutPassApproval::where('out_pass_id', $outPassId)->get();

        if ($approvals->isEmpty()) {
            return;
        }

        // Check if any approval is rejected
        if ($approvals->contains('status', 'rejected')) {
            $outPass->status = 'rejected';
            $outPass->save();
            return;
        }

        // Check if all approvals are approved
        $allApproved = $approvals->every(function ($approval) {
            return $approval->status === 'approved';
        });

        if ($allApproved) {
            $outPass->status = 'approved';
            $outPass->approved_at = now();
            $outPass->save();
        } elseif ($outPass->status === 'approved') {
            // If not all approved but status is approved, revert to pending
            $outPass->status = 'pending';
            $outPass->approved_at = null;
            $outPass->save();
        }
    }

    /**
     * Send error response.
     *
     * @param  string  $message
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $code
     * @return mixed
     */
    private function sendErrorResponse($message, $request, $code = 403)
    {
        if ($request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => $message
            ], $code);
        }

        return redirect()->back()->with('error', $message);
    }

    /**
     * Export approvals data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request)
    {
        $query = OutPassApproval::with(['outPass', 'approver']);

        if ($request->filled('out_pass_id')) {
            $query->where('out_pass_id', $request->out_pass_id);
        }

        if ($request->filled('approver_id')) {
            $query->where('approver_id', $request->approver_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('created_at', [$request->date_from, $request->date_to]);
        }

        $approvals = $query->orderBy('created_at', 'desc')->get();

        $filename = 'out-pass-approvals-' . date('Y-m-d') . '.csv';
        $handle = fopen('php://output', 'w');

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        // Add CSV headers
        fputcsv($handle, [
            'ID',
            'Out Pass Code',
            'Approver',
            'Approval Level',
            'Status',
            'Comments',
            'Action At',
            'Created At'
        ]);

        // Add data rows
        foreach ($approvals as $approval) {
            fputcsv($handle, [
                $approval->id,
                $approval->outPass->pass_code ?? 'N/A',
                $approval->approver->name ?? 'Unknown',
                ucfirst($approval->approval_level),
                ucfirst($approval->status),
                $approval->comments,
                $approval->action_at ? $approval->action_at->format('Y-m-d H:i:s') : 'N/A',
                $approval->created_at->format('Y-m-d H:i:s')
            ]);
        }

        fclose($handle);
        exit;
    }
}