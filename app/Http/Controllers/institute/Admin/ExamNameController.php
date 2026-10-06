<?php

namespace App\Http\Controllers\institute\Admin;
use App\Http\Controllers\Controller;

use App\Models\ExamName;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\InstituteBranchAccess;
use Illuminate\Support\Facades\Validator;

class ExamNameController extends Controller
{
    use InstituteBranchAccess;

    public function index()
    {
        $context = $this->getInstituteBranchContext();
        $instituteId = $context['institute_id'];
        
        $examNames = ExamName::where('institute_id', $instituteId)
            ->orderBy('academic_year', 'desc')
            ->orderBy('name')
            ->paginate(20);
            
        // Get unique academic years for filter
        $academicYears = ExamName::where('institute_id', $instituteId)
            ->distinct()
            ->pluck('academic_year')
            ->sortDesc();
            
        return view('instituteAdmin.exam_names.index', compact('examNames', 'academicYears'));
    }

    public function create()
    {
        $context = $this->getInstituteBranchContext();
        
        // Generate current academic year (e.g., 2024-2025)
        $currentYear = date('Y');
        $nextYear = date('Y') + 1;
        $academicYear = "{$currentYear}-{$nextYear}";
        
        return view('instituteAdmin.exam_names.create', compact('academicYear'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'academic_year' => 'required|string|max:20',
            'description' => 'nullable|string',
            'term' => 'required|in:mid_term,final,prelim,unit_test,other',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $context = $this->getInstituteBranchContext();
        
        $examName = ExamName::create([
            'institute_id' => $context['institute_id'],
            'name' => $request->name,
            'academic_year' => $request->academic_year,
            'description' => $request->description,
            'term' => $request->term,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('institute.exam-names.index')
            ->with('success', 'Exam name created successfully!');
    }

    public function edit($id)
    {
        $context = $this->getInstituteBranchContext();
        $examName = ExamName::where('institute_id', $context['institute_id'])
            ->findOrFail($id);
            
        return view('instituteAdmin.exam_names.edit', compact('examName'));
    }

    public function update(Request $request, $id)
    {
        $context = $this->getInstituteBranchContext();
        $examName = ExamName::where('institute_id', $context['institute_id'])
            ->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'academic_year' => 'required|string|max:20',
            'description' => 'nullable|string',
            'term' => 'required|in:mid_term,final,prelim,unit_test,other',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $examName->update([
            'name' => $request->name,
            'academic_year' => $request->academic_year,
            'description' => $request->description,
            'term' => $request->term,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('institute.exam-names.index')
            ->with('success', 'Exam name updated successfully!');
    }

    public function destroy($id)
    {
        $context = $this->getInstituteBranchContext();
        $examName = ExamName::where('institute_id', $context['institute_id'])
            ->findOrFail($id);
            
        // Check if exam name is being used
        if ($examName->exams()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete exam name. It is being used in exams.');
        }
        
        $examName->delete();
        
        return redirect()->route('institute.exam-names.index')
            ->with('success', 'Exam name deleted successfully!');
    }

    // API endpoint to get exam names for AJAX
    public function getExamNames(Request $request)
    {
        $context = $this->getInstituteBranchContext();
        $instituteId = $context['institute_id'];
        
        $query = ExamName::where('institute_id', $instituteId)
            ->active()
            ->orderBy('name');
            
        // Filter by academic year if provided
        if ($request->has('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }
    
        
        $examNames = $query->get(['id', 'exam_name_id', 'name', 'academic_year', 'term']);
        
        return response()->json([
            'success' => true,
            'exam_names' => $examNames
        ]);
    }
}