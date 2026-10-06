<?php

namespace App\Http\Controllers\institute\Admin;
use App\Http\Controllers\Controller;
use App\Models\StudentParentDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EditStudentDetailsController extends Controller
{
    // In StudentParentDetails model (if relationships are defined)
    public function editstudentdetails(Request $request)
    {
        
        //$studentHashId = $request->student_hash_id;
        $studentHashId ='OGHZHWU4H8YLKT9';
        
        if (!$studentHashId) {
            abort(400, 'Student ID is required');
        }
        
        // Load student with all relationships
        $student = StudentParentDetails::with([
            'documents',
            'bankAccount',
            'academicTransportDetails',
            'address',
            'user'
        ])->where('student_hash_id', $studentHashId)->first();
        // dd($student-> bankAccount);
        if (!$student) {
            abort(404, 'Student not found');
        }
       
        return view('instituteAdmin.StudentFiles.EditStudentDetails', compact('student'));
    }

    // In StudentParentDetails model (if relationships are defined)
    public function studentProfile(Request $request)
    {
        //$student = $request->student_hash_id;
        $student = Auth::user();
        if (!$student) {
            abort(400, 'Student ID is required');
        }
        
        // Load student with all relationships
        $student = StudentParentDetails::with([
            'documents',
            'bankAccount',
            'academicTransportDetails',
            'address',
            'user'
        ])->where('user_id', $student->id)->first();
        // dd($student -> documents);
        if (!$student) {
            abort(404, 'Student not found');
        }
       
        return view('instituteAdmin.StudentFiles.StudentProfile', compact('student'));
    }
}