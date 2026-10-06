<?php
namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class NotificationController extends Controller
{
    /**
     * Display notifications based on user type
     */
    public function index()
    {
        $user = auth()->user();
        $instituteId = $user->institute_id;
        $userType = $this->getUserType($user);

        $notifications = $user->notifications()
            ->where('data->institute_id', $instituteId)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $unreadCount = $user->unreadNotifications()
            ->where('data->institute_id', $instituteId)
            ->count();

        $readCount = $notifications->total() - $unreadCount;

        $thisMonthCount = $user->notifications()
            ->where('data->institute_id', $instituteId)
            ->whereMonth('created_at', now()->month)
            ->count();

        // Get category counts based on user type
        $categoryCounts = $this->getCategoryCounts($user, $instituteId, $userType);

        // Pass user type to view
        View::share('userType', $userType);

        return view('instituteAdmin.DashboardFiles.notification', compact(
            'notifications',
            'unreadCount',
            'readCount',
            'thisMonthCount',
            'categoryCounts',
            'userType'
        ));
    }

    /**
     * Mark a single notification as read
     */
    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->find($id);
        
        if ($notification) {
            $notification->markAsRead();
            
            if (request()->wantsJson()) {
                return response()->json(['success' => true]);
            }
        }

        return redirect()->back();
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        
        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back();
    }

    /**
     * Delete a single notification
     */
    public function destroy($id)
    {
        $notification = auth()->user()->notifications()->find($id);
        
        if ($notification) {
            $notification->delete();
            
            if (request()->wantsJson()) {
                return response()->json(['success' => true]);
            }
        }

        return redirect()->back();
    }

    /**
     * Delete all notifications
     */
    public function destroyAll()
    {
        auth()->user()->notifications()->delete();
        
        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back();
    }

    /**
     * Get notifications for AJAX requests
     */
    public function getNotifications()
    {
        $user = auth()->user();
        $instituteId = $user->institute_id;

        $notifications = $user->notifications()
            ->where('data->institute_id', $instituteId)
            ->latest()
            ->take(20)
            ->get()
            ->map(function ($notification) {
                $data = $notification->data;
                return [
                    'id' => $notification->id,
                    'type' => $data['type'] ?? 'general',
                    'title' => $data['title'] ?? 'Notification',
                    'message' => $data['message'] ?? '',
                    'time' => $notification->created_at->diffForHumans(),
                    'datetime' => $notification->created_at->format('Y-m-d H:i:s'),
                    'action_url' => $data['action_url'] ?? '#',
                    'icon' => $data['icon'] ?? $this->getDefaultIcon($data['type'] ?? 'general'),
                    'color' => $data['color'] ?? $this->getDefaultColor($data['type'] ?? 'general'),
                    'thumbnail' => $data['thumbnail'] ?? null,
                    'is_read' => !is_null($notification->read_at)
                ];
            });

        $unreadCount = $user->unreadNotifications()
            ->where('data->institute_id', $instituteId)
            ->count();

        return response()->json([
        'success' => true,
        'notifications' => $notifications,
        'unread_count' => $unreadCount
    ]);
    }

    /**
     * Get unread count only
     */
    public function unreadCount()
    {
        $count = auth()->user()->unreadNotifications()
            ->where('data->institute_id', auth()->user()->institute_id)
            ->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Save notification settings
     */
    public function saveSettings(Request $request)
    {
        $settings = $request->validate([
            'email_notifications' => 'boolean',
            'push_notifications' => 'boolean',
            'notice_notifications' => 'boolean',
            'gallery_notifications' => 'boolean',
            'attendance_notifications' => 'boolean',
            'fee_notifications' => 'boolean',
            'leave_notifications' => 'boolean',
        ]);

        // Save to user meta or preferences table
        $user = auth()->user();
        
        // You can create a user_preferences table or use JSON column
        $user->notification_settings = $settings;
        $user->save();

        return response()->json(['success' => true, 'message' => 'Settings saved successfully']);
    }

    /**
     * Get notification settings
     */
    public function getSettings()
    {
        $user = auth()->user();
        $settings = $user->notification_settings ?? [
            'email_notifications' => true,
            'push_notifications' => true,
            'notice_notifications' => true,
            'gallery_notifications' => true,
            'attendance_notifications' => true,
            'fee_notifications' => true,
            'leave_notifications' => true,
        ];

        return response()->json(['success' => true, 'settings' => $settings]);
    }

    /**
     * Determine user type
     */
    private function getUserType($user)
    {
        if ($user->hasRole('admin') || $user->hasRole('super-admin')) {
            return 'admin';
        } elseif ($user->hasRole('employee') || $user->user_type === 'employee') {
            return 'employee';
        } elseif ($user->hasRole('student') || $user->user_type === 'student') {
            return 'student';
        }
        
        return 'user';
    }

    /**
     * Get category counts based on user type
     */
    private function getCategoryCounts($user, $instituteId, $userType)
    {
        $categories = [
            'notice_created' => 0,
            'announcement' => 0,
            'gallery_upload' => 0,
            'attendance' => 0,
            'fee' => 0,
            'leave' => 0,
        ];

        // Common categories for all users
        $categories['notice_created'] = $user->notifications()
            ->where('data->institute_id', $instituteId)
            ->where('data->type', 'notice_created')
            ->count();

        $categories['gallery_upload'] = $user->notifications()
            ->where('data->institute_id', $instituteId)
            ->where('data->type', 'gallery_upload')
            ->count();

        // User type specific categories
        if ($userType === 'employee') {
            $categories['attendance'] = $user->notifications()
                ->where('data->institute_id', $instituteId)
                ->where('data->type', 'attendance')
                ->count();
            
            $categories['leave'] = $user->notifications()
                ->where('data->institute_id', $instituteId)
                ->where('data->type', 'leave')
                ->count();
        }

        if ($userType === 'student') {
            $categories['fee'] = $user->notifications()
                ->where('data->institute_id', $instituteId)
                ->where('data->type', 'fee')
                ->count();
            
            $categories['attendance'] = $user->notifications()
                ->where('data->institute_id', $instituteId)
                ->where('data->type', 'attendance')
                ->count();
        }

        if ($userType === 'admin') {
            $categories['hr_update'] = $user->notifications()
                ->where('data->institute_id', $instituteId)
                ->where('data->type', 'hr_update')
                ->count();
        }

        return $categories;
    }

    /**
     * Get default icon for notification type
     */
    private function getDefaultIcon($type)
    {
        $icons = [
            'notice_created' => 'bullhorn',
            'announcement' => 'megaphone',
            'gallery_upload' => 'images',
            'attendance' => 'calendar-check',
            'fee' => 'rupee-sign',
            'leave' => 'calendar-minus',
            'meeting' => 'users',
            'hr_update' => 'user-tie',
            'employee_onboarding' => 'user-plus',
            'student_onboarding' => 'user-graduate',
            'general' => 'bell'
        ];

        return $icons[$type] ?? 'bell';
    }

    /**
     * Get default color for notification type
     */
    private function getDefaultColor($type)
    {
        $colors = [
            'notice_created' => 'warning',
            'announcement' => 'info',
            'gallery_upload' => 'purple',
            'attendance' => 'success',
            'fee' => 'danger',
            'leave' => 'primary',
            'meeting' => 'secondary',
            'hr_update' => 'dark',
            'employee_onboarding' => 'success',
            'student_onboarding' => 'info',
            'general' => 'secondary'
        ];

        return $colors[$type] ?? 'secondary';
    }
}