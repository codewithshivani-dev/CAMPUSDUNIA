<?php
// app/Http/Controllers/institute/Admin/InterviewConfigurationController.php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use App\Models\InterviewConfiguration;
use App\Models\Departments;
use App\Models\InterviewRound;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class InterviewConfigurationController extends Controller
{
    public function getInterviewConfiguration()
    {
        $selectDepartments = Departments::where('institute_id', Auth::user()->institute_id)
            ->select('department_id', 'department')
            ->distinct()
            ->get();

        return view('instituteAdmin.AdmissionConfiguration.interviewConfiguration', compact('selectDepartments'));
    }

    public function previewIndex()
    {
        $configurations = InterviewConfiguration::where('institute_id', Auth::user()->institute_id)
            ->with('department')
            ->latest('created_at')
            ->get();

        return view('instituteAdmin.AdmissionConfiguration.InterviewPreview', compact('configurations'));
    }

    public function previewDetails($id)
    {
        $configuration = InterviewConfiguration::where('institute_id', Auth::user()->institute_id)
            ->with(['department', 'venueBuilding', 'venueBlock', 'venueFloor', 'venueRoom'])
            ->findOrFail($id);

        $rounds = InterviewRound::where('interview_config_id', $configuration->interview_config_id)
            ->orderBy('round_date')
            ->orderBy('start_time')
            ->get();

        foreach (['application_documents', 'skills', 'selected_rounds', 'round_weightages', 'onboarding_documents'] as $field) {
            if (is_string($configuration->{$field}) && $configuration->{$field} !== '') {
                $configuration->{$field} = json_decode($configuration->{$field}, true) ?: [];
            }
        }

        return view('instituteAdmin.AdmissionConfiguration.InterviewPreviewDetails', compact('configuration', 'rounds'));
    }


    private function generateInterviewConfigId($academicYear)
    {

        $year = substr($academicYear, 0, 4);

        $lastConfig = InterviewConfiguration::where('academic_year', $academicYear)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastConfig && $lastConfig->interview_config_id) {

            $lastSequence = intval(substr($lastConfig->interview_config_id, -4));
            $newSequence = $lastSequence + 1;
        } else {

            $newSequence = 1;
        }


        return 'IC-' . $year . '-' . str_pad($newSequence, 4, '0', STR_PAD_LEFT);
    }

    public function store(Request $request)
    {
        try {
            $mode = $request->input('steps.interview.mode', 'online');
            $meetingLink = trim((string) $request->input('steps.interview.meeting_link', ''));
            if (!in_array($mode, ['offline', 'online', 'hybrid'], true)) {
                return response()->json(['success' => false, 'message' => 'Invalid interview mode.'], 422);
            }
            if (in_array($mode, ['online', 'hybrid'], true) && $meetingLink !== '' && !filter_var($meetingLink, FILTER_VALIDATE_URL)) {
                return response()->json(['success' => false, 'message' => 'Enter a valid Google Meet URL.'], 422);
            }

            Log::info('Interview Configuration Request:', $request->all());

            // Generate the interview_config_id
            $interviewConfigId = $this->generateInterviewConfigId($request->academic_year);

            // Process rounds data for JSON storage
            $roundsJson = null;
            if (isset($request->steps['interview']['rounds'])) {
                $roundsJson = json_encode($request->steps['interview']['rounds']);
            }

            $data = [
                'institute_id' => Auth::user()->institute_id,
                'academic_year' => $request->academic_year,
                'department_id' => $request->department_id,
                'interview_config_id' => $interviewConfigId,

                // Step 1 
                'step1_enabled' => $request->steps['application']['enabled'] ?? true,
                'form_title' => $request->steps['application']['form_title'] ?? null,
                'application_start_date' => $request->steps['application']['start_date'] ?? null,
                'application_end_date' => $request->steps['application']['end_date'] ?? null,
                'max_applications' => $request->steps['application']['max_applications'] ?? 500,
                'application_documents' => json_encode($request->steps['application']['docs'] ?? []),

                'skills' => json_encode($request->steps['application']['skills']['list'] ?? []),

                // Step 2
                'step2_enabled' => $request->steps['interview']['enabled'] ?? true,
                'interview_mode' => $mode,
                'dress_code' => $request->steps['interview']['dressCode'] ?? null,
                'custom_dress_code' => $request->steps['interview']['customDressCode'] ?? null,
                'venue_building' => $request->steps['interview']['location']['venue'] ?? null,
                'venue_room' => $request->steps['interview']['location']['room'] ?? null,
                'venue_address' => $request->steps['interview']['location']['address'] ?? null,
                'venue_building_id' => $request->steps['interview']['location']['building_id'] ?? null,
                'venue_block_id' => $request->steps['interview']['location']['block_id'] ?? null,
                'venue_floor_id' => $request->steps['interview']['location']['floor_id'] ?? null,
                'venue_room_id' => $request->steps['interview']['location']['room_id'] ?? null,
                'online_meet_link' => $request->steps['interview']['meeting_link'] ?? null,
                'interview_rounds' => $roundsJson,

                // Step 3 - Store round names (already converted by frontend)
                'step3_enabled' => $request->steps['selection']['enabled'] ?? true,
                'selected_rounds' => json_encode($request->steps['selection']['selectedRounds'] ?? []), // Now contains names
                'selection_method' => $request->steps['selection']['method'] ?? 'all',
                'round_weightages' => json_encode($request->steps['selection']['weightages'] ?? []),
                'offer_letter_generation' => $request->steps['selection']['offerLetter'] ?? 'auto',
                'acceptance_deadline_days' => $request->steps['selection']['acceptanceDays'] ?? 7,
                'send_rejection_emails' => $request->steps['selection']['sendRejection'] ?? true,

                // Step 4
                'step4_enabled' => $request->steps['onboarding']['enabled'] ?? true,
                'joining_date' => $request->steps['onboarding']['joiningDate'] ?? null,
                'reporting_time' => $request->steps['onboarding']['reportingTime'] ?? '09:00',
                'reporting_venue' => $request->steps['onboarding']['reportingVenue'] ?? 'HR Office',
                'induction_program' => $request->steps['onboarding']['induction'] ?? 'Mandatory',
                'induction_days' => $request->steps['onboarding']['inductionDays'] ?? 3,
                'training_months' => $request->steps['onboarding']['trainingMonths'] ?? 6,
                'onboarding_documents' => json_encode($request->steps['onboarding']['docs'] ?? []),
            ];

            // Check if configuration already exists
            $existingConfig = InterviewConfiguration::where([
                'institute_id' => Auth::user()->institute_id,
                'academic_year' => $request->academic_year,
                'department_id' => $request->department_id,
            ])->first();

            if ($existingConfig) {
                // If updating, keep the existing interview_config_id
                $data['interview_config_id'] = $existingConfig->interview_config_id;

                // Update the configuration
                InterviewConfiguration::where([
                    'institute_id' => Auth::user()->institute_id,
                    'academic_year' => $request->academic_year,
                    'department_id' => $request->department_id,
                ])->update($data);

                // Get the updated record
                $config = InterviewConfiguration::where([
                    'institute_id' => Auth::user()->institute_id,
                    'academic_year' => $request->academic_year,
                    'department_id' => $request->department_id,
                ])->first();

                // Handle interview rounds in the interview_rounds table for update
                if (isset($request->steps['interview']['rounds'])) {
                    // Delete existing rounds for this config
                    InterviewRound::where('interview_config_id', $existingConfig->interview_config_id)->delete();

                    // Insert new rounds
                    $insertData = [];
                    foreach ($request->steps['interview']['rounds'] as $round) {
                        $insertData[] = [
                            'institute_id' => Auth::user()->institute_id,
                            'interview_config_id' => $existingConfig->interview_config_id,
                            'branch_id' => null,
                            'name' => $round['name'],
                            'desc' => $round['desc'] ?? '',
                            'type' => $round['type'],
                            'type_label' => $round['typeLabel'],
                            'panel' => $round['panel'],
                            'round_date' => $round['roundDate'],
                            'start_time' => $round['startTime'],
                            'end_time' => $round['endTime'],
                            'duration' => $round['duration'],
                            'status' => 'active',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }

                    if (!empty($insertData)) {
                        InterviewRound::insert($insertData);
                    }
                }

                $message = 'Configuration updated successfully';
            } else {
                // If creating new, use the generated interview_config_id
                $config = InterviewConfiguration::create($data);

                // Handle interview rounds in the interview_rounds table for new record
                if (isset($request->steps['interview']['rounds'])) {
                    $insertData = [];
                    foreach ($request->steps['interview']['rounds'] as $round) {
                        $insertData[] = [
                            'institute_id' => Auth::user()->institute_id,
                            'interview_config_id' => $interviewConfigId,
                            'branch_id' => null,
                            'name' => $round['name'],
                            'desc' => $round['desc'] ?? '',
                            'type' => $round['type'],
                            'type_label' => $round['typeLabel'],
                            'panel' => $round['panel'],
                            'round_date' => $round['roundDate'],
                            'start_time' => $round['startTime'],
                            'end_time' => $round['endTime'],
                            'duration' => $round['duration'],
                            'status' => 'active',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }

                    if (!empty($insertData)) {
                        InterviewRound::insert($insertData);
                    }
                }

                $message = 'Configuration saved successfully';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $config,
                'interview_config_id' => $config->interview_config_id
            ]);

        } catch (\Exception $e) {
            Log::error('Interview Configuration Error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $config = InterviewConfiguration::findOrFail($id);

            // Get rounds from the interview_rounds table
            $rounds = InterviewRound::where('interview_config_id', $config->interview_config_id)->get();

            // Decode JSON fields when returning
            if ($config->application_documents) {
                $config->application_documents = json_decode($config->application_documents, true);
            }
            if ($config->skills) {
                $config->skills = json_decode($config->skills, true);
            }
            if ($config->selected_rounds) {
                $config->selected_rounds = json_decode($config->selected_rounds, true);
            }
            if ($config->round_weightages) {
                $config->round_weightages = json_decode($config->round_weightages, true);
            }
            if ($config->onboarding_documents) {
                $config->onboarding_documents = json_decode($config->onboarding_documents, true);
            }

            // Add rounds to the response
            $config->interview_rounds_list = $rounds;

            return response()->json([
                'success' => true,
                'data' => $config
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function index(Request $request)
    {
        try {
            $query = InterviewConfiguration::where('institute_id', Auth::user()->institute_id)
                ->with('department'); // Eager load department relationship

            if ($request->academic_year) {
                $query->where('academic_year', $request->academic_year);
            }

            if ($request->department_id) {
                $query->where('department_id', $request->department_id);
            }

            $configs = $query->orderBy('created_at', 'desc')->paginate(10);

            // Decode JSON fields for each config
            foreach ($configs as $config) {
                if ($config->application_documents) {
                    $config->application_documents = json_decode($config->application_documents, true);
                }
                if ($config->skills) {
                    $config->skills = json_decode($config->skills, true);
                }
                if ($config->interview_rounds) {
                    $config->interview_rounds = json_decode($config->interview_rounds, true);
                }
                if ($config->selected_rounds) {
                    $config->selected_rounds = json_decode($config->selected_rounds, true);
                }
                if ($config->round_weightages) {
                    $config->round_weightages = json_decode($config->round_weightages, true);
                }
                if ($config->onboarding_documents) {
                    $config->onboarding_documents = json_decode($config->onboarding_documents, true);
                }
            }

            return response()->json([
                'success' => true,
                'data' => $configs
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get the latest interview_config_id for a specific academic year
     */
    public function getLatestConfigId($academicYear)
    {
        try {
            $latestConfig = InterviewConfiguration::where('academic_year', $academicYear)
                ->orderBy('id', 'desc')
                ->first();

            if ($latestConfig) {
                return response()->json([
                    'success' => true,
                    'interview_config_id' => $latestConfig->interview_config_id
                ]);
            } else {
                return response()->json([
                    'success' => true,
                    'interview_config_id' => null
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}