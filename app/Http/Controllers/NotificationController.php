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
            
            // Handle different notification types
            if ($data['type'] === 'stock_update') {
                $text = "Stock for " . ($data['product_name'] ?? 'Unknown Product') . " updated from " . ($data['old_quantity'] ?? 'N/A') . " to " . ($data['new_quantity'] ?? 'N/A');
                $url = route('products.show', $data['product_id'] ?? 1);
            } elseif ($data['type'] === 'low_stock_alert') {
                $text = "🚨 " . ($data['stock_status'] ?? 'LOW STOCK') . " - " . ($data['product_name'] ?? 'Unknown Product') . " (Current: " . ($data['current_quantity'] ?? 'N/A') . " units)";
                $url = route('products.show', $data['product_id'] ?? 1);
            } elseif (str_starts_with($data['type'] ?? '', 'stock_request')) {
                if ($data['type'] === 'stock_request_new') {
                    $text = "📦 New Stock Request: " . ($data['product_name'] ?? 'Unknown Product') . " - Quantity: " . ($data['quantity_requested'] ?? 'N/A') . " (from " . ($data['requester_name'] ?? 'Staff') . ")";
                } else {
                    $text = ($data['product_name'] ?? 'Unknown Product') . " - Quantity: " . ($data['quantity_requested'] ?? 'N/A');
                }
                $url = route('stock-requests.show', $data['stock_request_id'] ?? 1);
            } else {
                $text = $data['message'] ?? 'Notification details not available';
                $url = $data['url'] ?? '#';
            }
            
            // Set appropriate title based on notification type
            if ($data['type'] === 'stock_request_new') {
                $title = "📦 New Stock Request";
            } elseif ($data['type'] === 'low_stock_alert') {
                $title = "🚨 Low Stock Alert";
            } else {
                $title = $data['message'] ?? 'Notification';
            }
            
            return [
                'id' => $notification->id,
                'title' => $title,
                'text' => $text,
                'time' => $notification->created_at->diffForHumans(),
                'type' => $data['type'] ?? 'unknown',
                'read' => $notification->read_at !== null,
                'url' => $url
            ];
        });
        
        return response()->json($formattedNotifications);
    }
}
