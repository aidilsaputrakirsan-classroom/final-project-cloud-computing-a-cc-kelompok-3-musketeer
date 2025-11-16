<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Report;

class ReportController extends Controller
{
    public function __construct()
    {
        // gunakan class penuh agar tidak bergantung pada alias 'is_admin'
        $this->middleware([\Illuminate\Auth\Middleware\Authenticate::class, \App\Http\Middleware\IsAdmin::class]);
    }

    // index sekarang hanya menampilkan pending
    public function index(Request $request)
    {
        $query = Report::with(['user','post','handledBy'])
                       ->where('status', 'pending')
                       ->latest();

        $reports = $query->paginate(12)->withQueryString();

        $counts = [
            'total'    => Report::count(),
            'accepted' => Report::where('status','accepted')->count(),
            'rejected' => Report::where('status','rejected')->count(),
            'pending'  => Report::where('status','pending')->count(),
        ];

        return view('admin.reports.index', compact('reports','counts'));
    }

    // history: menampilkan laporan yang sudah diproses (accepted & rejected)
    public function history(Request $request)
    {
        $query = Report::with(['user','post','handledBy'])
                       ->whereIn('status', ['accepted','rejected'])
                       ->latest();

        if ($request->filled('status') && in_array($request->status, ['accepted','rejected'])) {
            $query->where('status', $request->status);
        }

        $reports = $query->paginate(12)->withQueryString();

        $counts = [
            'total'    => Report::count(),
            'accepted' => Report::where('status','accepted')->count(),
            'rejected' => Report::where('status','rejected')->count(),
            'pending'  => Report::where('status','pending')->count(),
        ];

        return view('admin.reports.history', compact('reports','counts'));
    }

    // unchanged
    public function changeStatus(Request $request, Report $report)
    {
        $request->validate(['status' => 'required|in:pending,accepted,rejected']);
        $report->status = $request->status;
        $report->handled_by = auth()->id();
        $report->save();

        return back()->with('success','Status laporan diperbarui.');
    }

    public function show(Report $report)
    {
        $report->load(['user','post','handledBy']);
        return view('admin.reports.show', compact('report'));
    }
}
