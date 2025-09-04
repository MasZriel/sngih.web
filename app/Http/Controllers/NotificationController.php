<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function read($id)
    {
        $notification = Auth::user()->notifications()->find($id);

        if ($notification) {
            $notification->markAsRead();
            return redirect($notification->data['url'] ?? '/');
        }

        return redirect()->back()->with('error', 'Notifikasi tidak ditemukan.');
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return redirect()->back()->with('success', 'Semua notifikasi telah ditandai sebagai telah dibaca.');
    }
}