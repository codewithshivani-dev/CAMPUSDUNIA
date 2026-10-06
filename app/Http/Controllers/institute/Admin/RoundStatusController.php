<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\RoundStatus;
use Illuminate\Support\Facades\Log;
class RoundStatusController extends Controller
{
    /**
     * Initialize round status for a single lead
     */
    public function initializeRoundStatus($leadId)
    {
        $user = Auth::user();

        // Check Institute
        if (!$user || !$user->institute_id) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 422);
        }

        $lead = Lead::with(['InterviewRegistration.interviewConfiguration.interviewRounds'])
            ->where('institute_id', $user->institute_id)
            ->find($leadId);

        if (!$lead) {
            return response()->json([
                'success' => false,
                'message' => 'Lead not found'
            ]);
        }

        // Check if round status already exists for this lead
        $existingRoundStatuses = DB::table('round_status')
            ->where('lead_id', $lead->lead_id)  // Use formatted lead_id
            ->where('institute_id', $user->institute_id)
            ->get();

        if ($existingRoundStatuses->count() > 0) {
            return response()->json([
                'success' => true,
                'message' => 'Round status already exists for this lead',
                'exists' => true,
                'count' => $existingRoundStatuses->count(),
                'data' => $existingRoundStatuses
            ]);
        }

        // Get rounds from the actual data
        $rounds = [];

        if (
            $lead->InterviewRegistration &&
            $lead->InterviewRegistration->interviewConfiguration &&
            $lead->InterviewRegistration->interviewConfiguration->interviewRounds &&
            $lead->InterviewRegistration->interviewConfiguration->interviewRounds->count() > 0
        ) {

            $interviewRounds = $lead->InterviewRegistration->interviewConfiguration->interviewRounds;

            foreach ($interviewRounds as $index => $round) {
                // ALWAYS set to 'pending' for new rounds, regardless of what's in the round config
                $status = 'pending';

                $rounds[] = [
                    'institute_id' => $user->institute_id,
                    'branch_id' => $user->branch_id ?? null,
                    'reference_id' => null,
                    'lead_id' => $lead->lead_id,  // Store the formatted lead_id
                    'name' => $round->name ?? 'Round ' . ($index + 1),
                    'round_status' => $status,
                    'lifecycle_status' => 'not_started',
                    'desc' => $round->description ?? '-',
                    'type' => $round->type ?? 'general',
                    'type_label' => $round->type_label ?? ($round->type ? ucfirst($round->type) . ' Round' : 'Round ' . ($index + 1)),
                    'panel' => $round->panel ?? '',
                    'round_date' => $round->round_date ?? date('Y-m-d'),
                    'start_time' => $round->start_time ?? '09:00:00',
                    'end_time' => $round->end_time ?? '10:00:00',
                    'duration' => $round->duration ?? 60,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }

        // Only insert if rounds were found
        if (!empty($rounds)) {
            DB::table('round_status')->insert($rounds);

            $inserted = DB::table('round_status')
                ->where('lead_id', $lead->lead_id)
                ->where('institute_id', $user->institute_id)
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Round status initialized successfully with pending status',
                'exists' => false,
                'count' => count($inserted),
                'data' => $inserted
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'No interview rounds found for this lead',
            'exists' => false,
            'count' => 0,
            'data' => []
        ]);
    }
    /**
     * Initialize round status for all interview leads
     */
    public function initializeAllRoundStatus()
    {
        $user = Auth::user();

        // Check Institute
        if (!$user || !$user->institute_id) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 422);
        }

        $leads = Lead::with(['InterviewRegistration.interviewConfiguration.interviewRounds'])
            ->where('institute_id', $user->institute_id)
            ->where('lead_type', 'Interview')
            ->get();

        $initialized = 0;
        $alreadyExists = 0;
        $noRounds = 0;
        $results = [];

        foreach ($leads as $lead) {
            $result = $this->initializeRoundStatus($lead->id); // Keep using $lead->id here to find the lead
            $responseData = json_decode($result->getContent(), true);

            if ($responseData['success']) {
                if (isset($responseData['exists']) && $responseData['exists']) {
                    $alreadyExists++;
                    $results[] = [
                        'lead_id' => $lead->lead_id,  // Store the formatted lead_id
                        'lead_name' => $lead->name,
                        'status' => 'already_exists',
                        'count' => $responseData['count'] ?? 0
                    ];
                } else if ($responseData['count'] > 0) {
                    $initialized++;
                    $results[] = [
                        'lead_id' => $lead->lead_id,  // Store the formatted lead_id
                        'lead_name' => $lead->name,
                        'status' => 'initialized',
                        'count' => $responseData['count']
                    ];
                } else {
                    $noRounds++;
                    $results[] = [
                        'lead_id' => $lead->lead_id,  // Store the formatted lead_id
                        'lead_name' => $lead->name,
                        'status' => 'no_rounds',
                        'count' => 0
                    ];
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Initialized: $initialized, Already existed: $alreadyExists, No rounds: $noRounds",
            'summary' => [
                'initialized' => $initialized,
                'already_exists' => $alreadyExists,
                'no_rounds' => $noRounds,
                'total' => count($leads)
            ],
            'details' => $results
        ]);
    }
    public function checkRoundStatus($leadId)
    {
        $user = Auth::user();

        if (!$user || !$user->institute_id) {
            return response()->json([
                'success' => false,
                'message' => 'Not associated with any institute.'
            ], 422);
        }

        // Find the lead by ID or lead_id
        $lead = Lead::where('institute_id', $user->institute_id)
            ->where(function ($query) use ($leadId) {
                $query->where('id', $leadId)
                    ->orWhere('lead_id', $leadId);
            })
            ->first();

        if (!$lead) {
            return response()->json([
                'success' => false,
                'message' => 'Lead not found'
            ]);
        }

        // Check round_status table using lead_id (formatted string)
        $roundStatuses = DB::table('round_status')
            ->where('lead_id', $lead->lead_id)
            ->where('institute_id', $user->institute_id)
            ->get();

        return response()->json([
            'success' => true,
            'lead_id' => $lead->lead_id,
            'lead_db_id' => $lead->id,
            'rounds_count' => $roundStatuses->count(),
            'rounds' => $roundStatuses
        ]);
    }
    public function updateMarks(Request $request, $id)
    {
        try {
            $request->validate([
                'marks' => 'required|numeric|min:0'
            ]);

            $roundStatus = RoundStatus::findOrFail($id);

            $this->authorizeRound($roundStatus);
            if (!in_array($roundStatus->lifecycle_status, ['completed'], true)) {
                return response()->json(['success' => false, 'message' => 'Complete this round to enter marks.'], 422);
            }

            // For test rounds, validate against total_marks
            if ($roundStatus->type == 'test' && $roundStatus->total_marks && $request->marks > $roundStatus->total_marks) {
                return response()->json([
                    'success' => false,
                    'message' => 'Marks cannot exceed total marks (' . $roundStatus->total_marks . ')'
                ], 422);
            }

            // For non-test rounds, validate rating is between 1-5
            if ($roundStatus->type != 'test' && ($request->marks < 1 || $request->marks > 5)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Rating must be between 1 and 5 stars'
                ], 422);
            }

            $roundStatus->marks = $request->marks;
            $roundStatus->save();

            // Auto-update round status based on marks for test rounds
            if ($roundStatus->type == 'test' && $roundStatus->total_marks && $roundStatus->passing_marks) {
                if ($request->marks >= $roundStatus->passing_marks) {
                    $roundStatus->round_status = 'passed';
                } else {
                    $roundStatus->round_status = 'failed';
                }
                $roundStatus->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Saved successfully',
                'data' => $roundStatus
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('RoundStatus not found: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Round status record not found'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error saving: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error saving: ' . $e->getMessage()
            ], 500);
        }
    }

    private function authorizeRound(RoundStatus $roundStatus): Lead
    {
        $user = Auth::user();
        abort_unless($user && $user->institute_id == $roundStatus->institute_id, 403);
        $lead = Lead::where('lead_id', $roundStatus->lead_id)
            ->where('institute_id', $roundStatus->institute_id)
            ->where('lead_type', 'Interview')
            ->firstOrFail();
        return $lead;
    }

    public function startRound(Request $request, $id)
    {
        $round = RoundStatus::findOrFail($id);
        $this->authorizeRound($round);
        if ($round->lifecycle_status === 'completed') {
            return response()->json(['success' => false, 'message' => 'Completed rounds cannot be started again.'], 422);
        }
        $round->update([
            'lifecycle_status' => 'in_progress',
            'started_at' => now()
        ]);
        Log::info('Interview round started', ['round_status_id' => $round->id, 'lead_id' => $round->lead_id]);
        return response()->json(['success' => true, 'message' => 'Round started successfully', 'data' => $round->fresh()]);
    }

    public function completeRound(Request $request, $id)
    {
        $round = RoundStatus::findOrFail($id);
        $this->authorizeRound($round);
        if ($round->lifecycle_status === 'completed') {
            return response()->json(['success' => false, 'message' => 'Round is already completed.'], 422);
        }
        $round->update([
            'lifecycle_status' => 'completed',
            'completed_at' => now()
        ]);
        Log::info('Interview round completed', ['round_status_id' => $round->id, 'lead_id' => $round->lead_id]);
        return response()->json(['success' => true, 'message' => 'Round completed successfully', 'data' => $round->fresh()]);
    }

    public function rescheduleRound(Request $request, $id)
    {
        $request->validate(['round_date' => 'required|date', 'start_time' => 'required|date_format:H:i']);
        $round = RoundStatus::findOrFail($id);
        $this->authorizeRound($round);
        $previous = $round->round_date
            ? \Carbon\Carbon::parse($round->round_date)->format('Y-m-d') . ' ' . \Carbon\Carbon::parse($round->start_time)->format('H:i')
            : null;
        $round->update([
            'lifecycle_status' => 'rescheduled',
            'rescheduled_from' => $previous,
            'rescheduled_at' => now(),
            'round_date' => $request->round_date,
            'start_time' => $request->start_time,
        ]);
        Log::info('Interview round rescheduled', ['round_status_id' => $round->id, 'lead_id' => $round->lead_id, 'new_date' => $request->round_date, 'new_time' => $request->start_time]);
        return response()->json(['success' => true, 'message' => 'Round rescheduled successfully', 'data' => $round->fresh()]);
    }
}