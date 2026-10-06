<?php
namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeNotificationController extends Controller
{
    public function index()
    {
        $user = auth()->user();
       
        $instituteId = $user->institute_id;

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

        // Get counts by category
        $categoryCounts = [
            'notice_created' => $user->notifications()
                ->where('data->institute_id', $instituteId)
                ->where('data->type', 'notice_created')
                ->count(),
            'announcement' => $user->notifications()
                ->where('data->institute_id', $instituteId)
                ->where('data->type', 'announcement')
                ->count(),
            'meeting' => $user->notifications()
                ->where('data->institute_id', $instituteId)
                ->where('data->type', 'meeting')
                ->count(),
            'hr_update' => $user->notifications()
                ->where('data->institute_id', $instituteId)
                ->where('data->type', 'hr_update')
                ->count(),
        ];

        return view('instituteAdmin.EmployeeFiles.EmployeeNotifications', compact(
            'notifications', 
            'unreadCount', 
            'readCount', 
            'thisMonthCount',
            'categoryCounts'
        ));
    }

    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->find($id);
        
        if ($notification) {
            $notification->markAsRead();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $notification = auth()->user()->notifications()->find($id);
        
        if ($notification) {
            $notification->delete();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }

    public function destroyAll()
    {
        auth()->user()->notifications()->delete();
        return response()->json(['success' => true]);
    }

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
                return [
                    'id' => $notification->id,
                    'type' => $notification->data['type'] ?? 'general',
                    'title' => $notification->data['title'] ?? 'Notification',
                    'message' => $notification->data['message'] ?? '',
                    'time' => $notification->created_at->diffForHumans(),
                    'action_url' => $notification->data['action_url'] ?? '#',
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

    public function unreadCount()
    {
        $count = auth()->user()->unreadNotifications()
            ->where('data->institute_id', auth()->user()->institute_id)
            ->count();

        return response()->json(['count' => $count]);
    }

    public function saveSettings(Request $request)
    {
        $settings = $request->input('settings');
        
        // Save settings to user meta or preferences table
        // You can create a user_preferences table to store these
        
        return response()->json(['success' => true]);
    }

    public function exportPDF()
    {
        $user = auth()->user();
        $notifications = $user->notifications()
            ->where('data->institute_id', $user->institute_id)
            ->get();

        // Generate PDF logic here
        // You can use barryvdh/laravel-dompdf package
        
        return response()->json(['success' => true]);
    }

    public function exportExcel()
    {
        $user = auth()->user();
        $notifications = $user->notifications()
            ->where('data->institute_id', $user->institute_id)
            ->get();

        // Generate Excel logic here
        // You can use maatwebsite/excel package
        
        return response()->json(['success' => true]);
    }
}