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

        // filter status accepted / rejected jika dipilih
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

        // Simpan dulu referensi post (kalau ada)
        $post = $report->post;

        // Jika admin menerima laporan, hapus postingan & komentar
        if ($request->status === 'accepted' && $post) {

            // hapus komentar kalau ada relasi comments
            if (method_exists($post, 'comments')) {
                $post->comments()->delete();
            }

            // hapus post
            $post->delete();
        }

        // Update status laporan + admin yang menangani
        $report->status     = $request->status;
        $report->handled_by = auth()->id();
        $report->save();

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
        return view('admin.reports.show', compact('report'));
    }

    public function showHistory(Report $report)
    {
        $report->load(['user', 'post', 'handledBy']);

        // pastikan hanya laporan yang SUDAH diproses boleh diakses
        if (!in_array($report->status, ['accepted', 'rejected'])) {
            abort(404);
        }

        return view('admin.reports.history_show', compact('report'));
    }
}
