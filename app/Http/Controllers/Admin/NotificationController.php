<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get all unread notifications for the authenticated admin user.
     */
    public function getUnread()
    {
        $admin = Auth::guard('admin')->user();
        
        if (!$admin) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        $notifications = $admin->notifications()
            ->where('read_at', null)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get(['id', 'data', 'created_at']);
        
        return response()->json([
            'notifications' => $notifications,
            'count' => $notifications->count()
        ]);
    }
    
    /**
     * Get all notifications for the authenticated admin user.
     */
    public function getAll()
    {
        $admin = Auth::guard('admin')->user();
        
        if (!$admin) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        $notifications = $admin->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return response()->json($notifications);
    }
    
    /**
     * Mark a specific notification as read.
     */
    public function markAsRead($id)
    {
        $admin = Auth::guard('admin')->user();
        
        if (!$admin) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        $notification = $admin->notifications()->find($id);
        
        if (!$notification) {
            return response()->json(['error' => 'Notification not found'], 404);
        }
        
        $notification->markAsRead();
        
        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read'
        ]);
    }
    
    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        $admin = Auth::guard('admin')->user();
        
        if (!$admin) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        $admin->unreadNotifications->markAsRead();
        
        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read'
        ]);
    }
    
    /**
     * Delete a specific notification.
     */
    public function delete($id)
    {
        $admin = Auth::guard('admin')->user();
        
        if (!$admin) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        $notification = $admin->notifications()->find($id);
        
        if (!$notification) {
            return response()->json(['error' => 'Notification not found'], 404);
        }
        
        $notification->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Notification deleted successfully'
        ]);
    }
    
    /**
     * Delete all notifications.
     */
    public function deleteAll()
    {
        $admin = Auth::guard('admin')->user();
        
        if (!$admin) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        $admin->notifications()->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'All notifications deleted successfully'
        ]);
    }
}