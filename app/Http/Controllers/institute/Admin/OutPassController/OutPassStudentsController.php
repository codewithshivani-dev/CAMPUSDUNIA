<?php

namespace App\Http\Controllers\institute\Admin\OutPassController;
use App\Http\Controllers\Controller;

use App\Models\OutPassStudents;
use App\Models\StudentParentDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class OutPassStudentsController extends Controller
{
    /**
     * Display a listing of students.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = OutPassStudents::orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'LIKE', "%{$search}%")
                  ->orWhere('student_id', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('parent_contact', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('class')) {
            $query->where('class', $request->class);
        }

        if ($request->filled('section')) {
            $query->where('section', $request->section);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $students = $query->paginate(15)->withQueryString();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $students
            ]);
        }

        return view('students.index', compact('students'));
    }

    /**
     * Show the form for creating a new student.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('students.create');
    }

    /**
     * Store a newly created student.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
  
        $validator = Validator::make($request->all(), [
            'student_id' => 'required',
            'full_name' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'address' => 'required|string',
            'class' => 'required|string|max:50',
            'section' => 'required|string|max:10',
            'roll_number' => 'required|string|max:20',
            'email' => 'required|email',
            'parent_name' => 'required|string|max:255',
            'parent_contact' => 'required|string|max:20',
            'admission_year' => 'required|string|max:10',
            'parent_email' => 'nullable|email',
            'date_of_birth' => 'nullable|date',
            'blood_group' => 'nullable|string|max:10',
            'medical_conditions' => 'nullable|string',
            'institute_id' => 'nullable|string',
            'branch_id' => 'nullable|string',
            'user_id' => 'nullable|string',
            'is_hosteler' => 'nullable|boolean',
            'hostel_name' => 'nullable|string|max:100',
            'room_number' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // try {
            DB::beginTransaction();

            $student = OutPassStudents::create($request->all());

            DB::commit();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Student created successfully.',
                    'data' => $student
                ], 201);
            }

            return redirect()->route('students.index')
                ->with('success', 'Student created successfully.');

        // } catch (\Exception $e) {
        //     DB::rollBack();
            
        //     $error = 'Failed to create student: ' . $e->getMessage();
            
        //     if ($request->wantsJson()) {
        //         return response()->json([
        //             'success' => false,
        //             'message' => $error
        //         ], 500);
        //     }
            
        //     return redirect()->back()
        //         ->with('error', $error)
        //         ->withInput();
        // }
    }

    /**
     * Display the specified student.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $student = OutPassStudents::findOrFail($id);

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $student
            ]);
        }

        return view('students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified student.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $student = OutPassStudents::findOrFail($id);
        return view('students.edit', compact('student'));
    }

    /**
     * Update the specified student.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $student = OutPassStudents::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'student_id' => 'required|unique:out_pass_students,student_id,' . $id,
            'full_name' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'address' => 'required|string',
            'class' => 'required|string|max:50',
            'section' => 'required|string|max:10',
            'roll_number' => 'required|string|max:20',
            'email' => 'required|email|unique:out_pass_students,email,' . $id,
            'parent_name' => 'required|string|max:255',
            'parent_contact' => 'required|string|max:20',
            'admission_year' => 'required|string|max:10',
            'parent_email' => 'nullable|email',
            'date_of_birth' => 'nullable|date',
            'blood_group' => 'nullable|string|max:10',
            'medical_conditions' => 'nullable|string',
            'is_hosteler' => 'nullable|boolean',
            'hostel_name' => 'nullable|string|max:100',
            'room_number' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $student->update($request->all());

            DB::commit();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Student updated successfully.',
                    'data' => $student
                ]);
            }

            return redirect()->route('students.show', $id)
                ->with('success', 'Student updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            $error = 'Failed to update student: ' . $e->getMessage();
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $error
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', $error)
                ->withInput();
        }
    }

    /**
     * Remove the specified student.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $student = OutPassStudents::findOrFail($id);

        // Check if student has any pending out passes
        $hasActivePasses = \App\Models\OutPassMigration::where('requester_type', OutPassStudents::class)
            ->where('requester_id', $student->id)
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        if ($hasActivePasses) {
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete student with active out passes.'
                ], 403);
            }
            return redirect()->route('students.index')
                ->with('error', 'Cannot delete student with active out passes.');
        }

        $student->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Student deleted successfully.'
            ]);
        }

        return redirect()->route('students.index')
            ->with('success', 'Student deleted successfully.');
    }

    /**
     * Fetch student details by ID (AJAX) - Used by blade form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function fetchStudent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Student ID is required'
            ], 422);
        }

        $student = StudentParentDetails::with('address','academicTransportDetails')->where('student_hash_id', $request->student_id)->first();
        if ($student) {
            return response()->json([
                'success' => true,
                'data' => [
                    'name' => $student->first_name,
                    'contact' => $student->mobile,
                    'address' => $student->address->student_perm_address_line1,
                    'class' => optional($student->academicTransportDetails)->course_subtype,
                    'section' => optional($student->academicTransportDetails)->section_id,
                    'rollNo' => $student->registration_number,
                    'email' => $student->email,
                    'parentName' => $student->father_first_name,
                    'parentContact' => $student->father_phone,
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Student not found'
        ], 404);
    }

    /**
     * Toggle student active status.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function toggleStatus($id)
    {
        $student = OutPassStudents::findOrFail($id);
        $student->is_active = !$student->is_active;
        $student->save();

        $status = $student->is_active ? 'activated' : 'deactivated';

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Student {$status} successfully.",
                'is_active' => $student->is_active
            ]);
        }

        return redirect()->back()
            ->with('success', "Student {$status} successfully.");
    }

    /**
     * Search students (AJAX).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $search = $request->get('q', '');
        
        $students = OutPassStudentsOutPassStudente('full_name', 'LIKE', "%{$search}%")
            ->orWhere('student_id', 'LIKE', "%{$search}%")
            ->orWhere('email', 'LIKE', "%{$search}%")
            ->where('is_active', true)
            ->limit(10)
            ->get(['id', 'student_id', 'full_name', 'email', 'class', 'section']);

        return response()->json($students);
    }

    /**
     * Get students by class.
     *
     * @param  string  $class
     * @return \Illuminate\Http\Response
     */
    public function getByClass($class)
    {
        $students = OutPassStudents::where('class', $class)
            ->where('is_active', true)
            ->orderBy('roll_number')
            ->get(['id', 'student_id', 'full_name', 'section', 'roll_number']);

        return response()->json([
            'success' => true,
            'data' => $students
        ]);
    }

    /**
     * Get student statistics.
     *
     * @return \Illuminate\Http\Response
     */
    public function statistics()
    {
        $stats = [
            'total' => OutPassStudents::count(),
            'active' => OutPassStudents::where('is_active', true)->count(),
            'inactive' => OutPassStudents::where('is_active', false)->count(),
            'hostelers' => OutPassStudents::where('is_hosteler', true)->count(),
            'day_scholars' => OutPassStudents::where('is_hosteler', false)->count(),
            'by_class' => OutPassStudents::where('is_active', true)
                ->select('class', \DB::raw('count(*) as total'))
                ->groupBy('class')
                ->get(),
            'by_blood_group' => OutPassStudents::where('is_active', true)
                ->whereNotNull('blood_group')
                ->select('blood_group', \DB::raw('count(*) as total'))
                ->groupBy('blood_group')
                ->get(),
        ];

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        }

        return view('students.statistics', compact('stats'));
    }
}