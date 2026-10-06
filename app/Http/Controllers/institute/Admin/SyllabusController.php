<?php
namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use App\Models\Syllabus;
use App\Models\EmployeeDetails;
use App\Models\ProductDetails;
use App\Models\CourseFeeStructure;
use App\Models\SubjectsCoursewise;
use App\Models\StudentParentDetails;
use App\Models\Departments;
use App\Models\DepartmentCategory;
use App\Models\InstituteBasicDetails;
use App\Models\SyllabusTopic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage; 
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\SyllabusExport;
use Maatwebsite\Excel\Facades\Excel;

class SyllabusController extends Controller 
{
    use \App\Traits\InstituteBranchAccess;   
  
    public function create()
    {
        $context = $this->getInstituteBranchContext();
        if (!$context['institute_id']) {
            return redirect()->back()->with('error', 'You are not associated with any institute.');
        }
        
        $instituteId = $context['institute_id']; 
        $branchId = $context['branch_id'] ?? null;
        $user = Auth::user();
        
        // Get institute type
        $institute = InstituteBasicDetails::where('fincap_merchant_id', $instituteId)->first();
        $instituteType = $institute->type ?? '';
        
        // Get categories for this institute
        $categories = DepartmentCategory::where('institute_id', $instituteId)->get();
        
        // Initialize variables
        $employee = null;
        $employeeDepartments = collect();
        $assignedSubjects = collect();
        
        if ($user->hasRole('employee') || $user->hasRole('Teacher')) { 
            $employee = EmployeeDetails::where('user_id', $user->id)
                ->where('institute_id', $instituteId)
                ->first();
                
            if ($employee) {
                $employeeDepartments = Departments::where('department_id', $employee->department_id)
                    ->where('institute_id', $instituteId)
                    ->get();
                
                $assignedSubjects = \App\Models\AssignSubjectsToEmployee::where('employee_id', $employee->employee_id)
                    ->where('institute_id', $instituteId)
                    ->when($branchId, function($query) use ($branchId) {
                        return $query->where('branch_id', $branchId);
                    })
                    ->with(['subject' => function($query) {
                        $query->select('subject_id', 'subject_name', 'semester_id');
                    }])
                    ->get()
                    ->pluck('subject.subject_id')
                    ->filter()
                    ->unique();
            }
        }
        
        return view('instituteAdmin.Syllabus.UploadSyllabus', compact(
            'categories', 
            'employee', 
            'employeeDepartments',
            'instituteId',
            'branchId',
            'instituteType',
            'assignedSubjects'
        ));
    }

    public function getSubTypes($courseType)
    {
        $user = Auth::user();
        $instituteId = $user->institute_id;
        
        $subTypes = ProductDetails::where('course_type', $courseType)
            ->where('institute_id', $instituteId)
            ->select('product_id', 'sub_type')
            ->get();
            
        return response()->json($subTypes);
    }

    public function getSubjects($productId)
    {
        $user = Auth::user();
        $instituteId = $user->institute_id;
        $isEmployee = $user->hasRole('employee') || $user->hasRole('Teacher'); 
        
        $query = SubjectsCoursewise::where('course_detail_id', $productId)
            ->where('institute_id', $instituteId);
        
        if ($isEmployee) {
            $employee = EmployeeDetails::where('user_id', $user->id)
                ->where('institute_id', $instituteId)
                ->first();
                
            if ($employee) {
                $assignedSubjectIds = \App\Models\AssignSubjectsToEmployee::where('employee_id', $employee->employee_id)
                    ->where('institute_id', $instituteId)  
                    ->pluck('subject_id')
                    ->filter()
                    ->unique()
                    ->toArray();
                
                if (!empty($assignedSubjectIds)) {
                    $query->whereIn('subject_id', $assignedSubjectIds);
                } else {
                    return response()->json([]);
                }
            }
        }
        
        $subjects = $query->select('subject_id', 'subject_name', 'semester_id')
            ->get();
            
        return response()->json($subjects);
    }

    public function getSubjectSyllabusStatus($subjectId)
    {
        $user = Auth::user();
        $context = $this->getInstituteBranchContext();

        if (!$context['institute_id'] || !$subjectId) {
            return response()->json([
                'status' => 'success',
                'subject_id' => $subjectId,
                'monthly_count' => 0,
                'monthly_labels' => [],
                'whole_semester_uploaded' => false,
                'message' => 'No syllabus has been uploaded for this subject yet.',
            ]);
        }

        $query = Syllabus::where('subject_id', $subjectId)
            ->where('institute_id', $context['institute_id']);

        if (!empty($context['branch_id'])) {
            $query->where('branch_id', $context['branch_id']);
        }

        $records = $query->get(['term_type', 'term_value']);
        $summary = $this->buildSyllabusStatusSummary($records);

        return response()->json([
            'status' => 'success',
            'subject_id' => $subjectId,
            'monthly_count' => $summary['monthly_count'],
            'monthly_labels' => $summary['monthly_labels'],
            'whole_semester_uploaded' => $summary['whole_semester_uploaded'],
            'message' => $summary['message'],
        ]);
    }

    private function buildSyllabusStatusSummary($records)
    {
        $monthlyLabels = [];
        $wholeSemesterUploaded = false;

        foreach ($records as $record) {
            $termType = trim((string) ($record->term_type ?? ''));
            if ($termType === 'monthly') {
                $value = trim((string) ($record->term_value ?? ''));
                if ($value !== '') {
                    $monthlyLabels[] = $value;
                }
                continue;
            }

            if ($termType === '' || $termType === 'semester' || $termType === 'yearly') {
                $wholeSemesterUploaded = true;
            }
        }

        $monthlyLabels = array_values(array_unique($monthlyLabels));
        $monthlyCount = count($monthlyLabels);

        if ($monthlyCount > 0 && $wholeSemesterUploaded) {
            $message = 'Syllabus for this subject is already uploaded for ' . $monthlyCount . ' month' . ($monthlyCount > 1 ? 's' : '') . ': ' . implode(', ', $monthlyLabels) . '. A whole-semester syllabus is also uploaded.';
        } elseif ($monthlyCount > 0) {
            $message = 'Syllabus for this subject is already uploaded for ' . $monthlyCount . ' month' . ($monthlyCount > 1 ? 's' : '') . ': ' . implode(', ', $monthlyLabels) . '.';
        } elseif ($wholeSemesterUploaded) {
            $message = 'Syllabus for this subject is already uploaded as a whole semester.';
        } else {
            $message = 'No syllabus has been uploaded for this subject yet.';
        }

        return [
            'monthly_count' => $monthlyCount,
            'monthly_labels' => $monthlyLabels,
            'whole_semester_uploaded' => $wholeSemesterUploaded,
            'message' => $message,
        ];
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $context = $this->getInstituteBranchContext();
        if (!$context['institute_id']) {
            return back()->with('error', 'You are not associated with any institute.'); 
        }
        $instituteId = $context['institute_id'];
        $branchId = $context['branch_id'] ?? null;
        $isEmployee = $user->hasRole('employee') || $user->hasRole('Teacher');

        $request->merge([
            'department_id' => $request->input('department_id') ?? $request->input('department') ?? null,
            'course_detail_id' => $request->input('course_detail_id') ?? $request->input('subType') ?? null,
            'subject_id' => $request->input('subject_id') ?? $request->input('subject') ?? null,
            'title' => $request->input('title') ?? $request->input('syllabus_title') ?? null,
            'syllabus_for' => $request->input('syllabus_for') ?? 'subject_level',
            'term_type' => $request->input('term_type') ?? 'semester',
            'term_value' => $request->input('term_value') ?? $request->input('semester_id') ?? null,
        ]);
        
        $institute = InstituteBasicDetails::where('fincap_merchant_id', $instituteId)->first();
        $instituteType = $institute->type ?? '';

        if ($request->filled('semester_id') && !$request->filled('term_value')) {
            $request->merge([
                'term_type' => $request->input('term_type', 'semester'),
                'term_value' => $request->input('term_value', $request->input('semester_id')),
            ]);
        }

        $rules = [
            'department_id' => 'nullable|string',
            'course_detail_id' => 'nullable|string',
            'subject_id' => 'nullable|string',
            'title' => 'nullable|string|max:255',
            'file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ];
        
        if ($instituteType === 'School') {
            $rules['syllabus_for'] = 'required|in:class_level,subject_level';
        } else {
            $rules['syllabus_for'] = 'required|in:class_level,subject_level';
        }
        
        if ($request->syllabus_for === 'subject_level' && $instituteType !== 'School') {
            $rules['term_type'] = 'nullable|string';
            $rules['term_value'] = 'nullable|string';
        }
        $request->validate($rules);

        if ($request->filled('department_id')) {
            $department = Departments::where('department_id', $request->department_id)
                ->where('institute_id', $instituteId)
                ->first();
            if (!$department) {
                return redirect()->back()->with('error', 'Invalid department selected.');
            }
        }

        if ($request->filled('course_detail_id')) {
            $courseDetail = ProductDetails::where('product_id', $request->course_detail_id)
                ->where('institute_id', $instituteId)
                ->first();
            if (!$courseDetail) {
                return redirect()->back()->with('error', 'Invalid course selected.');
            }
        }

        if ($request->filled('subject_id')) {
            $subject = SubjectsCoursewise::where('subject_id', $request->subject_id)
                ->where('institute_id', $instituteId)
                ->first();
            if (!$subject) {
                return redirect()->back()->with('error', 'Invalid subject selected.');
            }
        }

        $employeeId = null;
        if ($isEmployee) {
            $employee = EmployeeDetails::where('user_id', $user->id)
                ->where('institute_id', $instituteId)
                ->first();
            if (!$employee) {
                $employee = EmployeeDetails::where('user_id', $user->id)->first();
                if (!$employee) {
                    return redirect()->back()->with('error', 'Employee record not found. Please contact administrator.');
                } else {
                    $employeeId = $employee->employee_id;
                }
            } else {
                $employeeId = $employee->employee_id;
            }
        } else {
            $employeeId = 'ADMIN-' . date('YmdHis');
        }

        $selectedMonths = [];
        if ($request->filled('selected_months')) {
            $selectedMonths = array_values(array_filter(array_map('trim', explode(',', $request->input('selected_months')))));
        } elseif ($request->has('month_selector') && is_array($request->month_selector)) {
            $selectedMonths = array_values(array_filter($request->month_selector));
        }

        $uploadMode = $request->input('upload_mode', 'whole_semester');
        $isMonthWiseUpload = $uploadMode === 'month_wise' || !empty($selectedMonths);

        $rules = [
            'department_id' => 'nullable|string',
            'course_detail_id' => 'nullable|string',
            'subject_id' => 'nullable|string',
            'title' => 'nullable|string|max:255',
            'file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ];
        
        if ($instituteType === 'School') {
            $rules['syllabus_for'] = 'required|in:class_level,subject_level';
        } else {
            $rules['syllabus_for'] = 'required|in:class_level,subject_level';
        }
        
        if ($request->syllabus_for === 'subject_level' && $instituteType !== 'School') {
            $rules['term_type'] = 'nullable|string';
            $rules['term_value'] = 'nullable|string';
        }
        
        if ($isMonthWiseUpload) {
            $rules['files'] = 'nullable|array';
            foreach ($selectedMonths as $index => $monthValue) {
                $rules["files.$index"] = 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120';
            }
        }
        $request->validate($rules);

        $normalizedTermType = in_array($request->input('term_type'), ['monthly', 'semester', 'yearly'], true)
            ? $request->input('term_type')
            : 'semester';

        $syllabusRecords = [];
        $topicsData = [];
        $createdPaths = [];
        $duplicateExists = false;

        if ($isMonthWiseUpload) {
            foreach ($selectedMonths as $index => $monthValue) {
                $monthFile = $request->file("files.$index");
                if (!$monthFile || !$monthFile->isValid()) {
                    return redirect()->back()->with('error', 'Please upload a file for each selected month.')->withInput();
                }

                $monthLabel = $this->formatMonthLabel($monthValue);
                $generatedSyllabusId = 'SYL-' . strtoupper(Str::random(8));
                $path = $monthFile->store('syllabuses', 'public');
                $createdPaths[] = $path;
                $fileName = $monthFile->getClientOriginalName();
                $parsedText = '';

                if (strtolower($monthFile->getClientOriginalExtension()) === 'pdf') {
                    $parsedText = $this->extractPdfText($monthFile->getContent());
                }

                $previewText = $request->input("topics.$index.parsed_text");
                $parsedText = $this->resolveParsedContent($previewText, $parsedText);

                $syllabusData = [
                    'institute_id' => $instituteId,
                    'department_id' => $request->department_id,
                    'syllabus_id' => $generatedSyllabusId,
                    'employee_id' => $employeeId,
                    'course_detail_id' => $request->course_detail_id,
                    'subject_id' => $request->subject_id,
                    'title' => $request->title,
                    'description' => $request->input("topics.$index.description") ?? $request->description,
                    'file_path' => $path,
                    'file_name' => $fileName,
                    'uploaded_date' => now()->format('Y-m-d'),
                    'syllabus_for' => $request->syllabus_for,
                    'parsed_content' => $parsedText,
                    'term_type' => 'monthly',
                    'term_value' => $monthLabel,
                ];
                
                if ($branchId) {
                    $syllabusData['branch_id'] = $branchId;
                }
                if (!$isEmployee) {
                    $syllabusData['uploaded_by_admin'] = true;
                    $syllabusData['uploaded_by_user_id'] = $user->id;
                }
                $syllabusRecords[] = $syllabusData;

                $topicsData[] = [
                    'topic_id' => 'TOPIC-' . strtoupper(Str::random(10)),
                    'institute_id' => $instituteId,
                    'syllabus_id' => $generatedSyllabusId,
                    'branch_id' => $branchId ?? '',
                    'subject_id' => $request->subject_id,
                    'topic_name' => $request->input("topics.$index.topic_name") ?: $this->formatTopicName($monthLabel, $request->title),
                    'book_name' => '',
                    'start_date' => null,
                    'end_date' => null,
                    'description' => $request->input("topics.$index.description") ?? '',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $duplicateCheck = Syllabus::where('course_detail_id', $request->course_detail_id)
                    ->where('subject_id', $request->subject_id)
                    ->where('institute_id', $instituteId)
                    ->where('term_type', 'monthly')
                    ->where('term_value', $monthLabel);
                if ($branchId) {
                    $duplicateCheck->where('branch_id', $branchId);
                }
                $duplicateExists = $duplicateExists || $duplicateCheck->exists();
            }
        } else {
            $fileName = null;
            $path = '';
            $parsedText = '';
            
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $path = $file->store('syllabuses', 'public');
                $fileName = $file->getClientOriginalName();
                $createdPaths[] = $path;

                if (strtolower($file->getClientOriginalExtension()) === 'pdf') {
                    $parsedText = $this->extractPdfText($file->getContent());
                }
            }

            $generatedSyllabusId = 'SYL-' . strtoupper(Str::random(8));
            $parsedText = '';
            if ($request->hasFile('file')) {
                $parsedText = $this->extractPdfText($request->file('file')->getContent());
            }
            $previewText = $request->input('topics.0.parsed_text');
            $parsedText = $this->resolveParsedContent($previewText, $parsedText);

            $syllabusData = [
                'institute_id' => $instituteId,
                'department_id' => $request->department_id,
                'syllabus_id' => $generatedSyllabusId,
                'employee_id' => $employeeId,
                'course_detail_id' => $request->course_detail_id,
                'subject_id' => $request->subject_id,
                'title' => $request->title,
                'description' => $request->description,
                'file_path' => $path,
                'file_name' => $fileName,
                'uploaded_date' => now()->format('Y-m-d'),
                'syllabus_for' => $request->syllabus_for,
                'parsed_content' => $parsedText,
            ];

            if (!empty($selectedMonths)) {
                $syllabusData['term_type'] = 'monthly';
                $syllabusData['term_value'] = implode(', ', $selectedMonths);
            } else {
                $termValue = $request->input('term_value') ?: $request->input('semester_id');
                if ($request->filled('term_type')) {
                    $syllabusData['term_type'] = $normalizedTermType;
                }
                if (!empty($termValue)) {
                    $syllabusData['term_value'] = $termValue;
                }
            }
            
            if ($branchId) {
                $syllabusData['branch_id'] = $branchId;
            }
            if (!$isEmployee) {
                $syllabusData['uploaded_by_admin'] = true;
                $syllabusData['uploaded_by_user_id'] = $user->id;
            }
            $syllabusRecords[] = $syllabusData;

            if (!empty($request->topics)) {
                foreach ($request->topics as $topic) {
                    $topicsData[] = [
                        'topic_id'    => 'TOPIC-' . strtoupper(Str::random(10)),
                        'institute_id'=> $instituteId,
                        'syllabus_id' => $generatedSyllabusId,
                        'branch_id'   => $branchId ?? '',
                        'subject_id'  => $request->subject_id,
                        'topic_name'  => $topic['topic_name'] ?? 'Full Semester Syllabus',
                        'book_name'   => $topic['book_name'] ?? '',
                        'start_date'  => $topic['start_date'] ?? null,
                        'end_date'    => $topic['end_date'] ?? null,
                        'description' => $topic['description'] ?? '',
                        'created_at'  => now(),
                        'updated_at'  => now(),
                    ];
                }
            }

            $duplicateCheck = Syllabus::where('course_detail_id', $request->course_detail_id)
                ->where('subject_id', $request->subject_id)
                ->where('institute_id', $instituteId);
            if (!empty($selectedMonths)) {
                $duplicateCheck->where('term_type', 'monthly');
                $duplicateCheck->where('term_value', implode(', ', $selectedMonths));
            } elseif ($request->syllabus_for === 'subject_level' && $instituteType !== 'School') {
                $duplicateCheck->where('term_type', $normalizedTermType);
                $duplicateCheck->where('term_value', $request->term_value ?? $request->semester_id);
            }
            if ($branchId) {
                $duplicateCheck->where('branch_id', $branchId);
            }
            $duplicateExists = $duplicateCheck->exists();
        }

        try {
            \DB::beginTransaction();
            foreach ($syllabusRecords as $syllabusData) {
                Syllabus::create($syllabusData);
            }
            if (!empty($topicsData)) {
                SyllabusTopic::insert($topicsData);
            }
            \DB::commit();
            
            $message = $duplicateExists
                ? 'Syllabus uploaded successfully. A similar entry already existed, so a new record was created.'
                : ($isMonthWiseUpload ? 'Syllabus uploaded successfully for each selected month.' : 'Syllabus and Topic uploaded successfully!');
            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            \DB::rollBack();
            foreach ($createdPaths as $pathToDelete) {
                if ($pathToDelete && Storage::disk('public')->exists($pathToDelete)) {
                    Storage::disk('public')->delete($pathToDelete);
                }
            }
            return redirect()->back()->with('error', 'Failed to upload syllabus. ' . $e->getMessage());
        }
    }

    /**
     * Extract text from PDF content using multiple strategies
     * This method works without any external dependencies
     */
private function extractPdfText($fileContent)
{
    if (empty($fileContent)) {
        return '';
    }

    try {
        // FIRST: Try to extract text content from streams
        $extractedText = '';
        
        // Try primary extraction methods - these should find the Lorem ipsum text
        $extractedText = $this->extractTextFromPdfStreams($fileContent);
        
        // Log for debugging
        \Log::info('PDF Stream extraction result length: ' . strlen($extractedText));
        \Log::info('PDF Stream extraction preview: ' . substr($extractedText, 0, 100));
        
        if (empty($extractedText) || strlen($extractedText) < 20) {
            $extractedText = $this->extractTextFromObjects($fileContent);
            \Log::info('PDF Object extraction result length: ' . strlen($extractedText));
        }
        
        if (empty($extractedText) || strlen($extractedText) < 20) {
            $extractedText = $this->extractTextFromStrings($fileContent);
            \Log::info('PDF String extraction result length: ' . strlen($extractedText));
        }
        
        // Clean the extracted text
        $extractedText = $this->cleanExtractedText($extractedText);
        
        // Check if we have readable text
        if ($this->hasReadableText($extractedText)) {
            Log::info('PDF text extracted successfully! Length: ' . strlen($extractedText));
            return substr($extractedText, 0, 3000) . (strlen($extractedText) > 3000 ? '...' : '');
        }
        
        // Only treat strongly scanned-style content as unreadable when there is no readable text at all
        $scannedIndicators = ['CamScanner', 'scanned by', 'ocr', 'optical character recognition'];
        $hasStrongScannedIndicator = false;
        foreach ($scannedIndicators as $indicator) {
            if (stripos($fileContent, $indicator) !== false) {
                $hasStrongScannedIndicator = true;
                break;
            }
        }
        
        if ($hasStrongScannedIndicator && empty($extractedText)) {
            Log::info('PDF detected as scanned - no text found');
            return 'This appears to be a scanned PDF. No text could be extracted. Please use OCR to extract text.';
        }
        
        // Fallback: try the original extraction method
        Log::info('Using fallback extraction method');
        $fallbackText = $this->extractPdfTextFallback($fileContent);

        if ($this->hasReadableText($fallbackText)) {
            return $fallbackText;
        }

        return $fallbackText;
        
    } catch (\Exception $e) {
        \Log::error('PDF text extraction error: ' . $e->getMessage());
        return $this->extractPdfTextFallback($fileContent);
    }
}
private function resolveParsedContent($previewText, $fallbackText)
{
    $previewText = trim((string) $previewText);
    if ($previewText !== '') {
        return $previewText;
    }

    return trim((string) $fallbackText);
}

private function hasReadableText($text)
{
    if (empty($text)) {
        return false;
    }

    $text = trim($text);
    if (strlen($text) < 10) {
        return false;
    }

    $pdfSyntaxTokens = ['bt', 'et', 'tj', 'tm', 'td', 'tf', 'cm', 'q', 'do', 'stream', 'endstream', 'obj', 'endobj'];
    if (preg_match('/\b(?:' . implode('|', $pdfSyntaxTokens) . ')\b/i', $text)) {
        return false;
    }

    $words = preg_split('/\s+/', $text);
    $wordCount = 0;
    $realWords = 0;

    foreach ($words as $word) {
        $word = trim($word);
        if ($word === '') {
            continue;
        }

        $wordCount++;
        $cleanWord = preg_replace('/[^A-Za-zÀ-ÿ]/u', '', $word);
        if ($cleanWord === '') {
            continue;
        }

        if (strlen($cleanWord) >= 3 && preg_match('/[aeiou]/i', $cleanWord)) {
            $realWords++;
        }
    }

    if ($wordCount < 3) {
        return false;
    }

    return ($realWords / $wordCount) >= 0.25;
}
    /**
     * Extract text from PDF stream data
     */
private function extractTextFromPdfStreams($content)
{
    $texts = [];
    
    // Find all stream data
    preg_match_all('/stream\s*(.*?)\s*endstream/s', $content, $matches, PREG_SET_ORDER);
    
    foreach ($matches as $match) {
        $streamData = $match[1] ?? '';
        if (empty($streamData)) continue;
        
        // Try various decompression methods
        $decodedCandidates = [];
        
        // Try raw stream
        $decodedCandidates[] = $streamData;
        
        // Try gzuncompress
        if (function_exists('gzuncompress')) {
            $decoded = @gzuncompress($streamData);
            if ($decoded !== false && $decoded !== '') {
                $decodedCandidates[] = $decoded;
            }
        }
        
        // Try gzinflate
        if (function_exists('gzinflate')) {
            $decoded = @gzinflate($streamData);
            if ($decoded !== false && $decoded !== '') {
                $decodedCandidates[] = $decoded;
            }
        }
        
        // Try gzdecode
        if (function_exists('gzdecode')) {
            $decoded = @gzdecode($streamData);
            if ($decoded !== false && $decoded !== '') {
                $decodedCandidates[] = $decoded;
            }
        }
        
        // Try base64 decode
        $decoded = @base64_decode($streamData, true);
        if ($decoded !== false && $decoded !== '') {
            $decodedCandidates[] = $decoded;
        }
        
        // Process each decoded candidate
        foreach ($decodedCandidates as $candidate) {
            // Extract text from the candidate
            $extracted = $this->extractReadableTextFromStream($candidate);
            if (!empty($extracted)) {
                $texts[] = $extracted;
            }
        }
    }
    
    // Join all extracted texts
    if (!empty($texts)) {
        $combined = implode(' ', array_unique($texts));
        // Clean up
        $combined = preg_replace('/[^\x20-\x7E\x0A\x0D]/', ' ', $combined);
        $combined = preg_replace('/\s+/', ' ', $combined);
        return trim($combined);
    }
    
    return '';
}
private function extractReadableTextFromStream($data)
{
    if (empty($data)) {
        return '';
    }
    
    // Remove non-printable characters but keep spaces and newlines
    $data = preg_replace('/[^\x20-\x7E\x0A\x0D]/', ' ', $data);
    
    // Extract text from TJ operators (common in PDFs)
    preg_match_all('/\(([^()]*)\)\s*Tj/', $data, $tjMatches);
    if (!empty($tjMatches[1])) {
        $texts = array_map('trim', $tjMatches[1]);
        $texts = array_filter($texts, function($t) {
            return strlen($t) > 3 && preg_match('/[A-Za-z]/', $t);
        });
        if (!empty($texts)) {
            return implode(' ', $texts);
        }
    }
    
    // Extract text from square brackets (with spaces)
    preg_match_all('/\[([^\]]*)\]\s*TJ/', $data, $tjArrayMatches);
    if (!empty($tjArrayMatches[1])) {
        foreach ($tjArrayMatches[1] as $match) {
            // Extract strings between parentheses
            preg_match_all('/\(([^()]*)\)/', $match, $stringMatches);
            if (!empty($stringMatches[1])) {
                $texts = array_map('trim', $stringMatches[1]);
                $texts = array_filter($texts, function($t) {
                    return strlen($t) > 2 && preg_match('/[A-Za-z]/', $t);
                });
                if (!empty($texts)) {
                    return implode(' ', $texts);
                }
            }
        }
    }
    
    // Generic text extraction - look for readable words
    preg_match_all('/\b[A-Za-z][A-Za-z\s\-\.]{3,}\b/', $data, $wordMatches);
    $texts = [];
    foreach ($wordMatches[0] ?? [] as $text) {
        $cleaned = trim($text);
        if (!empty($cleaned) && strlen($cleaned) > 3 && $this->isReadableText($cleaned)) {
            $texts[] = $cleaned;
        }
    }
    
    // Try to extract sentences
    if (empty($texts)) {
        preg_match_all('/[A-Z][A-Za-z\s\-\.]{10,}[.!?]/', $data, $sentenceMatches);
        foreach ($sentenceMatches[0] ?? [] as $sentence) {
            $cleaned = trim($sentence);
            if (!empty($cleaned) && strlen($cleaned) > 10 && $this->isReadableText($cleaned)) {
                $texts[] = $cleaned;
            }
        }
    }
    
    return implode(' ', array_unique($texts));
}
    /**
     * Extract text from PDF object data
     */
    private function extractTextFromObjects($content)
    {
        $texts = [];
        
        preg_match_all('/obj\s*<<(.*?)>>/s', $content, $matches);
        
        foreach ($matches[1] ?? [] as $objectData) {
            $extracted = $this->extractReadableText($objectData);
            if (!empty($extracted)) {
                $texts[] = $extracted;
            }
        }
        
        return implode(' ', array_unique($texts));
    }

    /**
     * Extract text from string literals in PDF
     */
    private function extractTextFromStrings($content)
    {
        $texts = [];
        
        // Extract from parentheses
        preg_match_all('/\(([^()]*)\)/', $content, $matches);
        foreach ($matches[1] ?? [] as $text) {
            $cleaned = trim($text);
            if (!empty($cleaned) && $this->isReadableText($cleaned)) {
                $texts[] = $cleaned;
            }
        }
        
        // Extract from hex strings
        preg_match_all('/<([0-9A-Fa-f]{2,})>/', $content, $hexMatches);
        foreach ($hexMatches[1] ?? [] as $hex) {
            $converted = @hex2bin($hex);
            if ($converted !== false) {
                $cleaned = trim($converted);
                if (!empty($cleaned) && $this->isReadableText($cleaned)) {
                    $texts[] = $cleaned;
                }
            }
        }
        
        return implode(' ', array_unique($texts));
    }

    /**
     * Extract readable text from data
     */
private function extractReadableText($data)
{
    if (empty($data)) {
        return '';
    }
    
    // Remove non-printable characters but keep spaces and newlines
    $data = preg_replace('/[^\x20-\x7E\x0A\x0D]/', ' ', $data);
    
    // Look for readable words (with vowels)
    preg_match_all('/\b[A-Za-z][A-Za-z\s\-\.]{3,}\b/', $data, $matches);
    
    $texts = [];
    foreach ($matches[0] ?? [] as $text) {
        $cleaned = trim($text);
        if (!empty($cleaned) && strlen($cleaned) > 3 && $this->isReadableText($cleaned)) {
            $texts[] = $cleaned;
        }
    }
    
    // Also try to extract sentences with periods
    preg_match_all('/[A-Z][A-Za-z\s\-\.]{10,}[.!?]/', $data, $sentenceMatches);
    foreach ($sentenceMatches[0] ?? [] as $sentence) {
        $cleaned = trim($sentence);
        if (!empty($cleaned) && strlen($cleaned) > 10) {
            $texts[] = $cleaned;
        }
    }
    
    return implode(' ', array_unique($texts));
}

    /**
     * Check if text appears readable
     */
    private function isReadableText($text)
    {
        if (empty($text) || strlen($text) < 3) return false;
        
        $letters = preg_match_all('/[A-Za-z]/', $text);
        $spaces = substr_count($text, ' ');
        $words = $spaces + 1;
        
        $letterRatio = $letters / max(1, strlen($text));
        return $letterRatio > 0.4 && $words >= 2;
    }

    /**
     * Clean extracted text
     */
private function cleanExtractedText($text)
{
    if (empty($text)) {
        return '';
    }
    
    // Remove common PDF artifacts
    $text = preg_replace('/\b(Tj|ET|BT|Tm|Td|TD|Tc|Tw|Tz|TL|Ts|q|Q|cm|Do|re|f|S|W|n)\b/', ' ', $text);
    $text = preg_replace('/\/\w+\s*\[[^\]]*\]/', ' ', $text);
    $text = preg_replace('/\/\w+\s*\([^)]*\)/', ' ', $text);
    $text = preg_replace('/\b[0-9]+\s+[0-9]+\s+[0-9]+\s+[0-9]+\b/', ' ', $text);
    
    // Remove non-printable characters (but keep spaces and newlines)
    $text = preg_replace('/[^\x20-\x7E\x0A\x0D]/', ' ', $text);
    
    // Remove multiple spaces and trim
    $text = preg_replace('/\s+/', ' ', $text);
    
    return trim($text);
}

    /**
     * Fallback PDF text extraction method (original logic)
     */
    private function extractPdfTextFallback($pdfContent)
    {
        if (empty($pdfContent)) {
            return '';
        }

        $rawContent = $pdfContent;
        $textContent = @iconv('UTF-8', 'UTF-8//IGNORE', $rawContent);
        if ($textContent === false) {
            $textContent = $rawContent;
        }

        $textContent = str_replace(["\r", "\n", "\t"], ' ', $textContent);
        $textContent = str_replace(["\x00", "\x01", "\x02", "\x03", "\x04", "\x05", "\x06", "\x07", "\x08", "\x09", "\x0b", "\x0c", "\x0e", "\x0f", "\x10", "\x11", "\x12", "\x13", "\x14", "\x15", "\x16", "\x17", "\x18", "\x19", "\x1a", "\x1b", "\x1c", "\x1d", "\x1e", "\x1f"], ' ', $textContent);

        $normalizedContent = preg_replace('/\s+/', ' ', trim($textContent));

        if (preg_match('/scanned by|camscanner|ocr/i', $normalizedContent) === 1) {
            return 'PDF uploaded successfully but its text could not be parsed automatically.';
        }

        $candidateTexts = $this->extractTextCandidatesFromContent($normalizedContent);
        $streamCandidates = $this->extractTextCandidatesFromPdfStreams($rawContent);
        $candidateTexts = array_merge($candidateTexts, $streamCandidates);

        $text = '';
        foreach ($candidateTexts as $candidateText) {
            if ($this->isLikelyReadableText($candidateText)) {
                $text = $candidateText;
                break;
            }
        }

        if ($text === '' && !empty($candidateTexts)) {
            usort($candidateTexts, function ($a, $b) {
                return strlen($b) - strlen($a);
            });
            $text = trim($candidateTexts[0]);
        }

        if (strlen($text) > 3000) {
            return substr($text, 0, 3000) . '...';
        }

        if (empty($text) || strlen($text) < 5) {
            return 'PDF uploaded successfully but its text could not be parsed automatically.';
        }

        return $text;
    }

    private function isLikelyReadableText($text)
    {
        if (empty($text)) {
            return false;
        }

        if (preg_match('/scanned by|camscanner|ocr/i', $text) === 1) {
            return false;
        }

        $nonAsciiMatches = [];
        preg_match_all('/[^\x00-\x7F]/', $text, $nonAsciiMatches);
        $nonAsciiCount = count($nonAsciiMatches[0] ?? []);
        if ($nonAsciiCount > 0 && ($nonAsciiCount / max(1, strlen($text))) >= 0.05) {
            return false;
        }

        $tokens = preg_split('/\s+/', trim($text));
        if (!is_array($tokens) || count($tokens) === 0) {
            return false;
        }

        $wordCount = 0;
        $goodWordCount = 0;
        $suspiciousWordCount = 0;
        foreach ($tokens as $token) {
            $cleanToken = trim($token);
            if ($cleanToken === '') {
                continue;
            }

            $wordCount++;
            if (preg_match('/^[A-Za-z]{3,}$/', $cleanToken) !== 1) {
                $suspiciousWordCount++;
                continue;
            }

            if (preg_match('/[aeiou]/i', $cleanToken) === 1) {
                $goodWordCount++;
            } else {
                $suspiciousWordCount++;
            }
        }

        if ($wordCount < 3) {
            return false;
        }

        if ($suspiciousWordCount / $wordCount >= 0.6) {
            return false;
        }

        return $goodWordCount >= 3 && ($goodWordCount / $wordCount) >= 0.6;
    }

    private function extractTextCandidatesFromContent($content)
    {
        $candidateTexts = [];

        $textMatches = [];
        preg_match_all('/\(([^()]*)\)/', $content, $textMatches);
        if (!empty($textMatches[1])) {
            foreach ($textMatches[1] as $match) {
                $cleanText = trim($match);
                $cleanText = preg_replace('/[^\p{L}\p{N}\s._,:;()\/-]+/u', ' ', $cleanText);
                $cleanText = trim($cleanText);

                if ($cleanText !== '' && preg_match('/\p{L}|\p{N}/u', $cleanText)) {
                    $candidateTexts[] = $cleanText;
                }
            }
        }

        $structureWords = ['pdf', 'type', 'pages', 'catalog', 'stream', 'endobj', 'endstream', 'obj', 'producer', 'length', 'font', 'page', 'kids', 'count', 'r', 'bt', 'et', 'tf', 'td', 'tj', 'tm', 'tr', 'tc', 'tl', 'ts', 'tw', 'tz'];
        $tokens = preg_split('/[^\p{L}\p{N}]+/u', $content);
        if (is_array($tokens)) {
            foreach ($tokens as $token) {
                $cleanToken = trim($token);
                if ($cleanToken === '') {
                    continue;
                }

                $cleanToken = strtolower($cleanToken);
                if (preg_match('/^[a-z]{3,}$/', $cleanToken) !== 1) {
                    continue;
                }

                if (!in_array($cleanToken, $structureWords, true)) {
                    $candidateTexts[] = $token;
                }
            }
        }

        return $candidateTexts;
    }

    private function extractTextCandidatesFromPdfStreams($content)
    {
        $candidateTexts = [];
        $streamMatches = [];
        preg_match_all('/stream\s*(.*?)\s*endstream/s', $content, $streamMatches, PREG_SET_ORDER);

        foreach ($streamMatches as $streamMatch) {
            $rawStream = trim($streamMatch[1] ?? '');
            if ($rawStream === '') {
                continue;
            }

            $decodedCandidates = [$rawStream];

            $rawBase64 = base64_decode($rawStream, true);
            if ($rawBase64 !== false) {
                $decodedCandidates[] = $rawBase64;
            }

            if (function_exists('gzuncompress')) {
                $decoded = @gzuncompress($rawStream);
                if ($decoded !== false && $decoded !== '') {
                    $decodedCandidates[] = $decoded;
                }
            }
            if (function_exists('gzdecode')) {
                $decoded = @gzdecode($rawStream);
                if ($decoded !== false && $decoded !== '') {
                    $decodedCandidates[] = $decoded;
                }
            }

            foreach ($decodedCandidates as $decodedCandidate) {
                $decodedText = preg_replace('/\s+/', ' ', trim($decodedCandidate));
                if ($decodedText === '') {
                    continue;
                }

                $candidateTexts = array_merge($candidateTexts, $this->extractTextCandidatesFromContent($decodedText));

                $literalMatches = [];
                preg_match_all('/\(([^()]*)\)/', $decodedText, $literalMatches);
                foreach ($literalMatches[1] ?? [] as $literalText) {
                    $cleanLiteral = trim($literalText);
                    $cleanLiteral = preg_replace('/[^\p{L}\p{N}\s._,:;()\/-]+/u', ' ', $cleanLiteral);
                    $cleanLiteral = trim($cleanLiteral);
                    if ($cleanLiteral !== '' && preg_match('/\p{L}|\p{N}/u', $cleanLiteral)) {
                        $candidateTexts[] = $cleanLiteral;
                    }
                }
            }
        }

        return $candidateTexts;
    }

    private function formatMonthLabel($monthValue)
    {
        if (empty($monthValue)) {
            return 'Month';
        }

        if (preg_match('/^(\d{4})-(\d{2})$/', $monthValue, $matches)) {
            $monthNumber = (int) $matches[2];
            $monthName = date('M', mktime(0, 0, 0, $monthNumber, 1));
            return $monthName;
        }

        return $monthValue;
    }

    private function formatTopicName($monthLabel, $title)
    {
        if (!empty($title)) {
            return $title . ' - ' . $monthLabel;
        }

        return 'Syllabus - ' . $monthLabel;
    }

    public function viewAllsyllabus(Request $request)
    {
        $user = Auth::user();
        $context = $this->getInstituteBranchContext();

        if (!$context['institute_id']) {
            return back()->with('error', 'You are not associated with any institute.');
        }

        $instituteId = $context['institute_id'];
        $branchId = $context['branch_id'] ?? null;
        $isEmployee = $user->hasRole('Employee') || $user->hasRole('Teacher');

        $baseQuery = Syllabus::query()
            ->from('syllabuses as s')
            ->leftJoin('employee_details as e', 's.employee_id', '=', 'e.employee_id')
            ->leftJoin('subjects_coursewise as sub', 's.subject_id', '=', 'sub.subject_id')
            ->leftJoin('product_details as p', 's.course_detail_id', '=', 'p.product_id')
            ->leftJoin('syllabus_topics as st', 's.syllabus_id', '=', 'st.syllabus_id')
            ->where('s.institute_id', $instituteId);

        if ($branchId) {
            $baseQuery->where('s.branch_id', $branchId);
        }

        if ($isEmployee) {
            $employee = EmployeeDetails::where('user_id', $user->id)
                ->where('institute_id', $instituteId)
                ->first();

            if ($employee) {
                $baseQuery->where('s.employee_id', $employee->employee_id);
            }
        }

        if ($request->filled('department_id')) {
            $baseQuery->where('s.department_id', $request->department_id);
        }

        if ($request->filled('subject')) {
            $baseQuery->where('sub.subject_name', 'like', "%{$request->subject}%");
        }

        if ($request->filled('course')) {
            $baseQuery->where('p.course_type', 'like', "%{$request->course}%");
        }

        if ($request->filled('uploaded_by')) {
            $baseQuery->where('e.name', 'like', "%{$request->uploaded_by}%");
        }

        if ($request->filled('title')) {
            $baseQuery->where('s.title', 'like', "%{$request->title}%");
        }

        $allsyllabuses = (clone $baseQuery)
            ->select(
                's.id',
                's.syllabus_id',
                's.subject_id',
                'e.name as employee_name',
                'p.course_type',
                'p.sub_type',
                'sub.subject_name',
                's.title',
                's.file_name',
                's.file_path',
                's.uploaded_date',
                's.created_at',
                's.term_type',
                's.term_value',
                's.parsed_content',
                'st.topic_id',
                'st.topic_name',
                'st.book_name',
                'st.start_date',
                'st.end_date',
                'st.description',
                'st.subject_id as topic_subject_id',
                'st.branch_id as topic_branch_id',
                'st.institute_id as topic_institute_id',
                'st.created_at as topic_created_at',
                'st.updated_at as topic_updated_at'
            )
            ->distinct('s.syllabus_id')
            ->orderByDesc('s.created_at')
            ->paginate(15)
            ->withQueryString();

        $grouped = $allsyllabuses->getCollection()->groupBy('syllabus_id');

        $finalData = $grouped->map(function ($rows) {
            $first = $rows->first();

            $first->topics = $rows->filter(fn ($r) => $r->topic_id)->map(function ($t) {
                return [
                    'topic_id'     => $t->topic_id,
                    'topic_name'   => $t->topic_name,
                    'book_name'    => $t->book_name,
                    'start_date'   => $t->start_date,
                    'end_date'     => $t->end_date,
                    'description'  => $t->description,
                    'subject_id'   => $t->topic_subject_id,
                    'branch_id'    => $t->topic_branch_id,
                    'institute_id' => $t->topic_institute_id,
                    'created_at'   => $t->topic_created_at,
                    'updated_at'   => $t->topic_updated_at,
                ];
            })->values();

            return $first;
        })->values();

        $allsyllabuses->setCollection($finalData);

        $subjects  = (clone $baseQuery)->distinct()->pluck('sub.subject_name')->filter();
        $courses   = (clone $baseQuery)->distinct()->pluck('p.course_type')->filter();
        $uploaders = (clone $baseQuery)->distinct()->pluck('e.name')->filter();
        $titles    = (clone $baseQuery)->distinct()->pluck('s.title')->filter();
        $departments = Departments::where('institute_id', $instituteId)->get();

        return view('instituteAdmin.Syllabus.ViewAllSyllabus', compact(
            'allsyllabuses',
            'subjects',
            'courses',
            'uploaders',
            'titles',
            'departments'
        ));
    }

    public function show($subjectId)
    {
        $user = Auth::user();
        $context = $this->getInstituteBranchContext();

        if (!$context['institute_id'] || !$subjectId) {
            return back()->with('error', 'Syllabus not found.');
        }

        $instituteId = $context['institute_id'];
        $branchId = $context['branch_id'] ?? null;

        $query = Syllabus::query()
            ->from('syllabuses as s')
            ->leftJoin('subjects_coursewise as sub', 's.subject_id', '=', 'sub.subject_id')
            ->leftJoin('product_details as p', 's.course_detail_id', '=', 'p.product_id')
            ->leftJoin('employee_details as e', 's.employee_id', '=', 'e.employee_id')
            ->where('s.subject_id', $subjectId)
            ->where('s.institute_id', $instituteId);

        if ($branchId) {
            $query->where('s.branch_id', $branchId);
        }

        $syllabusRows = $query->select(
                's.*',
                'sub.subject_name',
                'p.course_type',
                'p.sub_type',
                'e.name as uploaded_by'
            )
            ->orderByDesc('s.created_at')
            ->get();

        if ($syllabusRows->isEmpty()) {
            return back()->with('error', 'Syllabus not found.');
        }

        $subjectName = $syllabusRows->first()->subject_name ?? 'Syllabus';
        $files = $syllabusRows->map(function ($row) {
            return [
                'id' => $row->id,
                'syllabus_id' => $row->syllabus_id,
                'file_name' => $row->file_name,
                'file_path' => $row->file_path,
                'file_url' => $row->file_path ? asset('image/' . $row->file_path) : null,
                'uploaded_date' => $row->uploaded_date,
                'term_type' => $row->term_type,
                'term_value' => $row->term_value,
                'title' => $row->title,
            ];
        });

        $topics = SyllabusTopic::where('subject_id', $subjectId)
            ->where('institute_id', $instituteId)
            ->when($branchId, fn ($query) => $query->where('branch_id', $branchId))
            ->orderBy('start_date')
            ->get();

        return view('instituteAdmin.Syllabus.SyllabusDetail', compact(
            'subjectId',
            'subjectName',
            'files',
            'topics'
        ));
    }

    /**
     * Show edit form to add additional months/topics for a subject
     */
    public function edit($subjectId)
    {
        $user = Auth::user();
        $context = $this->getInstituteBranchContext();

        if (!$context['institute_id'] || !$subjectId) {
            return back()->with('error', 'Syllabus not found.');
        }

        $instituteId = $context['institute_id'];
        $branchId = $context['branch_id'] ?? null;

        $files = Syllabus::where('subject_id', $subjectId)
            ->where('institute_id', $instituteId)
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->orderByDesc('created_at')
            ->get();

        $topics = SyllabusTopic::where('subject_id', $subjectId)
            ->where('institute_id', $instituteId)
            ->when($branchId, fn ($query) => $query->where('branch_id', $branchId))
            ->orderBy('start_date')
            ->get();

        $subjectName = $files->first()->subject_name ?? ($files->first()->title ?? 'Syllabus');

        $courseStartDate = null;
        $courseEndDate = null;
        $courseDetailId = $files->first()->course_detail_id ?? null;

        if ($courseDetailId) {
            $courseDetail = CourseFeeStructure::where('product_id', $courseDetailId)
                ->where('institute_id', $instituteId)
                ->first();

            if ($courseDetail) {
                $courseStartDate = $courseDetail->course_start_date ?? $courseDetail->start_date ?? null;
                $courseEndDate = $courseDetail->course_end_date ?? $courseDetail->end_date ?? null;
            }
        }

        $topicStart = $topics->pluck('start_date')->filter()->min();
        $topicEnd = $topics->pluck('end_date')->filter()->max();

        if (!$courseStartDate && $topicStart) {
            $courseStartDate = $topicStart;
        }

        if (!$courseEndDate && $topicEnd) {
            $courseEndDate = $topicEnd;
        }

        if ($courseStartDate && $courseEndDate) {
            $startCarbon = Carbon::parse($courseStartDate)->startOfMonth();
            $endCarbon = Carbon::parse($courseEndDate)->startOfMonth();
            if ($endCarbon->lt($startCarbon)) {
                [$startCarbon, $endCarbon] = [$endCarbon, $startCarbon];
            }
        } else {
            $startCarbon = Carbon::now()->startOfMonth();
            $endCarbon = Carbon::now()->endOfMonth()->startOfMonth();
        }

        $topicsByMonth = $topics->groupBy(function ($topic) {
            $date = $topic->start_date ?? $topic->end_date;
            return $date ? Carbon::parse($date)->format('Y-m') : null;
        })->filter();

        $topicsByMonthArray = $topicsByMonth->map(function ($group) {
            return $group->map(function ($topic) {
                return [
                    'id' => $topic->id,
                    'topic_id' => $topic->topic_id,
                    'topic_name' => $topic->topic_name,
                    'description' => $topic->description,
                    'start_date' => $topic->start_date,
                    'end_date' => $topic->end_date,
                    'delete_url' => route('instituteAdmin.syllabus.topic.destroy', ['id' => $topic->id]),
                    'created_at' => $topic->created_at,
                    'updated_at' => $topic->updated_at,
                ];
            })->values()->toArray();
        })->toArray();

        if ($topicsByMonth->isNotEmpty()) {
            $topicMonthKeys = $topicsByMonth->keys()->all();
            $topicMinMonth = Carbon::parse($topicsByMonth->keys()->min() . '-01');
            $topicMaxMonth = Carbon::parse($topicsByMonth->keys()->max() . '-01');
            if ($topicMinMonth->lt($startCarbon)) {
                $startCarbon = $topicMinMonth;
            }
            if ($topicMaxMonth->gt($endCarbon)) {
                $endCarbon = $topicMaxMonth;
            }
        }

        $months = [];
        $current = $startCarbon->copy();
        while ($current->lte($endCarbon)) {
            $months[] = $current->format('Y-m');
            $current->addMonth();
        }

        $uploadedMonths = $files->filter(function ($file) {
            return trim(strtolower($file->term_type)) === 'monthly' && !empty($file->term_value);
        })->map(function ($file) {
            $value = trim($file->term_value);
            if (preg_match('/^\d{4}-\d{2}$/', $value)) {
                return $value;
            }
            return Carbon::parse($value)->format('Y-m');
        })->filter()->unique()->values()->all();

        $filesByMonth = $files->filter(function ($file) {
            return trim(strtolower($file->term_type)) === 'monthly' && !empty(trim($file->term_value));
        })->groupBy(function ($file) {
            $value = trim($file->term_value);
            if (preg_match('/^\d{4}-\d{2}$/', $value)) {
                return $value;
            }
            try {
                return Carbon::parse($value)->format('Y-m');
            } catch (\Exception $e) {
                return $value;
            }
        });

        $filesByMonthArray = $filesByMonth->map(function ($group) {
            return $group->map(function ($file) {
                return [
                    'id' => $file->id,
                    'syllabus_id' => $file->syllabus_id,
                    'file_path' => $file->file_path,
                    'file_name' => $file->file_name,
                    'title' => $file->title,
                    'description' => $file->description,
                    'term_value' => $file->term_value,
                    'uploaded_date' => $file->uploaded_date,
                    'download_url' => route('instituteAdmin.syllabus.download', ['id' => $file->id]),
                    'delete_url' => route('instituteAdmin.syllabus.destroy', ['id' => $file->id]),
                ];
            })->values()->all();
        })->toArray();

        $completedMonths = collect($topicsByMonth->keys())->merge($uploadedMonths)->unique()->values()->all();
        $displayContext = $this->resolveSyllabusDisplayContext($files);
        $displayMode = $displayContext['mode'];
        $displayTermValue = $displayContext['term_value'];

        return view('instituteAdmin.Syllabus.SyllabusEdit', compact(
            'subjectId',
            'subjectName',
            'files',
            'topics',
            'months',
            'topicsByMonth',
            'topicsByMonthArray',
            'filesByMonth',
            'filesByMonthArray',
            'completedMonths',
            'courseStartDate',
            'courseEndDate',
            'displayMode',
            'displayTermValue'
        ));
    }

    private function resolveSyllabusDisplayContext($files)
    {
        $normalizedFiles = collect($files);
        $semesterLikeFiles = $normalizedFiles->filter(function ($file) {
            $termType = trim(strtolower((string) ($file->term_type ?? '')));
            return in_array($termType, ['semester', 'yearly'], true);
        });

        if ($semesterLikeFiles->isNotEmpty()) {
            $termValue = trim((string) ($semesterLikeFiles->first()->term_value ?? ''));
            return [
                'mode' => 'semester',
                'term_value' => $termValue !== '' ? $termValue : null,
            ];
        }

        return [
            'mode' => 'monthly',
            'term_value' => null,
        ];
    }

    /**
     * Handle update: insert new topics/months
     */
    public function update(Request $request, $subjectId)
    {
        $user = Auth::user();
        $context = $this->getInstituteBranchContext();

        if (!$context['institute_id'] || !$subjectId) {
            return back()->with('error', 'Invalid request.');
        }

        $instituteId = $context['institute_id'];
        $branchId = $context['branch_id'] ?? null;

        $data = $request->validate([
            'selected_month_value' => 'required|string|regex:/^\d{4}-\d{2}$/',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'parsed_preview_text' => 'nullable|string',
            'topic_action' => 'nullable|string|in:add,edit',
            'topic_id' => 'nullable|string',
            'topic_name' => 'nullable|string|max:255',
            'topic_description' => 'nullable|string',
            'topic_start_date' => 'nullable|date',
            'topic_end_date' => 'nullable|date',
            'topic_file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ]);

        $monthValue = trim($data['selected_month_value']);
        $monthLabel = Carbon::createFromFormat('Y-m', $monthValue)->format('F Y');

        $baseQuery = Syllabus::where('subject_id', $subjectId)
            ->where('institute_id', $instituteId);

        if ($branchId) {
            $baseQuery->where('branch_id', $branchId);
        }

        $baseRecord = $baseQuery->first();
        if (!$baseRecord) {
            return back()->with('error', 'Subject syllabus record not found.');
        }

        $monthlyQuery = Syllabus::where('subject_id', $subjectId)
            ->where('institute_id', $instituteId)
            ->where('term_type', 'monthly')
            ->where(function ($query) use ($monthValue, $monthLabel) {
                $query->where('term_value', $monthValue)
                    ->orWhere('term_value', $monthLabel);
            });

        if ($branchId) {
            $monthlyQuery->where('branch_id', $branchId);
        }

        $monthlySyllabus = $monthlyQuery->first();

        if (!$monthlySyllabus && !$request->hasFile('file')) {
            // If the request is only for topic add/edit and there's no monthly syllabus, return error
            if ($request->filled('topic_action')) {
                return back()->with('error', 'No monthly syllabus exists for this month. Please upload the month syllabus before adding topics.');
            }
            return back()->with('error', 'No existing monthly syllabus found for this month. Upload a file to create one.');
        }

        $filePath = $monthlySyllabus->file_path ?? null;
        $fileName = $monthlySyllabus->file_name ?? null;
        $parsedText = $monthlySyllabus->parsed_content ?? '';

        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            $file = $request->file('file');
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
            $filePath = $file->store('syllabuses', 'public');
            $fileName = $file->getClientOriginalName();

            if (strtolower($file->getClientOriginalExtension()) === 'pdf') {
                $parsedText = $this->extractPdfText($file->getContent());
            }

            $parsedText = $this->resolveParsedContent($request->input('parsed_preview_text'), $parsedText);
        }

        if ($monthlySyllabus) {
            $monthlySyllabus->title = $data['title'] ?? $monthlySyllabus->title;
            $monthlySyllabus->description = $data['description'] ?? $monthlySyllabus->description;
            $monthlySyllabus->file_path = $filePath;
            $monthlySyllabus->file_name = $fileName;
            $monthlySyllabus->parsed_content = $parsedText;
            $monthlySyllabus->save();
        } else {
            $generatedSyllabusId = 'SYL-' . strtoupper(Str::random(8));

            $newSyllabus = new Syllabus();
            $newSyllabus->institute_id = $instituteId;
            $newSyllabus->branch_id = $branchId;
            $newSyllabus->department_id = $baseRecord->department_id;
            $newSyllabus->syllabus_id = $generatedSyllabusId;
            $newSyllabus->employee_id = $baseRecord->employee_id;
            $newSyllabus->course_detail_id = $baseRecord->course_detail_id;
            $newSyllabus->subject_id = $subjectId;
            $newSyllabus->title = $data['title'] ?? $baseRecord->title;
            $newSyllabus->description = $data['description'] ?? $baseRecord->description;
            $newSyllabus->file_path = $filePath;
            $newSyllabus->file_name = $fileName;
            $newSyllabus->uploaded_date = now()->format('Y-m-d');
            $newSyllabus->syllabus_for = $baseRecord->syllabus_for;
            $newSyllabus->parsed_content = $parsedText;
            $newSyllabus->term_type = 'monthly';
            $newSyllabus->term_value = $monthLabel;
            $newSyllabus->save();
            $monthlySyllabus = $newSyllabus;
        }

        // Handle topic name provided on monthly upload without explicit topic action
        if (!$request->filled('topic_action') && !empty($data['topic_name'])) {
            $existingTopic = SyllabusTopic::where('subject_id', $subjectId)
                ->where('institute_id', $instituteId)
                ->where('syllabus_id', $monthlySyllabus->syllabus_id)
                ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
                ->first();

            if ($existingTopic) {
                $existingTopic->topic_name = $data['topic_name'];
                $existingTopic->updated_at = now();
                $existingTopic->save();
            } else {
                $newTopic = new SyllabusTopic();
                $newTopic->topic_id = 'TOPIC-' . strtoupper(Str::random(10));
                $newTopic->institute_id = $instituteId;
                $newTopic->branch_id = $branchId ?? '';
                $newTopic->syllabus_id = $monthlySyllabus->syllabus_id;
                $newTopic->subject_id = $subjectId;
                $newTopic->topic_name = $data['topic_name'];
                $newTopic->description = '';
                $newTopic->start_date = null;
                $newTopic->end_date = null;
                $newTopic->created_at = now();
                $newTopic->updated_at = now();
                $newTopic->save();
            }
        }

        // Handle topic add/edit if requested
        if ($request->filled('topic_action')) {
            $topicAction = $request->input('topic_action');
            $topicName = $request->input('topic_name') ?? $request->input('new_topic_name') ?? $request->input('topic_action_name');
            $topicDesc = $request->input('topic_description') ?? $request->input('new_topic_description');
            $topicStart = $request->input('topic_start_date') ?? $request->input('new_topic_start_date');
            $topicEnd = $request->input('topic_end_date') ?? $request->input('new_topic_end_date');

            if (!$monthlySyllabus) {
                return back()->with('error', 'Monthly syllabus not found to attach topic.');
            }

            if ($topicAction === 'add') {
                $newTopic = new SyllabusTopic();
                $newTopic->topic_id = 'TOPIC-' . strtoupper(Str::random(10));
                $newTopic->institute_id = $instituteId;
                $newTopic->branch_id = $branchId ?? '';
                $newTopic->syllabus_id = $monthlySyllabus->syllabus_id;
                $newTopic->subject_id = $subjectId;
                $newTopic->topic_name = $topicName ?? 'New Topic';
                $newTopic->description = $topicDesc ?? '';
                $newTopic->start_date = $topicStart ?? null;
                $newTopic->end_date = $topicEnd ?? null;
                $newTopic->created_at = now();
                $newTopic->updated_at = now();
                $newTopic->save();

                // handle optional topic file upload - attach as syllabus file
                if ($request->hasFile('topic_file') && $request->file('topic_file')->isValid()) {
                    $f = $request->file('topic_file');
                    $p = $f->store('syllabuses', 'public');
                    \App\Models\SyllabusFile::create([
                        'syllabus_id' => $monthlySyllabus->syllabus_id,
                        'file_path' => $p,
                        'file_name' => $f->getClientOriginalName(),
                    ]);
                }

                return redirect()->route('instituteAdmin.syllabus.edit', ['subjectId' => $subjectId])->with('success', 'Topic added successfully.');
            }

            if ($topicAction === 'edit') {
                $topicId = $request->input('topic_id');
                $topicRecord = SyllabusTopic::where('topic_id', $topicId)
                    ->orWhere('id', $topicId)
                    ->where('institute_id', $instituteId)
                    ->first();

                if (!$topicRecord) {
                    return back()->with('error', 'Topic not found.');
                }

                $topicRecord->topic_name = $topicName ?? $topicRecord->topic_name;
                $topicRecord->description = $topicDesc ?? $topicRecord->description;
                $topicRecord->start_date = $topicStart ?? $topicRecord->start_date;
                $topicRecord->end_date = $topicEnd ?? $topicRecord->end_date;
                $topicRecord->updated_at = now();
                $topicRecord->save();

                if ($request->hasFile('topic_file') && $request->file('topic_file')->isValid()) {
                    $f = $request->file('topic_file');
                    $p = $f->store('syllabuses', 'public');
                    \App\Models\SyllabusFile::create([
                        'syllabus_id' => $monthlySyllabus->syllabus_id,
                        'file_path' => $p,
                        'file_name' => $f->getClientOriginalName(),
                    ]);
                }

                return redirect()->route('instituteAdmin.syllabus.edit', ['subjectId' => $subjectId])->with('success', 'Topic updated successfully.');
            }
        }

        return redirect()->route('instituteAdmin.syllabus.edit', ['subjectId' => $subjectId])
            ->with('success', 'Monthly syllabus updated successfully.');
    }

    /**
     * Return syllabus rows as JSON for client-side rendering
     */
    public function listJson(Request $request)
    {
        $user = Auth::user();
        $context = $this->getInstituteBranchContext();

        if (!$context['institute_id']) {
            return response()->json(['data' => []]);
        }

        $instituteId = $context['institute_id'];
        $branchId = $context['branch_id'] ?? null;
        $isEmployee = $user->hasRole('Employee') || $user->hasRole('Teacher');

        $baseQuery = Syllabus::query()
            ->from('syllabuses as s')
            ->leftJoin('employee_details as e', 's.employee_id', '=', 'e.employee_id')
            ->leftJoin('subjects_coursewise as sub', 's.subject_id', '=', 'sub.subject_id')
            ->leftJoin('product_details as p', 's.course_detail_id', '=', 'p.product_id')
            ->where('s.institute_id', $instituteId);

        if ($branchId) {
            $baseQuery->where('s.branch_id', $branchId);
        }

        if ($isEmployee) {
            $employee = EmployeeDetails::where('user_id', $user->id)
                ->where('institute_id', $instituteId)
                ->first();

            if ($employee) {
                $baseQuery->where('s.employee_id', $employee->employee_id);
            }
        }

        $rows = $baseQuery->leftJoin('syllabus_topics as st', 's.syllabus_id', '=', 'st.syllabus_id')
            ->select(
                's.id',
                's.syllabus_id',
                's.subject_id',
                'e.name as employee_name',
                'p.course_type',
                'p.sub_type',
                'sub.subject_name',
                's.title',
                's.file_name',
                's.file_path',
                's.uploaded_date',
                's.created_at',
                's.term_type',
                's.term_value',
                's.parsed_content',
                'st.topic_id',
                'st.topic_name',
                'st.description',
                'st.start_date',
                'st.end_date'
            )
            ->orderByDesc('s.created_at')
            ->limit(1000)
            ->get();

        // Aggregate by subject so one row represents a subject
        $grouped = $rows->groupBy('subject_id');
        $result = $grouped->map(function ($items, $subjectId) {
            $first = $items->first();
            $monthlyLabels = $items->filter(function ($it) {
                return trim((string)($it->term_type ?? '')) === 'monthly' && trim((string)($it->term_value ?? '')) !== '';
            })->pluck('term_value')->unique()->values()->all();

            $wholeSemester = $items->contains(function ($it) {
                $tt = trim((string)($it->term_type ?? ''));
                return $tt === '' || $tt === 'semester' || $tt === 'yearly';
            });

            $topics = $items->filter(function ($it) {
                return !empty($it->topic_id);
            })->map(function ($it) {
                return [
                    'topic_id' => $it->topic_id,
                    'topic_name' => $it->topic_name,
                    'description' => $it->description,
                    'start_date' => $it->start_date,
                    'end_date' => $it->end_date,
                ];
            })->unique('topic_id')->values()->all();

            return [
                'subject_id' => $subjectId,
                'subject_name' => $first->subject_name ?? 'N/A',
                'course_type' => $first->course_type ?? null,
                'sub_type' => $first->sub_type ?? null,
                'title' => $first->title ?? null,
                'latest_uploaded_date' => $items->max('created_at'),
                'uploader' => $first->employee_name ?? 'Admin',
                'monthly_count' => count($monthlyLabels),
                'monthly_labels' => $monthlyLabels,
                'whole_semester_uploaded' => (bool)$wholeSemester,
                'sample_file_path' => $first->file_path ?? null,
                'sample_file_name' => $first->file_name ?? null,
                'topics' => $topics,
            ];
        })->values()->all();

        return response()->json(['data' => $result]);
    }

    public function filesBySubject($subjectId)
    {
        $user = Auth::user();
        $context = $this->getInstituteBranchContext();

        if (!$context['institute_id'] || !$subjectId) {
            return response()->json(['data' => []]);
        }

        $instituteId = $context['institute_id'];
        $branchId = $context['branch_id'] ?? null;

        $query = Syllabus::where('subject_id', $subjectId)
            ->where('institute_id', $instituteId);

        if (!empty($branchId)) {
            $query->where('branch_id', $branchId);
        }

        $rows = $query->select(
            'id',
            'syllabus_id',
            'file_path',
            'file_name',
            'term_type',
            'term_value',
            'parsed_content',
            'uploaded_date'
        )
        ->orderByDesc('created_at')
        ->get();

        $files = $rows->map(function($r) {
            return [
                'id' => $r->id,
                'syllabus_id' => $r->syllabus_id,
                'file_path' => $r->file_path,
                'file_name' => $r->file_name,
                'term_type' => $r->term_type,
                'term_value' => $r->term_value,
                'parsed_content' => $r->parsed_content,
                'uploaded_date' => $r->uploaded_date,
            ];
        })->values();

        return response()->json(['data' => $files]);
    }
    
    public function download($id)
    {
        $user = Auth::user();
        $instituteId = $user->institute_id;
        
        $syllabus = Syllabus::where('id', $id)
            ->where('institute_id', $instituteId)
            ->firstOrFail();
            
        $filePath = storage_path('app/public/' . $syllabus->file_path);
        
        if (!file_exists($filePath)) {
            return back()->with('error', 'File not found.');
        }
        
        return response()->download($filePath, $syllabus->file_name);
    }
    
    public function destroy($id)
    {
        $user = Auth::user();
        $instituteId = $user->institute_id;
        
        $syllabus = Syllabus::where('id', $id)
            ->where('institute_id', $instituteId)
            ->firstOrFail();
            
        if (Storage::disk('public')->exists($syllabus->file_path)) {
            Storage::disk('public')->delete($syllabus->file_path);
        }
        
        $syllabus->delete();
        
        return back()->with('success', 'Syllabus deleted successfully.');
    }

    public function destroyTopic($id)
    {
        $user = Auth::user();
        $instituteId = $user->institute_id;

        $topic = SyllabusTopic::where('id', $id)->where('institute_id', $instituteId)->firstOrFail();
        $topic->delete();

        return back()->with('success', 'Topic deleted successfully.');
    }

    public function studentView()
    {
        $user = Auth::user();
        
        $student = StudentParentDetails::where('user_id', $user->id)->first();
        
        if (!$student) {
            return back()->with('error', 'Student record not found.');
        }
        
        $academicDetails = DB::table('academic_transport_details')
            ->where('student_hash_id', $student->student_hash_id)
            ->first();
        
        if (!$academicDetails) {
            return back()->with('error', 'Student academic details not found.');
        }
        
        $syllabuses = collect();
        
        if (!empty($academicDetails->course_detail_id)) {
            $syllabuses = DB::table('syllabuses as s')
                ->leftJoin('employee_details as e', 's.employee_id', '=', 'e.employee_id')
                ->leftJoin('subjects_coursewise as sub', 's.subject_id', '=', 'sub.subject_id')
                ->leftJoin('product_details as p', 's.course_detail_id', '=', 'p.product_id')
                ->where('s.course_detail_id', $academicDetails->course_detail_id)
                ->where('s.institute_id', $student->institute_id)
                ->select(
                    's.id',
                    's.syllabus_id',
                    'e.name as uploaded_by',
                    'p.course_type',
                    'p.sub_type',
                    'sub.subject_name',
                    's.title',
                    's.file_name',
                    's.file_path',
                    's.uploaded_date',
                    's.created_at',
                    's.description',
                    's.parsed_content'
                )
                ->orderByDesc('s.created_at')
                ->get();
        }
        
        if ($syllabuses->isEmpty() && $academicDetails->course_type && $academicDetails->course_subtype) {
            $product = DB::table('product_details')
                ->where('course_type', $academicDetails->course_type)
                ->where('sub_type', $academicDetails->course_subtype)
                ->where('institute_id', $student->institute_id)
                ->first();
            
            if ($product) {
                $syllabuses = DB::table('syllabuses as s')
                    ->leftJoin('employee_details as e', 's.employee_id', '=', 'e.employee_id')
                    ->leftJoin('subjects_coursewise as sub', 's.subject_id', '=', 'sub.subject_id')
                    ->leftJoin('product_details as p', 's.course_detail_id', '=', 'p.product_id')
                    ->where('s.course_detail_id', $product->product_id)
                    ->where('s.institute_id', $student->institute_id)
                    ->select(
                        's.id',
                        's.syllabus_id',
                        'e.name as uploaded_by',
                        'p.course_type',
                        'p.sub_type',
                        'sub.subject_name',
                        's.title',
                        's.file_name',
                        's.file_path',
                        's.uploaded_date',
                        's.created_at',
                        's.description',
                        's.parsed_content'
                    )
                    ->orderByDesc('s.created_at')
                    ->get();
            }
        }
        
        if ($syllabuses->isEmpty()) {
            $syllabuses = DB::table('syllabuses as s')
                ->leftJoin('employee_details as e', 's.employee_id', '=', 'e.employee_id')
                ->leftJoin('subjects_coursewise as sub', 's.subject_id', '=', 'sub.subject_id')
                ->leftJoin('product_details as p', 's.course_detail_id', '=', 'p.product_id')
                ->where('s.institute_id', $student->institute_id)
                ->select(
                    's.id',
                    's.syllabus_id',
                    'e.name as uploaded_by',
                    'p.course_type',
                    'p.sub_type',
                    'sub.subject_name',
                    's.title',
                    's.file_name',
                    's.file_path',
                    's.uploaded_date',
                    's.created_at',
                    's.description',
                    's.parsed_content'
                )
                ->orderByDesc('s.created_at')
                ->get();
        }
            
        $viewData = [
            'syllabuses' => $syllabuses,
            'studentDetails' => (object)[
                'course_type' => $academicDetails->course_type,
                'course_subtype' => $academicDetails->course_subtype,
                'student_hash_id' => $student->student_hash_id
            ],
            'student' => $student
        ];
        
        return view('instituteAdmin.Syllabus.index', $viewData);
    }
    
    public function downloadAllSyllabus(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:excel,csv,pdf',
        ]);

        $user = Auth::user();
        $context = $this->getInstituteBranchContext();

        if (!$context['institute_id']) {
            return response()->json(['message' => 'Institute not found'], 403);
        }

        $instituteId = $context['institute_id'];
        $branchId = $context['branch_id'] ?? null;
        $isEmployee = $user->hasRole('Employee') || $user->hasRole('Teacher');

        $baseQuery = DB::table('syllabuses as s')
            ->leftJoin('employee_details as e', 's.employee_id', '=', 'e.employee_id')
            ->leftJoin('subjects_coursewise as sub', 's.subject_id', '=', 'sub.subject_id')
            ->leftJoin('product_details as p', 's.course_detail_id', '=', 'p.product_id')
            ->leftJoin('syllabus_topics as st', 's.syllabus_id', '=', 'st.syllabus_id')
            ->where('s.institute_id', $instituteId);

        if ($branchId) {
            $baseQuery->where('s.branch_id', $branchId);
        }

        if ($isEmployee) {
            $employee = EmployeeDetails::where('user_id', $user->id)
                ->where('institute_id', $instituteId)
                ->first();

            if ($employee) {
                $baseQuery->where('s.employee_id', $employee->employee_id);
            }
        }

        if ($request->filled('department_id')) {
            $baseQuery->where('s.department_id', $request->department_id);
        }

        if ($request->filled('subject')) {
            $baseQuery->where('sub.subject_name', 'like', "%{$request->subject}%");
        }

        if ($request->filled('course')) {
            $baseQuery->where('p.course_type', 'like', "%{$request->course}%");
        }

        if ($request->filled('uploaded_by')) {
            $baseQuery->where('e.name', 'like', "%{$request->uploaded_by}%");
        }

        if ($request->filled('title')) {
            $baseQuery->where('s.title', 'like', "%{$request->title}%");
        }

        $rows = $baseQuery
            ->select(
                's.syllabus_id',
                'e.name as employee_name',
                'p.course_type',
                'p.sub_type',
                'sub.subject_name',
                's.title',
                's.file_name',
                's.uploaded_date',
                's.term_type',
                's.term_value',
                's.parsed_content',
                'st.topic_id',
                'st.topic_name',
                'st.book_name',
                'st.start_date',
                'st.end_date',
                'st.description'
            )
            ->orderByDesc('s.created_at')
            ->get();

        if ($rows->isEmpty()) {
            return response()->json(['message' => 'No syllabus found'], 404);
        }

        $grouped = $rows->groupBy('syllabus_id');

        $finalData = $grouped->map(function ($rows) {
            $first = $rows->first();

            $first->topics = $rows->filter(fn ($r) => $r->topic_id)->map(function ($t) {
                return [
                    'topic_name' => $t->topic_name,
                    'book_name'  => $t->book_name,
                    'start_date' => $t->start_date,
                    'end_date'   => $t->end_date,
                    'description'=> $t->description,
                ];
            })->values();

            return $first;
        })->values();

        switch ($validated['type']) {
            case 'excel':
                return Excel::download(
                    new SyllabusExport($finalData),
                    'syllabus.xlsx'
                );
            case 'csv':
                return Excel::download(
                    new SyllabusExport($finalData),
                    'syllabus.csv'
                );
            case 'pdf':
                $pdf = Pdf::loadView('pdf.syllabus_export', [
                    'syllabuses' => $finalData
                ]);
                return $pdf->download('syllabus.pdf');
            default:
                return response()->json(['message' => 'Invalid type'], 400);
        }
    }

    /**
 * Get course info for a subject (start/end dates, semester)
 */

public function getSubjectCourseInfo(Request $request)
{
    $subjectId = $request->input('subject_id');
    $courseDetailId = $request->input('course_detail_id');
    
    if (!$subjectId) {
        return response()->json(['status' => 'error', 'message' => 'Subject ID required']);
    }
    
    $context = $this->getInstituteBranchContext();
    $instituteId = $context['institute_id'] ?? null;
    
    if (!$instituteId) {
        return response()->json(['status' => 'error', 'message' => 'Institute not found']);
    }
    
    $response = [
        'status' => 'success',
        'semester_id' => null,
        'course_start_date' => null,
        'course_end_date' => null,
    ];
    
    // Get subject info
    $subject = SubjectsCoursewise::where('subject_id', $subjectId)
        ->where('institute_id', $instituteId)
        ->first();
    
    if ($subject) {
        $response['semester_id'] = $subject->semester_id;
    }
    
    // Get course detail info for dates
    if ($courseDetailId) {
        $courseDates = CourseFeeStructure::where('product_id', $courseDetailId)
            ->when(!empty($context['branch_id']), function ($q) use ($context) {
                return $q->where('branch_id', $context['branch_id']);
            })
            ->orderByDesc('id')
            ->first();

        if (!$courseDates) {
            $courseDetail = ProductDetails::where('product_id', $courseDetailId)
                ->where('institute_id', $instituteId)
                ->first();

            $courseDates = CourseFeeStructure::when($courseDetail && $courseDetail->course_type, function ($q) use ($courseDetail) {
                    return $q->where('course_type', $courseDetail->course_type);
                })
                ->when($courseDetail && $courseDetail->sub_type, function ($q) use ($courseDetail) {
                    return $q->where('sub_type', $courseDetail->sub_type);
                })
                ->when(!empty($context['branch_id']), function ($q) use ($context) {
                    return $q->where('branch_id', $context['branch_id']);
                })
                ->orderByDesc('id')
                ->first();
        }

        if ($courseDates) {
            $response['course_start_date'] = !empty($courseDates->course_start_date)
                ? \Carbon\Carbon::parse($courseDates->course_start_date)->toDateString()
                : null;
            $response['course_end_date'] = !empty($courseDates->course_end_date)
                ? \Carbon\Carbon::parse($courseDates->course_end_date)->toDateString()
                : null;
        }
    }
    
    return response()->json($response);
}

public function updateAjax(Request $request, $subjectId)
{
    $user = Auth::user();
    $context = $this->getInstituteBranchContext();

    if (!$context['institute_id'] || !$subjectId) {
        return response()->json(['status' => 'error', 'message' => 'Invalid request.'], 400);
    }

    $instituteId = $context['institute_id'];
    $branchId = $context['branch_id'] ?? null;

    $data = $request->validate([
        'editing_file_id' => 'nullable|integer',
        'topic_id' => 'nullable|string',
        'topic_name' => 'nullable|string|max:255',
        'title' => 'nullable|string|max:255',
        'description' => 'nullable|string',
        'file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        'selected_month_value' => 'nullable|string',
    ]);

    $fileId = $data['editing_file_id'] ?? null;
    if (!$fileId) {
        return response()->json(['status' => 'error', 'message' => 'File ID is required.'], 400);
    }

    $syllabus = Syllabus::where('id', $fileId)
        ->where('subject_id', $subjectId)
        ->where('institute_id', $instituteId)
        ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
        ->first();

    if (!$syllabus) {
        return response()->json(['status' => 'error', 'message' => 'Syllabus record not found.'], 404);
    }

    $filePath = $syllabus->file_path;
    $fileName = $syllabus->file_name;
    $parsedText = $syllabus->parsed_content;

    if ($request->hasFile('file') && $request->file('file')->isValid()) {
        $file = $request->file('file');
        if ($filePath && Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }
        $filePath = $file->store('syllabuses', 'public');
        $fileName = $file->getClientOriginalName();

        if (strtolower($file->getClientOriginalExtension()) === 'pdf') {
            $parsedText = $this->extractPdfText($file->getContent());
        }
        $parsedText = $this->resolveParsedContent($request->input('parsed_preview_text'), $parsedText);
    }

    $syllabus->title = $data['title'] ?? $syllabus->title;
    $syllabus->description = $data['description'] ?? $syllabus->description;
    $syllabus->file_path = $filePath;
    $syllabus->file_name = $fileName;
    $syllabus->parsed_content = $parsedText;
    $syllabus->save();

    if (!empty($data['topic_id'])) {
        $topic = SyllabusTopic::where('subject_id', $subjectId)
            ->where('institute_id', $instituteId)
            ->where(function ($query) use ($data) {
                $query->where('topic_id', $data['topic_id']);
                if (is_numeric($data['topic_id'])) {
                    $query->orWhere('id', $data['topic_id']);
                }
            })
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->first();

        if ($topic) {
            if (!empty($data['topic_name'])) {
                $topic->topic_name = $data['topic_name'];
            }
            if (array_key_exists('description', $data)) {
                $topic->description = $data['description'];
            }
            $topic->updated_at = now();
            $topic->save();
        }
    }

    return response()->json([
        'status' => 'success',
        'message' => 'Syllabus updated successfully.',
        'file_name' => $fileName,
        'title' => $syllabus->title,
        'description' => $syllabus->description,
    ]);
}
}