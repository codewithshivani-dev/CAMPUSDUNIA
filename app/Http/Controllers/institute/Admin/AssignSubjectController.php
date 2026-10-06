<?php
namespace App\Http\Controllers\institute\Admin;
use App\Http\Controllers\Controller;
use App\Models\EmployeeDetails;
use App\Models\ProductDetails;
use App\Models\SubjectsCoursewise;
use App\Models\Departments;
use Illuminate\Support\Facades\Mail;
use App\Models\DepartmentCategory;
use App\Models\AssignSubjectsToEmployee;
use App\Models\EmployeeSubjectLecture;
use App\Models\InstituteBasicDetails;
use App\Models\AddBlock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
 use \App\Traits\SendsInstituteNotifications; 
use App\Exports\EmployeeSubjectAssignmentsExport;


class AssignSubjectController extends Controller
{
     use \App\Traits\InstituteBranchAccess; 
     use SendsInstituteNotifications;
    public function getsubjectsdata()
    {
        $context = $this->getInstituteBranchContext();

        if (!$context['institute_id']) {
            return redirect()->back()->with('error', 'You are not associated with any institute.');
        }

        // Departments
        $departments = $this->getCommonQuery(Departments::class)
            ->select('department_id', 'department')
            ->orderBy('department')
            ->get();

        // Department Categories
        $categories = $this->getCommonQuery(DepartmentCategory::class)
            ->select('department_category_id', 'category_name')
            ->orderBy('category_name')
            ->get();

        // ===============================
        // ASSIGNED SUBJECTS
        // ===============================

        $assignedSubjects = DB::table('assign_subjects_to_employee as ase')
            ->select(
                'ase.id',
                'ase.emp_assign_subject_id',
                'ase.employee_id',
                'ase.subject_id',
                'ase.sub_subject_id',
                'ase.subject_type',
                'ase.subject_display_name',
                'ase.semester_id',
                'ase.section_id',
                'ase.course_detail_id',
                'ase.assigned_date',
                'ase.status',
                'ase.remarks'
            )
            ->where('ase.institute_id', $context['institute_id'])

                ->when($context['is_branch_admin'] && $context['branch_id'], function ($query) use ($context) {
                    return $query->where('ase.branch_id', $context['branch_id']);
                }, function ($query) {
                    return $query->whereNull('ase.branch_id');
                })

                // Employee filter
            ->when(request('employee_name'), function ($query) use ($context) {

                        $query->whereIn('ase.employee_id', function ($subQuery) use ($context) {

                            $subQuery->select('employee_id')
                                ->from('employee_details')
                                ->where('name', 'LIKE', '%' . request('employee_name') . '%')
                                ->where('institute_id', $context['institute_id']);

                            if ($context['is_branch_admin'] && $context['branch_id']) {
                                $subQuery->where('branch_id', $context['branch_id']);
                            } else {
                                $subQuery->whereNull('branch_id');
                            }
                        });

                    })

                // Subject name filter
                ->when(request('subject_name'), function ($query) {
                    return $query->where('ase.subject_display_name', 'LIKE', '%' . request('subject_name') . '%');
                })

                // Subject type filter
                ->when(request('subject_type'), function ($query) {
                    return $query->where('ase.subject_type', request('subject_type'));
                })

            ->orderBy('ase.assigned_date', 'desc')
            ->get();


        // ===============================
        // FINCAP DATA
        // ===============================

        $merchantId = auth()->user()->institute_id;

        $fincapMerchants = InstituteBasicDetails::where('fincap_merchant_id', $merchantId)
            ->with([
                'stakeholders',
                'stakeholderDocuments',
                'authorizedUser',
                'authorizedUserDocuments',
                'documents',
                'beneficiary'
            ])
            ->first();


        // ===============================
        // SECTION DATA
        // ===============================

        $courseIds = DB::table('assign_subjects_to_employee')
            ->where('institute_id', $context['institute_id'])
            ->pluck('course_detail_id')
            ->filter()
            ->unique();

        $sectionDataMap = [];

        if ($courseIds->isNotEmpty()) {

            $courseFeeStructures = DB::table('course_fee_structures as cfs')
                ->whereIn('cfs.product_id', $courseIds)
                ->where('cfs.institute_id', $context['institute_id'])

                ->when($context['is_branch_admin'] && $context['branch_id'], function ($query) use ($context) {
                    return $query->where('cfs.branch_id', $context['branch_id']);
                }, function ($query) {
                    return $query->whereNull('cfs.branch_id');
                })

                ->select('product_id', 'sections')
                ->get();

            foreach ($courseFeeStructures as $courseFee) {

                if (!empty($courseFee->sections)) {

                    $sections = json_decode($courseFee->sections, true);

                    if (is_array($sections)) {

                        foreach ($sections as $section) {

                            if (isset($section['id']) && isset($section['name'])) {

                                $key = $courseFee->product_id . '_' . $section['id'];

                                $sectionDataMap[$key] = [
                                    'section_id' => $section['id'],
                                    'section_name' => $section['name'],
                                    'seats' => $section['seats'] ?? 0
                                ];
                            }
                        }
                    }
                }
            }
        }


        // ===============================
        // PREPARE COLLECTIONS
        // ===============================

        $employees = collect();
        $subjects = collect();
        $subSubjects = collect();

        if ($assignedSubjects->isNotEmpty()) {

            $employeeIds = $assignedSubjects->pluck('employee_id')->unique();
            $subjectIds = $assignedSubjects->pluck('subject_id')->unique();
            $subSubjectIds = $assignedSubjects->pluck('sub_subject_id')->filter()->unique();

            // Employees
            $employees = DB::table('employee_details as e')
                ->whereIn('e.employee_id', $employeeIds)
                ->where('e.institute_id', $context['institute_id'])

                ->when($context['is_branch_admin'] && $context['branch_id'], function ($query) use ($context) {
                    return $query->where('e.branch_id', $context['branch_id']);
                }, function ($query) {
                    return $query->whereNull('e.branch_id');
                })

                ->select('employee_id', 'name')
                ->get()
                ->keyBy('employee_id');

            // Subjects
            $subjects = DB::table('subjects_coursewise as s')
                ->whereIn('s.subject_id', $subjectIds)
                ->where('s.institute_id', $context['institute_id'])

                ->when($context['is_branch_admin'] && $context['branch_id'], function ($query) use ($context) {
                    return $query->where('s.branch_id', $context['branch_id']);
                }, function ($query) {
                    return $query->whereNull('s.branch_id');
                })

                ->select('subject_id', 'subject_name')
                ->get()
                ->keyBy('subject_id');

            // Sub Subjects
            if ($subSubjectIds->isNotEmpty()) {

                $subSubjects = DB::table('sub_subjects as ss')
                    ->whereIn('ss.sub_subject_id', $subSubjectIds)
                    ->where('ss.institute_id', $context['institute_id'])

                    ->when($context['is_branch_admin'] && $context['branch_id'], function ($query) use ($context) {
                        return $query->where('ss.branch_id', $context['branch_id']);
                    }, function ($query) {
                        return $query->whereNull('ss.branch_id');
                    })

                    ->select('sub_subject_id', 'subject_id', 'sub_subject_name')
                    ->get()
                    ->keyBy('sub_subject_id');
            }

            // ===============================
            // MAP DATA
            // ===============================

            $assignedSubjects = $assignedSubjects->map(function ($assign) use ($employees, $subjects, $subSubjects, $sectionDataMap) {

                $assign->name = $employees[$assign->employee_id]->name ?? 'N/A';
                $assign->main_subject_name = $subjects[$assign->subject_id]->subject_name ?? 'N/A';

                $assign->section_name = 'N/A';

                if ($assign->section_id && $assign->course_detail_id) {

                    $key = $assign->course_detail_id . '_' . $assign->section_id;

                    if (isset($sectionDataMap[$key])) {
                        $assign->section_name = $sectionDataMap[$key]['section_name'];
                    } elseif ($assign->section_id === 'all') {
                        $assign->section_name = 'All Sections';
                    }
                }

                if ($assign->subject_type === 'sub_subject' && $assign->sub_subject_id) {

                    $subSubject = $subSubjects[$assign->sub_subject_id] ?? null;

                    if ($subSubject) {

                        $assign->sub_subject_name = $subSubject->sub_subject_name;

                        $assign->display_subject_name =
                            ($subjects[$assign->subject_id]->subject_name ?? 'Unknown')
                            . ' > ' .
                            $subSubject->sub_subject_name;

                    } else {

                        $assign->display_subject_name =
                            $subjects[$assign->subject_id]->subject_name ?? 'N/A';
                    }

                } else {

                    $assign->display_subject_name =
                        $subjects[$assign->subject_id]->subject_name ?? 'N/A';
                }

                return $assign;
            });
        }


        // ===============================
        // SECTION NAME FILTER (IMPORTANT)
        // ===============================

        if (request('section_filter')) {

            $search = strtolower(request('section_filter'));

            $assignedSubjects = $assignedSubjects->filter(function ($assign) use ($search) {

                return isset($assign->section_name)
                    && strpos(strtolower($assign->section_name), $search) !== false;
            });
        }


        // ===============================
        // FILTER LISTS
        // ===============================

        $employeeList = $employees->pluck('name', 'employee_id')->toArray();

        $subjectList = $subjects
            ->pluck('subject_name')
            ->unique()
            ->values()
            ->toArray();

        $sectionList = collect($sectionDataMap)
            ->pluck('section_name')
            ->unique()
            ->values()
            ->toArray();
            

        $page = request()->get('page', 1);
        $perPage = 15;

        $assignedSubjects = new \Illuminate\Pagination\LengthAwarePaginator(
            $assignedSubjects->forPage($page, $perPage),
            $assignedSubjects->count(),
            $perPage,
            $page,
            [
                'path' => request()->url(),
                'query' => request()->query()
            ]
        );
            

        return view(
            'instituteAdmin.EmployeeFiles.AssignSubjectsToEmployee',
            compact(
                'departments',
                'assignedSubjects',
                'categories',
                'fincapMerchants',
                'employeeList',
                'subjectList',
                'sectionList'
            )
        );
    }

    // ✅ Get Employees by Department with institute context
    public function getEmployeesByDepartment($departmentId)
    {
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }

        $employees = EmployeeDetails::where('department_id', $departmentId)
            ->where('institute_id', $context['institute_id'])
            ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            }, function($query) {
                return $query->whereNull('branch_id');
            })
            ->select('employee_id', 'name')
            ->get();

        return response()->json($employees);
    }

    // ✅ Get Course Types by Department with institute context
    public function getCourseTypesByDepartment($departmentId)
    {
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }

        $courseTypes = ProductDetails::where('department_id', $departmentId)
            ->where('institute_id', $context['institute_id'])
            ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            }, function($query) {
                return $query->whereNull('branch_id');
            })
            ->select('course_type', 'finacp_merchant_sub_category_id')
            ->distinct()
            ->get();

        return response()->json($courseTypes);
    }

    // ✅ Get Sub Types by Department + Course Type with institute context
    public function getSubTypesForAssignment(Request $request)
    {
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }

        $departmentId = $request->department_id;
        $courseType = $request->course_type;

        $query = ProductDetails::where('department_id', $departmentId)
            ->where('course_type', $courseType)
            ->where('institute_id', $context['institute_id'])
            ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            }, function($query) {
                return $query->whereNull('branch_id');
            });

        $subTypes = $query->select('product_id', 'sub_type')->get();

        if ($subTypes->isEmpty()) {
        }

        return response()->json($subTypes);
    }

    // ✅ Get Semesters by Sub Type with institute context
    public function getSemestersBySubType($productId)
    {
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }

        $semesters = SubjectsCoursewise::where('course_detail_id', $productId)
            ->where('institute_id', $context['institute_id'])
            ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            }, function($query) {
                return $query->whereNull('branch_id');
            })
            ->select('semester_id')
            ->distinct()
            ->orderByRaw("CASE WHEN semester_id = 'all_semesters' THEN 1 ELSE 2 END, semester_id")
            ->get();

        return response()->json($semesters);
    }

    public function getSubjectsForAssignment(Request $request)
    {
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }

        $subjects = SubjectsCoursewise::where('course_detail_id', $request->product_id)
            ->where('semester_id', $request->semester_id)
            ->where('institute_id', $context['institute_id'])
            ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            }, function($query) {
                return $query->whereNull('branch_id');
            })
            ->select('subject_id', 'subject_name', 'semester_id')
            ->with(['subSubjects' => function($query) use ($context) {
                // Add institute/branch context to sub-subjects query
                $query->where('institute_id', $context['institute_id'])
                    ->when($context['is_branch_admin'] && $context['branch_id'], function($q) use ($context) {
                        return $q->where('branch_id', $context['branch_id']);
                    }, function($q) {
                        return $q->whereNull('branch_id');
                    });
            }])
            ->get()
            ->map(function($subject) {
                return [
                    'subject_id' => $subject->subject_id,
                    'subject_name' => $subject->subject_name,
                    'semester_id' => $subject->semester_id,
                    'has_sub_subjects' => $subject->subSubjects->isNotEmpty(),
                    'sub_subjects' => $subject->subSubjects->map(function($subSubject) {
                        return [
                            'sub_subject_id' => $subSubject->sub_subject_id,
                            'sub_subject_name' => $subSubject->sub_subject_name,
                            'subject_id' => $subSubject->subject_id
                        ];
                    })->toArray()
                ];
            });

        return response()->json($subjects);
    }
    
    // ✅ Get Sections by Product ID from course_fee_structures table
    public function getSectionsByProduct($productId)
    {
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }

        try {
            // Get ALL course fee structures for debugging
            $allFeeStructures = \App\Models\CourseFeeStructure::where('institute_id', $context['institute_id'])
                ->get(['product_id', 'sections']);
            
            // Get course fee structure for the specific product
            $courseFee = \App\Models\CourseFeeStructure::where('product_id', $productId)
                ->where('institute_id', $context['institute_id'])
                ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
                    return $query->where('branch_id', $context['branch_id']);
                }, function($query) {
                    return $query->whereNull('branch_id');
                })
                ->first();

            if ($courseFee) {
                // Try to decode section_data
                if (!empty($courseFee->sections)) {
                    $decoded = json_decode($courseFee->sections, true);
                    if (is_array($decoded)) {
                    }
                }
            }

            if (!$courseFee) {
                return response()->json([
                    'success' => true,
                    'sections' => [],
                    'debug' => [
                        'product_id' => $productId,
                        'institute_id' => $context['institute_id'],
                        'branch_id' => $context['branch_id'] ?? null,
                        'all_structures_count' => $allFeeStructures->count()
                    ]
                ]);
            }

            if (empty($courseFee->sections)) {
                return response()->json([
                    'success' => true,
                    'sections' => [],
                    'debug' => [
                        'section_data_exists' => false
                    ]
                ]);
            }
            // Parse the JSON section data
            $sectionData = json_decode($courseFee->sections, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                // Try alternative: maybe it's stored as serialized array
                $sectionData = @unserialize($courseFee->sections);
            }
            
            if (!is_array($sectionData)) {
                return response()->json([
                    'success' => true,
                    'sections' => [],
                    'debug' => [
                        'section_data_type' => gettype($sectionData),
                        'raw_data' => $courseFee->sections
                    ]
                ]);
            }

            // Format sections for dropdown
            $sections = [];
            foreach ($sectionData as $index => $section) {
                // Handle different possible formats
                if (is_array($section)) {
                    $sections[] = [
                        'section_id' => $section['id'] ?? $section['section_id'] ?? ('section_' . ($index + 1)),
                        'section_name' => $section['name'] ?? $section['section_name'] ?? ('Section ' . chr(65 + $index)), // A, B, C...
                        'seats' => $section['seats'] ?? $section['seats_count'] ?? 0
                    ];
                } elseif (is_string($section)) {
                    // If it's just a string array
                    $sections[] = [
                        'section_id' => 'section_' . ($index + 1),
                        'section_name' => $section,
                        'seats' => 0
                    ];
                }
            }
            return response()->json([
                'success' => true,
                'sections' => $sections,
                'debug' => [
                    'raw_section_data' => $courseFee->sections,
                    'product_id' => $productId
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching sections',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    // ✅ Show assigned subject by ID with institute context
    public function showassignedsubjectsbyid($id)
    {
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }

        // Get assignment with all related data including section name
        $assignment = DB::table('assign_subjects_to_employee as ase')
            ->join('employee_details as e', 'ase.employee_id', '=', 'e.employee_id')
            ->leftJoin('subjects_coursewise as s', 'ase.subject_id', '=', 's.subject_id')
            ->leftJoin('sub_subjects as ss', function($join) {
                $join->on('ase.sub_subject_id', '=', 'ss.sub_subject_id')
                    ->on('ase.subject_id', '=', 'ss.subject_id');
            })
            ->leftJoin('employee_subject_lectures as esl', 'ase.emp_assign_subject_id', '=', 'esl.emp_assign_subject_id')
            ->leftJoin('departments as d', 'ase.department_id', '=', 'd.department_id')
            ->leftJoin('product_details as pd', 'ase.course_detail_id', '=', 'pd.product_id')
            ->leftJoin('course_fee_structures as cfs', 'ase.course_detail_id', '=', 'cfs.product_id')
            ->select(
                // Assignment basic info
                'ase.id',
                'ase.emp_assign_subject_id',
                'ase.employee_id',
                'e.name as employee_name',
                'ase.department_id',
                'd.department as department_name',
                'ase.course_detail_id',
                'pd.course_type',
                'pd.sub_type as branch_name',
                
                // Subject info
                'ase.subject_id',
                'ase.sub_subject_id',
                'ase.subject_type',
                'ase.subject_display_name',
                DB::raw('CASE 
                    WHEN ase.subject_type = "sub_subject" THEN ss.sub_subject_name 
                    ELSE s.subject_name 
                END as display_name'),
                's.subject_name as main_subject_name',
                'ss.sub_subject_name',
                
                // Semester and dates
                'ase.semester_id',
                'ase.section_id',
                'cfs.sections',
                'ase.assigned_date',
                'ase.status',
                'ase.remarks',
                
                // Lecture timing details
                'esl.frequency',
                'esl.start_time',
                'esl.end_time',
                'esl.valid_from',
                'esl.valid_to',
                'esl.days_of_week',
                'esl.day_of_month',
                'esl.location',
                'esl.subject_type as lecture_subject_type',
                
                // Institute info
                'ase.institute_id',
                'ase.branch_id',
                
                // Timestamps
                'ase.created_at',
                'ase.updated_at',
                'esl.created_at as lecture_created_at',
                'esl.updated_at as lecture_updated_at'
            )
            ->where('ase.id', $id)
            ->where('ase.institute_id', $context['institute_id'])
            ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
                return $query->where('ase.branch_id', $context['branch_id']);
            }, function($query) {
                return $query->whereNull('ase.branch_id');
            })
            ->first();

        if (!$assignment) {
            return response()->json([
                'success' => false,
                'message' => 'Assignment not found or you do not have access.'
            ], 404);
        }

        // Extract section name from section_data
        $sectionName = 'N/A';
        if ($assignment->section_id === 'all') {
            $sectionName = 'All Sections';
        } elseif ($assignment->section_id && $assignment->sections) {
            try {
                $sections = json_decode($assignment->$section_data, true);
                if (is_array($sections)) {
                    foreach ($sections as $section) {
                        if (isset($section['id']) && $section['id'] === $assignment->section_id) {
                            $sectionName = $section['name'] ?? 'N/A';
                            break;
                        }
                    }
                }
            } catch (\Exception $e) {
            }
        }
        
        // Add section_name to the assignment object
        $assignment->section_name = $sectionName;

        // Decode days_of_week if it's JSON
        if ($assignment->days_of_week && is_string($assignment->days_of_week)) {
            try {
                $days = json_decode($assignment->days_of_week, true);
                $assignment->days_of_week = is_array($days) ? $days : [];
            } catch (\Exception $e) {
                $assignment->days_of_week = [];
            }
        }

        // Clean up - remove section_data from response
        unset($assignment->sections);

        // Format the response with all data
        $response = [
            'success' => true,
            'data' => $assignment
        ];

        return view(
        'instituteAdmin.EmployeeFiles.ViewAssignedSubject',
        compact('assignment')
     );
    }


    public function storeassignsubject(Request $request)
    {
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return redirect()->back()->with('error', 'You are not associated with any institute.');
        }
        
        // Validate with multiple section IDs
        $request->validate([
            'employee_id'      => ['required'],
            'department_id'    => ['required'],
            'course_detail_id' => ['required'],
            'section_ids'      => ['required', 'array'],
            'section_ids.*'    => ['required', 'string'],
            'section_selection' => ['required', 'in:single,all'],
            'assigned_date'    => ['required', 'date'],
            'remarks'          => ['nullable', 'string'],
        ]);

        // Verify employee belongs to current institute/branch
        $employee = EmployeeDetails::where('employee_id', $request->employee_id)
            ->where('institute_id', $context['institute_id'])
            ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            }, function($query) {
                return $query->whereNull('branch_id');
            })
            ->first();

        if (!$employee) {
            return redirect()->back()->with('error', 'Employee not found or you do not have access.');
        }
         
        // Verify course belongs to current institute/branch and get its mode
        $course = ProductDetails::where('product_id', $request->course_detail_id)
            ->where('institute_id', $context['institute_id'])
            ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            }, function($query) {
                return $query->whereNull('branch_id');
            })
            ->first();

        if (!$course) {
            return redirect()->back()->with('error', 'Course not found or you do not have access.');
        }

        // Determine default lecture mode based on course mode
        // For hybrid, default to offline (admin can change)
        $defaultLectureMode = 'offline';
        if (strtolower($course->mode_of_course ?? 'offline') === 'online') {
            $defaultLectureMode = 'online';
        }

        // Get section IDs from request
        $sectionIds = $request->section_ids;
        
        // If "all" was selected but no sections found, check database
        if ($request->section_selection === 'all' && (empty($sectionIds) || (count($sectionIds) === 1 && $sectionIds[0] === 'all'))) {
            $courseFee = \App\Models\CourseFeeStructure::where('product_id', $request->course_detail_id)
                ->where('institute_id', $context['institute_id'])
                ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
                    return $query->where('branch_id', $context['branch_id']);
                }, function($query) {
                    return $query->whereNull('branch_id');
                })
                ->first();

            if ($courseFee && !empty($courseFee->sections)) {
                $sectionData = json_decode($courseFee->sections, true);
                if (is_array($sectionData)) {
                    $sectionIds = [];
                    foreach ($sectionData as $section) {
                        if (isset($section['id'])) {
                            $sectionIds[] = $section['id'];
                        }
                    }
                }
            }
            
            if (empty($sectionIds)) {
                $sectionIds = ['all'];
            }
        }

        $employeeId = $request->employee_id;
        $departmentId = $request->department_id;
        $courseId   = $request->course_detail_id;
        $assigned   = $request->assigned_date;
        $remarks    = $request->remarks;

        $assignmentsCreated = 0;
        $lecturesCreated = 0;
        $errors = [];
          // Array to collect all lectures for notification
        $allLecturesForNotification = [];
        
        foreach ($request->subjects as $subjectKey => $data) {
            $validated = validator($data, [
                'subject_type'    => ['required', 'in:main_subject,sub_subject'],
                'subject_id'      => ['required'],
                'semester_id'     => ['required'],
                'frequency'       => ['required', Rule::in(['one_time','daily','weekly','monthly'])],
                'start_time'      => ['required', 'date_format:H:i'],
                'end_time'        => ['required', 'date_format:H:i', 'after:start_time'],
                'valid_from'      => ['required', 'date'],
                'valid_to'        => ['nullable', 'date', 'after_or_equal:valid_from'],
                'days_of_week'    => ['nullable', 'array'],
                'day_of_month'    => ['nullable', 'integer', 'min:1', 'max:31'],
                'location'        => ['nullable','string','max:255'],
                'building_id'     => ['nullable', 'integer'],
                'block_id'        => ['nullable', 'integer'],
                'floor_id'        => ['nullable', 'integer'],
                'room_id'         => ['nullable', 'integer'],
                'display_name'    => ['required', 'string'],
                'parent_subject_id' => ['nullable', 'required_if:subject_type,sub_subject'],
                // Add lecture mode validation
                'lecture_mode'    => ['nullable', 'in:offline,online'],
                'meeting_link'    => ['nullable', 'string', 'max:255'],
                'meeting_id'      => ['nullable', 'string', 'max:100'],
                'meeting_password' => ['nullable', 'string', 'max:50'],
                'meeting_instructions' => ['nullable', 'string']
            ])->validate();

            // Determine the actual subject ID to use
            $actualSubjectId = null;
            $subSubjectId = null;
            
            if ($validated['subject_type'] === 'sub_subject') {
                $actualSubjectId = $validated['parent_subject_id'] ?? null;
                $subSubjectId = $validated['subject_id'];
            } else {
                $actualSubjectId = $validated['subject_id'];
                $subSubjectId = null;
            }

            // Verify main subject belongs to current institute/branch
            $subject = SubjectsCoursewise::where('subject_id', $actualSubjectId)
                ->where('institute_id', $context['institute_id'])
                ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
                    return $query->where('branch_id', $context['branch_id']);
                }, function($query) {
                    return $query->whereNull('branch_id');
                })
                ->first();

            if (!$subject) {
                throw new \Exception('Subject not found or you do not have access.');
            }

            // For sub-subjects, verify it exists
            if ($subSubjectId) {
                $subSubject = \App\Models\SubSubject::where('sub_subject_id', $subSubjectId)
                    ->where('subject_id', $actualSubjectId)
                    ->where('institute_id', $context['institute_id'])
                    ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
                        return $query->where('branch_id', $context['branch_id']);
                    }, function($query) {
                        return $query->whereNull('branch_id');
                    })
                    ->first();

                if (!$subSubject) {
                    throw new \Exception('Sub-subject not found or you do not have access.');
                }
            }
            
            // Determine lecture mode (use submitted value or default based on course)
            $lectureMode = $validated['lecture_mode'] ?? $defaultLectureMode;
            $meetingLink = ($lectureMode === 'online' && isset($validated['meeting_link'])) ? $validated['meeting_link'] : null;
            $meetingId = ($lectureMode === 'online' && isset($validated['meeting_id'])) ? $validated['meeting_id'] : null;
            $meetingPassword = ($lectureMode === 'online' && isset($validated['meeting_password'])) ? $validated['meeting_password'] : null;
            $meetingInstructions = ($lectureMode === 'online' && isset($validated['meeting_instructions'])) ? $validated['meeting_instructions'] : null;
            
            // Create assignment for EACH section
            foreach ($sectionIds as $sectionId) {
                // 1) Create unique emp_assign_subject_id for each section
                $empAssignId = 'EMPASG-' . strtoupper(Str::random(8));
                
                // Check if assignment already exists for this combination
                $existingAssignment = AssignSubjectsToEmployee::where([
                    'employee_id'      => $employeeId,
                    'course_detail_id' => $courseId,
                    'subject_id'       => $actualSubjectId,
                    'sub_subject_id'   => $subSubjectId,
                    'semester_id'      => $validated['semester_id'],
                    'section_id'       => $sectionId,
                    'institute_id'     => $context['institute_id'],
                ])->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
                    return $query->where('branch_id', $context['branch_id']);
                }, function($query) {
                    return $query->whereNull('branch_id');
                })->first();

                if ($existingAssignment) {
                    continue;
                }

                // 2) Create assignment row with institute context
                $assignmentData = [
                    'emp_assign_subject_id' => $empAssignId,
                    'employee_id'      => $employeeId,
                    'department_id'    => $departmentId,
                    'course_detail_id' => $courseId,
                    'subject_id'       => $actualSubjectId,
                    'sub_subject_id'   => $subSubjectId,
                    'subject_type'     => $validated['subject_type'],
                    'subject_display_name' => $validated['display_name'],
                    'semester_id'      => $validated['semester_id'],
                    'section_id'       => $sectionId,
                    'assigned_date'    => $assigned,
                    'remarks'          => $remarks ?? null,
                    'institute_id'     => $context['institute_id'],
                    'branch_id'        => $context['is_branch_admin'] ? $context['branch_id'] : null,
                ];
                
                $assignment = AssignSubjectsToEmployee::create($assignmentData);
                $assignmentsCreated++;
                
                // 3) Check clashes (with section-specific checking)
                $this->ensureNoClash($assignment->employee_id, $validated, $context, $sectionId);

                // 4) Generate combined location name and create lecture timing
                $combinedLocation = $this->generateLocationName(
                    $validated['building_id'] ?? null,
                    $validated['block_id'] ?? null,
                    $validated['floor_id'] ?? null,
                    $validated['room_id'] ?? null
                );
                
                $lectureData = [
                    'emp_assign_subject_id' => $assignment->emp_assign_subject_id,
                    'department_id' => $assignment->department_id,
                    'subject_type'  => $validated['subject_type'],
                    'frequency'     => $validated['frequency'],
                    'start_time'    => $validated['start_time'],
                    'end_time'      => $validated['end_time'],
                    'valid_from'    => $validated['valid_from'],
                    'valid_to'      => $validated['valid_to'] ?? null,
                    'days_of_week'  => $validated['frequency'] === 'weekly' ? ($validated['days_of_week'] ?? []) : null,
                    'day_of_month'  => $validated['frequency'] === 'monthly' ? ($validated['day_of_month'] ?? null) : null,
                    'location'      => $combinedLocation,
                    'building_id'   => $validated['building_id'] ?? null,
                    'block_id'      => $validated['block_id'] ?? null,
                    'floor_id'      => $validated['floor_id'] ?? null,
                    'room_id'       => $validated['room_id'] ?? null,
                    'section_id'    => $sectionId,
                    'institute_id'  => $context['institute_id'],
                    'branch_id'     => $context['is_branch_admin'] ? $context['branch_id'] : null,
                    // Add lecture mode fields
                    'lecture_mode'  => $lectureMode,
                    'meeting_link'  => $meetingLink,
                    'meeting_id'    => $meetingId,
                    'meeting_password' => $meetingPassword,
                    'meeting_instructions' => $meetingInstructions
                ];
                
                EmployeeSubjectLecture::create($lectureData);
                $lecturesCreated++;

                // After creating the lecture, store its data for notification
                $lectureDetailsForNotif = [
                    'subject_name' => $subject->subject_name ?? 'N/A',
                    'display_name' => $validated['display_name'],
                    'section_name' => $this->getSectionName($courseId, $sectionId, $context),
                    'semester_id' => $validated['semester_id'],
                    'assigned_date' => date('d-m-Y', strtotime($assigned)),
                    'frequency' => $validated['frequency'],
                    'start_time' => $validated['start_time'],
                    'end_time' => $validated['end_time'],
                    'valid_from' => date('d-m-Y', strtotime($validated['valid_from'])),
                    'valid_to' => $validated['valid_to'] ? date('d-m-Y', strtotime($validated['valid_to'])) : null,
                    'days_of_week' => $validated['days_of_week'] ?? [],
                    'day_of_month' => $validated['day_of_month'] ?? null,
                    'location' => $combinedLocation,
                    'lecture_mode' => $lectureMode,
                    'meeting_link' => $meetingLink,
                    'meeting_id' => $meetingId,
                    'meeting_password' => $meetingPassword,
                    'meeting_instructions' => $meetingInstructions,
                    'course_type' => $course->course_type ?? null,
                    'sub_type' => $course->sub_type ?? null,
                    'course_detail_id' => $courseId,
                ];
                
                // Add to collection (use a unique key to avoid duplicates)
                $uniqueKey = $validated['subject_id'] . '_' . $sectionId . '_' . $validated['semester_id'];
                if (!isset($allLecturesForNotification[$uniqueKey])) {
                    $allLecturesForNotification[$uniqueKey] = $lectureDetailsForNotif;
                }
            }
        }   
        
        // After all assignments are created, send a single notification with all lectures
        if (!empty($allLecturesForNotification)) {
            $this->sendBulkLectureAssignmentNotification(
                $employeeId,
                array_values($allLecturesForNotification), // Re-index array
                $context
            );
        }
       
        if (!empty($errors)) {
            $errorMessage = 'Some assignments failed: ' . implode('; ', $errors);
            return back()->with('warning', $errorMessage);
        }

        $message = 'Assignments created successfully. ';
        if ($request->section_selection === 'all') {
            $message .= 'Created assignments for ' . count($sectionIds) . ' sections. ';
        }
        $message .= "Total: {$assignmentsCreated} assignments and {$lecturesCreated} lecture timings created.";
        return back()->with('success', $message);
    }

    /**
     * Clash detection with institute context
     */
    protected function ensureNoClash(string $employeeId, array $data, array $context, string $sectionId): void
    {
        $query = EmployeeSubjectLecture::query()
            ->where('institute_id', $context['institute_id'])
            ->where('section_id', $sectionId) // Check clashes only within same section
            ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            }, function($query) {
                return $query->whereNull('branch_id');
            })
            ->whereHas('assignment', function ($q) use ($employeeId, $context) {
                $q->where('employee_id', $employeeId)
                ->where('institute_id', $context['institute_id'])
                ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
                    return $query->where('branch_id', $context['branch_id']);
                }, function($query) {
                    return $query->whereNull('branch_id');
                });
            })
            // Date-range intersects existing [valid_from, valid_to]
            ->where(function ($q) use ($data) {
                $vf = $data['valid_from'];
                $vt = $data['valid_to'] ?? null;

                $q->where(function ($q2) use ($vf, $vt) {
                    // Open-ended: treat valid_to as 9999-12-31
                    $q2->where('valid_from', '<=', $vt ?? '9999-12-31')
                    ->where(function ($q3) use ($vf) {
                        $q3->whereNull('valid_to')
                            ->orWhere('valid_to', '>=', $vf);
                    });
                });
            })
            // Time overlaps
            ->where(function ($q) use ($data) {
                $start = $data['start_time'];
                $end   = $data['end_time'];
                $q->where('start_time', '<', $end)
                ->where('end_time', '>', $start);
            });

        $existing = $query->get();

        foreach ($existing as $ex) {
            // Frequency compatibility check
            if ($data['frequency'] === 'daily' || $ex->frequency === 'daily') {
                // Daily collides with anything within date & time window
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'start_time' => ["Time clashes with an existing daily schedule for this employee in section {$sectionId}."],
                ]);
            }

            if ($data['frequency'] === 'weekly' && $ex->frequency === 'weekly') {
                $newDays = collect($data['days_of_week'] ?? []);
                $oldDays = collect($ex->days_of_week ?? []);
                if ($newDays->intersect($oldDays)->isNotEmpty()) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'days_of_week' => ["Weekly time clashes on the same weekday(s) in section {$sectionId}."],
                    ]);
                }
            }

            if ($data['frequency'] === 'monthly' && $ex->frequency === 'monthly') {
                if (($data['day_of_month'] ?? 0) === (int) $ex->day_of_month) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'day_of_month' => ["Monthly time clashes on the same day of month in section {$sectionId}."],
                    ]);
                }
            }
        }
    }
    
    /**
     * Generate combined location name from location IDs
     */
    private function generateLocationName($buildingId, $blockId, $floorId, $roomId)
    {
        $parts = [];
        
        if ($buildingId) {
            $building = \App\Models\AddBuilding::find($buildingId);
            if ($building) {
                $parts[] = $building->name;
            }
        }
        
        if ($blockId) {
            $block = AddBlock::find($blockId);
            if ($block) {
                $parts[] = $block->name;
            }
        }
        
        if ($floorId) {
            $floor = \App\Models\AddFloor::find($floorId);
            if ($floor) {
                $parts[] = $floor->floor_number;
            }
        }
        
        if ($roomId) {
            $room = \App\Models\AddRooms::find($roomId);
            if ($room) {
                $parts[] = $room->room_name;
            }
        }
        
        return implode(' - ', $parts);
    }

    public function EmployeeAssignsubjectForm()
    {
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return redirect()->back()->with('error', 'You are not associated with any institute.');
        }

        // ✅ Get departments for current institute/branch
        $departments = $this->getCommonQuery(Departments::class)
            ->select('department_id', 'department')
            ->orderBy('department')
            ->get();

        // ✅ Get department categories for dropdown
        $categories = $this->getCommonQuery(DepartmentCategory::class)
            ->select('department_category_id', 'category_name')
            ->orderBy('category_name')
            ->get();

        // ✅ Get buildings for location selection
        $buildings = \App\Models\AddBuilding::where('institute_id', $context['institute_id'])
            ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            }, function($query) {
                return $query->whereNull('branch_id');
            })
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        // ✅ Get course modes for all products
        $products = ProductDetails::where('institute_id', $context['institute_id'])
            ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            }, function($query) {
                return $query->whereNull('branch_id');
            })
            ->select('product_id', 'mode_of_course')
            ->get();
        
        $courseModes = [];
        foreach ($products as $product) {
            $mode = strtolower($product->mode_of_course ?? 'offline');
            // For hybrid, default to offline for lecture mode
            $courseModes[$product->product_id] = ($mode === 'online') ? 'online' : 'offline';
        }

        // ✅ Already assigned subjects list with institute/branch context
        $assignedSubjects = DB::table('assign_subjects_to_employee as ase')
            ->select(
                'ase.id',
                'ase.emp_assign_subject_id',
                'ase.employee_id',
                'ase.subject_id',
                'ase.sub_subject_id',
                'ase.subject_type',
                'ase.subject_display_name',
                'ase.semester_id',
                'ase.section_id',
                'ase.course_detail_id',
                'ase.assigned_date',
                'ase.status',
                'ase.remarks'
            )
            ->where('ase.institute_id', $context['institute_id'])
            ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
                return $query->where('ase.branch_id', $context['branch_id']);
            }, function($query) {
                return $query->whereNull('ase.branch_id');
            })
            ->orderBy('ase.assigned_date', 'desc')
            ->get();

        $merchantId = auth()->user()->institute_id;
        $fincapMerchants = InstituteBasicDetails::where('fincap_merchant_id', $merchantId)
            ->with(['stakeholders','stakeholderDocuments','authorizedUser','authorizedUserDocuments', 'documents','beneficiary'])
            ->first();

        // Collect course IDs to fetch section data efficiently
        $courseIds = $assignedSubjects->pluck('course_detail_id')->filter()->unique()->values();
        $sectionDataMap = [];

        if ($courseIds->isNotEmpty()) {
            // Fetch all section data for relevant courses
            $courseFeeStructures = DB::table('course_fee_structures as cfs')
                ->whereIn('cfs.product_id', $courseIds)
                ->where('cfs.institute_id', $context['institute_id'])
                ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
                    return $query->where('cfs.branch_id', $context['branch_id']);
                }, function($query) {
                    return $query->whereNull('cfs.branch_id');
                })
                ->select('product_id', 'sections')
                ->get();

            // Build a map of section_id => section_name for each course
            foreach ($courseFeeStructures as $courseFee) {
                if (!empty($courseFee->sections)) {
                    try {
                        $sections = json_decode($courseFee->sections, true);
                        if (is_array($sections)) {
                            foreach ($sections as $section) {
                                if (isset($section['id']) && isset($section['name'])) {
                                    // Create unique key: product_id + section_id
                                    $key = $courseFee->product_id . '_' . $section['id'];
                                    $sectionDataMap[$key] = [
                                        'section_id' => $section['id'],
                                        'section_name' => $section['name'],
                                        'seats' => $section['seats'] ?? 0
                                    ];
                                }
                            }
                        }
                    } catch (\Exception $e) {
                    }
                }
            }
        }

        // Now let's manually join with other tables
        if ($assignedSubjects->isNotEmpty()) {
            $employeeIds = $assignedSubjects->pluck('employee_id')->unique();
            $subjectIds = $assignedSubjects->pluck('subject_id')->unique();
            $subSubjectIds = $assignedSubjects->pluck('sub_subject_id')->filter()->unique();
            
            // Get employees
            $employees = DB::table('employee_details as e')
                ->whereIn('e.employee_id', $employeeIds)
                ->where('e.institute_id', $context['institute_id'])
                ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
                    return $query->where('e.branch_id', $context['branch_id']);
                }, function($query) {
                    return $query->whereNull('e.branch_id');
                })
                ->select('employee_id', 'name')
                ->get()
                ->keyBy('employee_id');
            
            // Get main subjects
            $subjects = DB::table('subjects_coursewise as s')
                ->whereIn('s.subject_id', $subjectIds)
                ->where('s.institute_id', $context['institute_id'])
                ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
                    return $query->where('s.branch_id', $context['branch_id']);
                }, function($query) {
                    return $query->whereNull('s.branch_id');
                })
                ->select('subject_id', 'subject_name')
                ->get()
                ->keyBy('subject_id');
            
            // Get sub-subjects if any
            $subSubjects = [];
            if ($subSubjectIds->isNotEmpty()) {
                $subSubjects = DB::table('sub_subjects as ss')
                    ->whereIn('ss.sub_subject_id', $subSubjectIds)
                    ->where('ss.institute_id', $context['institute_id'])
                    ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
                        return $query->where('ss.branch_id', $context['branch_id']);
                    }, function($query) {
                        return $query->whereNull('ss.branch_id');
                    })
                    ->select('sub_subject_id', 'subject_id', 'sub_subject_name')
                    ->get()
                    ->keyBy('sub_subject_id');
            }
            
            // Combine the data with section names
            $assignedSubjects = $assignedSubjects->map(function($assign) use ($employees, $subjects, $subSubjects, $sectionDataMap) {
                $assign->name = $employees[$assign->employee_id]->name ?? 'N/A';
                $assign->main_subject_name = $subjects[$assign->subject_id]->subject_name ?? 'N/A';
                
                // Get section name
                $assign->section_name = 'N/A';
                if ($assign->section_id && $assign->course_detail_id) {
                    $key = $assign->course_detail_id . '_' . $assign->section_id;
                    if (isset($sectionDataMap[$key])) {
                        $assign->section_name = $sectionDataMap[$key]['section_name'];
                    } elseif ($assign->section_id === 'all') {
                        $assign->section_name = 'All Sections';
                    }
                }
                
                if ($assign->subject_type === 'sub_subject' && $assign->sub_subject_id) {
                    $subSubject = $subSubjects[$assign->sub_subject_id] ?? null;
                    if ($subSubject) {
                        $assign->sub_subject_name = $subSubject->sub_subject_name;
                        $assign->display_subject_name = 
                            ($subjects[$assign->subject_id]->subject_name ?? 'Unknown') . 
                            ' > ' . 
                            $subSubject->sub_subject_name;
                    } else {
                        $assign->display_subject_name = $assign->subject_display_name ?? 
                            ($subjects[$assign->subject_id]->subject_name ?? 'N/A') . ' > (Sub-subject not found)';
                    }
                } else {
                    $assign->display_subject_name = $subjects[$assign->subject_id]->subject_name ?? 'N/A';
                }
                
                return $assign;
            });
        }

        return view('instituteAdmin.EmployeeFiles.AssignSubjectsEmployeePanel',
            compact('departments', 'assignedSubjects', 'categories', 'fincapMerchants', 'buildings', 'courseModes'));
    }

    public function downloadEmployeeSubjects(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:excel,csv,pdf',
            'ids'  => 'nullable|string',
        ]);

        $context = $this->getInstituteBranchContext();

        if (!$context['institute_id']) {
            abort(403, 'You are not associated with any institute.');
        }

        $query = DB::table('assign_subjects_to_employee as ase')
            ->select(
                'ase.id',
                'ase.emp_assign_subject_id',
                'ase.employee_id',
                'ase.subject_id',
                'ase.sub_subject_id',
                'ase.subject_type',
                'ase.subject_display_name',
                'ase.semester_id',
                'ase.section_id',
                'ase.course_detail_id',
                'ase.assigned_date',
                'ase.status',
                'ase.remarks',
                'e.name as employee_name',
                's.subject_name',
                'ss.sub_subject_name'
            )
            ->leftJoin('employee_details as e', 'ase.employee_id', '=', 'e.employee_id')
            ->leftJoin('subjects_coursewise as s', 'ase.subject_id', '=', 's.subject_id')
            ->leftJoin('sub_subjects as ss', 'ase.sub_subject_id', '=', 'ss.sub_subject_id')
            ->where('ase.institute_id', $context['institute_id'])
            ->when(
                $context['is_branch_admin'] && $context['branch_id'],
                fn ($q) => $q->where('ase.branch_id', $context['branch_id']),
                fn ($q) => $q->whereNull('ase.branch_id')
            )
            ->orderBy('ase.assigned_date', 'desc');

        // ✅ Filter selected rows
        if (!empty($validated['ids'])) {
            $ids = array_filter(explode(',', $validated['ids']));
            $query->whereIn('ase.id', $ids);
        }

        $assignments = $query->get();

        if ($assignments->isEmpty()) {
            return redirect()->back()->with('error', 'No subject assignments found.');
        }

        // ✅ Prepare display subject name
        foreach ($assignments as $row) {
            if ($row->subject_type === 'sub_subject' && $row->sub_subject_name) {
                $row->display_subject =
                    $row->subject_name . ' > ' . $row->sub_subject_name;
            } else {
                $row->display_subject = $row->subject_name;
            }
        }

        switch ($validated['type']) {

            case 'excel':
                return Excel::download(
                    new EmployeeSubjectAssignmentsExport($assignments),
                    'employee_subject_assignments.xlsx'
                );

            case 'csv':
                return Excel::download(
                    new EmployeeSubjectAssignmentsExport($assignments),
                    'employee_subject_assignments.csv',
                    \Maatwebsite\Excel\Excel::CSV
                );

            case 'pdf':
                $pdf = Pdf::loadView(
                    'pdf.employee_subject_assignment_export',
                    ['assignments' => $assignments]
                )->setPaper('A4', 'landscape');

                return $pdf->download('employee_subject_assignments.pdf');
        }
    }

    /**
     * Check if a specific notification channel is enabled for a module
     */
    private function isNotificationEnabled($instituteId, $moduleName, $channel)
    {
        try {
            $setting = \App\Models\InstituteNotificationSetting::where('institute_id', $instituteId)
                ->where('module_name', $moduleName)
                ->first();
            
            if (!$setting) {
                // If no setting found, use default (email enabled by default)
                return $channel === 'email';
            }
            
            switch ($channel) {
                case 'email':
                    return $setting->email_enabled;
                case 'whatsapp':
                    return $setting->whatsapp_enabled;
                case 'sms':
                    return $setting->sms_enabled;
                default:
                    return false;
            }
        } catch (\Exception $e) {
            \Log::error('Error checking notification status: ' . $e->getMessage());
            return $channel === 'email'; // Default to email only on error
        }
    }

    /**
     * Send lecture assignment notification to employee
     */
    private function sendLectureAssignmentNotification($employeeId, $lectureData, $assignmentData, $context)
    {
        try {
            // Get employee details
            $employee = EmployeeDetails::where('employee_id', $employeeId)
                ->where('institute_id', $context['institute_id'])
                ->first();
            
            if (!$employee || empty($employee->email)) {
                return;
            }
            
            // Check if email notification is enabled
            $emailEnabled = $this->isNotificationEnabled(
                $context['institute_id'],
                'subject_lecture_assignment',
                'email'
            );
            
            if (!$emailEnabled) {
                return;
            }
            
            // Get course details to include course_type and sub_type
            $courseDetails = null;
            if (isset($assignmentData['course_detail_id'])) {
                $courseDetails = ProductDetails::where('product_id', $assignmentData['course_detail_id'])
                    ->where('institute_id', $context['institute_id'])
                    ->first();
            }
            
            // Prepare lecture details for email
            $lectureDetails = [
                'subject_name' => $assignmentData['subject_name'] ?? 'N/A',
                'display_name' => $assignmentData['display_name'] ?? $assignmentData['subject_name'] ?? 'N/A',
                'section_name' => $assignmentData['section_name'] ?? 'N/A',
                'semester_id' => $assignmentData['semester_id'] ?? 'N/A',
                'assigned_date' => date('d-m-Y', strtotime($assignmentData['assigned_date'])),
                'frequency' => $lectureData['frequency'],
                'start_time' => $lectureData['start_time'],
                'end_time' => $lectureData['end_time'],
                'valid_from' => date('d-m-Y', strtotime($lectureData['valid_from'])),
                'valid_to' => $lectureData['valid_to'] ? date('d-m-Y', strtotime($lectureData['valid_to'])) : null,
                'days_of_week' => $lectureData['days_of_week'] ?? [],
                'day_of_month' => $lectureData['day_of_month'] ?? null,
                'location' => $lectureData['location'] ?? null,
                'lecture_mode' => $lectureData['lecture_mode'] ?? 'offline',
                'meeting_link' => $lectureData['meeting_link'] ?? null,
                'meeting_password' => $lectureData['meeting_password'] ?? null,
                'meeting_instructions' => $lectureData['meeting_instructions'] ?? null,
                // Add course type and sub type
                'course_type' => $courseDetails ? $courseDetails->course_type : null,
                'sub_type' => $courseDetails ? $courseDetails->sub_type : null,
                'course_detail_id' => $assignmentData['course_detail_id'] ?? null,
            ];
            
            // Send Email (collect all lectures before sending)
            // Since this method is called for each section, we need to collect all lectures for this employee
            // We'll use a static cache to collect lectures and send one email with all assignments
            
            static $lecturesToSend = [];
            static $employeeDetails = null;
            static $contextDetails = null;
            
            // Store context for later use
            if (!$contextDetails) {
                $contextDetails = $context;
            }
            
            // Store employee details
            if (!$employeeDetails) {
                $employeeDetails = $employee;
            }
            
            // Add this lecture to the collection
            $lectureKey = md5(json_encode($lectureDetails));
            if (!isset($lecturesToSend[$lectureKey])) {
                $lecturesToSend[$lectureKey] = $lectureDetails;
            }
            
            // We'll send the email at the end of the request using a register shutdown function
            // But for now, let's check if this is the last lecture being processed
            // This is a workaround - better approach would be to collect all lectures in the main loop
            
        } catch (\Exception $e) {
            \Log::error('Error sending lecture assignment notification: ' . $e->getMessage());
        }
    }
    
    /**
     * Send bulk lecture assignment notification (call this after all assignments are created)
     */
    private function sendBulkLectureAssignmentNotification($employeeId, $lecturesData, $context)
    {
        try {
            // Get employee details
            $employee = EmployeeDetails::where('employee_id', $employeeId)
                ->where('institute_id', $context['institute_id'])
                ->first();
            
            if (!$employee || empty($employee->email)) {
                \Log::info('Employee not found or no email for notification', ['employee_id' => $employeeId]);
                return;
            }
            
            // Check if email notification is enabled
            $emailEnabled = $this->isNotificationEnabled(
                $context['institute_id'],
                'subject_lecture_assignment',
                'email'
            );
            
            if (!$emailEnabled) {
                \Log::info('Email notifications disabled for subject_lecture_assignment', ['institute_id' => $context['institute_id']]);
                return;
            }
            
            // Check if we have any lectures to send
            if (empty($lecturesData)) {
                \Log::info('No lectures data to send notification', ['employee_id' => $employeeId]);
                return;
            }
            
            // Prepare all lectures for email
            $lecturesForEmail = [];
            foreach ($lecturesData as $lecture) {
                // Ensure all required fields are present with defaults
                $lecturesForEmail[] = [
                    'subject_name' => $lecture['subject_name'] ?? 'N/A',
                    'display_name' => $lecture['display_name'] ?? $lecture['subject_name'] ?? 'N/A',
                    'section_name' => $lecture['section_name'] ?? 'N/A',
                    'semester_id' => $lecture['semester_id'] ?? 'N/A',
                    'assigned_date' => $lecture['assigned_date'] ?? date('d-m-Y'),
                    'frequency' => $lecture['frequency'] ?? 'one_time',
                    'start_time' => $lecture['start_time'] ?? '00:00',
                    'end_time' => $lecture['end_time'] ?? '00:00',
                    'valid_from' => $lecture['valid_from'] ?? date('d-m-Y'),
                    'valid_to' => $lecture['valid_to'] ?? null,
                    'days_of_week' => $lecture['days_of_week'] ?? [],
                    'day_of_month' => $lecture['day_of_month'] ?? null,
                    'location' => $lecture['location'] ?? null,
                    'lecture_mode' => $lecture['lecture_mode'] ?? 'offline',
                    'meeting_link' => $lecture['meeting_link'] ?? null,
                    'meeting_password' => $lecture['meeting_password'] ?? null,
                    'meeting_instructions' => $lecture['meeting_instructions'] ?? null,
                    'course_type' => $lecture['course_type'] ?? null,
                    'sub_type' => $lecture['sub_type'] ?? null,
                    'course_detail_id' => $lecture['course_detail_id'] ?? null,
                ];
            }
            
            // Send single email with all lectures
            Mail::send('emails.lecture-assigned', [
                'employeeName' => $employee->name,
                'lectures' => $lecturesForEmail,
                'lecturesCount' => count($lecturesForEmail),
                'isMultiple' => count($lecturesForEmail) > 1
            ], function ($message) use ($employee, $lecturesForEmail) {
                $subject = count($lecturesForEmail) . ' New Lecture Assignment' . (count($lecturesForEmail) > 1 ? 's' : '') . ' - ' . config('app.name');
                $message->to($employee->email)
                        ->subject($subject);
            });
            
            \Log::info('Bulk lecture assignment email sent successfully', [
                'employee_email' => $employee->email,
                'lectures_count' => count($lecturesForEmail),
                'institute_id' => $context['institute_id']
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Failed to send bulk lecture assignment email: ' . $e->getMessage(), [
                'employee_id' => $employeeId,
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
    
    /**
     * Get section name by product ID and section ID
     */
    private function getSectionName($productId, $sectionId, $context)
    {
        if ($sectionId === 'all') {
            return 'All Sections';
        }
        
        try {
            $courseFee = \App\Models\CourseFeeStructure::where('product_id', $productId)
                ->where('institute_id', $context['institute_id'])
                ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
                    return $query->where('branch_id', $context['branch_id']);
                }, function($query) {
                    return $query->whereNull('branch_id');
                })
                ->first();
            
            if ($courseFee && !empty($courseFee->sections)) {
                $sections = json_decode($courseFee->sections, true);
                if (is_array($sections)) {
                    foreach ($sections as $section) {
                        if (isset($section['id']) && $section['id'] == $sectionId) {
                            return $section['name'] ?? 'N/A';
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::error('Error getting section name: ' . $e->getMessage());
        }
        
        return 'N/A';
    }
}