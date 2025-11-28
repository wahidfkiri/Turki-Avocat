<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function show($notificationId)
    {
        $notification = Notification::findOrFail($notificationId);
        
        // Check if user owns the notification or is admin
        if (auth()->user()->hasRole('admin') || $notification->user_id === auth()->id()) {
            // Mark as read when viewing
            $notification->update(['is_read' => true]);
            
            return redirect()->route('tasks.show', ['task' => $notification->task_id]);
        }
        
        abort(403, 'Unauthorized');
    }

    public function index(Request $request)
    {
        if(auth()->user()->hasRole('admin')) {
            $query = Notification::query();
        } else {
        $query = auth()->user()->notifications();
        }
        
        // Apply status filter
        if ($request->has('status')) {
            if ($request->status === 'unread') {
                $query->where('is_read', false);
            } elseif ($request->status === 'read') {
                $query->where('is_read', true);
            }
        }
        
        // Apply search filter
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('message', 'like', "%{$search}%")
                  ->orWhere('data', 'like', "%{$search}%");
            });
        }
        
        $notifications = $query->latest()->paginate(20);
        
        // Get counts for summary cards
        if(auth()->user()->hasRole('admin')) {
            $totalNotifications = Notification::count();
            $unreadNotifications = Notification::where('is_read', false)->count();
            $readNotifications = Notification::where('is_read', true)->count();
            $todayNotifications = Notification::whereDate('created_at', today())->count();
        } else {
        $totalNotifications = auth()->user()->notifications()->count();
        $unreadNotifications = auth()->user()->notifications()->where('is_read', false)->count();
        $readNotifications = auth()->user()->notifications()->where('is_read', true)->count();
        $todayNotifications = auth()->user()->notifications()->whereDate('created_at', today())->count();
        }
        
        return view('notifications.index', compact(
            'notifications',
            'totalNotifications',
            'unreadNotifications',
            'readNotifications',
            'todayNotifications'
        ));
    }

    public function destroy(Notification $notification)
    {
        // Check if user owns the notification or is admin
        if (auth()->user()->hasRole('admin') || $notification->user_id === auth()->id()) {
            $notification->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Notification deleted successfully'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized'
        ], 403);
    }

    public function markAsRead(Notification $notification)
    {
        // Check if user owns the notification or is admin
        if (auth()->user()->hasRole('admin') || $notification->user_id === auth()->id()) {
            $notification->update(['is_read' => true]);
            
            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized'
        ], 403);
    }

    public function markAsUnread(Notification $notification)
    {
        // Check if user owns the notification or is admin
        if (auth()->user()->hasRole('admin') || $notification->user_id === auth()->id()) {
            $notification->update(['is_read' => false]);
            
            return response()->json([
                'success' => true,
                'message' => 'Notification marked as unread'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized'
        ], 403);
    }

    public function markAllRead()
    {
        auth()->user()->notifications()->update(['is_read' => true]);
        
        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read'
        ]);
    }

    public function markMultipleRead(Request $request)
    {
        $ids = $request->ids;
        
        $query = auth()->user()->notifications()->whereIn('id', $ids);
        $query->update(['is_read' => true]);
        
        return response()->json([
            'success' => true,
            'message' => 'Selected notifications marked as read'
        ]);
    }

    public function deleteMultiple(Request $request)
    {
        $ids = $request->ids;
        
        $query = auth()->user()->notifications()->whereIn('id', $ids);
        $query->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Selected notifications deleted successfully'
        ]);
    }

    public function deleteAllRead()
    {
        auth()->user()->notifications()->where('is_read', true)->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'All read notifications deleted successfully'
        ]);
    }
}