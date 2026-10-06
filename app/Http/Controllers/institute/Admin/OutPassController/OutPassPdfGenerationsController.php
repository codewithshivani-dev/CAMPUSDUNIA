<?php

namespace App\Http\Controllers;

use App\Models\OutPassPdfGeneration;
use App\Models\OutPassMigration;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class OutPassPdfGenerationController extends Controller
{
    /**
     * Display a listing of PDF generations.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = OutPassPdfGeneration::with(['outPass', 'generator'])
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($request->filled('out_pass_id')) {
            $query->where('out_pass_id', $request->out_pass_id);
        }

        if ($request->filled('generated_by')) {
            $query->where('generated_by', $request->generated_by);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
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

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('file_name', 'LIKE', "%{$search}%")
                  ->orWhereHas('outPass', function ($oq) use ($search) {
                      $oq->where('pass_code', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('generator', function ($uq) use ($search) {
                      $uq->where('name', 'LIKE', "%{$search}%")
                         ->orWhere('email', 'LIKE', "%{$search}%");
                  });
            });
        }

        $pdfGenerations = $query->paginate(15)->withQueryString();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $pdfGenerations
            ]);
        }

        return view('pdf-generations.index', compact('pdfGenerations'));
    }

    /**
     * Store a newly created PDF generation record.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'in' => 'nullable|string',
            'branch_id' => 'nullable|string',
            'out_pass_id' => 'required|integer|exists:out_pass_migrations,id',
            'generated_by' => 'nullable|integer|exists:users,id',
            'file_name' => 'required|string|max:255',
            'file_path' => 'nullable|string|max:500',
            'file_size' => 'nullable|integer|min:0',
            'status' => 'required|in:generated,failed,downloaded',
            'options' => 'nullable|array',
            'downloaded_at' => 'nullable|date',
            'downloaded_ip' => 'nullable|ip',
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

            // Create PDF generation record
            $pdfGeneration = new OutPassPdfGeneration();
            $pdfGeneration->in = $request->in;
            $pdfGeneration->branch_id = $request->branch_id;
            $pdfGeneration->out_pass_id = $request->out_pass_id;
            $pdfGeneration->generated_by = $request->generated_by ?? auth()->id();
            $pdfGeneration->file_name = $request->file_name;
            $pdfGeneration->file_path = $request->file_path;
            $pdfGeneration->file_size = $request->file_size;
            $pdfGeneration->status = $request->status;
            $pdfGeneration->options = $request->options ? json_encode($request->options) : null;
            $pdfGeneration->downloaded_at = $request->downloaded_at;
            $pdfGeneration->downloaded_ip = $request->downloaded_ip;
            
            $pdfGeneration->save();

            DB::commit();

            $message = 'PDF generation record created successfully.';

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'data' => $pdfGeneration->load(['outPass', 'generator'])
                ], 201);
            }

            return redirect()->route('pdf-generations.show', $pdfGeneration->id)
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            
            $error = 'Failed to create PDF generation record: ' . $e->getMessage();
            
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
     * Display the specified PDF generation record.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pdfGeneration = OutPassPdfGeneration::with(['outPass', 'generator'])
            ->findOrFail($id);

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $pdfGeneration
            ]);
        }

        return view('pdf-generations.show', compact('pdfGeneration'));
    }

    /**
     * Update the specified PDF generation record.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $pdfGeneration = OutPassPdfGeneration::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'status' => 'sometimes|in:generated,failed,downloaded',
            'downloaded_at' => 'nullable|date',
            'downloaded_ip' => 'nullable|ip',
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

            if ($request->has('status')) {
                $pdfGeneration->status = $request->status;
            }

            if ($request->has('downloaded_at')) {
                $pdfGeneration->downloaded_at = $request->downloaded_at;
            }

            if ($request->has('downloaded_ip')) {
                $pdfGeneration->downloaded_ip = $request->downloaded_ip;
            }

            $pdfGeneration->save();

            DB::commit();

            $message = 'PDF generation record updated successfully.';

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'data' => $pdfGeneration
                ]);
            }

            return redirect()->route('pdf-generations.show', $id)
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            
            $error = 'Failed to update PDF generation record: ' . $e->getMessage();
            
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
     * Remove the specified PDF generation record from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $pdfGeneration = OutPassPdfGeneration::findOrFail($id);

        try {
            DB::beginTransaction();

            // Delete physical file if exists
            if ($pdfGeneration->file_path && Storage::disk('public')->exists($pdfGeneration->file_path)) {
                Storage::disk('public')->delete($pdfGeneration->file_path);
            }

            $pdfGeneration->delete();

            DB::commit();

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'PDF generation record deleted successfully.'
                ]);
            }

            return redirect()->route('pdf-generations.index')
                ->with('success', 'PDF generation record deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            $error = 'Failed to delete PDF generation record: ' . $e->getMessage();
            
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
     * Get PDF generations by out pass ID.
     *
     * @param  int  $outPassId
     * @return \Illuminate\Http\Response
     */
    public function getByOutPass($outPassId)
    {
        $pdfGenerations = OutPassPdfGeneration::where('out_pass_id', $outPassId)
            ->with(['generator'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $pdfGenerations,
            'count' => $pdfGenerations->count()
        ]);
    }

    /**
     * Get PDF generations by generator ID.
     *
     * @param  int  $generatorId
     * @return \Illuminate\Http\Response
     */
    public function getByGenerator($generatorId)
    {
        $pdfGenerations = OutPassPdfGeneration::where('generated_by', $generatorId)
            ->with(['outPass'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $pdfGenerations
        ]);
    }

    /**
     * Mark PDF as downloaded.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function markDownloaded(Request $request, $id)
    {
        $pdfGeneration = OutPassPdfGeneration::findOrFail($id);

        try {
            DB::beginTransaction();

            $pdfGeneration->status = 'downloaded';
            $pdfGeneration->downloaded_at = now();
            $pdfGeneration->downloaded_ip = $request->ip();
            $pdfGeneration->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'PDF marked as downloaded successfully.',
                'data' => $pdfGeneration
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark PDF as downloaded: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download PDF file.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function download($id)
    {
        $pdfGeneration = OutPassPdfGeneration::findOrFail($id);

        if (!$pdfGeneration->file_path || !Storage::disk('public')->exists($pdfGeneration->file_path)) {
            return response()->json([
                'success' => false,
                'message' => 'PDF file not found.'
            ], 404);
        }

        // Update download status
        $pdfGeneration->status = 'downloaded';
        $pdfGeneration->downloaded_at = now();
        $pdfGeneration->downloaded_ip = request()->ip();
        $pdfGeneration->save();

        return Storage::disk('public')->download($pdfGeneration->file_path, $pdfGeneration->file_name);
    }

    /**
     * Get statistics.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function statistics(Request $request)
    {
        $query = OutPassPdfGeneration::query();

        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('created_at', [$request->date_from, $request->date_to]);
        }

        $stats = [
            'total_generations' => $query->count(),
            'by_status' => (clone $query)
                ->select('status', DB::raw('count(*) as total'))
                ->groupBy('status')
                ->get()
                ->keyBy('status'),
            'by_generator' => (clone $query)
                ->select('generated_by', DB::raw('count(*) as total'))
                ->with('generator:id,name')
                ->groupBy('generated_by')
                ->get()
                ->map(function ($item) {
                    return [
                        'generator_id' => $item->generated_by,
                        'generator_name' => $item->generator->name ?? 'Unknown',
                        'total' => $item->total
                    ];
                }),
            'today' => (clone $query)->whereDate('created_at', Carbon::today())->count(),
            'this_week' => (clone $query)->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count(),
            'this_month' => (clone $query)->whereMonth('created_at', Carbon::now()->month)->count(),
            'total_file_size' => (clone $query)->sum('file_size'),
            'average_file_size' => (clone $query)->avg('file_size'),
            'download_rate' => (clone $query)->where('status', 'downloaded')->count() / max($query->count(), 1) * 100,
        ];

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        }

        return view('pdf-generations.statistics', compact('stats'));
    }

    /**
     * Export PDF generations data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request)
    {
        $query = OutPassPdfGeneration::with(['outPass', 'generator']);

        if ($request->filled('out_pass_id')) {
            $query->where('out_pass_id', $request->out_pass_id);
        }

        if ($request->filled('generated_by')) {
            $query->where('generated_by', $request->generated_by);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('created_at', [$request->date_from, $request->date_to]);
        }

        $pdfGenerations = $query->orderBy('created_at', 'desc')->get();

        $filename = 'pdf-generations-' . date('Y-m-d') . '.csv';
        $handle = fopen('php://output', 'w');

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        // Add CSV headers
        fputcsv($handle, [
            'ID',
            'Out Pass Code',
            'Generated By',
            'File Name',
            'File Size (KB)',
            'Status',
            'Options',
            'Downloaded At',
            'Downloaded IP',
            'Created At'
        ]);

        // Add data rows
        foreach ($pdfGenerations as $pdf) {
            fputcsv($handle, [
                $pdf->id,
                $pdf->outPass->pass_code ?? 'N/A',
                $pdf->generator->name ?? 'System',
                $pdf->file_name,
                $pdf->file_size ? round($pdf->file_size / 1024, 2) : 'N/A',
                $pdf->status,
                $pdf->options ? json_encode($pdf->options) : 'N/A',
                $pdf->downloaded_at ? $pdf->downloaded_at->format('Y-m-d H:i:s') : 'N/A',
                $pdf->downloaded_ip ?? 'N/A',
                $pdf->created_at->format('Y-m-d H:i:s')
            ]);
        }

        fclose($handle);
        exit;
    }

    /**
     * Clean up old PDF files.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function cleanup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'days' => 'required|integer|min:30|max:365',
            'delete_files' => 'nullable|boolean',
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

            $query = OutPassPdfGeneration::where('created_at', '<', $cutoffDate)
                ->where('status', '!=', 'failed');

            $records = $query->get();
            $recordCount = $records->count();
            $fileCount = 0;

            // Delete physical files if requested
            if ($request->boolean('delete_files')) {
                foreach ($records as $record) {
                    if ($record->file_path && Storage::disk('public')->exists($record->file_path)) {
                        Storage::disk('public')->delete($record->file_path);
                        $fileCount++;
                    }
                }
            }

            // Delete records
            $query->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Cleanup completed: {$recordCount} records deleted, {$fileCount} files deleted.",
                'deleted_records' => $recordCount,
                'deleted_files' => $fileCount
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to cleanup: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Retry failed PDF generation.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function retry($id)
    {
        $pdfGeneration = OutPassPdfGeneration::findOrFail($id);

        if ($pdfGeneration->status !== 'failed') {
            return response()->json([
                'success' => false,
                'message' => 'Only failed PDF generations can be retried.'
            ], 400);
        }

        // Here you would implement the logic to regenerate the PDF
        // This depends on your PDF generation service

        return response()->json([
            'success' => true,
            'message' => 'PDF generation retry initiated.',
            'data' => $pdfGeneration
        ]);
    }

    /**
     * Get generation trends.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function trends(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'days' => 'nullable|integer|min:7|max:90',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $days = $request->get('days', 30);
        $startDate = Carbon::now()->subDays($days);

        $trends = OutPassPdfGeneration::where('created_at', '>=', $startDate)
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN status = "downloaded" THEN 1 ELSE 0 END) as downloaded'),
                DB::raw('SUM(CASE WHEN status = "failed" THEN 1 ELSE 0 END) as failed')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $trends
        ]);
    }
}