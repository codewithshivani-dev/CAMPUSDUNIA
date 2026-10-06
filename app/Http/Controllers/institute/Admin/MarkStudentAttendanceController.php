<?php

namespace App\Http\Controllers\institute\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class MarkStudentAttendanceController extends Controller
{
    public function index(Request $request)
    {
        // Get logged in employee's ID
        $user = Auth::user();
        $context = $this->getInstituteBranchContext();
        
        // Get employee details
        $employee = DB::table('employee_details')
            ->where('user_id', $user->id)
            ->first();
            
        if (!$employee) {
            return redirect()->route('login')->with('error', 'Employee record not found.');
        }
        
        $employeeId = $employee->employee_id;
        
        // Get date from request or use today
        $selectedDate = $request->date ?? Carbon::today()->format('Y-m-d');
        $date = Carbon::parse($selectedDate);
        
        // Get department
        $department = DB::table('departments as d')
            ->join('employee_details as ed', 'd.department_id', '=', 'ed.department_id')
            ->where('ed.employee_id', $employeeId)
            ->where('ed.institute_id', $context['institute_id'])
            ->when($context['branch_id'], function($query) use ($context) {
                return $query->where('ed.branch_id', $context['branch_id']);
            })
            ->select('d.department', 'd.department_id')
            ->first();

       $dayName = $date->format('D'); // "Mon", "Tue", etc.
    
        $lectures = DB::table('employee_subject_lectures as esl')
            ->join('assign_subjects_to_employee as ase', 'esl.emp_assign_subject_id', '=', 'ase.emp_assign_subject_id')
            ->join('subjects_coursewise as sc', 'ase.subject_id', '=', 'sc.subject_id')
            ->join('product_details as pd', 'sc.course_detail_id', '=', 'pd.product_id')
            ->leftJoin('course_fee_structures as cfs', function($join) use ($context) {
                $join->on('pd.product_id', '=', 'cfs.product_id')
                    ->where('cfs.institute_id', $context['institute_id'])
                    ->when($context['branch_id'], function($query) use ($context) {
                        return $query->where('cfs.branch_id', $context['branch_id']);
                    }, function($query) {
                        return $query->whereNull('cfs.branch_id');
                    });
            })
            ->where('ase.employee_id', $employeeId)
            ->where('ase.institute_id', $context['institute_id'])
            ->when($context['branch_id'], function($query) use ($context) {
                return $query->where('ase.branch_id', $context['branch_id']);
            })
            ->where('esl.valid_from', '<=', $date)
            ->where(function($query) use ($date) {
                $query->where('esl.valid_to', '>=', $date)
                    ->orWhereNull('esl.valid_to');
            })
            ->where(function($query) use ($date, $dayName) {
                // Check for daily lectures
                $query->where('esl.frequency', 'daily')
                    // Check for weekly lectures on this day
                    ->orWhere(function($q) use ($dayName) {
                        $q->where('esl.frequency', 'weekly')
                        ->whereJsonContains('esl.days_of_week', $dayName);
                    })
                    // Check for monthly lectures on this day of month
                    ->orWhere(function($q) use ($date) {
                        $q->where('esl.frequency', 'monthly')
                        ->where('esl.day_of_month', $date->day);
                    });
            })
            ->select(
                'esl.id as lecture_id',
                'esl.start_time',
                'esl.end_time',
                'sc.subject_name',
                'sc.subject_id',
                'pd.course_type',
                'pd.sub_type',
                'pd.product_id',
                'ase.semester_id',
                'ase.section_id',
                'ase.academic_year',
                'ase.department_id',
                'cfs.sections as section_data', // Get section data
                DB::raw("CONCAT(DATE_FORMAT(esl.start_time, '%h:%i %p'), ' - ', DATE_FORMAT(esl.end_time, '%h:%i %p')) as time_slot")
            )
            ->orderBy('esl.start_time')
            ->get();

        // Check if attendance is already marked for each lecture
        // AND get section names for each lecture
        $lectures = $lectures->map(function($lecture) use ($context, $selectedDate) {
        $lecture->attendance_marked = $this->isAttendanceMarked($lecture->lecture_id, $selectedDate, $context);
        
        // Get section name from section_data
        $lecture->section_name = $lecture->section_id; // Default to ID
        
        if ($lecture->section_id === 'all') {
            $lecture->section_name = 'All Sections';
        } elseif (!empty($lecture->section_data)) {
            try {
                $sections = json_decode($lecture->section_data, true);
                if (is_array($sections)) {
                    foreach ($sections as $section) {
                        if (isset($section['id']) && $section['id'] === $lecture->section_id) {
                            $lecture->section_name = $section['name'] ?? $lecture->section_id;
                            break;
                        }
                    }
                }
            } catch (\Exception $e) {
                // Keep original section_id as name
            }
        }
        
        return $lecture;
        });
        return view('instituteAdmin.StudentAttendance.simple-attendance', 
            compact('department', 'employee', 'lectures', 'selectedDate', 'date'));
    }

    public function getStudentsForLecture($lectureId, $date = null)
    {
        $context = $this->getInstituteBranchContext();
        $user = Auth::user();
        
        // Get employee details
        $employee = DB::table('employee_details')
            ->where('user_id', $user->id)
            ->first();
            
        if (!$employee) {
            return redirect()->route('login')->with('error', 'Employee record not found.');
        }
        
        $employeeId = $employee->employee_id;
        
        // Use provided date or today
        $attendanceDate = $date ? Carbon::parse($date)->format('Y-m-d') : Carbon::today()->format('Y-m-d');

        // Get lecture details WITH section_id from assign_subjects_to_employee
        $lecture = DB::table('employee_subject_lectures as esl')
            ->join('assign_subjects_to_employee as ase', 'esl.emp_assign_subject_id', '=', 'ase.emp_assign_subject_id')
            ->join('subjects_coursewise as sc', 'ase.subject_id', '=', 'sc.subject_id')
            ->join('product_details as pd', 'sc.course_detail_id', '=', 'pd.product_id')
            ->leftJoin('course_fee_structures as cfs', function($join) use ($context) {
                $join->on('pd.product_id', '=', 'cfs.product_id')
                    ->where('cfs.institute_id', $context['institute_id'])
                    ->when($context['branch_id'], function($query) use ($context) {
                        return $query->where('cfs.branch_id', $context['branch_id']);
                    }, function($query) {
                        return $query->whereNull('cfs.branch_id');
                    })
                    ->where('pd.status', 'active');
            })
            ->where('esl.id', $lectureId)
            ->where('ase.employee_id', $employeeId)
            ->where('ase.institute_id', $context['institute_id'])
            ->when($context['branch_id'], function($query) use ($context) {
                return $query->where('ase.branch_id', $context['branch_id']);
            })
            ->select(
                'esl.id as lecture_id',
                'esl.start_time',
                'esl.end_time',
                'sc.subject_name',
                'sc.subject_id',
                'pd.course_type',
                'pd.sub_type',
                'pd.product_id',
                'ase.semester_id',
                'ase.section_id', // Get section_id from assignment table
                'cfs.academic_year_id',
                'cfs.department_id',
                'cfs.academic_year_id as academic_year',
                'cfs.sections as section_data', // Get sections JSON data
                DB::raw("CONCAT(DATE_FORMAT(esl.start_time, '%h:%i %p'), ' - ', DATE_FORMAT(esl.end_time, '%h:%i %p')) as time_slot")
            )
            ->first();

        if (!$lecture) {
            return redirect()->back()->with('error', 'Lecture not found or you do not have access.');
        }

        // Get section information from course_fee_structures
        $sectionId = $lecture->section_id;
        $sections = collect([]);
        
        // Parse section_data from course_fee_structures
        $allSectionsData = [];
        if (!empty($lecture->section_data)) {
            try {
                $allSectionsData = json_decode($lecture->section_data, true);
            } catch (\Exception $e) {
                $allSectionsData = [];
            }
        }
        
        if ($sectionId === 'all') {
            // Get all sections
            if (is_array($allSectionsData) && count($allSectionsData) > 0) {
                foreach ($allSectionsData as $section) {
                    if (isset($section['id'])) {
                        $sections->push((object)[
                            'section_id' => $section['id'],
                            'section_name' => $section['name'] ?? $section['id'],
                            'seats' => $section['seats'] ?? 0
                        ]);
                    }
                }
            }
        } else {
            // Get specific section name
            $sectionName = $sectionId; // Default to ID if name not found
            $sectionSeats = 0;
            
            if (is_array($allSectionsData)) {
                foreach ($allSectionsData as $section) {
                    if (isset($section['id']) && $section['id'] === $sectionId) {
                        $sectionName = $section['name'] ?? $sectionId;
                        $sectionSeats = $section['seats'] ?? 0;
                        break;
                    }
                }
            }
            
            $sections->push((object)[
                'section_id' => $sectionId,
                'section_name' => $sectionName,
                'seats' => $sectionSeats
            ]);
        }

        // Get students for this lecture - FILTERED BY SECTION
        $students = $this->getStudentsForProduct(
            $lecture->product_id, 
            $lecture->semester_id, 
            $lecture->academic_year_id,
            $lecture->course_type,
            $lecture->sub_type,
            $context,
            $sectionId // Pass section_id
        );

        // For each student, get their section name
        $students = $students->map(function($student) use ($allSectionsData) {
            if ($student->section_id && is_array($allSectionsData)) {
                foreach ($allSectionsData as $section) {
                    if (isset($section['id']) && $section['id'] === $student->section_id) {
                        $student->section_name = $section['name'] ?? $student->section_id;
                        $student->section_seats = $section['seats'] ?? 0;
                        break;
                    }
                }
            }
            
            if (!isset($student->section_name)) {
                $student->section_name = $student->section_id;
            }
            
            return $student;
        });

        // Get existing attendance if marked
        $existingAttendance = $this->getExistingAttendance($lectureId, $attendanceDate, $context);

        return view('instituteAdmin.StudentAttendance.attendance', 
            compact('lecture', 'students', 'employee', 'attendanceDate', 'existingAttendance', 'sections'));
    }

    public function saveAttendance(Request $request)
    {
        $context = $this->getInstituteBranchContext();
        $user = Auth::user();
        
        // Get employee details
        $employee = DB::table('employee_details')
            ->where('user_id', $user->id)
            ->first();
            
        if (!$employee) {
            return redirect()->route('login')->with('error', 'Employee record not found.');
        }
        
        $employeeId = $employee->employee_id;
        
        DB::transaction(function () use ($request, $employeeId, $context) {
            // Get lecture details to find assignment WITH section_id
            $lecture = DB::table('employee_subject_lectures as esl')
                ->join('assign_subjects_to_employee as ase', 'esl.emp_assign_subject_id', '=', 'ase.emp_assign_subject_id')
                ->where('esl.id', $request->lecture_id)
                ->where('ase.employee_id', $employeeId)
                ->where('ase.institute_id', $context['institute_id'])
                ->when($context['branch_id'], function($query) use ($context) {
                    return $query->where('ase.branch_id', $context['branch_id']);
                })
                ->select('ase.emp_assign_subject_id', 'ase.section_id')
                ->first();

            if (!$lecture) {
                throw new \Exception("Lecture not found or you don't have permission.");
            }

            foreach ($request->attendance as $studentId => $status) {
                // Verify student belongs to same institute/branch AND section
                $student = DB::table('student_parent_details as spd')
                    ->join('academic_transport_details as atd', 'spd.student_hash_id', '=', 'atd.student_hash_id')
                    ->where('spd.student_hash_id', $studentId)
                    ->where('spd.institute_id', $context['institute_id'])
                    ->when($context['branch_id'], function($query) use ($context) {
                        return $query->where('spd.branch_id', $context['branch_id']);
                    });
                
                // If section is specific, verify student is in that section
                if ($lecture->section_id !== 'all') {
                    $student->where('atd.section_id', $lecture->section_id);
                }
                
                $studentExists = $student->exists();

                if (!$studentExists) {
                    throw new \Exception("Student not found in your institute/branch or not in the assigned section.");
                }

                $uniqueId = 'ATT' . strtoupper(Str::random(6));
                
                DB::table('student_attendance')->updateOrInsert(
                    [
                        'student_hash_id' => $studentId,
                        'emp_assign_subject_id' => $lecture->emp_assign_subject_id,
                        'date' => $request->attendance_date,
                    ],
                    [
                        'student_attandance_id' => $uniqueId,
                        'start_time' => $request->start_time,
                        'end_time' => $request->end_time,
                        'status' => ucfirst($status),
                        'remarks' => $request->remarks[$studentId] ?? null,
                        'academic_year_id' => $request->academic_year,
                        'section_id' => $lecture->section_id, // Store section_id from assignment
                        'institute_id' => $context['institute_id'],
                        'branch_id' => $context['branch_id'],
                        'department_id' => $request->department_id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        });

        return redirect()->route('employee.attendance.index', ['date' => $request->attendance_date])
            ->with('success', 'Attendance saved successfully!');
    }

    // Helper methods
    private function isAttendanceMarked($lectureId, $date, $context)
    {
        return DB::table('student_attendance as sa')
            ->join('employee_subject_lectures as esl', 'sa.emp_assign_subject_id', '=', 'esl.emp_assign_subject_id')
            ->where('esl.id', $lectureId)
            ->where('sa.date', $date)
            ->where('sa.institute_id', $context['institute_id'])
            ->when($context['branch_id'], function($query) use ($context) {
                return $query->where('sa.branch_id', $context['branch_id']);
            })
            ->exists();
    }

    private function getStudentsForProduct($productId, $semesterId, $academicYearId, $courseType, $subType, $context, $sectionId = null)
    {
        // Get course fee structure for section data
        $courseFee = DB::table('course_fee_structures')
            ->where('product_id', $productId)
            ->where('academic_year_id', $academicYearId)
            ->where('institute_id', $context['institute_id'])
            ->when($context['branch_id'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            }, function($query) {
                return $query->whereNull('branch_id');
            })
            ->first();
        
        // Parse section data for filtering
        $sectionData = [];
        if ($courseFee && !empty($courseFee->sections)) {
            try {
                $sectionData = json_decode($courseFee->sections, true);
            } catch (\Exception $e) {
                $sectionData = [];
            }
        }

        // Start with basic student query
        $studentsQuery = DB::table('academic_transport_details as atd')
            ->join('student_parent_details as spd', 'atd.student_hash_id', '=', 'spd.student_hash_id')
            ->join('course_fee_structures as cfs', function($join) use ($context) {
                $join->on('atd.academic_year_id', '=', 'cfs.academic_year_id')
                    ->where('cfs.institute_id', $context['institute_id'])
                    ->when($context['branch_id'], function($query) use ($context) {
                        return $query->where('cfs.branch_id', $context['branch_id']);
                    });
            })
            ->where('cfs.product_id', $productId)
            ->where('atd.course_type', $courseType)
            ->where('atd.course_subtype', $subType)
            ->where('spd.institute_id', $context['institute_id'])
            ->when($context['branch_id'], function($query) use ($context) {
                return $query->where('spd.branch_id', $context['branch_id']);
            });

        // Filter by academic_year_id if available
        if ($academicYearId) {
            $studentsQuery->where('cfs.academic_year_id', $academicYearId);
        }
        
        // Filter by semester if available and not 'all_semesters'
        if ($semesterId && $semesterId !== 'all_semesters') {
            $studentsQuery->where('atd.semester_id', $semesterId);
        }
        
        // CRITICAL: Filter by section from academic_transport_details
        if ($sectionId) {
            if ($sectionId === 'all') {
                // If 'all' sections, get students from ALL sections for this product
                if (is_array($sectionData) && count($sectionData) > 0) {
                    $sectionIds = array_column($sectionData, 'id');
                    $studentsQuery->whereIn('atd.section_id', $sectionIds);
                }
                // If no section_data, get all students without section filter
            } else {
                // Filter by specific section
                $studentsQuery->where('atd.section_id', $sectionId);
            }
        }
        
        $students = $studentsQuery->select(
                'spd.student_hash_id',
                'spd.registration_number',
                'spd.first_name',
                'spd.middle_name',
                'spd.last_name',
                'cfs.academic_year_id',
                'atd.section_id', // Get section_id from academic_transport_details
                'atd.semester_id',
                'atd.session_id',
                DB::raw("CONCAT(spd.first_name, ' ', COALESCE(spd.middle_name, ''), ' ', spd.last_name) as full_name"),
                'atd.course_type',
                'atd.course_subtype'
            )
            ->orderBy('atd.section_id') // Order by section first
            ->orderBy('spd.registration_number')
            ->get();

        return $students;
    }

    private function getExistingAttendance($lectureId, $date, $context)
    {
        $attendance = DB::table('student_attendance as sa')
            ->join('employee_subject_lectures as esl', 'sa.emp_assign_subject_id', '=', 'esl.emp_assign_subject_id')
            ->where('esl.id', $lectureId)
            ->where('sa.date', $date)
            ->where('sa.institute_id', $context['institute_id'])
            ->when($context['branch_id'], function($query) use ($context) {
                return $query->where('sa.branch_id', $context['branch_id']);
            })
            ->select('sa.student_hash_id', 'sa.status', 'sa.remarks', 'sa.section_id')
            ->get()
            ->keyBy('student_hash_id');

        return $attendance;
    }

    private function getInstituteBranchContext()
    {
        $user = Auth::user();
        
        if (!$user) {
            return [
                'institute_id' => null,
                'branch_id' => null,
                'is_branch_admin' => false
            ];
        }

        if ($user->institute_id) {
            return [
                'institute_id' => $user->institute_id,
                'branch_id' => $user->branch_id ?? null,
                'is_branch_admin' => $user->hasRole('branch_admin')
            ];
        }

        if ($user->hasRole('employee')) {
            $employee = DB::table('employee_details')
                ->where('user_id', $user->id)
                ->select('institute_id', 'branch_id')
                ->first();
                
            if ($employee) {
                return [
                    'institute_id' => $employee->institute_id,
                    'branch_id' => $employee->branch_id,
                    'is_branch_admin' => false
                ];
            }
        }

        return [
            'institute_id' => session('institute_id'),
            'branch_id' => session('branch_id'),
            'is_branch_admin' => false
        ];
    }

    // Add this helper method to your controller
    private function getSectionNameForLecture($productId, $sectionId, $context = null)
    {
        if (!$context) {
            $context = $this->getInstituteBranchContext();
        }
        
        if ($sectionId === 'all') {
            return 'All Sections';
        }
        
        // Get course fee structure
        $courseFee = DB::table('course_fee_structures')
            ->where('product_id', $productId)
            ->where('institute_id', $context['institute_id'])
            ->when($context['branch_id'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            }, function($query) {
                return $query->whereNull('branch_id');
            })
            ->first();
        
        if ($courseFee && !empty($courseFee->sections)) {
            try {
                $sections = json_decode($courseFee->sections, true);
                if (is_array($sections)) {
                    foreach ($sections as $section) {
                        if (isset($section['id']) && $section['id'] === $sectionId) {
                            return $section['name'] ?? $sectionId;
                        }
                    }
                }
            } catch (\Exception $e) {
                return $sectionId;
            }
        }
        
        return $sectionId;
    }
}