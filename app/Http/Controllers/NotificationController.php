<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()->paginate(20);
        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        
        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        
        return response()->json(['success' => true]);
    }

    public function getUnreadCount()
    {
        $count = Auth::user()->unreadNotifications()->count();
        return response()->json(['count' => $count]);
    }

    public function getRecentNotifications()
    {
        $notifications = Auth::user()->notifications()->take(5)->get();
        
        $formattedNotifications = $notifications->map(function ($notification) {
            $data = $notification->data;
            
            return [
                'id' => $notification->id,
                'title' => $data['message'] ?? 'Stock Update',
                'text' => "Stock for {$data['product_name']} updated from {$data['old_quantity']} to {$data['new_quantity']}",
                'time' => $notification->created_at->diffForHumans(),
                'type' => $data['type'] ?? 'stock_update',
                'read' => $notification->read_at !== null,
                'url' => route('products.show', $data['product_id'] ?? 1)
            ];
        });
        
        return response()->json($formattedNotifications);
    }
}
