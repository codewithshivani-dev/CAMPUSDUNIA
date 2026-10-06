<?php

namespace App\Http\Controllers\institute\Admin\AdmissionConfiguration;
use App\Http\Controllers\Controller;
use App\Models\AdmissionProcessConfig;
use App\Models\ProductDetails;
use App\Models\CommonCustomFees;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PreviewController extends Controller
{
    public function index()
    {
        $instituteId = Auth::user()->institute_id;

        $configurations = AdmissionProcessConfig::with('department')
            ->whereNotNull('admissionprocess_confun_id')
            ->where('institute_id', $instituteId)
            ->get();
   
        return view('instituteAdmin.AdmissionConfiguration.PreviewTab', compact('configurations'));
    }
    
    public function show($id)
    {
        $instituteId = Auth::user()->institute_id;
        $config = $configurations = AdmissionProcessConfig::with([
            'department',
            'counsellingTimeSlots',
            'entranceTestSlots'
        ])->where('id',$id)->where('institute_id', $instituteId)->first();
        $customFees = CommonCustomFees::where('institute_id', $instituteId)
            ->where('status', 'active')
            ->get();
        return view('instituteAdmin.AdmissionConfiguration.PreviewDetail', compact('config','customFees', 'id'));
    }
    
    public function create()
    {
        return view('configurations.create-edit');
    }
    
    public function store(Request $request)
    {
        // Validation and store logic
        $validated = $this->validateRequest($request);
        
        // Generate ID
        $id = 'CONF-' . date('Y') . '-' . strtoupper(substr($validated['department'], 0, 2)) . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
        
        // Save logic here
        
        return redirect()->route('config.list')->with('success', 'Configuration created successfully!');
    }
    
    public function edit($id)
    {
        $config = $this->getConfigById($id);
        return view('configurations.create-edit', compact('config'));
    }
    
    public function update(Request $request, $id)
    {
        // Validation and update logic
        $validated = $this->validateRequest($request);
        
        // Update logic here
        
        return redirect()->route('config.list')->with('success', 'Configuration updated successfully!');
    }
    
    public function destroy($id)
    {
        // Delete logic here
        
        return response()->json(['success' => true]);
    }
    
    private function validateRequest(Request $request)
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'department' => 'required|string',
            'session' => 'required|string',
            'status' => 'required|in:draft,active,inactive',
            'form_enabled' => 'boolean',
            'tests_enabled' => 'boolean',
            'counselling_enabled' => 'boolean',
            'onboarding_enabled' => 'boolean',
            
            // Form validation if enabled
            'form.mode' => 'required_if:form_enabled,1',
            'form.fee' => 'nullable|numeric|min:0',
            'form.max_forms' => 'nullable|integer|min:1',
            'form.start_date' => 'required_if:form_enabled,1|date',
            'form.end_date' => 'required_if:form_enabled,1|date|after:form.start_date',
            
            // Tests validation if enabled
            'tests' => 'nullable|array',
            'tests.*.name' => 'required_if:tests_enabled,1|string',
            'tests.*.date' => 'required_if:tests_enabled,1|date',
            'tests.*.duration' => 'required_if:tests_enabled,1|integer|min:1',
            'tests.*.passing' => 'nullable|numeric|min:0|max:100',
            
            // Counselling validation if enabled
            'counselling.mode' => 'required_if:counselling_enabled,1',
            'counselling.session_duration' => 'nullable|integer|min:1',
            'counselling.max_candidates' => 'nullable|integer|min:1',
            'counselling.slots' => 'nullable|array',
            'counselling.slots.*.time' => 'required_if:counselling_enabled,1',
            'counselling.slots.*.capacity' => 'required_if:counselling_enabled,1|integer|min:1',
            
            // Onboarding validation if enabled
            'onboarding.session_start' => 'required_if:onboarding_enabled,1|date',
            'onboarding.classes_start' => 'required_if:onboarding_enabled,1|date',
            'onboarding.fees' => 'nullable|array',
            'onboarding.fees.*.name' => 'required_if:onboarding_enabled,1|string',
            'onboarding.fees.*.amount' => 'required_if:onboarding_enabled,1|numeric|min:0',
        ]);
    }
    
    private function getConfigById($id)
    {
        // Fetch from database in real app
        return [
            'id' => $id,
            'name' => 'Computer Science Admission 2024',
            'department' => 'Computer Science',
            'session' => '2024-2025',
            'status' => 'active',
            'created' => '15 Jan 2024',
            'form_enabled' => true,
            'tests_enabled' => true,
            'counselling_enabled' => true,
            'onboarding_enabled' => true
        ];
    }
    
}