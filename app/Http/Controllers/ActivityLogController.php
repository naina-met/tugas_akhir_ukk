<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')
                            ->latest();

        // Filter by module
        if ($request->has('module') && $request->module) {
            $query->where('module', $request->module);
        }

        // Filter by action
        if ($request->has('action') && $request->action) {
            $query->where('action', $request->action);
        }

        // Filter by user
        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        // Search by item name
        if ($request->has('search') && $request->search) {
            $query->where('item_name', 'like', '%' . $request->search . '%');
        }

        $activityLogs = $query->paginate(15);
        $users = User::where('role', 'Admin')->get();

        return view('activity-logs', compact('activityLogs', 'users'));
    }
}
