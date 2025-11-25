<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\ActivityLog; // <<< TAMBAH INI
use App\Notification\GeneralNotification;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware([\Illuminate\Auth\Middleware\Authenticate::class, \App\Http\Middleware\IsAdmin::class]);
    }

    public function index(Request $request)
    {
        $query = Report::with(['user','post','handledBy'])
                       ->where('status', 'pending')
                       ->latest();

        $reports = $query->paginate(12)->withQueryString();

        $groupedReports = $reports->getCollection()->groupBy('post_id');

        $counts = [
            'total'    => Report::count(),
            'accepted' => Report::where('status','accepted')->count(),
            'rejected' => Report::where('status','rejected')->count(),
            'pending'  => Report::where('status','pending')->count(),
        ];

        return view('admin.reports.index', [
            'reports'        => $reports,
            'groupedReports' => $groupedReports,
            'counts'         => $counts,
        ]);
    }

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

    public function changeStatus(Request $request, Report $report)
    {
        $request->validate([
            'status' => 'required|in:pending,accepted,rejected',
        ]);

        $oldStatus = $report->status;
        $post      = $report->post;

        if ($request->status === 'accepted' && $post) {
            $owner = $post->user;

            $owner->notify(new GeneralNotification(
                'post_deleted',
                "Postingan Anda berjudul '{$post->title}': telah dihapus oleh admin karena melanggar ketentuan."
            ));

            $post->delete();
        }

        // Update status laporan utama
        $report->status     = $request->status;
        $report->handled_by = auth()->id();
        $report->save();

        // Update semua laporan pending lain untuk post yg sama
        if ($report->post_id) {
            Report::where('post_id', $report->post_id)
                ->where('id', '!=', $report->id)
                ->where('status', 'pending')
                ->update([
                    'status'     => $request->status,
                    'handled_by' => auth()->id(),
                ]);
        }

        // === ACTIVITY LOG: report.status_changed ===
        ActivityLog::record(
            action: 'report.status_changed',
            description: "Admin mengubah status laporan menjadi {$request->status}",
            context: 'report',
            detail: [
                'report_id' => $report->id,
                'post_id'   => $report->post_id,
                'from'      => $oldStatus,
                'to'        => $request->status,
            ]
        );

        $message = $request->status === 'accepted'
            ? 'Laporan diterima, postingan telah dihapus.'
            : 'Laporan ditolak, postingan tetap ada.';

        return redirect()
            ->route('admin.reports.index')
            ->with('success', $message);
    }

    public function show(Report $report)
    {
        $report->load(['user','post','handledBy']);

        $allReportsForPost = collect();
        if ($report->post_id) {
            $allReportsForPost = Report::with(['user'])
                ->where('post_id', $report->post_id)
                ->orderBy('created_at')
                ->get();
        }

        return view('admin.reports.show', [
            'report'            => $report,
            'allReportsForPost' => $allReportsForPost,
        ]);
    }

    public function showHistory(Report $report)
    {
        $report->load(['user', 'post', 'handledBy']);

        if (!in_array($report->status, ['accepted', 'rejected'])) {
            abort(404);
        }

        $allReportsForPost = collect();

        if ($report->post_id) {
            $allReportsForPost = Report::with(['user'])
                ->where('post_id', $report->post_id)
                ->whereIn('status', ['accepted', 'rejected'])
                ->orderBy('created_at')
                ->get();
        }

        return view('admin.reports.history_show', [
            'report'            => $report,
            'allReportsForPost' => $allReportsForPost,
        ]);
    }
}
