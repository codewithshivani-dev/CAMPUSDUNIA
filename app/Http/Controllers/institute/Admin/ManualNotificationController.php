<?php
namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;

use App\Models\ManualNotification;
use App\Models\User;
use Illuminate\Http\Request;

class ManualNotificationController extends Controller
{
    // Form to create
    public function create()
{
    // fetch all users with roles superadmin OR employee
    $users = User::role(['admin', 'employee'])->get();

    return view('instituteAdmin.DashboardFiles.createnotification', compact('users'));
}

    // Save notification
    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        ManualNotification::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'title' => $request->title,
            'message' => $request->message,
        ]);

        return redirect()->back()->with('success', 'Notification sent successfully!');
    }

    // Show received notifications
    public function inbox()
    {
        $notifications = ManualNotification::where('receiver_id', auth()->id())
                                           ->latest()
                                           ->paginate(10);
        return view('notifications.inbox', compact('notifications'));
    }

    // Mark as read
    public function markAsRead($id)
    {
        $notification = ManualNotification::where('receiver_id', auth()->id())->find($id);
        if ($notification) {
            $notification->update(['is_read' => true]);
        }
        return redirect()->back();
    }
}
