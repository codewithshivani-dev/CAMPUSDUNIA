<?php

namespace App\Http\Controllers;

use App\Models\OutPassHistory;
use App\Models\OutPassMigration;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OutPassHistoryController extends Controller
{
    /**
     * Display a listing of history records.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = OutPassHistory::with(['outPass', 'user'])
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($request->filled('out_pass_id')) {
            $query->where('out_pass_id', $request->out_pass_id);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
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

        if ($request->filled('previous_status')) {
            $query->where('previous_status', $request->previous_status);
        }

        if ($request->filled('new_status')) {
            $query->where('new_status', $request->new_status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('remarks', 'LIKE', "%{$search}%")
                  ->orWhere('ip_address', 'LIKE', "%{$search}%")
                  ->orWhereHas('outPass', function ($oq) use ($search) {
                      $oq->where('pass_code', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'LIKE', "%{$search}%")
                         ->orWhere('email', 'LIKE', "%{$search}%");
                  });
            });
        }

        $histories = $query->paginate(15)->withQueryString();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $histories
            ]);
        }

        return view('out-pass-history.index', compact('histories'));
    }

    /**
     * Store a newly created history record.
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
            'user_id' => 'nullable|integer|exists:users,id',
            'action' => 'required|in:created,submitted,viewed,approved,rejected,cancelled,printed,downloaded,returned,expired',
            'previous_status' => 'nullable|in:pending,approved,rejected,cancelled,expired,used',
            'new_status' => 'nullable|in:pending,approved,rejected,cancelled,expired,used',
            'remarks' => 'nullable|string|max:1000',
            'ip_address' => 'nullable|ip',
            'user_agent' => 'nullable|string|max:500',
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

            // Create history record
            $history = new OutPassHistory();
            $history->institute_id = $request->institute_id;
            $history->branch_id = $request->branch_id;
            $history->out_pass_id = $request->out_pass_id;
            $history->user_id = $request->user_id ?? auth()->id();
            $history->action = $request->action;
            $history->previous_status = $request->previous_status;
            $history->new_status = $request->new_status;
            $history->remarks = $request->remarks;
            $history->ip_address = $request->ip_address ?? $request->ip();
            $history->user_agent = $request->user_agent ?? $request->header('User-Agent');
            
            $history->save();

            DB::commit();

            $message = 'History record created successfully.';

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'data' => $history->load(['outPass', 'user'])
                ], 201);
            }

            return redirect()->route('out-pass-history.show', $history->id)
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            
            $error = 'Failed to create history record: ' . $e->getMessage();
            
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
     * Display the specified history record.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $history = OutPassHistory::with(['outPass', 'user'])
            ->findOrFail($id);

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $history
            ]);
        }

        return view('out-pass-history.show', compact('history'));
    }

    /**
     * Remove the specified history record from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $history = OutPassHistory::findOrFail($id);

        // Optional: Add permission check here
        // if (!auth()->user()->can('delete-history')) {
        //     return $this->sendErrorResponse('Unauthorized', 403);
        // }

        $history->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'History record deleted successfully.'
            ]);
        }

        return redirect()->route('out-pass-history.index')
            ->with('success', 'History record deleted successfully.');
    }

    /**
     * Get history by out pass ID.
     *
     * @param  int  $outPassId
     * @return \Illuminate\Http\Response
     */
    public function getByOutPass($outPassId)
    {
        $histories = OutPassHistory::where('out_pass_id', $outPassId)
            ->with(['user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $histories,
            'count' => $histories->count()
        ]);
    }

    /**
     * Get history by user ID.
     *
     * @param  int  $userId
     * @return \Illuminate\Http\Response
     */
    public function getByUser($userId)
    {
        $histories = OutPassHistory::where('user_id', $userId)
            ->with(['outPass'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $histories
        ]);
    }

    /**
     * Get history by action type.
     *
     * @param  string  $action
     * @return \Illuminate\Http\Response
     */
    public function getByAction($action)
    {
        $validator = Validator::make(['action' => $action], [
            'action' => 'required|in:created,submitted,viewed,approved,rejected,cancelled,printed,downloaded,returned,expired'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $histories = OutPassHistory::where('action', $action)
            ->with(['outPass', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $histories
        ]);
    }

    /**
     * Get status change history.
     *
     * @param  int  $outPassId
     * @return \Illuminate\Http\Response
     */
    public function getStatusChanges($outPassId)
    {
        $histories = OutPassHistory::where('out_pass_id', $outPassId)
            ->whereNotNull('previous_status')
            ->whereNotNull('new_status')
            ->with(['user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $histories
        ]);
    }

    /**
     * Get timeline for an out pass.
     *
     * @param  int  $outPassId
     * @return \Illuminate\Http\Response
     */
    public function getTimeline($outPassId)
    {
        $histories = OutPassHistory::where('out_pass_id', $outPassId)
            ->with(['user'])
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'action' => $item->action,
                    'action_label' => $item->action_label,
                    'action_icon' => $item->action_icon,
                    'action_color' => $item->action_color,
                    'user_name' => $item->user->name ?? 'System',
                    'previous_status' => $item->previous_status,
                    'new_status' => $item->new_status,
                    'remarks' => $item->remarks,
                    'ip_address' => $item->ip_address,
                    'created_at' => $item->created_at->format('Y-m-d H:i:s'),
                    'created_at_diff' => $item->created_at->diffForHumans(),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $histories
        ]);
    }

    /**
     * Get statistics.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function statistics(Request $request)
    {
        $query = OutPassHistory::query();

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
            'total_records' => $query->count(),
            'by_action' => (clone $query)
                ->select('action', DB::raw('count(*) as total'))
                ->groupBy('action')
                ->get()
                ->keyBy('action'),
            'by_user' => (clone $query)
                ->select('user_id', DB::raw('count(*) as total'))
                ->with('user:id,name')
                ->groupBy('user_id')
                ->get()
                ->map(function ($item) {
                    return [
                        'user_id' => $item->user_id,
                        'user_name' => $item->user->name ?? 'System',
                        'total' => $item->total
                    ];
                }),
            'today' => (clone $query)->whereDate('created_at', Carbon::today())->count(),
            'this_week' => (clone $query)->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count(),
            'this_month' => (clone $query)->whereMonth('created_at', Carbon::now()->month)->count(),
            'status_changes' => (clone $query)->whereNotNull('previous_status')->count(),
            'peak_hours' => $this->getPeakHours(clone $query),
        ];

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        }

        return view('out-pass-history.statistics', compact('stats'));
    }

    /**
     * Get peak hours for actions.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return array
     */
    private function getPeakHours($query)
    {
        return $query->select(DB::raw('HOUR(created_at) as hour'), DB::raw('count(*) as total'))
            ->groupBy('hour')
            ->orderBy('hour')
            ->get()
            ->pluck('total', 'hour')
            ->toArray();
    }

    /**
     * Export history data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request)
    {
        $query = OutPassHistory::with(['outPass', 'user']);

        if ($request->filled('out_pass_id')) {
            $query->where('out_pass_id', $request->out_pass_id);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('created_at', [$request->date_from, $request->date_to]);
        }

        $histories = $query->orderBy('created_at', 'desc')->get();

        $filename = 'out-pass-history-' . date('Y-m-d') . '.csv';
        $handle = fopen('php://output', 'w');

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        // Add CSV headers
        fputcsv($handle, [
            'ID',
            'Out Pass Code',
            'User',
            'Action',
            'Previous Status',
            'New Status',
            'Remarks',
            'IP Address',
            'User Agent',
            'Created At'
        ]);

        // Add data rows
        foreach ($histories as $history) {
            fputcsv($handle, [
                $history->id,
                $history->outPass->pass_code ?? 'N/A',
                $history->user->name ?? 'System',
                $history->action,
                $history->previous_status,
                $history->new_status,
                $history->remarks,
                $history->ip_address,
                $history->user_agent,
                $history->created_at->format('Y-m-d H:i:s')
            ]);
        }

        fclose($handle);
        exit;
    }

    /**
     * Clear old history records.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function clearOld(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'days' => 'required|integer|min:30|max:365',
            'institute_id' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $cutoffDate = Carbon::now()->subDays($request->days);

            $query = OutPassHistory::where('created_at', '<', $cutoffDate);

            if ($request->filled('institute_id')) {
                $query->where('institute_id', $request->institute_id);
            }

            $count = $query->count();
            $query->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "{$count} old history records deleted successfully.",
                'deleted_count' => $count
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear old history: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Log an action (helper method for other controllers).
     *
     * @param  array  $data
     * @return OutPassHistory
     */
    public static function logAction(array $data)
    {
        $history = new OutPassHistory();
        $history->institute_id = $data['institute_id'] ?? null;
        $history->branch_id = $data['branch_id'] ?? null;
        $history->out_pass_id = $data['out_pass_id'];
        $history->user_id = $data['user_id'] ?? auth()->id();
        $history->action = $data['action'];
        $history->previous_status = $data['previous_status'] ?? null;
        $history->new_status = $data['new_status'] ?? null;
        $history->remarks = $data['remarks'] ?? null;
        $history->ip_address = $data['ip_address'] ?? request()->ip();
        $history->user_agent = $data['user_agent'] ?? request()->header('User-Agent');
        
        $history->save();

        return $history;
    }
}