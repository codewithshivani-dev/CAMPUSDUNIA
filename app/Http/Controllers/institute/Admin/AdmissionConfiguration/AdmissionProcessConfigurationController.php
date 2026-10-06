<?php

namespace App\Http\Controllers\institute\Admin\AdmissionConfiguration;

use App\Http\Controllers\Controller;
use App\Models\AdmissionProcessConfig;
use App\Models\CounsellingTimeSlot;
use App\Models\EntranceTestSlot;
use App\Models\EntranceTest;
use App\Models\AcademicYear;
use App\Models\Departments;
use App\Models\CommonCustomFees;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AdmissionProcessConfigurationController extends Controller
{
    /**
     * Display the admission process configuration page.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please log in first.');
        }
        
        $instituteId = $user->institute_id;
        $selectedAcademicYear = $request->query('academic_year', '2026-2027');
        
        // Get all academic years for the institute
        $academicYears = $this->getAcademicYears($instituteId);
        
        // Get configuration for selected academic year
        $config = AdmissionProcessConfig::with(['counsellingTimeSlots', 'entranceTestSlots'])
            ->where('institute_id', $instituteId)
            ->where('academic_year', $selectedAcademicYear) 
            ->first();
        // Get departments for dropdown
        $departments = Departments::where('institute_id', $instituteId)
            ->when($user->is_branch_admin && $user->branch_id, function($query) use ($user) {
                $query->where('branch_id', $user->branch_id);
            }, function($query) {
                $query->whereNull('branch_id');
            })
            ->orderBy('department')
            ->get();
        
        // Get active custom fees
        $customFees = CommonCustomFees::where('institute_id', $instituteId)
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->get();

        
        if (!$config) {
            $config = $this->createDefaultConfig($instituteId, $selectedAcademicYear);
        }

        return view('instituteAdmin.AdmissionConfiguration.ConfigureSteps', [
            'config' => $config,
            'academicYears' => $academicYears,
            'selectedAcademicYear' => $selectedAcademicYear,
            'selectDepartments' => $departments,
            'applicationFeeType' => $customFees
        ]);
    }

    /**
     * Save admission process configuration.
     */
    public function save(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'academic_year' => 'required|string',
            'admission_form_enabled' => 'boolean',
            'admission_form_mode' => 'in:online,offline,both',
            'admission_form_fee_amount' => 'nullable',
            'admission_form_max' => 'integer|min:1',
            'admission_form_start_date' => 'nullable|date',
            'admission_form_end_date' => 'nullable|date|after_or_equal:admission_form_start_date',
            
            'entrance_tests_enabled' => 'boolean',
            'entrance_tests_mode' => 'in:online,offline,both',
            'entrance_tests' => 'nullable|json',
            'entrance_test_fee_amount' => 'nullable',
            
            'counselling_enabled' => 'boolean',
            'counselling_mode' => 'in:online,offline,both',
            'counselling_session_duration' => 'integer|min:15',
            'counselling_max_candidates' => 'integer|min:1',
            'counselling_start_date' => 'nullable|date',
            'counselling_end_date' => 'nullable|date|after_or_equal:counselling_start_date',
            'counselling_working_start' => 'nullable|date_format:H:i',
            'counselling_working_end' => 'nullable|date_format:H:i|after:counselling_working_start',
            'counselling_break_start' => 'nullable|date_format:H:i',
            'counselling_break_end' => 'nullable|date_format:H:i|after:counselling_break_start',
            'counselling_time_slots' => 'nullable|json',
            
            'onboarding_enabled' => 'boolean',
            'onboarding_admission_fee' => 'numeric|min:0',
            'onboarding_security_deposit' => 'numeric|min:0',
            'onboarding_other_charges' => 'numeric|min:0',
            'onboarding_start_date' => 'nullable|date',
            'onboarding_classes_start_date' => 'nullable|date|after_or_equal:onboarding_start_date',
        ]);
        Log::info($request->all([]));
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        DB::beginTransaction();

        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }
            
            $instituteId = $user->institute_id;
            $academicYear = $request->academic_year;
            // Convert 2024-2025 → 2425
            $shortYear = str_replace('-', '', substr($academicYear, 2));

            // Generate 6 random uppercase characters
            $random = strtoupper(substr(bin2hex(random_bytes(3)), 0, 6));


            // Create a new main config record for every save action
            $config = new AdmissionProcessConfig();
            $config->institute_id = $instituteId;
            $config->academic_year = $academicYear;
            $config->admissionprocess_confun_id = "APC-{$shortYear}-{$random}";

            $config->department_id = $request->department_id ?? null;
            $config->product_id = $request->class_id ? collect($request->class_id)->toArray() : null;
            // Admission Form
            $config->admission_form_enabled = $request->admission_form_enabled ?? true;
            $config->admission_form_mode = $request->admission_form_mode ?? 'online';
            $config->admission_form_fee_amount = $request->admission_form_fee_amount ?? 0;
            $config->admission_form_max = $request->admission_form_max ?? 500;
            $config->admission_form_start_date = $request->admission_form_start_date;
            $config->admission_form_end_date = $request->admission_form_end_date;
            
            // Entrance Tests
            $config->entrance_tests_enabled = $request->entrance_tests_enabled ?? true;
            $config->entrance_tests_mode = $request->entrance_tests_mode ?? 'online';
            $config->entrance_tests = $request->entrance_tests ? json_decode($request->entrance_tests, true) : null;
            // Save selected global entrance test fee amount/reference
            if (Schema::hasColumn('admission_process_configs', 'entrance_test_fee_amount')) {
                $config->entrance_test_fee_amount = $request->entrance_test_fee_amount ?? null;
            }
            
            // Counselling
            $config->counselling_enabled = $request->counselling_enabled ?? true;
            $config->counselling_mode = $request->counselling_mode ?? 'online';
            $config->counselling_session_duration = $request->counselling_session_duration ?? 45;
            $config->counselling_max_candidates = $request->counselling_max_candidates ?? 1;
            $config->counselling_start_date = $request->counselling_start_date;
            $config->counselling_end_date = $request->counselling_end_date;
            if (Schema::hasColumn('admission_process_configs', 'counselling_time_slots')) {
                $config->counselling_time_slots = $request->counselling_time_slots ? json_decode($request->counselling_time_slots, true) : null;
            }
            $config->counselling_working_start = $request->counselling_working_start;
            $config->counselling_working_end = $request->counselling_working_end;
            $config->counselling_break_start = $request->counselling_break_start;
            $config->counselling_break_end = $request->counselling_break_end;
            // $config->counselling_days = $request->counselling_days;
            
            // Onboarding
            $config->onboarding_enabled = $request->onboarding_enabled ?? true;
            $config->onboarding_admission_fee = $request->onboarding_admission_fee ?? 0;
            $config->onboarding_security_deposit = $request->onboarding_security_deposit ?? 0;
            $config->onboarding_other_charges = $request->onboarding_other_charges ?? 0;
            $config->onboarding_start_date = $request->onboarding_start_date;
            $config->onboarding_classes_start_date = $request->onboarding_classes_start_date;
            $config->onboarding_documents_list = $request->onboarding_documents_list ?? null;
            $config->is_active = true;
            $config->save();
            if ($request->entrance_tests_enabled == true) {
                // Handle entrance tests slots
                $this->saveEntranceTestSlots($config->id, $request->entrance_tests);
                // Handle entrance tests records
                $this->saveEntranceTests($config->id, $request->entrance_tests);
            }

            if ($request->counselling_enabled == true) {
                // Handle counselling time slots
                $this->saveCounsellingTimeSlots($config->id, $request->counselling_time_slots, $request->counselling_start_date, $request->counselling_end_date);
            }
            DB::commit();

            // Format counselling slots for frontend
            // $formattedCounsellingSlots = $this->formatCounsellingSlotsForFrontend($config);

            return response()->json([
                'success' => true,
                'message' => 'Admission process configuration saved successfully!',
                'config' => [
                    'id' => $config->id,
                    'institute_id' => $config->institute_id,
                    'academic_year' => $config->academic_year,
                    // Admission Form
                    'admission_form_enabled' => $config->admission_form_enabled,
                    'admission_form_mode' => $config->admission_form_mode,
                    'admission_form_fee_amount' => $config->admission_form_fee_amount,
                    'admission_form_max' => $config->admission_form_max,
                    'admission_form_start_date' => $config->admission_form_start_date?->format('Y-m-d'),
                    'admission_form_end_date' => $config->admission_form_end_date?->format('Y-m-d'),
                    // Entrance Tests
                    'entrance_tests_enabled' => $config->entrance_tests_enabled,
                    'entrance_tests_mode' => $config->entrance_tests_mode,
                    'entrance_tests' => $config->entrance_tests,
                    'entrance_test_fee_amount' => $config->entrance_test_fee_amount ?? null,
                    // Counselling
                    'counselling_enabled' => $config->counselling_enabled,
                    'counselling_mode' => $config->counselling_mode,
                    'counselling_session_duration' => $config->counselling_session_duration,
                    'counselling_max_candidates' => $config->counselling_max_candidates,
                    'counselling_start_date' => $config->counselling_start_date?->format('Y-m-d'),
                    'counselling_end_date' => $config->counselling_end_date?->format('Y-m-d'),
                    'counselling_working_start' => $config->counselling_working_start?->format('H:i'),
                    'counselling_working_end' => $config->counselling_working_end?->format('H:i'),
                    'counselling_break_start' => $config->counselling_break_start?->format('H:i'),
                    'counselling_break_end' => $config->counselling_break_end?->format('H:i'),
                    // 'counselling_days' => $config->counselling_days ?? [],
                    'counselling_time_slots_frontend' => $formattedCounsellingSlots ?? [],
                    // Onboarding
                    'onboarding_enabled' => $config->onboarding_enabled,
                    'onboarding_admission_fee' => $config->onboarding_admission_fee,
                    'onboarding_security_deposit' => $config->onboarding_security_deposit,
                    'onboarding_other_charges' => $config->onboarding_other_charges,
                    'onboarding_start_date' => $config->onboarding_start_date?->format('Y-m-d'),
                    'onboarding_classes_start_date' => $config->onboarding_classes_start_date?->format('Y-m-d'),
                    'is_active' => $config->is_active
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error saving admission process config: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to save configuration: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Save entrance test slots to database.
     */
    private function saveEntranceTestSlots($configId, $entranceTestsJson)
    {
        if (!$entranceTestsJson) return;

        $entranceTests = json_decode($entranceTestsJson, true);
        if (!$entranceTests || !is_array($entranceTests)) return;

        $user = Auth::user();
        if (!$user) return;
        
        $instituteId = $user->institute_id;
        $branchId = $user->branch_id ?? null;

        // Delete old test slots
        EntranceTestSlot::where('admission_process_config_id', $configId)->delete();

        $testSlotsToInsert = [];

        foreach ($entranceTests as $test) {
            if (!isset($test['slotConfiguration']) || !isset($test['slotConfiguration']['numberOfSlots'])) {
                continue;
            }

            $numberOfSlots = $test['slotConfiguration']['numberOfSlots'];
            $testDate = $test['date'] ?? null;
            $testName = $test['name'] ?? 'Test';
            $duration = $test['duration'] ?? 120;

            if (!$testDate) continue;

            for ($i = 1; $i <= $numberOfSlots; $i++) {
                $slotTime = $test['slotConfiguration']['slotTimes'][$i - 1] ?? '09:00';
                $slotCapacity = $test['slotConfiguration']['slotCapacities'][$i - 1] ?? 50;

                // Calculate end time
                $endTime = $this->calculateEndTime($slotTime, $duration);

                $testSlotsToInsert[] = [
                    'institute_id' => $instituteId,
                    'branch_id' => $branchId,
                    'admission_process_config_id' => $configId,
                    'test_id' => $test['id'] ?? null,
                    'test_name' => $testName,
                    'test_marks' => $test['testMarks'] ?? null,
                    'test_passing_marks_percentage' => $test['passingPercentage'] ?? null,
                    'test_date' => $testDate,
                    'slot_number' => $i,
                    'start_time' => $slotTime,
                    'end_time' => $endTime,
                    'duration_minutes' => $duration,
                    'capacity' => $slotCapacity,
                    'test_fee' => $test['fee'] ?? $test['feeId'] ?? null,
                    'venue' => $test['buildingName'] ?? null,
                    'block_name' => $test['blockName'] ?? null,
                    'floor_name' => $test['floorName'] ?? null,
                    'room_name' => $test['roomName'] ?? null,
                    'booked_count' => 0,
                    'status' => 'scheduled',
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }

        // Insert in batches
        if (!empty($testSlotsToInsert)) {
            foreach (array_chunk($testSlotsToInsert, 100) as $chunk) {
                EntranceTestSlot::insert($chunk);
            }
        }
    }

    /**
     * Save entrance test records to database.
     * This creates test configuration records linked to the admission config
     */
    private function saveEntranceTests($configId, $entranceTestsJson)
    {
        if (!$entranceTestsJson) return;

        $entranceTests = json_decode($entranceTestsJson, true);
        if (!$entranceTests || !is_array($entranceTests)) return;

        $user = Auth::user();
        if (!$user) return;
        
        $instituteId = $user->institute_id;
        $branchId = $user->branch_id ?? null;

        // Delete old entrance test records for this config (only those without a student/lead)
        EntranceTest::where('admission_config_id', $configId)
            ->whereNull('lead_id')
            ->delete();

        $testsToInsert = [];

        foreach ($entranceTests as $test) {
            $testId = $test['id'] ?? null;
            $testName = $test['name'] ?? 'Test';
            $testDate = $test['date'] ?? null;
            $testMarks = $test['testMarks'] ?? null;
            $passingMarks = $test['passingPercentage'] ?? null;

            if (!$testDate) continue;

            // Create a master test record for the configuration
            $testsToInsert[] = [
                'institute_id' => $instituteId,
                'branch_id' => $branchId,
                'admission_config_id' => $configId,
                'test_id' => $testId,
                'test_name' => $testName,
                'entrance_test_date' => $testDate,
                'test_marks' => $testMarks,
                'test_passing_marks' => $passingMarks,
                'test_status' => 'pending', // Valid enum value: pending, passed, failed, absent
                'test_payment' => 'pending',
                'entrance_payment' => $test['fee'] ?? 0,
                'entrance_test_fee' => $test['fee'] ?? $test['feeId'] ?? null,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        // Insert test records in batches
        if (!empty($testsToInsert)) {
            foreach (array_chunk($testsToInsert, 100) as $chunk) {
                EntranceTest::insert($chunk);
            }
        }
    }

    /**
     * Save counselling time slots to database.
     */
    private function saveCounsellingTimeSlots($configId, $timeSlotsJson, $startDate, $endDate)
    {
        if (!$timeSlotsJson || !$startDate || !$endDate) {
            // Clear existing slots if no slots provided
            CounsellingTimeSlot::where('admission_process_config_id', $configId)->delete();
            return;
        }

        $timeSlotsData = json_decode($timeSlotsJson, true);
        if (!$timeSlotsData || !is_array($timeSlotsData)) return;

        DB::beginTransaction();

        try {
            // Delete old slots for this config
            CounsellingTimeSlot::where('admission_process_config_id', $configId)->delete();

            $slotsToInsert = [];
            $startDateObj = Carbon::parse($startDate);
            $endDateObj = Carbon::parse($endDate);

            // Debug: Log the data structure
            \Log::info('Time slots data structure:', ['data' => $timeSlotsData]);

            // Process each date-based key
            foreach ($timeSlotsData as $dateKey => $dayData) {
                // Debug the date key
                \Log::info('Processing date key:', ['key' => $dateKey]);
                
                // Try different date parsing methods
                $slotDate = null;
                
                // Method 1: Direct parsing if it's a valid date string
                try {
                    $slotDate = Carbon::parse($dateKey);
                } catch (\Exception $e) {
                    // Method 2: Parse format "2026-2-4" (Y-n-j)
                    if (preg_match('/(\d{4})-(\d{1,2})-(\d{1,2})/', $dateKey, $matches)) {
                        $year = $matches[1];
                        $month = $matches[2];
                        $day = $matches[3];
                        
                        try {
                            $slotDate = Carbon::create($year, $month, $day);
                        } catch (\Exception $e) {
                            \Log::warning('Failed to parse date from key:', ['key' => $dateKey, 'matches' => $matches]);
                            continue;
                        }
                    }
                }
                
                if (!$slotDate) {
                    \Log::warning('Could not parse date from key:', ['key' => $dateKey]);
                    continue;
                }

                // Check if date is within range
                if ($slotDate->between($startDateObj, $endDateObj)) {
                    $dayOfWeek = $slotDate->format('l');
                    $displayDate = $slotDate->format('Y-m-d');
                    
                    // Log for debugging
                    \Log::info('Processing slots for date:', [
                        'date_key' => $dateKey,
                        'parsed_date' => $displayDate,
                        'day_of_week' => $dayOfWeek,
                        'slot_count' => isset($dayData['slots']) ? count($dayData['slots']) : 0
                    ]);
                    
                    if (isset($dayData['slots']) && is_array($dayData['slots'])) {
                        foreach ($dayData['slots'] as $index => $slot) {
                            // Validate slot data
                            if (!isset($slot['time']) || empty($slot['time'])) {
                                \Log::warning('Skipping slot missing time:', ['slot' => $slot]);
                                continue;
                            }
                            
                            // Calculate end time
                            $duration = $slot['duration'] ?? 45;
                            $endTime = $this->calculateEndTime($slot['time'], $duration);
                            
                            $slotsToInsert[] = [
                                'admission_process_config_id' => $configId,
                                'day_of_week' => $dayOfWeek,
                                'slot_date' => $displayDate,
                                'start_time' => $slot['time'],
                                'end_time' => $endTime,
                                'duration_minutes' => $duration,
                                'capacity' => $slot['capacity'] ?? 1,
                                'booked_count' => 0,
                                'status' => isset($slot['active']) && $slot['active'] ? 'active' : 'inactive',
                                'is_break_slot' => false,
                                'notes' => null,
                                'sort_order' => $index + 1, // Use index for ordering
                                'created_at' => now(),
                                'updated_at' => now()
                            ];
                        }
                    }
                } else {
                    \Log::info('Date outside range, skipping:', [
                        'slot_date' => $slotDate->format('Y-m-d'),
                        'start_date' => $startDateObj->format('Y-m-d'),
                        'end_date' => $endDateObj->format('Y-m-d')
                    ]);
                }
            }

            // Log total slots to insert
            \Log::info('Total slots to insert:', ['count' => count($slotsToInsert)]);

            // Insert all slots
            if (!empty($slotsToInsert)) {
                foreach (array_chunk($slotsToInsert, 100) as $chunk) {
                    CounsellingTimeSlot::insert($chunk);
                }
                \Log::info('Successfully inserted counselling time slots', [
                    'config_id' => $configId,
                    'slot_count' => count($slotsToInsert)
                ]);
            } else {
                \Log::info('No slots to insert for config', ['config_id' => $configId]);
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error saving counselling time slots:', [
                'config_id' => $configId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Calculate end time based on start time and duration
     */
    private function calculateEndTime($startTime, $durationMinutes)
    {
        try {
            $time = Carbon::createFromFormat('H:i', $startTime);
            $time->addMinutes($durationMinutes);
            return $time->format('H:i');
        } catch (\Exception $e) {
            // If format fails, try alternative approach
            $parts = explode(':', $startTime);
            if (count($parts) === 2) {
                $hours = (int)$parts[0];
                $minutes = (int)$parts[1];
                
                $totalMinutes = ($hours * 60) + $minutes + $durationMinutes;
                
                $endHours = floor($totalMinutes / 60);
                $endMinutes = $totalMinutes % 60;
                
                return sprintf('%02d:%02d', $endHours, $endMinutes);
            }
            
            return '00:00';
        }
    }

    /**
     * Toggle step enabled/disabled.
     */
    public function toggleStep(Request $request, $step)
    {
        $validator = Validator::make($request->all(), [
            'enabled' => 'required|boolean',
            'academic_year' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }
            
            $instituteId = $user->institute_id;
            $academicYear = $request->academic_year;

            $config = AdmissionProcessConfig::where('institute_id', $instituteId)
                ->where('academic_year', $academicYear)
                ->first();

            if (!$config) {
                return response()->json([
                    'success' => false,
                    'message' => 'Configuration not found'
                ], 404);
            }

            $field = match($step) {
                'form' => 'admission_form_enabled',
                'test' => 'entrance_tests_enabled',
                'counselling' => 'counselling_enabled',
                'onboarding' => 'onboarding_enabled',
                default => null
            };

            if (!$field) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid step'
                ], 400);
            }

            $config->$field = $request->enabled;
            $config->save();

            return response()->json([
                'success' => true,
                'message' => ucfirst($step) . ' step ' . ($request->enabled ? 'enabled' : 'disabled') . ' successfully',
                'config' => $config
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle step: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get admission process configuration.
     */
    public function getConfig(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }
            
            $instituteId = $user->institute_id;
            $academicYear = $request->query('academic_year', '2024-2025');

            $config = AdmissionProcessConfig::with(['counsellingTimeSlots', 'entranceTestSlots'])
                ->where('institute_id', $instituteId)
                ->where('academic_year', $academicYear)
                ->first();

            if (!$config) {
                // Return default config structure
                $defaultConfig = $this->createDefaultConfig($instituteId, $academicYear);
                
                // Format counselling slots for frontend
                $formattedCounsellingSlots = $this->formatCounsellingSlotsForFrontend($defaultConfig);
                
                return response()->json([
                    'success' => true,
                    'config' => [
                        'id' => $defaultConfig->id,
                        'institute_id' => $defaultConfig->institute_id,
                        'academic_year' => $defaultConfig->academic_year,
                        // Admission Form
                        'admission_form_enabled' => $defaultConfig->admission_form_enabled,
                        'admission_form_mode' => $defaultConfig->admission_form_mode,
                        'admission_form_fee_amount' => $defaultConfig->admission_form_fee_amount,
                        'admission_form_max' => $defaultConfig->admission_form_max,
                        'admission_form_start_date' => $defaultConfig->admission_form_start_date?->format('Y-m-d'),
                        'admission_form_end_date' => $defaultConfig->admission_form_end_date?->format('Y-m-d'),
                        // Entrance Tests
                        'entrance_tests_enabled' => $defaultConfig->entrance_tests_enabled,
                        'entrance_tests_mode' => $defaultConfig->entrance_tests_mode,
                        'entrance_tests' => $defaultConfig->entrance_tests,
                        // Counselling
                        'counselling_enabled' => $defaultConfig->counselling_enabled,
                        'counselling_mode' => $defaultConfig->counselling_mode,
                        'counselling_session_duration' => $defaultConfig->counselling_session_duration,
                        'counselling_max_candidates' => $defaultConfig->counselling_max_candidates,
                        'counselling_start_date' => $defaultConfig->counselling_start_date?->format('Y-m-d'),
                        'counselling_end_date' => $defaultConfig->counselling_end_date?->format('Y-m-d'),
                        'counselling_working_start' => $defaultConfig->counselling_working_start?->format('H:i'),
                        'counselling_working_end' => $defaultConfig->counselling_working_end?->format('H:i'),
                        'counselling_break_start' => $defaultConfig->counselling_break_start?->format('H:i'),
                        'counselling_break_end' => $defaultConfig->counselling_break_end?->format('H:i'),
                        'counselling_days' => $defaultConfig->counselling_days,
                        'counselling_time_slots_frontend' => $formattedCounsellingSlots,
                        // Onboarding
                        'onboarding_enabled' => $defaultConfig->onboarding_enabled,
                        'onboarding_admission_fee' => $defaultConfig->onboarding_admission_fee,
                        'onboarding_security_deposit' => $defaultConfig->onboarding_security_deposit,
                        'onboarding_other_charges' => $defaultConfig->onboarding_other_charges,
                        'onboarding_start_date' => $defaultConfig->onboarding_start_date?->format('Y-m-d'),
                        'onboarding_classes_start_date' => $defaultConfig->onboarding_classes_start_date?->format('Y-m-d'),
                        'is_active' => $defaultConfig->is_active
                    ]
                ]);
            }

            // Format counselling slots for frontend
            $formattedCounsellingSlots = $this->formatCounsellingSlotsForFrontend($config);

            return response()->json([
                'success' => true,
                'config' => [
                    'id' => $config->id,
                    'institute_id' => $config->institute_id,
                    'academic_year' => $config->academic_year,
                    // Admission Form
                    'admission_form_enabled' => $config->admission_form_enabled,
                    'admission_form_mode' => $config->admission_form_mode,
                    'admission_form_fee_amount' => $config->admission_form_fee_amount,
                    'admission_form_max' => $config->admission_form_max,
                    'admission_form_start_date' => $config->admission_form_start_date?->format('Y-m-d'),
                    'admission_form_end_date' => $config->admission_form_end_date?->format('Y-m-d'),
                    // Entrance Tests
                    'entrance_tests_enabled' => $config->entrance_tests_enabled,
                    'entrance_tests_mode' => $config->entrance_tests_mode,
                    'entrance_tests' => $config->entrance_tests,
                    // Counselling
                    'counselling_enabled' => $config->counselling_enabled,
                    'counselling_mode' => $config->counselling_mode,
                    'counselling_session_duration' => $config->counselling_session_duration,
                    'counselling_max_candidates' => $config->counselling_max_candidates,
                    'counselling_start_date' => $config->counselling_start_date?->format('Y-m-d'),
                    'counselling_end_date' => $config->counselling_end_date?->format('Y-m-d'),
                    'counselling_working_start' => $config->counselling_working_start?->format('H:i'),
                    'counselling_working_end' => $config->counselling_working_end?->format('H:i'),
                    'counselling_break_start' => $config->counselling_break_start?->format('H:i'),
                    'counselling_break_end' => $config->counselling_break_end?->format('H:i'),
                    'counselling_days' => $config->counselling_days,
                    'counselling_time_slots_frontend' => $formattedCounsellingSlots,
                    // Onboarding
                    'onboarding_enabled' => $config->onboarding_enabled,
                    'onboarding_admission_fee' => $config->onboarding_admission_fee,
                    'onboarding_security_deposit' => $config->onboarding_security_deposit,
                    'onboarding_other_charges' => $config->onboarding_other_charges,
                    'onboarding_start_date' => $config->onboarding_start_date?->format('Y-m-d'),
                    'onboarding_classes_start_date' => $config->onboarding_classes_start_date?->format('Y-m-d'),
                    'is_active' => $config->is_active
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Error fetching admission process config: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch configuration: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Format counselling slots for frontend consumption.
     */
    private function formatCounsellingSlotsForFrontend($config)
    {
        $formattedSlots = [];
        
        // Get counselling slots from database
        $counsellingSlots = $config->counsellingTimeSlots;
        
        // Group by date
        $slotsByDate = [];
        foreach ($counsellingSlots as $slot) {
            $dateKey = $slot->slot_date;
            if (!isset($slotsByDate[$dateKey])) {
                $slotsByDate[$dateKey] = [];
            }
            
            $slotsByDate[$dateKey][] = [
                'id' => $slot->id,
                'time' => Carbon::parse($slot->start_time)->format('H:i'),
                'capacity' => $slot->capacity,
                'duration' => $slot->duration_minutes,
                'active' => $slot->status === 'active'
            ];
        }
        
        // Format for frontend
        foreach ($slotsByDate as $date => $slots) {
            $dateObj = Carbon::parse($date);
            $dateKey = $dateObj->format('Y-n-j'); // Format: Y-m-d
            
            $nextId = 1;
            $slotArray = [];
            
            foreach ($slots as $slot) {
                $slotArray[] = [
                    'id' => $nextId++,
                    'time' => $slot['time'],
                    'capacity' => $slot['capacity'],
                    'duration' => $slot['duration'],
                    'active' => $slot['active']
                ];
            }
            
            // Sort slots by time
            usort($slotArray, function($a, $b) {
                return strtotime($a['time']) - strtotime($b['time']);
            });
            
            $formattedSlots[$dateKey] = [
                'slots' => $slotArray,
                'nextId' => $nextId
            ];
        }
        
        return $formattedSlots;
    }

    /**
     * Reset to default configuration.
     */
    public function reset(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }
            
            $instituteId = $user->institute_id;
            $academicYear = $request->query('academic_year', '2024-2025');

            // Delete existing config and related data
            $config = AdmissionProcessConfig::where('institute_id', $instituteId)
                ->where('academic_year', $academicYear)
                ->first();

            if ($config) {
                // Delete related data
                CounsellingTimeSlot::where('admission_process_config_id', $config->id)->delete();
                EntranceTestSlot::where('admission_process_config_id', $config->id)->delete();
                $config->delete();
            }

            // Create new default config
            $newConfig = $this->createDefaultConfig($instituteId, $academicYear);

            // Format counselling slots for frontend
            $formattedCounsellingSlots = $this->formatCounsellingSlotsForFrontend($newConfig);

            return response()->json([
                'success' => true,
                'message' => 'Configuration reset to default successfully',
                'config' => [
                    'id' => $newConfig->id,
                    'institute_id' => $newConfig->institute_id,
                    'academic_year' => $newConfig->academic_year,
                    // Admission Form
                    'admission_form_enabled' => $newConfig->admission_form_enabled,
                    'admission_form_mode' => $newConfig->admission_form_mode,
                    'admission_form_fee_amount' => $newConfig->admission_form_fee_amount,
                    'admission_form_max' => $newConfig->admission_form_max,
                    'admission_form_start_date' => $newConfig->admission_form_start_date?->format('Y-m-d'),
                    'admission_form_end_date' => $newConfig->admission_form_end_date?->format('Y-m-d'),
                    // Entrance Tests
                    'entrance_tests_enabled' => $newConfig->entrance_tests_enabled,
                    'entrance_tests_mode' => $newConfig->entrance_tests_mode,
                    'entrance_tests' => $newConfig->entrance_tests,
                    // Counselling
                    'counselling_enabled' => $newConfig->counselling_enabled,
                    'counselling_mode' => $newConfig->counselling_mode,
                    'counselling_session_duration' => $newConfig->counselling_session_duration,
                    'counselling_max_candidates' => $newConfig->counselling_max_candidates,
                    'counselling_start_date' => $newConfig->counselling_start_date?->format('Y-m-d'),
                    'counselling_end_date' => $newConfig->counselling_end_date?->format('Y-m-d'),
                    'counselling_working_start' => $newConfig->counselling_working_start?->format('H:i'),
                    'counselling_working_end' => $newConfig->counselling_working_end?->format('H:i'),
                    'counselling_break_start' => $newConfig->counselling_break_start?->format('H:i'),
                    'counselling_break_end' => $newConfig->counselling_break_end?->format('H:i'),
                    'counselling_days' => $newConfig->counselling_days,
                    'counselling_time_slots_frontend' => $formattedCounsellingSlots,
                    // Onboarding
                    'onboarding_enabled' => $newConfig->onboarding_enabled,
                    'onboarding_admission_fee' => $newConfig->onboarding_admission_fee,
                    'onboarding_security_deposit' => $newConfig->onboarding_security_deposit,
                    'onboarding_other_charges' => $newConfig->onboarding_other_charges,
                    'onboarding_start_date' => $newConfig->onboarding_start_date?->format('Y-m-d'),
                    'onboarding_classes_start_date' => $newConfig->onboarding_classes_start_date?->format('Y-m-d'),
                    'is_active' => $newConfig->is_active
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Error resetting admission process config: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to reset configuration: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get available academic years.
     */
    public function getSessionYears(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }
            
            $instituteId = $user->institute_id;
            
            // Get academic years from configs
            $academicYears = AdmissionProcessConfig::where('institute_id', $instituteId)
                ->distinct()
                ->pluck('academic_year')
                ->toArray();
            
            // If no years found, add current year
            if (empty($academicYears)) {
                $currentYear = date('Y');
                $nextYear = $currentYear + 1;
                $academicYears = ["{$currentYear}-{$nextYear}"];
            }
            
            // Sort years in descending order
            usort($academicYears, function($a, $b) {
                return strcmp($b, $a);
            });
            
            return response()->json([
                'success' => true,
                'academic_years' => $academicYears
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch academic years: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create default configuration.
     */
    private function createDefaultConfig($instituteId, $academicYear)
    {
        DB::beginTransaction();

        try {
            // Default dates
            $today = Carbon::today();
            $nextWeek = $today->copy()->addWeek();
            $twoWeeks = $today->copy()->addWeeks(2);
            $monthLater = $today->copy()->addMonth();

            // Default entrance tests
            $defaultTests = [
                [
                    'id' => 1,
                    'name' => 'Test 1',
                    'mode' => 'online',
                    'duration' => 120,
                    'passingPercentage' => 50,
                    'fee' => 500,
                    'maxAttempts' => 2,
                    'date' => $nextWeek->format('Y-m-d'),
                    'expectedStudents' => 100,
                    'slots' => [],
                    'slotConfiguration' => [
                        'numberOfSlots' => 2,
                        'slotTimes' => ['09:00', '14:00'],
                        'slotCapacities' => ['50', '50']
                    ]
                ]
            ];

            // Default counselling days
            $defaultCounsellingDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

            // Create the config
            $config = AdmissionProcessConfig::create([
                'institute_id' => $instituteId,
                'academic_year' => $academicYear,
                
                // Admission Form - Defaults
                'admission_form_enabled' => true,
                'admission_form_mode' => 'online',
                'admission_form_fee_amount' => 1000,
                'admission_form_max' => 500,
                'admission_form_start_date' => $today,
                'admission_form_end_date' => $nextWeek,
                
                // Entrance Tests - Defaults
                'entrance_tests_enabled' => true,
                'entrance_tests_mode' => 'online',
                'entrance_tests' => json_encode($defaultTests),
                
                // Counselling - Defaults
                'counselling_enabled' => true,
                'counselling_mode' => 'online',
                'counselling_session_duration' => 45,
                'counselling_max_candidates' => 1,
                'counselling_start_date' => $nextWeek,
                'counselling_end_date' => $twoWeeks,
                'counselling_working_start' => '09:00',
                'counselling_working_end' => '17:00',
                'counselling_break_start' => '13:00',
                'counselling_break_end' => '14:00',
                'counselling_days' => json_encode($defaultCounsellingDays),
                
                // Onboarding - Defaults
                'onboarding_enabled' => true,
                'onboarding_admission_fee' => 10000,
                'onboarding_security_deposit' => 5000,
                'onboarding_other_charges' => 2000,
                'onboarding_start_date' => $twoWeeks,
                'onboarding_classes_start_date' => $monthLater,
                'is_active' => true
            ]);

            // Create default entrance test slots
            $this->saveEntranceTestSlots($config->id, json_encode($defaultTests));

            DB::commit();
            return $config;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Get academic years for the institute.
     */
    private function getAcademicYears($instituteId)
    {
        $years = AdmissionProcessConfig::where('institute_id', $instituteId)
            ->distinct()
            ->pluck('academic_year')
            ->toArray();

        // If no years exist, create current year
        if (empty($years)) {
            $currentYear = date('Y');
            $nextYear = $currentYear + 1;
            $currentAcademicYear = "{$currentYear}-{$nextYear}";
            
            // Create default config for current year
            $this->createDefaultConfig($instituteId, $currentAcademicYear);
            
            $years = [$currentAcademicYear];
        }

        // Sort in descending order
        usort($years, function($a, $b) {
            return strcmp($b, $a);
        });

        return $years;
    }
}