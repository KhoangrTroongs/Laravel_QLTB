<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $notifications = $user->notifications()->paginate(20);

        if ($user->hasAnyRole(['admin', 'editor'])) {
            return view('notifications.index', compact('notifications'));
        }

        return view('notifications.public_index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return back()->with('success', 'Đã đánh dấu là đã đọc');
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return back()->with('success', 'Đã đánh dấu tất cả là đã đọc');
    }

    public function unreadCount()
    {
        return response()->json([
            'count' => Auth::user()->unreadNotifications->count(),
        ]);
    }
}
