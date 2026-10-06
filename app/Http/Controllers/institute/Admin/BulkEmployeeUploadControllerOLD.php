<?php
namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmployeeDetails;
use App\Models\User;
use App\Models\Departments;
use App\Models\Designations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class BulkEmployeeUploadController extends Controller
{  
    use \App\Traits\InstituteBranchAccess;

    public function getEmployeeUploadPage()
    {
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return back()->with('error', 'You are not associated with any institute.');
        }
        $departments = Departments::with('category')
            ->where('institute_id', $context['institute_id'])
            ->when($context['is_branch_admin'] && $context['branch_id'], function($q) use ($context) {
                $q->where('branch_id', $context['branch_id']);
            }, function($q) {
                $q->whereNull('branch_id');
            })
            ->get();
            

        $designations = Designations::where('institute_id', $context['institute_id'])
            ->when($context['is_branch_admin'] && $context['branch_id'], function($q) use ($context) {
                $q->where('branch_id', $context['branch_id']);
            }, function($q) {
                $q->whereNull('branch_id');
            })
            ->where('status', 'active')
            ->orderBy('designations')
            ->get();

        return view('instituteAdmin.BulkUpload.BulkUploadEmployee', compact('departments', 'designations'));
    }

    public function uploadBulkEmployees(Request $request)
    { 
        
        // Validate request
        $validator = Validator::make($request->all(), [
            'csv_file' => 'required|mimes:csv,txt',
            'default_department_id' => 'required',
            'default_designation_id' => 'required',
            'default_employment_type' => 'nullable',
            'default_salary_type' => 'nullable',
        ]);
       
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
           
        // Get institute/branch context
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return back()->with('error', 'You are not associated with any institute.');
        }

        // Get default department
        $department = Departments::where('department_id', $request->default_department_id)
            ->where('institute_id', $context['institute_id'])
            ->when($context['is_branch_admin'] && $context['branch_id'], function($q) use ($context) {
                $q->where('branch_id', $context['branch_id']);
            }, function($q) {
                $q->whereNull('branch_id');
            })
            ->first();
     
        if (!$department) {
            return back()->with('error', 'Invalid department selected or access denied.');
        }
        $selectedDepartmentName = strtolower(trim($department->department));
        // Get default designation
        $designation = Designations::where('designation_id', $request->default_designation_id)
            ->where('institute_id', $context['institute_id'])
            ->when($context['is_branch_admin'] && $context['branch_id'], function($q) use ($context) {
                $q->where('branch_id', $context['branch_id']);
            }, function($q) {
                $q->whereNull('branch_id');
            })
            ->first();
        
        if (!$designation) {
            return back()->with('error', 'Invalid designation selected or access denied.');
        }

        // Parse CSV file
        $file = $request->file('csv_file');
        $csvData = array_map('str_getcsv', file($file->getRealPath()));
       
     // Remove title row if exists (like "TEACHER DATA")
        $firstRow = $csvData[0] ?? [];

        if (count(array_filter($firstRow)) === 1) {
            array_shift($csvData); // Remove title row
        }

        // Now take the real header row
        $headers = array_map(function($header) {
            return strtolower(trim($header));
        }, array_shift($csvData));
                
        // Normalize headers - handle different variations
        $normalizedHeaders = [];
        foreach ($headers as $header) {
            $normalizedHeaders[] = $this->normalizeEmployeeHeader($header);
        }
        
        // Define expected headers based on your CSV
        $expectedHeaders = [
            'sn',
            'mobile number',
            'first name',
            'date of birth',
            'gender',
            'user role',
            'address line 1',
            'e-mail id',
            'department'
        ];
         
        // Check if CSV has required headers
        foreach ($expectedHeaders as $expected) {
           
            if (!in_array($expected, $normalizedHeaders)) {
                return back()->with('error', "CSV must contain '$expected' column. Found: " . implode(', ', $normalizedHeaders));
            }
        }

        DB::beginTransaction();

        // try {
            $successCount = 0;
            $errorMessages = [];
            $createdEmployees = [];
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
                    
                    // Process the data
                    $processedData = $this->processEmployeeRowData($rowData, $rowNumber);
                    
                    if (!$processedData['valid']) {
                        $errorMessages[] = $processedData['error'];
                        continue;
                    }
                    
                    // Check if mobile number already exists
                    $existingEmployee = EmployeeDetails::where('mobile_number', $processedData['mobile_number'])
                        ->where('institute_id', $context['institute_id'])
                        ->first();
                        
                    if ($existingEmployee) {
                        $errorMessages[] = [
                            'row' => $rowNumber,
                            'employee_name' => $processedData['first_name'],
                            'mobile_number' => $processedData['mobile_number'],
                            'department' => $csvDepartment,
                            'message' => "Mobile number already exists in system",
                            'details' => ''
                        ];
                        continue;
                    }
                    
                    // Check if email already exists
                    if (!empty($processedData['email'])) {
                        $existingEmail = EmployeeDetails::where('email', $processedData['email'])
                            ->where('institute_id', $context['institute_id'])
                            ->first();
                            
                        if ($existingEmail) {
                            $errorMessages[] = [
                                'row' => $rowNumber,
                                'employee_name' => $processedData['first_name'],
                                'mobile_number' => $processedData['mobile_number'],
                                'department' => $csvDepartment,
                                'message' => "Email already exists in system",
                                'details' => "Email: {$processedData['email']}"
                            ];
                            continue;
                        }
                    }
                    
                    // Generate employee code if not provided
                    $employeeCode = 'EMP' . strtoupper(Str::random(8));
                    
                    // Generate employee ID
                    $employeeId = 'EMP' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));
                    
                    // Get role from designation or CSV
                    $assignedRole = !empty($designation->roles) ? $designation->roles : 'employee';
                    
                    // Determine gender from CSV or default
                    $gender = $this->determineGender($processedData['gender']);
                    
                    // Get department from CSV or use default
                    $csvDepartment = strtolower(trim($this->cleanStringForDb($rowData['department'] ?? '')));
                    // ❌ Skip employee if department does NOT match selected one
                    if ($csvDepartment !== $selectedDepartmentName) {
                        continue;   // <-- VERY IMPORTANT (this skips the row)
                    }
                    // Since it matched, use CSV department
                    $finalDepartment = $csvDepartment;

                    // Set default values for optional fields
                    $employmentType = $request->default_employment_type ?? 'Full-time';
                    $salaryType = $request->default_salary_type ?? 'Monthly';

                    // Find department from DB based on CSV value
                    $deptFromCsv = Departments::whereRaw('LOWER(department) = ?', [$csvDepartment])
                        ->where('institute_id', $context['institute_id'])
                        ->first();

                    if (!$deptFromCsv) {
                        // If not found, skip this row (or you can assign default)
                        continue;
                    }
                                        
                    // Create employee data array
                    $employeeData = [
                        'institute_id' => $context['institute_id'],
                        'branch_id' => $context['is_branch_admin'] ? $context['branch_id'] : null,
                        'department_category_id' => $department->department_category_id,
                        'department_id' => $deptFromCsv->department_id,
                        'designation_id' => $designation->designation_id,
                        'assigned_role' => $assignedRole,
                        'employee_id' => $employeeId,
                        'employee_code' => $employeeCode,
                        'name' => $processedData['first_name'],
                        'mobile_number' => $processedData['mobile_number'],
                        'email' => $processedData['email'],
                        'nationality' => 'Indian',
                        'religion' => null,
                        'gender' => $gender,
                        'branch' => null,
                        'department' => $finalDepartment,
                        'designation' => $designation->designations,
                        'employment_type' => $employmentType,
                        'salary_type' => $salaryType,
                        'addressline1' => $processedData['address_line1'],
                        'addressline2' => null,
                        'state' => null,
                        'city' => null,
                        'pincode' => null,
                        'bank_name' => null,
                        'branch_name' => null,
                        'account_number' => null,
                        'ifsc_code' => null,
                        'pan_card' => null,
                        'aadhaar_number' => null,
                        'pan_number' => null,
                        'dob' => $processedData['dob'],
                        'doj' => null,
                        'status' => 'active',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                    
                    // Create employee record
                    $employee = EmployeeDetails::create($employeeData);
                    
                    // Create user account if email is provided
                    if (!empty($processedData['email'])) {
                        $this->createEmployeeUserAccount($employee, $assignedRole);
                    }
                    
                    // Add to created employees array
                    $createdEmployees[] = [
                        'name' => $processedData['first_name'],
                        'employee_code' => $employeeCode,
                        'employee_id' => $employeeId,
                        'mobile_number' => $processedData['mobile_number'],
                        'email' => $processedData['email'] ?? 'Not provided',
                        'password' => !empty($processedData['email']) ? '12345678' : 'Not applicable',
                        'department' => $finalDepartment,
                        'designation' => $designation->designations,
                        'role' => $assignedRole,
                        'employment_type' => $employmentType,
                        'salary_type' => $salaryType
                    ];
                    
                    $successCount++;

                // } catch (\Exception $e) {
                //     $employeeName = $this->cleanStringForDb($rowData['first name'] ?? '');
                //     $errorMessages[] = [
                //         'row' => $rowNumber,
                //         'employee_name' => $employeeName,
                //         'mobile_number' => $this->cleanContactNumber($rowData['mobile number'] ?? ''),
                //         'message' => "System error: " . $e->getMessage(),
                //         'details' => ''
                //     ];
                //     continue;
                // }
            }

            DB::commit();

            $summaryMessage = "Processed " . ($successCount + count($errorMessages)) . " rows. ";
            $summaryMessage .= "Successfully imported $successCount employees. ";
            $summaryMessage .= "Failed: " . count($errorMessages) . " rows.";
            $filteredErrors = array_filter($errorMessages, function ($error) use ($selectedDepartmentName) {
                if (!is_array($error) || !isset($error['department'])) {
                    return false;
                }

                return strtolower(trim($error['department'])) === strtolower(trim($selectedDepartmentName));
            });
            if ($successCount > 0) {
                session(['created_employees' => $createdEmployees]);
                session(['errors' => $filteredErrors]);   // 🔥 ADD THIS

                return redirect()->route('employee.bulk.upload.success')
                    ->with([
                        'selectedDepartment' => $department->department,
                        'createdEmployees' => $createdEmployees,
                        'errors' => $filteredErrors,
                    ]);
            }
            else {
                return back()->with('error', 'No employees were imported. ' . $summaryMessage);
            }

        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     return back()->with('error', 'Bulk upload failed: ' . $e->getMessage());
        // }
    }
    
    /**
     * Normalize employee CSV headers
     */
    private function normalizeEmployeeHeader($header)
    {
        $header = strtolower(trim($header));
        
        // Remove special characters and extra spaces
        $header = preg_replace('/[^\w\s.]/', '', $header);
        $header = preg_replace('/\s+/', ' ', $header);
        
        // Common variations mapping for employee headers
        $variations = [
            'serial no' => 'sn',
            'serial number' => 'sn',
            's.no' => 'sn',
            'mobile' => 'mobile number',
            'phone' => 'mobile number',
            'contact' => 'mobile number',
            'phone number' => 'mobile number',
            'contact number' => 'mobile number',
            'name' => 'first name',
            'full name' => 'first name',
            'employee name' => 'first name',
            'first name*' => 'first name',  
            'user role*' => 'user role',    
            'date of birth' => 'date of birth',
            'dob' => 'date of birth',
            'birth date' => 'date of birth',
            'sex' => 'gender',
            'role' => 'user role',
            'position' => 'user role',
            'address' => 'address line 1',
            'address 1' => 'address line 1',
            'email' => 'e-mail id',
            'email id' => 'e-mail id',
            'email address' => 'e-mail id',
            'dept' => 'department',
            'dept.' => 'department',
            'department name' => 'department',
        ];
        
        return $variations[$header] ?? $header;
    }

    /**
     * Process employee row data from CSV
     */
    private function processEmployeeRowData($rowData, $rowNumber)
    {
        // Extract and clean data
        $firstName = $this->cleanStringForDb($rowData['first name'] ?? '');
        $mobileNumber = $this->cleanContactNumber($rowData['mobile number'] ?? '');
        $email = $this->cleanStringForDb($rowData['e-mail id'] ?? '');
        $dob = $rowData['date of birth'] ?? '';
        $gender = $this->cleanStringForDb($rowData['gender'] ?? '');
        $addressLine1 = $this->cleanStringForDb($rowData['address line 1'] ?? '');
        
        // Check required fields
        if (empty($firstName)) {
            return [
                'valid' => false,
                'error' => [
                    'row' => $rowNumber,
                    'employee_name' => $firstName,
                    'mobile_number' => $mobileNumber,
                    'department' => $rowData['department'] ?? null,
                    'message' => "First Name is required",
                    'details' => ''
                ]
            ];
        }
        
        if (empty($mobileNumber)) {
            return [
                'valid' => false,
                'error' => [
                    'row' => $rowNumber,
                    'employee_name' => $firstName,
                    'mobile_number' => $mobileNumber,
                    'message' => "Mobile Number is required",
                    'details' => ''
                ]
            ];
        }
        
        if (empty($dob)) {
            return [
                'valid' => false,
                'error' => [
                    'row' => $rowNumber,
                    'employee_name' => $firstName,
                    'mobile_number' => $mobileNumber,
                    'message' => "Date of Birth is required",
                    'details' => ''
                ]
            ];
        }
        
        // Parse date
        $parsedDob = $this->parseDate($dob);
        if (!$parsedDob) {
            return [
                'valid' => false,
                'error' => [
                    'row' => $rowNumber,
                    'employee_name' => $firstName,
                    'mobile_number' => $mobileNumber,
                    'message' => "Invalid Date of Birth format",
                    'details' => "Provided: $dob"
                ]
            ];
        }
        
        // Validate email if provided
        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return [
                'valid' => false,
                'error' => [
                    'row' => $rowNumber,
                    'employee_name' => $firstName,
                    'mobile_number' => $mobileNumber,
                    'message' => "Invalid email format",
                    'details' => "Provided: $email"
                ]
            ];
        }
        
        return [
            'valid' => true,
            'first_name' => $firstName,
            'mobile_number' => $mobileNumber,
            'email' => $email,
            'dob' => $parsedDob,
            'gender' => $gender,
            'address_line1' => $addressLine1
        ];
    }

    /**
     * Clean string for database insertion
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
        $string = str_replace(["\xC2\xA0", "\xA0"], ' ', $string);
        $string = preg_replace('/[^\x{0009}\x{000A}\x{000D}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}\x{10000}-\x{10FFFF}]/u', '', $string);
        $string = preg_replace('/[\x00-\x1F\x7F-\x9F]/u', '', $string);
        $string = preg_replace('/\s+/', ' ', $string);
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
     * Parse date from various formats
     */
    private function parseDate($dateString)
    {
        if (empty($dateString) || strtolower($dateString) == 'null') {
            return null;
        }

        try {
            // Common date formats
            $formats = [
                'd-m-Y', 'd/m/Y', 'd.m.Y',  // 15-05-1990
                'm-d-Y', 'm/d/Y', 'm.d.Y',  // 05-15-1990
                'Y-m-d', 'Y/m/d', 'Y.m.d',  // 1990-05-15
                'd-M-Y', 'd/M/Y',           // 15-May-1990
                'd F Y', 'd F, Y',          // 15 May 1990
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
     * Determine gender from input
     */
    private function determineGender($genderInput)
    {
        if (empty($genderInput)) {
            return 'male'; // Default
        }
        
        $gender = strtolower(trim($genderInput));
        
        if (in_array($gender, ['male', 'm', 'boy', 'man'])) {
            return 'male';
        } elseif (in_array($gender, ['female', 'f', 'girl', 'woman'])) {
            return 'female';
        } elseif (in_array($gender, ['other', 'o', 'transgender'])) {
            return 'other';
        }
        
        return 'male'; // Default fallback
    }

    /**
     * Create employee user account
     */
    private function createEmployeeUserAccount($employee, $role)
    {
        $tempPassword = '12345678'; 
        
        $userData = [
            'name' => $employee->name,
            'email' => $employee->email,
            'phone' => $employee->mobile_number,
            'password' => Hash::make($tempPassword),
            'email_verified_at' => now(),
            'institute_id' => $employee->institute_id,
            'branch_id' => $employee->branch_id,
        ];
        
        $user = User::create($userData);
        
        try {
            $user->assignRole($role);    
        } catch (\Exception $e) {
            
            $user->assignRole('employee'); // Fallback role
        }
        
        // Update employee with user_id
        $employee->update(['user_id' => $user->id]);
        
        return $user;
    }

    /**
     * Success page after bulk upload
     */
    public function showEmployeeSuccessPage()
    {
        $createdEmployees = session('created_employees', []);
        $errors = session('errors', []);
            
        return view('instituteAdmin.BulkUpload.BulkUploadEmployeeSuccess', compact('createdEmployees', 'errors'));
    }

    /**
     * Download CSV template for employees
     */
    public function downloadEmployeeTemplate()
    {
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=employee_bulk_template.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = [
            'SN',
            'Mobile Number',
            'First Name',
            'Date of Birth',
            'Gender',
            'User Role',
            'Address Line 1',
            'E-mail ID',
            'Department'
        ];

        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($file, $columns);
            
            // Add sample data
            $samples = [
                [
                    '1',
                    '9876543210',
                    'Rahul Sharma',
                    '15-05-1990',
                    'Male',
                    'Teacher',
                    '123 Main Street, Mumbai',
                    'rahul.sharma@example.com',
                    'Teaching'
                ],
                [
                    '2',
                    '9876543211',
                    'Priya Patel',
                    '20-10-1985',
                    'Female',
                    'Accountant',
                    '456 Park Avenue, Delhi',
                    'priya.patel@example.com',
                    'Accounts'
                ],
                [
                    '3',
                    '9876543212',
                    'Amit Kumar',
                    '10-08-1992',
                    'Male',
                    'Clerk',
                    '789 Gandhi Road, Bangalore',
                    'amit.kumar@example.com',
                    'Administration'
                ]
            ];
            
            foreach ($samples as $sample) {
                fputcsv($file, $sample);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}