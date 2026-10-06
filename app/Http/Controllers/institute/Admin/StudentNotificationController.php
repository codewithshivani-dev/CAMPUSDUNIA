<?php
namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use App\Models\NoticeRead;
use Illuminate\Http\Request;

class StudentNotificationController extends Controller
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

        return view('instituteAdmin.StudentFiles.notification.index', compact(
            'notifications', 
            'unreadCount', 
            'readCount', 
            'thisMonthCount'
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
}