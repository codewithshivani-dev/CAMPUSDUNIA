<?php
namespace App\Http\Controllers\institute\Admin;
use App\Http\Controllers\Controller;
use App\Models\FincapMerchant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\StudentParentAddress;
use App\Models\StudentParentBankAccount;
use App\Models\StudentParentDetails;
use App\Models\StudentParentDocuments;
use App\Models\StudentAcademicTransportDetails;
use App\Models\DepartmentCategory;
use App\Models\TransportDetails;
use App\Models\StudentSibling;
use App\Models\InstituteBasicDetails;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Traits\InstituteBranchAccess;
use App\Traits\DepartmentRelationships;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\StudentsExport;
use Maatwebsite\Excel\Facades\Excel;

class AssignSiblingController extends Controller
{
    use InstituteBranchAccess, DepartmentRelationships;
    public function assignSiblingsPage()
    {
        
        return view('instituteAdmin.StudentFiles.AssignSibling');
    }

    public function storeAssignedSiblings(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'student_hash_id' => 'required|exists:student_parent_details,student_hash_id',
            'main_registration_number' => 'required|exists:student_parent_details,registration_number',
            'main_class_id' => 'required',
            'main_section_id' => 'required',
            'siblings' => 'sometimes|array',
            'siblings.*.student_hash_id' => 'required_with:siblings.*.registration_number',
            'siblings.*.registration_number' => 'required_with:siblings.*.student_hash_id',
            'siblings.*.class_id' => 'required_with:siblings.*.student_hash_id',
            'siblings.*.section_id' => 'required_with:siblings.*.student_hash_id'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Validation failed. Please check all fields.');
        }

        $context = $this->getInstituteBranchContext();

        DB::beginTransaction();
            // Get main student with academic details
            $mainStudent = StudentParentDetails::with('academicTransportDetails')
                ->where('student_hash_id', $request->student_hash_id)
                ->where('registration_number', $request->main_registration_number)
                ->firstOrFail();
            
            $createdCount = 0;
            $skippedCount = 0;
            
            // Generate ONE sibling pair ID for ALL students in this family
            $familyPairId = $this->generateUniqueSiblingId();
            
            // Array to collect all students in this family (including main)
            $allFamilyStudents = [
                [
                    'student' => $mainStudent,
                    'class_id' => $request->main_class_id ?? ($mainStudent->academicTransportDetails->course_subtype_id ?? null),
                    'section_id' => $request->main_section_id ?? ($mainStudent->academicTransportDetails->section_id ?? null),
                    'registration_number' => $mainStudent->registration_number,
                    'student_hash_id' => $mainStudent->student_hash_id,
                    'type' => 'main' // Set type as main for the first student
                ]
            ];

            if ($request->has('siblings') && is_array($request->siblings)) {

                foreach ($request->siblings as $index => $sibling) {

                    // Skip if no valid data
                    if (empty($sibling['student_hash_id']) || empty($sibling['registration_number'])) {
                        $skippedCount++;
                        continue;
                    }

                    // Skip if sibling registration is same as main student
                    if ($sibling['registration_number'] == $request->main_registration_number) {
                        $skippedCount++;
                        continue;
                    }

                    // Get sibling student with academic details
                    $siblingStudent = StudentParentDetails::with('academicTransportDetails')
                        ->where('student_hash_id', $sibling['student_hash_id'])
                        ->where('registration_number', $sibling['registration_number'])
                        ->first();

                    if (!$siblingStudent) {
                        $skippedCount++;
                        continue;
                    }

                    // Check if this student already has a sibling pair ID (already in a family)
                    $existingSiblingRecord = StudentSibling::where('student_hash_id', $siblingStudent->student_hash_id)->first();
                    
                    if ($existingSiblingRecord) {
                        $skippedCount++;
                        continue;
                    }

                    // Add to family students array with type 'sibling'
                    $allFamilyStudents[] = [
                        'student' => $siblingStudent,
                        'class_id' => $sibling['class_id'] ?? ($siblingStudent->academicTransportDetails->course_subtype_id ?? null),
                        'section_id' => $sibling['section_id'] ?? ($siblingStudent->academicTransportDetails->section_id ?? null),
                        'registration_number' => $siblingStudent->registration_number,
                        'student_hash_id' => $siblingStudent->student_hash_id,
                        'type' => 'sibling' // Set type as sibling for all other students
                    ];
                }
            }

            // Create ONE record for EACH student in the family with the same sibling_pair_id
            foreach ($allFamilyStudents as $familyStudent) {
                
                // Check if this student already has a sibling record
                $exists = StudentSibling::where('student_hash_id', $familyStudent['student_hash_id'])->exists();
                
                if (!$exists) {
                    // Create a record for this student with the family pair ID and type
                    StudentSibling::create([
                        'student_hash_id' => $familyStudent['student_hash_id'],
                        'student_registration_number' => $familyStudent['registration_number'],
                        'sibling_pair_id' => $familyPairId, 
                        'institute_id' => $context['institute_id'],
                        'branch_id' => $context['is_branch_admin'] ? $context['branch_id'] : null,
                        'course_subtype_id' => $familyStudent['class_id'],
                        'section_id' => $familyStudent['section_id'],
                        'type' => $familyStudent['type'], 
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);

                    $createdCount++;
                    
                } else {
                    $skippedCount++;
                }
            }

            DB::commit();

            $message = "Data Saved successfully. ";
            $message .= "Created: $createdCount records, Skipped: $skippedCount.";
            
            return redirect()->back()->with('success', $message);
    }

    /**
     * Generate a unique random sibling pair ID
     * 
     * @return string
     */
    private function generateUniqueSiblingId()
    {
        do {
            // Generate a random ID with prefix 'SIB' and 10 random characters
            $siblingPairId = 'SIB' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 10));
            
            // Check if this ID already exists in the database
            $exists = StudentSibling::where('sibling_pair_id', $siblingPairId)->exists();
            
        } while ($exists);
        
        return $siblingPairId;
    }

    public function getStudentByRegOrEmail(Request $request)
    {
        $context = $this->getInstituteBranchContext();
        
        $instituteId = $context['institute_id'];
        $branchId = $context['is_branch_admin']
            ? $context['branch_id']
            : null;
            
        if (!$instituteId) {
            return response()->json([
                'success' => false,
                'message' => 'Institute ID not found in session'
            ], 400);
        }
        
        $searchTerm = $request->input('search_term');
        $searchType = $request->input('search_type', 'registration'); // registration or email
        
        $query = StudentParentDetails::where('institute_id', $instituteId)
            ->with('academicTransportDetails');
        
        // 🔥 AUTO DETECT EMAIL OR REGISTRATION
        if (filter_var($searchTerm, FILTER_VALIDATE_EMAIL)) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('email', $searchTerm);
            });
        } else {
            $query->where('registration_number', $searchTerm);
        }
        
        $student = $query->first();
        
        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found with provided ' . $searchType . ': ' . $searchTerm
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'student_hash_id' => $student->student_hash_id,
                'registration_number' => $student->registration_number,
                'email' => $student->email,
                'father_email' => $student->father_email,
                'mother_email' => $student->mother_email,
                'first_name' => $student->first_name,
                'middle_name' => $student->middle_name,
                'last_name' => $student->last_name,
                'full_name' => trim($student->first_name . ' ' . $student->middle_name . ' ' . $student->last_name),
                'dob' => $student->dob,
                'mobile' => $student->mobile ?? $student->father_phone ?? $student->mother_phone,
                'class' => $student->academicTransportDetails->course_subtype,
                'classID' => $student->academicTransportDetails->course_subtype_id,
                'sectionID' => $student->academicTransportDetails->section_id,
            ]
        ]);
    }
}