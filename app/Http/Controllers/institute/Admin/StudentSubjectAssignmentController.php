<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use App\Traits\InstituteBranchAccess;
use App\Models\StudentSubjectAssignment;
use App\Models\SubjectsCoursewise;
use App\Models\SubSubject; // Add this
use App\Models\StudentParentDetails;
use App\Models\ProductDetails;
use App\Models\Departments;
use App\Models\InstituteBasicDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\StudentAssignmentsExport;

class StudentSubjectAssignmentController extends Controller
{
    use InstituteBranchAccess;
    
    // Add this method to get fincap merchants
    protected function getFincapMerchants()
    {
        $merchantId = auth()->user()->institute_id;
        $fincapMerchants = InstituteBasicDetails::where('fincap_merchant_id', $merchantId)
            ->with(['stakeholders','stakeholderDocuments','authorizedUser','authorizedUserDocuments', 'documents','beneficiary'])
            ->first();
            
        return $fincapMerchants;
    }
    
    // Get subjects by course for AJAX
    public function getSubjectsByCourse(Request $request)
    {
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }

        $request->validate([
            'course_detail_id' => 'required'
        ]);

        $subjects = SubjectsCoursewise::select(
                'subjects_coursewise.id',
                'subjects_coursewise.subject_id',
                'subjects_coursewise.subject_name',
                'subjects_coursewise.semester_id',
                'subjects_coursewise.assigned_date'
            )
            ->where('subjects_coursewise.course_detail_id', $request->course_detail_id)
            ->where('subjects_coursewise.institute_id', $context['institute_id'])
            ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
                return $query->where('subjects_coursewise.branch_id', $context['branch_id']);
            }, function($query) {
                return $query->whereNull('subjects_coursewise.branch_id');
            })
            ->where('subjects_coursewise.status', 'Active')
            ->with(['subSubjects' => function($query) {
                $query->select('id', 'sub_subject_id', 'sub_subject_name', 'subject_id')
                    ->where('status', 'active');
            }])
            ->get();

        return response()->json([
            'success' => true,
            'subjects' => $subjects
        ]);
    }


    public function store(Request $request)
{
    $context = $this->getInstituteBranchContext();

    if (!$context['institute_id']) {
        return response()->json([
            'success' => false,
            'message' => 'You are not associated with any institute.'
        ], 403);
    }

    $request->validate([
        'department_id' => 'nullable',
        'course_detail_id' => 'nullable',
        'student_ids' => 'nullable',
        'student_ids.*' => 'nullable',
        'subject_ids' => 'nullable|array',
        'subject_ids.*' => 'required',
        'sub_subject_ids' => 'nullable',
        'sub_subject_ids.*' => 'nullable',
        'assigned_date' => 'nullable|date',
        'remarks' => 'nullable|string|max:255',
    ]);

    if (empty($request->subject_ids) && empty($request->sub_subject_ids)) {
        return response()->json([
            'success' => false,
            'message' => 'Please select at least one subject or sub-subject.'
        ], 422);
    }

    // 🔥 FIX #1 — fetch sub-subjects correctly (branch_id NULL allowed for admin)
    if (!empty($request->sub_subject_ids)) {

        $subSubjects = \App\Models\SubSubject::whereIn('sub_subject_id', $request->sub_subject_ids)
            ->where('institute_id', $context['institute_id'])
            ->when($context['is_branch_admin'], function ($query) use ($context) {
                return $query->where(function ($q) use ($context) {
                    $q->where('branch_id', $context['branch_id'])
                      ->orWhereNull('branch_id');   // 🔥 Fix: allow NULL for branch admin also
                });
            }, function ($query) {
                return $query->whereNull('branch_id'); // institute admin sees GLOBAL records only
            })
            ->get(['sub_subject_id', 'subject_id']);

        $parentSubjectIdsFromSubSubjects = $subSubjects->pluck('subject_id')->unique()->toArray();

        $existingSubjectIds = $request->subject_ids ?? [];
        $allSubjectIds = array_unique(array_merge($existingSubjectIds, $parentSubjectIdsFromSubSubjects));
        $request->merge(['subject_ids' => $allSubjectIds]);
    }

    // Validate department access
    $department = $this->getCommonQuery(Departments::class)
        ->where('department_id', $request->department_id)
        ->first();

    if (!$department) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid department selected or you do not have access.'
        ], 403);
    }

    // Validate product access
    $product = $this->getCommonQuery(ProductDetails::class)
        ->where('product_id', $request->course_detail_id)
        ->first();

    if (!$product) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid course selected or you do not have access.'
        ], 403);
    }

    // Validate students
    $students = StudentParentDetails::whereIn('student_hash_id', $request->student_ids)
        ->where('institute_id', $context['institute_id'])
        ->when($context['is_branch_admin'], function ($query) use ($context) {
            return $query->where(function ($q) use ($context) {
                $q->where('branch_id', $context['branch_id'])
                  ->orWhereNull('branch_id');     // 🔥 FIX: allow NULL for branch admin
            });
        }, function ($query) {
            return $query->whereNull('branch_id');
        })
        ->get();

    if ($students->count() !== count($request->student_ids)) {
        return response()->json([
            'success' => false,
            'message' => 'Some selected students are invalid or you do not have access.'
        ], 403);
    }

    $successCount = 0;
    $failedAssignments = [];

    // ============================
    // MAIN SUBJECT ASSIGNMENT
    // ============================
    if (!empty($request->subject_ids)) {

        $subjects = SubjectsCoursewise::whereIn('subject_id', $request->subject_ids)
            ->where('course_detail_id', $request->course_detail_id)
            ->where('institute_id', $context['institute_id'])
            ->when($context['is_branch_admin'], function ($query) use ($context) {
                return $query->where(function ($q) use ($context) {
                    $q->where('branch_id', $context['branch_id'])
                      ->orWhereNull('branch_id');   // 🔥 FIX
                });
            }, function ($query) {
                return $query->whereNull('branch_id');
            })
            ->get();

        \Log::info('Found subjects:', $subjects->pluck('subject_id')->toArray());

        // Assign subjects
        foreach ($request->student_ids as $studentId) {
            foreach ($subjects as $subject) {

                $exists = StudentSubjectAssignment::where([
                        ['student_hash_id', '=', $studentId],
                        ['subject_id', '=', $subject->subject_id],
                        ['sub_subject_id', '=', null],
                        ['institute_id', '=', $context['institute_id']]
                    ])
                    ->when($context['is_branch_admin'], function ($query) use ($context) {
                        return $query->where(function ($q) use ($context) {
                            $q->where('branch_id', $context['branch_id'])
                              ->orWhereNull('branch_id'); // 🔥 FIX
                        });
                    })
                    ->first();

                if (!$exists) {
                    $data = $this->createWithInstituteBranchContext([
                        'student_hash_id' => $studentId,
                        'subject_id' => $subject->subject_id,
                        'sub_subject_id' => null,
                        'course_detail_id' => $request->course_detail_id,
                        'department_id' => $request->department_id,
                        'assigned_date' => $request->assigned_date,
                        'remarks' => $request->remarks,
                        'status' => 'Active',
                        'subject_type' => 'main_subject',
                    ]);

                    StudentSubjectAssignment::create($data);
                    $successCount++;
                }
            }
        }
    }

    // ============================
    // SUB-SUBJECT ASSIGNMENT
    // ============================
    if (!empty($request->sub_subject_ids)) {

        // 🔥 FIX #2: correctly fetch sub-subjects with NULL branch OR branch admin match
        $subSubjects = \App\Models\SubSubject::whereIn('sub_subject_id', $request->sub_subject_ids)
            ->where('institute_id', $context['institute_id'])
            ->when($context['is_branch_admin'], function ($query) use ($context) {
                return $query->where(function ($q) use ($context) {
                    $q->where('branch_id', $context['branch_id'])
                      ->orWhereNull('branch_id');   // 🔥 CRITICAL FIX
                });
            }, function ($query) {
                return $query->whereNull('branch_id');
            })
            ->with('subject')
            ->get();

        foreach ($request->student_ids as $studentId) {
            foreach ($subSubjects as $subSubject) {

                if (!$subSubject->subject) {
                    continue;
                }

                $exists = StudentSubjectAssignment::where([
                        ['student_hash_id', '=', $studentId],
                        ['subject_id', '=', $subSubject->subject->subject_id],
                        ['sub_subject_id', '=', $subSubject->sub_subject_id],
                        ['institute_id', '=', $context['institute_id']]
                    ])
                    ->when($context['is_branch_admin'], function ($query) use ($context) {
                        return $query->where(function ($q) use ($context) {
                            $q->where('branch_id', $context['branch_id'])
                              ->orWhereNull('branch_id'); // 🔥 FIX
                        });
                    })
                    ->first();

                if (!$exists) {
                    $data = $this->createWithInstituteBranchContext([
                        'student_hash_id' => $studentId,
                        'subject_id' => $subSubject->subject->subject_id,
                        'sub_subject_id' => $subSubject->sub_subject_id,
                        'course_detail_id' => $request->course_detail_id,
                        'department_id' => $request->department_id,
                        'assigned_date' => $request->assigned_date,
                        'remarks' => $request->remarks,
                        'status' => 'Active',
                        'subject_type' => 'sub_subject',
                    ]);

                    StudentSubjectAssignment::create($data);
                    $successCount++;
                }
            }
        }
    }

    return response()->json([
        'success' => true,
        'message' => "$successCount assignments created successfully."
    ]);
}

      // Get student subject details
    public function show($studentId)
    {
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }

        // Get student
        $student = StudentParentDetails::where('student_hash_id', $studentId)
            ->where('institute_id', $context['institute_id'])
            ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            }, function($query) {
                return $query->whereNull('branch_id');
            })
            ->first();

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found or you do not have access.'
            ], 404);
        }

        // Get assignments with subject details
        $assignments = StudentSubjectAssignment::where('student_hash_id', $studentId)
            ->where('institute_id', $context['institute_id'])
            ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            }, function($query) {
                return $query->whereNull('branch_id');
            })
            ->with(['subject' => function($query) {
                $query->select('id', 'subject_id', 'subject_name', 'semester_id', 'assigned_date');
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        // Get course details
        $course = ProductDetails::select('course_type', 'sub_type')
            ->where('product_id', $assignments->first()->course_detail_id ?? null)
            ->first();

        // Format response
        $mainSubjects = [];
        $subSubjects = [];
        
        foreach ($assignments as $assignment) {
            if ($assignment->subject_type === 'sub_subject' && $assignment->sub_subject_id) {
                // Get sub-subject details
                $subSub = \App\Models\SubSubject::where('sub_subject_id', $assignment->sub_subject_id)
                    ->where('subject_id', $assignment->subject_id)
                    ->first();
                    
                if ($subSub) {
                    $subSubjects[] = [
                        'subject_id' => $assignment->subject_id,
                        'subject_name' => $assignment->subject->subject_name ?? 'N/A',
                        'sub_subject_id' => $subSub->sub_subject_id,
                        'sub_subject_name' => $subSub->sub_subject_name,
                        'assigned_date' => $assignment->assigned_date,
                    ];
                }
            } else {
                // Main subject
                $subject = $assignment->subject;
                if ($subject) {
                    $mainSubjects[] = [
                        'subject_id' => $subject->subject_id,
                        'subject_name' => $subject->subject_name,
                        'semester_id' => $subject->semester_id,
                        'assigned_date' => $subject->assigned_date,
                    ];
                }
            }
        }

   return view(
        'instituteAdmin.CourseFiles.ViewAssignSubjectsToStudents',
        compact(
            'student',
            'course',
            'mainSubjects',
            'subSubjects',
            'assignments'
        )
    );
}

    // Index method for view
public function index(Request $request)
    {
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            abort(403, 'You are not associated with any institute.');
        }

        // Get distinct student assignments
        $distinctStudents = StudentSubjectAssignment::select('student_hash_id')
            ->where('institute_id', $context['institute_id'])

            // Student ID filter
            ->when($request->student_id, function ($query) use ($request) {
                return $query->where('student_hash_id', $request->student_id);
            })

            ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            }, function($query) {
                return $query->whereNull('branch_id');
            })
            ->distinct()
            ->pluck('student_hash_id');

        $assignmentDetails = [];

        foreach ($distinctStudents as $studentHashId) {

            // Get latest assignment for this student
            $latestAssignment = StudentSubjectAssignment::select(
                    'student_subject_assignments.*',
                    'student_parent_details.first_name',
                    'student_parent_details.last_name',
                    'student_parent_details.student_hash_id',
                    'student_parent_details.registration_number',
                    DB::raw('CONCAT(student_parent_details.first_name, " ", student_parent_details.last_name) as student_name'),
                    'product_details.course_type',
                    'product_details.sub_type'
                )
                ->join('student_parent_details', 'student_subject_assignments.student_hash_id', '=', 'student_parent_details.student_hash_id')
                ->join('product_details', 'student_subject_assignments.course_detail_id', '=', 'product_details.product_id')

                ->where('student_subject_assignments.student_hash_id', $studentHashId)
                ->where('student_subject_assignments.institute_id', $context['institute_id'])

                // Student Name filter
                ->when($request->student_name, function ($query) use ($request) {
                    return $query->where(DB::raw("CONCAT(student_parent_details.first_name,' ',student_parent_details.last_name)"), 'like', '%' . $request->student_name . '%');
                })

                // Course Type filter
                ->when($request->course_type, function ($query) use ($request) {
                    return $query->where('product_details.course_type', $request->course_type);
                })

                // Sub Type filter
                ->when($request->sub_type, function ($query) use ($request) {
                    return $query->where('product_details.sub_type', $request->sub_type);
                })

                // Status filter
                ->when($request->status, function ($query) use ($request) {
                    return $query->where('student_subject_assignments.status', $request->status);
                })

                ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
                    return $query->where('student_subject_assignments.branch_id', $context['branch_id']);
                }, function($query) {
                    return $query->whereNull('student_subject_assignments.branch_id');
                })

                ->orderBy('student_subject_assignments.created_at', 'desc')
                ->first();

            if ($latestAssignment) {

                // Count total subjects for this student
                $subjectCount = StudentSubjectAssignment::where('student_hash_id', $studentHashId)
                    ->where('institute_id', $context['institute_id'])

                    ->when($request->status, function ($query) use ($request) {
                        return $query->where('status', $request->status);
                    })

                    ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
                        return $query->where('branch_id', $context['branch_id']);
                    }, function($query) {
                        return $query->whereNull('branch_id');
                    })
                    ->count();

                $latestAssignment->subject_count = $subjectCount;

                $assignmentDetails[] = $latestAssignment;
            }
        }

        // Get categories for dropdown
        $categories = $this->getCommonQuery(\App\Models\DepartmentCategory::class)->get();

        // Get fincap merchants
        $fincapMerchants = $this->getFincapMerchants();

        // For datalist dropdowns
        $students = \App\Models\StudentParentDetails::select(
                'student_parent_details.student_hash_id',
                'student_parent_details.registration_number',
                DB::raw("CONCAT(student_parent_details.first_name,' ',student_parent_details.last_name) as student_name")
            )
            ->join('student_subject_assignments', 'student_parent_details.student_hash_id', '=', 'student_subject_assignments.student_hash_id')
            ->where('student_subject_assignments.institute_id', $context['institute_id'])

            ->when($context['is_branch_admin'] && $context['branch_id'], function ($query) use ($context) {
                return $query->where('student_subject_assignments.branch_id', $context['branch_id']);
            }, function ($query) {
                return $query->whereNull('student_subject_assignments.branch_id');
            })

            ->distinct()
            ->get();

        $courseTypes = \App\Models\ProductDetails::select('product_details.course_type')
            ->join('student_subject_assignments', 'product_details.product_id', '=', 'student_subject_assignments.course_detail_id')
            ->where('student_subject_assignments.institute_id', $context['institute_id'])

            ->when($context['is_branch_admin'] && $context['branch_id'], function ($query) use ($context) {
                return $query->where('student_subject_assignments.branch_id', $context['branch_id']);
            }, function ($query) {
                return $query->whereNull('student_subject_assignments.branch_id');
            })

            ->distinct()
            ->pluck('course_type');

        $subTypes = \App\Models\ProductDetails::select('product_details.sub_type')
            ->join('student_subject_assignments', 'product_details.product_id', '=', 'student_subject_assignments.course_detail_id')
            ->where('student_subject_assignments.institute_id', $context['institute_id'])

            ->when($context['is_branch_admin'] && $context['branch_id'], function ($query) use ($context) {
                return $query->where('student_subject_assignments.branch_id', $context['branch_id']);
            }, function ($query) {
                return $query->whereNull('student_subject_assignments.branch_id');
            })

            ->distinct()
            ->pluck('sub_type');

        $statuses = ['active','inactive'];

        $page = $request->get('page', 1);
        $perPage = 15;

        $assignments = new \Illuminate\Pagination\LengthAwarePaginator(
            collect($assignmentDetails)->forPage($page, $perPage),
            count($assignmentDetails),
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query()
            ]
        );

        
        return view('instituteAdmin.CourseFiles.AssignSubjectsToStudents', [
            'fincapMerchants' => $fincapMerchants,
            'assignments' => $assignments,
            'categories' => $categories,
            'students' => $students,
            'courseTypes' => $courseTypes,
            'subTypes' => $subTypes,
            'statuses' => $statuses,
        ]);
    }
    
    public function showassignsubjectStudentForm()
    {
        $context = $this->getInstituteBranchContext();
        if (!$context['institute_id']) {
            abort(403, 'You are not associated with any institute.');
        }
        // Get distinct student assignments
        $distinctStudents = StudentSubjectAssignment::select('student_hash_id')
            ->where('institute_id', $context['institute_id'])
            ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            }, function($query) {
                return $query->whereNull('branch_id');
            })
            ->distinct()
            ->pluck('student_hash_id');
        $assignmentDetails = [];
        foreach ($distinctStudents as $studentHashId) {
            // Get latest assignment for this student
            $latestAssignment = StudentSubjectAssignment::select(
                    'student_subject_assignments.*',
                    'student_parent_details.first_name',
                    'student_parent_details.last_name',
                    'student_parent_details.student_hash_id',
                    DB::raw('CONCAT(student_parent_details.first_name, " ", student_parent_details.last_name) as student_name'),
                    'product_details.course_type',
                    'product_details.sub_type'
                )
                ->join('student_parent_details', 'student_subject_assignments.student_hash_id', '=', 'student_parent_details.student_hash_id')
                ->join('product_details', 'student_subject_assignments.course_detail_id', '=', 'product_details.product_id')
                ->where('student_subject_assignments.student_hash_id', $studentHashId)
                ->where('student_subject_assignments.institute_id', $context['institute_id'])
                ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
                    return $query->where('student_subject_assignments.branch_id', $context['branch_id']);
                }, function($query) {
                    return $query->whereNull('student_subject_assignments.branch_id');
                })
                ->orderBy('student_subject_assignments.created_at', 'desc')
                ->first();
            if ($latestAssignment) {
                // Count total subjects for this student
                $subjectCount = StudentSubjectAssignment::where('student_hash_id', $studentHashId)
                    ->where('institute_id', $context['institute_id'])
                    ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
                        return $query->where('branch_id', $context['branch_id']);
                    }, function($query) {
                        return $query->whereNull('branch_id');
                    })
                    ->count();
                $latestAssignment->subject_count = $subjectCount;
                $assignmentDetails[] = $latestAssignment;
            }
        }
        // Get categories for dropdown
        $categories = $this->getCommonQuery(\App\Models\DepartmentCategory::class)->get();
        // Get fincap merchants
        $fincapMerchants = $this->getFincapMerchants();
        return view('instituteAdmin.CourseFiles.AssignSubjectsStudentsPanel', [
            'fincapMerchants' => $fincapMerchants,
            'assignments' => $assignmentDetails,
            'categories' => $categories
        ]);
    }

public function downloadSubjects(Request $request)
{
    $validated = $request->validate([
        'type' => 'required|in:excel,csv,pdf',
        'ids'  => 'nullable|string',
    ]);

    $context = $this->getInstituteBranchContext();

    if (!$context['institute_id']) {
        abort(403, 'You are not associated with any institute.');
    }

    // Get distinct student hash IDs first (same as index method)
    $distinctStudents = StudentSubjectAssignment::select('student_hash_id')
        ->where('institute_id', $context['institute_id'])
        ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
            return $query->where('branch_id', $context['branch_id']);
        }, function($query) {
            return $query->whereNull('branch_id');
        })
        ->distinct()
        ->pluck('student_hash_id');

    if ($distinctStudents->isEmpty()) {
        return redirect()->back()->with('error', 'No assignments found.');
    }

    $assignmentDetails = [];
    foreach ($distinctStudents as $studentHashId) {
        // Get latest assignment for this student (same as index method)
        $latestAssignment = StudentSubjectAssignment::select(
                'student_subject_assignments.*',
                'student_parent_details.first_name',
                'student_parent_details.last_name',
                'student_parent_details.student_hash_id',
                DB::raw('CONCAT(student_parent_details.first_name, " ", student_parent_details.last_name) as student_name'),
                'product_details.course_type',
                'product_details.sub_type'
            )
            ->join('student_parent_details', 'student_subject_assignments.student_hash_id', '=', 'student_parent_details.student_hash_id')
            ->join('product_details', 'student_subject_assignments.course_detail_id', '=', 'product_details.product_id')
            ->where('student_subject_assignments.student_hash_id', $studentHashId)
            ->where('student_subject_assignments.institute_id', $context['institute_id'])
            ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
                return $query->where('student_subject_assignments.branch_id', $context['branch_id']);
            }, function($query) {
                return $query->whereNull('student_subject_assignments.branch_id');
            })
            ->orderBy('student_subject_assignments.created_at', 'desc')
            ->first();

        if ($latestAssignment) {
            // Count total subjects for this student
            $subjectCount = StudentSubjectAssignment::where('student_hash_id', $studentHashId)
                ->where('institute_id', $context['institute_id'])
                ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
                    return $query->where('branch_id', $context['branch_id']);
                }, function($query) {
                    return $query->whereNull('branch_id');
                })
                ->count();

            $latestAssignment->subject_count = $subjectCount;
            
            // Apply ID filter if provided
            if (!empty($validated['ids'])) {
                $ids = array_filter(explode(',', $validated['ids']));
                if (in_array($latestAssignment->id, $ids)) {
                    $assignmentDetails[] = $latestAssignment;
                }
            } else {
                $assignmentDetails[] = $latestAssignment;
            }
        }
    }

    // Convert to collection
    $assignments = collect($assignmentDetails);

    if ($assignments->isEmpty()) {
        return redirect()->back()->with('error', 'No assignments found.');
    }

    switch ($validated['type']) {
        case 'excel':
            return Excel::download(
                new StudentAssignmentsExport($assignments),
                'student_assignments.xlsx'
            );

        case 'csv':
            return Excel::download(
                new StudentAssignmentsExport($assignments),
                'student_assignments.csv'
            );

        case 'pdf':
            $pdf = Pdf::loadView('pdf.student_assignment_export', [
                'assignments' => $assignments
            ]);
            return $pdf->download('student_assignments.pdf');
    }
}

}