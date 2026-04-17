<?php

namespace App\Http\Controllers;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()->notifications()->paginate(20);

        if (auth()->user()->hasAnyRole(['admin', 'editor'])) {
            return view('notifications.index', compact('notifications'));
        }

        return view('notifications.public_index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return back()->with('success', 'Đã đánh dấu là đã đọc');
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return back()->with('success', 'Đã đánh dấu tất cả là đã đọc');
    }

    public function unreadCount()
    {
        return response()->json([
            'count' => auth()->user()->unreadNotifications->count(),
        ]);
    }
}
