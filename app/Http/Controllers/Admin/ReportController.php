<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Report;
use App\Notification\GeneralNotification;

class ReportController extends Controller
{
    public function __construct()
    {
        // gunakan class penuh agar tidak bergantung pada alias 'is_admin'
        $this->middleware([\Illuminate\Auth\Middleware\Authenticate::class, \App\Http\Middleware\IsAdmin::class]);
    }

    // index sekarang hanya menampilkan pending, DI-KELOMPOKKAN per post
    public function index(Request $request)
    {
        $query = Report::with(['user','post','handledBy'])
                       ->where('status', 'pending')
                       ->latest();

        // tetap pakai pagination seperti biasa
        $reports = $query->paginate(12)->withQueryString();

        // kelompokkan collection berdasarkan post_id → 1 baris = 1 postingan
        $groupedReports = $reports->getCollection()->groupBy('post_id');

        $counts = [
            'total'    => Report::count(),
            'accepted' => Report::where('status','accepted')->count(),
            'rejected' => Report::where('status','rejected')->count(),
            'pending'  => Report::where('status','pending')->count(),
        ];

        return view('admin.reports.index', [
            'reports'        => $reports,        // untuk pagination
            'groupedReports' => $groupedReports, // untuk tabel
            'counts'         => $counts,
        ]);
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

        if ($request->status === 'accepted' && $post && !$post->trashed()) {
            $owner = $post->user;

            // Hapus postingan dari database (dan seluruh komentar jika ada)
            if (method_exists($post, 'comments')) {
                $post->comments()->delete();
            }
            $post->delete();

            // Kirim hanya SATU notifikasi ke user (ada link WhatsApp admin)
            if ($owner) {
                $adminUrl = "https://wa.me/6283140266116?text=" . urlencode("Halo admin, saya ingin menanyakan tentang laporan postingan saya yang dihapus.");
                $notifMsg = "Postingan Anda telah dihapus oleh admin. <a href='{$adminUrl}' style='color:#2c7774;' target='_blank'>Hubungi admin untuk informasi lebih lanjut</a>";

                $extra = [
                    'post_id' => $report->post_id,
                    'type' => 'report',
                ];
                $owner->notify(new GeneralNotification('report', $notifMsg, $extra));
            }
        }

        // Update status laporan + admin yang menangani (report yang dipilih)
        $report->status     = $request->status;
        $report->handled_by = auth()->id();
        $report->save();

        // Tambahan: update juga semua laporan pending lain di postingan yang sama
        if ($report->post_id) {
            Report::where('post_id', $report->post_id)
                ->where('id', '!=', $report->id)
                ->where('status', 'pending')
                ->update([
                    'status'     => $request->status,
                    'handled_by' => auth()->id(),
                ]);
        }

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

        // ambil semua laporan lain pada postingan yang sama
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

        // pastikan hanya laporan yang SUDAH diproses boleh diakses
        if (!in_array($report->status, ['accepted', 'rejected'])) {
            abort(404);
        }

        // ambil semua laporan accepted/rejected untuk postingan yang sama
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
