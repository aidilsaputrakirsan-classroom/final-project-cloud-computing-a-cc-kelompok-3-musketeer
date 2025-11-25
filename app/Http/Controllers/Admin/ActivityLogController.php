<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Tampilkan daftar activity log untuk admin.
     */
    public function index(Request $request)
    {
        $query = ActivityLog::query()
            ->with('user')
            ->latest();

        $actionPrefix = $request->string('action')->trim();
        if ($actionPrefix->isNotEmpty()) {
            $query->where('action', 'like', $actionPrefix->append('%')->toString());
        }

        if ($request->filled('user')) {
            $query->where('user_id', $request->integer('user'));
        }

        $search = $request->string('search')->trim();
        if ($search->isNotEmpty()) {
            $searchTerm = $search->toString();
            $query->where(function ($q) use ($searchTerm) {
                $term = "%{$searchTerm}%";
                $q->where('description', 'like', $term)
                    ->orWhere('context', 'like', $term);
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date('date_to'));
        }

        $logs = $query->paginate(20)->withQueryString();

        $users = User::query()
            ->orderBy('name')
            ->pluck('name', 'id');

        return view('admin.activity-logs.index', [
            'logs' => $logs,
            'users' => $users,
            'filters' => $request->only(['user', 'action', 'search', 'date_from', 'date_to']),
        ]);
    }
}

