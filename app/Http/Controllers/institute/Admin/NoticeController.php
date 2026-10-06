<?php
namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use App\Models\DepartmentCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Traits\InstituteBranchAccess;
use App\Traits\DepartmentRelationships;
use App\Notifications\NoticeCreatedNotification;
use App\Traits\SendsInstituteNotifications;
class NoticeController extends Controller
{
    
use InstituteBranchAccess, DepartmentRelationships;
use SendsInstituteNotifications;
    // Store method for saving notices
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'status' => 'required|in:draft,published',
            'recipient_type' => 'required|in:whole,academic,nonacademic',
            'notice_type' => 'required|in:student,employee,both',
            'department_category_id' => 'nullable|string',
            'department_id' => 'nullable|string',
            'attachment' => 'nullable|file|max:10240',
        ]);
    
        $user = Auth::user();
    
        if (!$user || !$user->institute_id) {
            return response()->json([
                'success' => false,
                'message' => 'Institute not found'
            ], 422);
        }
    
        // 🔐 Department validation (only if not whole institute)
        if ($request->recipient_type !== 'whole') {
            if (!$request->department_category_id || !$request->department_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Category and Department are required'
                ], 422);
            }
        }
    
        // 📎 File upload
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('notices', 'public');
        }
    
        // 📝 Save notice
        $notice = Notice::create([
            'institute_id' => $user->institute_id,
            'branch_id' => $user->branch_id ?? null,
            'department_category_id' => $request->department_category_id,
            'department_id' => $request->department_id,
            'title' => $request->title,
            'content' => $request->content,
            'status' => $request->status,
            'recipient_type' => $request->recipient_type,
            'notice_type' => $request->notice_type, // ✅ SINGLE COLUMN
            'attachment' => $attachmentPath,
        ]);
        
        $this->notifyNoticeRecipients($notice);
        return response()->json([
            'success' => true,
            'message' => 'Notice saved successfully',
            'notice' => $notice
        ]);
    }

    // Create method for showing the form
    public function create()
    {
        $context = $this->getInstituteBranchContext();

        if (!$context['institute_id']) {
            return redirect()->back()->with('error', 'You are not associated with any institute.');
        }

        $instituteId = $context['institute_id'];
        $branchId = $context['branch_id'] ?? null;
        $user = Auth::user();

        // Get categories for the institute
        $categories = DepartmentCategory::where('institute_id', $instituteId)->get();

        return view('instituteAdmin.NoticeBoard.Notice', compact('categories'));
    }

    // Make sure this method exists or use a trait
    // public function view(Request $request)
    // {
    //     $user = Auth::user();
    //     if (!$user || !$user->institute_id) {
    //         return redirect()->back()->with('error', 'Institute not found');
    //     }

    //     $query = Notice::where('institute_id', $user->institute_id);
        
    //     if ($user->usertype === 'student') {
    //         $query->whereIn('notice_type', ['student', 'both']);
    //     } elseif ($user->usertype === 'employee') {
    //         $query->whereIn('notice_type', ['employee', 'both']);
    //         $employee = $user->employeeDetails;
    //         if ($employee) {
    //             if ($employee->employee_type === 'academic') {
    //                 $query->where(function ($q) {
    //                     $q->where('recipient_type', 'whole')
    //                       ->orWhere('recipient_type', 'academic');
    //                 });
    //             } elseif ($employee->employee_type === 'nonacademic') {
    //                 $query->where(function ($q) {
    //                     $q->where('recipient_type', 'whole')
    //                       ->orWhere('recipient_type', 'nonacademic');
    //                 });
    //             }
    //         }
    //     }
        
    //     // 🔍 Title Search
    //     if ($request->filled('title')) {
    //         $query->where('title', 'like', '%' . $request->title . '%');
    //     }

    //     // 📌 Status Filter
    //     if ($request->filled('status')) {
    //         $query->where('status', $request->status);
    //     }

    //     // 👥 Recipient Filter
    //     if ($request->filled('recipient_type')) {
    //         $query->where('recipient_type', $request->recipient_type);
    //     }

    //     // 📅 Date Filter
    //     if ($request->filled('date')) {
    //         switch ($request->date) {
    //             case 'today':
    //                 $query->whereDate('created_at', today());
    //                 break;

    //             case 'week':
    //                 $query->where('created_at', '>=', now()->subDays(7));
    //                 break;

    //             case 'month':
    //                 $query->where('created_at', '>=', now()->subDays(30));
    //                 break;

    //             case 'year':
    //                 $query->where('created_at', '>=', now()->subDays(365));
    //                 break;
    //         }
    //     }

    //     $notices = $query->latest()->paginate(15)->withQueryString();

    //     // For datalist title suggestions
    //     $allTitles = Notice::where('institute_id', $user->institute_id)
    //                         ->pluck('title')
    //                         ->unique();

    //     return view('instituteAdmin.NoticeBoard.NoticeView',
    //         compact('notices', 'allTitles'));
    // }
    
    public function view(Request $request)
    {
        $user = Auth::user();
    
        if (!$user || !$user->institute_id) {
            return redirect()->back()->with('error', 'Institute not found');
        }
    
        $query = Notice::where('institute_id', $user->institute_id);
    
        // Students
        if ($user->hasRole('student')) {
    
            $query->whereIn('notice_type', ['student', 'both']);
        }
        // Employees
        elseif ($user->hasRole('employee')) {
    
            $query->whereIn('notice_type', ['employee', 'both']);
    
            $employee = $user->employeeDetails;
    
            if ($employee) {
    
                // Academic Employee
                if ($employee->employee_type === 'academic') {
    
                    $query->where(function ($q) {
                        $q->where('recipient_type', 'whole')
                          ->orWhere('recipient_type', 'academic');
                    });
                }
    
                // Non Academic Employee
                elseif ($employee->employee_type === 'nonacademic') {
    
                    $query->where(function ($q) {
                        $q->where('recipient_type', 'whole')
                          ->orWhere('recipient_type', 'nonacademic');
                    });
                }
            }
        }
        elseif (
            $user->hasRole('admin') ||
            $user->hasRole('superadmin')
        ){}
        else {
            // Prevent unknown roles from seeing notices
            $query->whereRaw('1 = 0');
        }
    
        // Title Search
        if ($request->filled('title')) {
    
            $query->where('title', 'like', '%' . $request->title . '%');
        }
    
        // Status Filter
        if ($request->filled('status')) {
    
            $query->where('status', $request->status);
        }
    
        // Recipient Filter
        if ($request->filled('recipient_type')) {
    
            $query->where('recipient_type', $request->recipient_type);
        }
    
        // Date Filter
        if ($request->filled('date')) {
    
            switch ($request->date) {
    
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
    
                case 'week':
                    $query->where('created_at', '>=', now()->subDays(7));
                    break;
    
                case 'month':
                    $query->where('created_at', '>=', now()->subDays(30));
                    break;
    
                case 'year':
                    $query->where('created_at', '>=', now()->subDays(365));
                    break;
            }
        }
    
        $notices = $query->latest()
                          ->paginate(15)
                          ->withQueryString();
    
        $allTitles = Notice::where('institute_id', $user->institute_id)
                            ->pluck('title')
                            ->unique();
    
        return view(
            'instituteAdmin.NoticeBoard.NoticeView',
            compact('notices', 'allTitles')
        );
    }
    
    public function edit($id)
    {
        $notice = Notice::findOrFail($id);
        $categories = DepartmentCategory::all();
        return view('instituteAdmin.NoticeBoard.Edit', compact('notice', 'categories'));
    }
    
    public function update(Request $request, $id)
    {
        $notice = Notice::findOrFail($id);
    
        $notice->update([
            'title' => $request->title,
            'content' => $request->content,
            'status' => $request->status,
            'recipient_type' => $request->recipient_type,
            'department_category_id' => $request->department_category_id,
            'department_id' => $request->department_id,
            'notice_type' => $request->notice_type,
        ]);
    
        return response()->json([
            'success' => true,
            'message' => 'Notice updated successfully'
        ]);
    }
    
}