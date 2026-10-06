<?php
namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use App\Models\Departments;
use App\Models\StudentParentDetails;
use App\Models\StudentParentAddress;
use App\Models\StudentAcademicTransportDetails;
use App\Models\StudentParentDocuments;
use App\Models\StudentParentBankAccount;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class BulkStudentsUploadController extends Controller
{  
    use \App\Traits\InstituteBranchAccess;

    public function getUploadPage()
    {
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return back()->with('error', 'You are not associated with any institute.');
        }

        $subTypes = DB::table('product_details')
            ->select('product_id', 'sub_type', 'department_id', 'course_type')
            ->where('institute_id', $context['institute_id'])
            ->when($context['is_branch_admin'] && $context['branch_id'], function($q) use ($context) {
                $q->where('branch_id', $context['branch_id']);
            }, function($q) {
                $q->whereNull('branch_id');
            })
            ->orderBy('sub_type')
            ->get();

        return view('instituteAdmin.BulkUpload.UploadBulkStudent', compact('subTypes'));
    }

    public function uploadBulkStudents(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'csv_file' => 'required|mimes:csv,txt',
            'course_detail_id' => 'required|exists:product_details,product_id'
        ]);
       
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
           
        // Get institute/branch context
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return back()->with('error', 'You are not associated with any institute.');
        }

        // Get course details from product_details
        $product = DB::table('product_details')
            ->where('product_id', $request->course_detail_id)
            ->where('institute_id', $context['institute_id'])
            ->when($context['is_branch_admin'] && $context['branch_id'], function($q) use ($context) {
                $q->where('branch_id', $context['branch_id']);
            }, function($q) {
                $q->whereNull('branch_id');
            })
            ->first();
     
        if (!$product) {
            return back()->with('error', 'Invalid course selected or access denied.');
        }

        // Get the selected class name from product
        $selectedClassName = $product->sub_type;
        
        // Get department from product
        $department = DB::table('departments')
            ->where('department_id', $product->department_id)
            ->first();
           
        if (!$department) {
            return back()->with('error', 'Department not found for this course.');
        }

        // Get course fee structure
        $courseFeeStructure = DB::table('course_fee_structures')
            ->where('product_id', $request->course_detail_id)
            ->where('institute_id', $context['institute_id'])
            ->when($context['is_branch_admin'] && $context['branch_id'], function($q) use ($context) {
                $q->where('branch_id', $context['branch_id']);
            }, function($q) {
                $q->whereNull('branch_id');
            })
            ->orderBy('batch_id', 'desc')
            ->first();
        
        if (!$courseFeeStructure) {
            return back()->with('error', 'No fee structure found for this course.');
        }

        // Parse CSV file
        $file = $request->file('csv_file');
        $csvData = array_map('str_getcsv', file($file->getRealPath()));
       
        // Get headers (first row)
        $headers = array_map(function($header) {
            // Clean headers and convert to lowercase
            return strtolower(trim($header));
        }, array_shift($csvData));
        
        // Normalize headers - handle different variations
        $normalizedHeaders = [];
        foreach ($headers as $header) {
            $normalizedHeaders[] = $this->normalizeHeader($header);
        }
        
        // Define expected headers for your CSV format
        $expectedHeaders = [
            'registration no.',
            'class',  
            'student name',
            'mother name',
            'section',
            'dob',
            'address',
            'father name',
            'father contact',
            'mother contact'
        ];
          
        // Check if CSV has required headers
        foreach ($expectedHeaders as $expected) {
            if (!in_array($expected, $normalizedHeaders)) {
                return back()->with('error', "CSV must contain '$expected' column. Found: " . implode(', ', $normalizedHeaders));
            }
        }
        
        $institute = DB::table('institutes')
            ->where('fincap_merchant_id', $context['institute_id'])
            ->first();

        $instituteName = $institute->name ?? 'school';
        DB::beginTransaction();

        // try {
            $successCount = 0;
            $errorMessages = [];
            $createdStudents = [];
            $skippedCount = 0;

            foreach ($csvData as $index => $row) {
                // try {
                    $rowNumber = $index + 2;
                    
                    // Skip empty rows
                    if (empty(array_filter($row))) {
                        continue;
                    }
                    
                    // Pad row to match header count
                    $row = array_pad($row, count($normalizedHeaders), '');
                    
                    // Combine normalized headers with row data
                    $rowData = array_combine($normalizedHeaders, $row);
                    // Extract student name and registration early for error reporting
                    
                    $studentName = $this->cleanStringForDb($rowData['student name'] ?? '');
                    $csvRegistrationNumber = $this->cleanStringForDb($rowData['registration no.'] ?? ''); // Store original CSV registration number 

                    // === NEW: Check if CSV class matches selected class ===
                    $csvClass = $this->cleanStringForDb($rowData['class'] ?? '');
                    
                    // Skip if class doesn't match (case-insensitive comparison)
                    if (strcasecmp(trim($csvClass), trim($selectedClassName)) !== 0) {
                        $skippedCount++;
                        continue;
                    }
                    
                    // Process the data
                    $processedData = $this->processRowData($rowData, $rowNumber, $studentName, $csvRegistrationNumber);
                    
                    if (!$processedData['valid']) {
                        $errorMessages[] = $processedData['error'];
                        continue;
                    }
                    
                    // Generate unique IDs
                    $studentHashId = $this->generateStudentHashId();
                    
                    // Use CSV registration number if available, otherwise generate one
                    $registrationNumber = !empty($csvRegistrationNumber) ? $csvRegistrationNumber : 
                                          'REG' . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);

                    // 🚫 Skip if registration number already exists
                    $exists = StudentParentDetails::where('registration_number', $registrationNumber)
                        ->where('institute_id', $context['institute_id'])
                        ->when($context['is_branch_admin'] && $context['branch_id'], function($q) use ($context) {
                            $q->where('branch_id', $context['branch_id']);
                        })
                        ->exists();

                    if ($exists) {
                        $errorMessages[] = [
                            'row' => $rowNumber,
                            'student_name' => $studentName,
                            'registration_number' => $registrationNumber,
                            'message' => "Registration number already exists — skipped",
                            'details' => ''
                        ];
                        $skippedCount++;
                        continue;
                    }                      
                    
                    // Determine which contact to use for login (father first, then mother)
                    $loginContact = $this->determineLoginContact(
                        $processedData['father_contact'],
                        $processedData['mother_contact']
                    );
                    // Check if phone already exists
                    $existingUserByPhone = User::where('phone', $loginContact)->first();

                    $phoneToSave = $loginContact; // default

                    if ($existingUserByPhone) {
                        // ✅ Allow student upload BUT do NOT save phone in users table
                        $phoneToSave = null;

                        // Log this as info (not error)
                        $errorMessages[] = [
                            'row' => $rowNumber,
                            'student_name' => $studentName,
                            'registration_number' => $csvRegistrationNumber,
                            'message' => "Phone already exists — user created with email only",
                            'details' => "Phone not stored in users table"
                        ];
                    }

                    // if (!$loginContact) {
                    //     $errorMessages[] = [
                    //         'row' => $rowNumber,
                    //         'student_name' => $studentName,
                    //         'registration_number' => $csvRegistrationNumber,
                    //         'message' => "At least one valid contact number is required",
                    //         'details' => "Father: '" . ($processedData['father_contact'] ?? 'empty') . "', Mother: '" . ($processedData['mother_contact'] ?? 'empty') . "'"
                    //     ];
                    //     continue;
                    // }
                   
                    // $existingUser = User::where('phone', $loginContact)
                    //         ->first();
                    //     if ($existingUser) {
                    //         $errorMessages[] = [
                    //             'row' => $rowNumber,
                    //             'student_name' => $studentName,
                    //             'registration_number' => $csvRegistrationNumber,
                    //             'message' => "Contact {$loginContact} already exists in system",
                    //             'details' => ''
                    //         ];
                    //         continue;
                    //     }
                         // Generate unique email
                        
                        $studentEmail = $this->generateStudentEmail(
                            $processedData['full_name'], 
                            $registrationNumber
                        );
                            // Check if email already exists
                        $existingUserByEmail = User::where('email', $studentEmail)->first();
                        if ($existingUserByEmail) {
                            // If email exists, add more random digitss
                            $studentEmail = $this->generateStudentEmail(
                                $processedData['full_name'], 
                                $registrationNumber . mt_rand(10, 99)
                            );
                        }
                        // Create user account using phone number as login
                        // $user = User::create([
                        //     'name' => $processedData['full_name'],
                        //     'email' => $studentEmail,
                        //     'phone' => $loginContact, 
                        //     'password' => Hash::make('12345678'),
                        //     'email_verified_at' => now(),
                        //     'institute_id' => $context['institute_id'],
                        //     'branch_id' => $context['is_branch_admin'] ? $context['branch_id'] : null,
                        // ]);

                        $user = User::create([
                            'name' => $processedData['full_name'],
                            'email' => $studentEmail,
                            'phone' => $phoneToSave,   // ✅ IMPORTANT (null if phone exists)
                            'password' => Hash::make('12345678'),
                            'email_verified_at' => now(),
                            'institute_id' => $context['institute_id'],
                            'branch_id' => $context['is_branch_admin'] ? $context['branch_id'] : null,
                        ]);

                        $user->assignRole('student');

                        // Parse semesters and sections
                        $semesters = $product->semesters ? json_decode($product->semesters, true) : [];
                        $sections = $courseFeeStructure->sections ? json_decode($courseFeeStructure->sections, true) : [];
                         
                        // Get section from CSV or find matching section
                        $csvSection = $rowData['section'] ?? '';
                        $sectionId = $this->findSectionId($csvSection, $sections);

                        // Get semester - default to first
                        $semesterId = !empty($semesters) ? $semesters[0] : null;

                        // Parse father and mother names
                        $fatherNameParts = $this->parseName($processedData['father_name']);
                        $motherNameParts = $this->parseName($processedData['mother_name']);

                        // ========== 1. Save StudentParentDetails ==========
                        $studentData = [
                            'student_hash_id' => $studentHashId,
                            'user_id' => $user->id,
                            'institute_id' => $context['institute_id'],
                            'branch_id' => $context['is_branch_admin'] ? $context['branch_id'] : null,
                            
                            // Student Details
                            'registration_number' => $registrationNumber,
                            'first_name' => $processedData['first_name'],
                            'middle_name' => $processedData['middle_name'],
                            'last_name' => $processedData['last_name'],
                            'dob' => $this->parseDate($processedData['dob'] ?? null),
                            'gender' => $this->determineGenderFromName($processedData['student_name']),
                            'mobile' => $loginContact,  
                           
                            'nationality' => 'Indian',
                            'religion' => null,
                            'blood_group' => null,
                            'category' => 'General',
                            
                            // Father Details
                            'father_first_name' => $fatherNameParts['first_name'],
                            'father_middle_name' => $fatherNameParts['middle_name'],
                            'father_last_name' => $fatherNameParts['last_name'],
                            'father_dob' => null,
                            'father_email' => null,
                            'father_phone' => $processedData['father_contact'],
                            'father_occupation' => null,
                            'father_income' => null,
                            'father_blood_group' => null,
                            'father_nationality' => 'Indian',
                            'father_religion' => null,
                            'father_category' => 'General',
                            
                            // Mother Details
                            'mother_first_name' => $motherNameParts['first_name'],
                            'mother_middle_name' => $motherNameParts['middle_name'],
                            'mother_last_name' => $motherNameParts['last_name'],
                            'mother_dob' => null,
                            'mother_email' => null,
                            'mother_phone' => $processedData['mother_contact'],
                            'mother_occupation' => null,
                            'mother_income' => null,
                            'mother_blood_group' => null,
                            'mother_nationality' => 'Indian',
                            'mother_religion' => null,
                            'mother_category' => 'General',
                            
                            // Guardian Details (default to father)
                            'guardian_type' => 'father',
                            'guardian_relation' => 'father',
                            'guardian_first_name' => $fatherNameParts['first_name'],
                            'guardian_middle_name' => $fatherNameParts['middle_name'],
                            'guardian_last_name' => $fatherNameParts['last_name'],
                            'guardian_gender' => 'male',
                            'guardian_dob' => null,
                            'guardian_email' => null,
                            'guardian_phone' => $processedData['father_contact'],
                            'alternate_phone_number' => $processedData['mother_contact'],
                            'guardian_occupation' => null,
                            'guardian_income' => null,
                            'guardian_blood_group' => null,
                            'guardian_nationality' => 'Indian',
                            'guardian_religion' => null,
                            'guardian_category' => 'General',
                        ];
                        
                        $studentParentDetails = StudentParentDetails::create($studentData);

                        // ========== 2. Save StudentParentAddress ==========
                        $addressData = [
                            'student_hash_id' => $studentHashId,
                            'institute_id' => $context['institute_id'],
                            'branch_id' => $context['is_branch_admin'] ? $context['branch_id'] : null,
                            
                            // Permanent Address (from CSV)
                            'student_perm_address_line1' => $processedData['address'] ?? null,
                            'student_perm_city' => null,
                            'student_perm_state' => null,
                            'student_perm_pincode' => null,
                         
                            
                            // Communication Address (same as permanent by default)
                            'student_comm_address_line1' => $processedData['address'] ?? null,
                            'student_comm_city' => null,
                            'student_comm_state' => null,
                            'student_comm_pincode' => null,
                       
                            
                            // Father Address
                            'parent_perm_address_line1' => $processedData['address'] ?? null,
                            'parent_perm_address_line2' => null,
                            'parent_perm_city' => null,
                            'parent_perm_state' => null,
                            'parent_perm_pincode' => null,
                             
                            // Guardian Address (same as father)
                            'guardian_perm_address_line1' => $processedData['address'] ?? null,
                            'guardian_perm_city' => null,
                            'guardian_perm_state' => null,
                            'guardian_perm_pincode' => null,
                        ];
                        
                        StudentParentAddress::create($addressData);

                        // ========== 3. Save StudentAcademicTransportDetails ==========
                        $academicData = [
                            'student_hash_id' => $studentHashId,
                            'institute_id' => $context['institute_id'],
                            'branch_id' => $context['is_branch_admin'] ? $context['branch_id'] : null,
                            
                            // Department and Category
                            'department_id' => $product->department_id,
                            'department_category_id' => $product->department_category_id,
                            'department' => $department->department ?? null,
                            
                            // Course details
                            'course_type_id' => $product->finacp_merchant_sub_category_id ?? null,
                            'course_type' => $product->course_type ?? null,
                            'course_subtype_id' => $product->product_id,
                            'course_subtype' => $product->sub_type ?? null,
                            
                            // Session, Semester, Section
                            'session_id' => $courseFeeStructure->session_id ?? null,
                            'semester_id' => $semesterId,
                            'section_id' => $sectionId,
                            
                            // Batch details
                            'batch_id' => $courseFeeStructure->batch_id,
                            'batch' => $courseFeeStructure->batch,
                            
                            // Academic Year
                            'academic_year_id' => $courseFeeStructure->academic_year_id,
                            'academic_year' => $courseFeeStructure->academic_year,
                            
                            // Course mode and type
                            'mode_of_course' => $product->mode_of_course ?? null,
                            'mode_type' => $product->mode_type ?? null,
                            
            
                        ];
                      
                        StudentAcademicTransportDetails::create($academicData);

                        // ========== 4. Save StudentParentDocuments ==========
                        $documentsData = [
                            'student_hash_id' => $studentHashId,
                            'institute_id' => $context['institute_id'],
                            'branch_id' => $context['is_branch_admin'] ? $context['branch_id'] : null,
                            
                            // Student documents (default null)
                            'parent_photo' => null,
                            'student_aadhaar_file' => null,
                            'student_pan_file' => null,
                            
                            // Father documents
                            'parent_photo' => null,
                            'parent_aadhaar_file' => null,
                            'parent_pan_file' => null,
                            
                     
                            // Guardian documents
                            'guardian_photo' => null,
                            'guardian_aadhaar_file' => null,
                            'guardian_pan_file' => null,
                
                        ];
                       
                        StudentParentDocuments::create($documentsData);

                        // ========== 5. Save StudentParentBankAccount ==========
                        $bankData = [
                            'student_hash_id' => $studentHashId,
                            'institute_id' => $context['institute_id'],
                            'branch_id' => $context['is_branch_admin'] ? $context['branch_id'] : null,
                            
                            // Student bank details (default null)
                            'student_benificiary_name' => $processedData['full_name'],
                            'student_bank_account_number' => null,
                            'student_bank_name' => null,
                            'student_ifsc_code' => null,
                            'student_account_type' => null,
                            
                            // Father bank details
                            'benificiary_name' => $processedData['father_name'],
                            'bank_account_number' => null,
                            'bank_name' => null,
                            'ifsc_code' => null,
                            'account_type' => null,
                            'upload_cancelled_cheque' => null,                        

                        ];
                 
                        StudentParentBankAccount::create($bankData);
                        
                        // Add to created students array
                        $createdStudents[] = [
                            'name' => $processedData['full_name'],
                            'login_contact' => $loginContact,
                            'email' => null,
                            'password' => '12345678',
                            'registration_number' => $registrationNumber,
                            'father_contact' => $processedData['father_contact'],
                            'mother_contact' => $processedData['mother_contact']
                        ];
                        
                        $successCount++;

                // } catch (\Exception $e) {
                //     $errorMessages[] = [
                //         'row' => $rowNumber,
                //         'student_name' => $studentName,
                //         'registration_number' => $csvRegistrationNumber,
                //         'message' => "System error: " . $e->getMessage(),
                //         'details' => ''
                //     ];
                //     continue;
                // }
            }

            DB::commit();

            $summaryMessage = "Processed " . ($successCount + $skippedCount) . " rows. ";
            $summaryMessage .= "Successfully imported $successCount students from class '$selectedClassName'. ";
            $summaryMessage .= "Skipped $skippedCount students from other classes.";

            if ($successCount > 0) {
                session(['created_students' => $createdStudents]);
                
                return redirect()->route('bulk.upload.success')
                    ->with('success', $summaryMessage)
                    ->with('errors', $errorMessages);
            } else {
                $errorText = implode(', ', array_map(function ($e) {
                return is_array($e) ? $e['message'] : $e;
            }, $errorMessages));
            
            return back()->with(
                'error',
                'No matching students found. ' . $summaryMessage . ' ' . $errorText
            );
            }

        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     return back()->with('error', 'Bulk upload failed: ' . $e->getMessage());
        // }
    }
    
    /**
     * Clean string for database insertion (handles encoding issues)
     */
    private function cleanStringForDb($string)
    {
        if (empty($string) || $string === null) {
            return null;
        }
        
        // Convert to string if it's not already
        $string = (string) $string;
        
        // Convert to UTF-8 if needed
        $encoding = mb_detect_encoding($string, ['UTF-8', 'ISO-8859-1', 'Windows-1252', 'ASCII'], true);
        if ($encoding !== 'UTF-8') {
            $string = mb_convert_encoding($string, 'UTF-8', $encoding);
        }
        
        // Remove problematic characters
        $string = str_replace(["\xC2\xA0", "\xA0"], ' ', $string); // Non-breaking spaces (both forms)
        $string = preg_replace('/[^\x{0009}\x{000A}\x{000D}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}\x{10000}-\x{10FFFF}]/u', '', $string); // Remove invalid XML chars
        $string = preg_replace('/[\x00-\x1F\x7F-\x9F]/u', '', $string); // Remove control characters
        $string = preg_replace('/\s+/', ' ', $string); // Normalize whitespace
        $string = trim($string);
        
        return $string;
    }

    /**
     * Normalize CSV headers
     */
    private function normalizeHeader($header)
    {
        $header = strtolower(trim($header));
        
        // Remove special characters and extra spaces
        $header = preg_replace('/[^\w\s.]/', '', $header);
        $header = preg_replace('/\s+/', ' ', $header);
        
        // Common variations mapping
        $variations = [
            'reg no' => 'registration no.',
            'registration no' => 'registration no.',
            'regno' => 'registration no.',
            'studentname' => 'student name',
            'mothername' => 'mother name',
            'fathername' => 'father name',
            'fathercontact' => 'father contact',
            'mothercontact' => 'mother contact',
            'date of birth' => 'dob',
            'birth date' => 'dob',
            'class/section' => 'class',
            'std' => 'class',
            'division' => 'section',
        ];
        
        return $variations[$header] ?? $header;
    }

    /**
     * Process row data from CSV
     */
    private function processRowData($rowData, $rowNumber, $studentName, $registrationNumber)
    {
        // Check required fields
        if (empty($rowData['student name'])) {
            return [
                'valid' => false,
                'error' => [
                    'row' => $rowNumber,
                    'student_name' => $studentName,
                    'registration_number' => $registrationNumber,
                    'message' => "Student Name is required",
                    'details' => ''
                ]
            ];
        }
        
       $fatherName = isset($rowData['father name']) ? $this->cleanStringForDb($rowData['father name']) : null;
        $dob = $rowData['dob'] ?? null;

        
        // Clean all string data
        $studentName = $this->cleanStringForDb($rowData['student name']);
        $fatherName = $this->cleanStringForDb($rowData['father name']);
        $motherName = isset($rowData['mother name']) ? $this->cleanStringForDb($rowData['mother name']) : '';
        $address = isset($rowData['address']) ? $this->cleanStringForDb($rowData['address']) : null;
        $dob = $rowData['dob'];
        
        // Parse student name
        $studentNameParts = $this->parseName($studentName);
        
        // Clean contact numbers
        $fatherContact = $this->cleanContactNumber($rowData['father contact'] ?? '');
        $motherContact = $this->cleanContactNumber($rowData['mother contact'] ?? '');
        
        return [
            'valid' => true,
            'full_name' => $studentName,
            'first_name' => $studentNameParts['first_name'],
            'middle_name' => $studentNameParts['middle_name'],
            'last_name' => $studentNameParts['last_name'],
            'father_name' => $fatherName,
            'mother_name' => $motherName,
            'dob' => $dob,
            'address' => $address,
            'father_contact' => $fatherContact,
            'mother_contact' => $motherContact,
            'student_name' => $studentName // Keep original for gender detection
        ];
    }

    /**
     * Clean special characters from string
     */
    private function cleanSpecialCharacters($string)
    {
        if (empty($string)) {
            return null;
        }
        
        // Remove non-breaking spaces and other special characters
        $string = preg_replace('/\xC2\xA0/', ' ', $string); // Remove non-breaking spaces
        $string = preg_replace('/[^\x20-\x7E\x{0C}\x{0A}\x{0D}]/u', ' ', $string); // Keep only printable characters
        $string = preg_replace('/\s+/', ' ', $string); // Replace multiple spaces with single space
        $string = trim($string);
        
        return $string;
    }

    /**
     * Clean contact number
     */
    private function cleanContactNumber($contact)
    {
        if (empty($contact)) {
            return null;
        }
        
        // First clean special characters
        $contact = $this->cleanSpecialCharacters($contact);
        
        // Remove all non-numeric characters
        $contact = preg_replace('/[^0-9]/', '', $contact);
        
        // Check if it's a valid Indian mobile number
        if (strlen($contact) === 10 && preg_match('/^[6-9][0-9]{9}$/', $contact)) {
            return $contact;
        }
        
        // If 11 digits and starts with 0, remove the 0
        if (strlen($contact) === 11 && strpos($contact, '0') === 0) {
            $contact = substr($contact, 1);
            if (preg_match('/^[6-9][0-9]{9}$/', $contact)) {
                return $contact;
            }
        }
        
        // If 12 digits and starts with 91, remove it
        if (strlen($contact) === 12 && strpos($contact, '91') === 0) {
            $contact = substr($contact, 2);
            if (preg_match('/^[6-9][0-9]{9}$/', $contact)) {
                return $contact;
            }
        }
        
        return null;
    }

    /**
     * Parse name into first, middle, last
     */
    private function parseName($fullName)
    {
        // Clean the name first
        $fullName = $this->cleanStringForDb($fullName);
        
        if (empty($fullName)) {
            return [
                'first_name' => null,
                'middle_name' => null,
                'last_name' => null
            ];
        }
        
        $nameParts = array_filter(explode(' ', $fullName), function($part) {
            return !empty(trim($part));
        });
        
        $nameParts = array_values($nameParts);
        $count = count($nameParts);
        
        if ($count === 1) {
            return [
                'first_name' => $nameParts[0],
                'middle_name' => null,
                'last_name' => null
            ];
        } elseif ($count === 2) {
            return [
                'first_name' => $nameParts[0],
                'middle_name' => null,
                'last_name' => $nameParts[1]
            ];
        } else {
            return [
                'first_name' => $nameParts[0],
                'middle_name' => implode(' ', array_slice($nameParts, 1, -1)),
                'last_name' => $nameParts[$count - 1]
            ];
        }
    }

    /**
     * Determine which contact to use for login
     */
    private function determineLoginContact($fatherContact, $motherContact)
    {
        // Prefer father's contact
        if ($fatherContact) {
            return $fatherContact;
        }
        
        // Use mother's contact if father's is not available
        if ($motherContact) {
            return $motherContact;
        }
        
        return null;
    }

    /**
     * Determine gender from name (basic detection)
     */
    private function determineGenderFromName($studentName)
    {
        // Convert to lowercase for matching
        $name = strtolower($studentName);
        
        // Common female name endings/patterns in Indian names
        $femalePatterns = ['kumari', 'devi', 'bai', 'begum', 'shree', 'sri', 'smt.', 'ms.', 'miss'];
        
        foreach ($femalePatterns as $pattern) {
            if (strpos($name, $pattern) !== false) {
                return 'female';
            }
        }
        
        // Default to male (since many Indian names don't indicate gender)
        return 'male';
    }

    /**
     * Find section ID from CSV section name (robust version)
     */
    private function findSectionId($csvSection, $sections)
    {
        if (empty($csvSection) || empty($sections)) {
            return null;
        }

        $csvSection = strtoupper(trim($csvSection));

        foreach ($sections as $section) {

            // Handle object OR array
            $sectionName = strtoupper(trim($section['name'] ?? $section->name ?? ''));
            $sectionId   = $section['id'] ?? $section->id ?? null;

            if ($sectionName === $csvSection) {
                return $sectionId; // section_1
            }
        }

        return null;
    }

    /**
     * Parse date from various formats
     */
    private function parseDate($dateString)
    {
        if (empty($dateString) || strtolower($dateString) == 'null') {
            return null;
        }

        try {
            // Common Indian date formats
            $formats = [
                'd-m-Y', 'd/m/Y', 'd.m.Y',  // 15-05-2005
                'm-d-Y', 'm/d/Y', 'm.d.Y',  // 05-15-2005
                'Y-m-d', 'Y/m/d', 'Y.m.d',  // 2005-05-15
                'd-M-Y', 'd/M/Y',           // 15-May-2005
                'd F Y', 'd F, Y',          // 15 May 2005
            ];
            
            // Clean the date string
            $dateString = trim($dateString);
            
            // Try each format
            foreach ($formats as $format) {
                $date = \DateTime::createFromFormat($format, $dateString);
                if ($date !== false) {
                    return $date->format('Y-m-d');
                }
            }
            
            // Try if it's an Excel serial number
            if (is_numeric($dateString) && $dateString > 0) {
                $unixTimestamp = ($dateString - 25569) * 86400;
                return date('Y-m-d', $unixTimestamp);
            }
            
            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Generate unique student hash ID
     */
    private function generateStudentHashId()
    {
        $part1 = Str::upper(Str::random(5));
        $part2 = Str::upper(Str::random(5));
        $part3 = Str::upper(Str::random(5));
        
        return $part1 . $part2 . $part3;
    }

    /**
     * Success page after bulk upload
     */
    public function showSuccessPage()
    {
        $createdStudents = session('created_students', []);
        $errors = session('errors', []);
        
        return view('instituteAdmin.BulkUpload.BulkUploadSuccess', compact('createdStudents', 'errors'));
    }

    /**
     * Download CSV template matching your format
     */
    public function downloadTemplate()
    {
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=student_bulk_template.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = [
            'REGISTRATION NO.',  // Optional
            'CLASS',             // Not used - selected from dropdown
            'STUDENT NAME',      // Required - e.g. "Rahul Kumar Singh" or "Priya Sharma"
            'MOTHER NAME',       // Optional - e.g. "Sunita Devi"
            'SECTION',           // Optional - e.g. "A", "B", "C"
            'DOB',               // Required - e.g. "15-05-2005" or "15/05/2005"
            'ADDRESS',           // Optional
            'FATHER NAME',       // Required - e.g. "Rajesh Kumar"
            'FATHER CONTACT',    // Required for login - e.g. "9876543210"
            'MOTHER CONTACT'     // Optional - e.g. "9876543211"
        ];

        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($file, $columns);
            
            // Add sample data
            $samples = [
                [
                    'REG001',
                    '10th',
                    'Rahul Kumar Singh',
                    'Sunita Devi',
                    'A',
                    '15-05-2005',
                    '123 Main Street, Mumbai',
                    'Rajesh Kumar Singh',
                    '9876543210',
                    '9876543211'
                ],
                [
                    'REG002',
                    '10th',
                    'Priya Sharma',
                    'Neeta Sharma',
                    'B',
                    '20-10-2006',
                    '456 Park Avenue, Delhi',
                    'Sunil Sharma',
                    '9876543212',
                    '9876543213'
                ],
                [
                    'REG003',
                    '10th',
                    'Amit',
                    'Sita Devi',
                    'C',
                    '10-08-2005',
                    '789 Gandhi Road, Bangalore',
                    'Ramesh Kumar',
                    '9876543214',
                    '' // No mother contact
                ]
            ];
            
            foreach ($samples as $sample) {
                fputcsv($file, $sample);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function generateStudentEmail($studentName, $registrationNumber = null)
    {
        // Clean the student name: lowercase, remove special chars
        $cleanName = preg_replace('/[^a-z]/i', '', strtolower($studentName));
        
        // Get initials or first few letters
        $nameParts = explode(' ', strtolower(trim($studentName)));
        $firstName = $nameParts[0] ?? '';
        $lastInitial = isset($nameParts[1]) ? substr($nameParts[1], 0, 1) : '';
        
        // Generate unique ID
        if ($registrationNumber) {
            // Get numbers from registration
            $numbers = preg_replace('/[^0-9]/', '', $registrationNumber);
            $uniqueId = !empty($numbers) ? substr($numbers, -4) : mt_rand(1000, 9999);
        } else {
            $uniqueId = mt_rand(1000, 9999);
        }
        
        // Format: firstname.lastinitial + uniqueId
        $emailUsername = $firstName;
        if ($lastInitial) {
            $emailUsername .= '.' . $lastInitial;
        }
        $emailUsername .= $uniqueId;
        
        // Remove any remaining special characters
        $emailUsername = preg_replace('/[^a-z0-9.]/', '', $emailUsername);
        
        return $emailUsername . '@aneesschool.com';
    }
    
    // public function updateStudentEmail()
    // {
    //     $users = User::select('id', 'email')->get();

    //     $updatedCount = 0;

    //     foreach ($users as $user) {
    //         $student = StudentParentDetails::where('user_id', $user->id)->first();

    //         if ($student) {
    //             $student->update([
    //                 'email' => $user->email
    //             ]);

    //             $updatedCount++;
    //         }
    //     }

    //     return "Student emails updated successfully! Total updated: " . $updatedCount;
    // }
}