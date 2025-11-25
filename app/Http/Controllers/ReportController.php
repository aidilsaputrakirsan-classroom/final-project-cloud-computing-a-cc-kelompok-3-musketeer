<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\Post;
use App\Models\ActivityLog; // <<< TAMBAH INI
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    // USER KIRIM LAPORAN
    public function store(Request $request, $postId)
    {
        $request->validate([
            'reason'  => 'required|string|max:255',
            'details' => 'nullable|string',
        ]);

        $post = Post::findOrFail($postId);

        $report = Report::create([
            'user_id' => Auth::id(),
            'post_id' => $post->id,
            'reason'  => $request->reason,
            'details' => $request->details,
        ]);

        // === ACTIVITY LOG: report.submitted ===
        ActivityLog::record(
            action: 'report.submitted',
            description: 'User mengirim laporan terhadap postingan',
            context: 'report',
            detail: [
                'report_id' => $report->id,
                'post_id'   => $post->id,
                'reason'    => $report->reason,
            ]
        );

        return redirect()->back()->with('success', 'Laporan Anda telah dikirim. Terima kasih!');
    }

    // ADMIN MENERIMA LAPORAN -> HAPUS POST
    public function accept(Report $report)
    {
        if ($report->post) {
            if (method_exists($report->post, 'comments')) {
                $report->post->comments()->delete();
            }

            $report->post->delete();
        }

        $oldStatus = $report->status;

        $report->status     = 'accepted';
        $report->handled_by = Auth::id();
        $report->save();

        // === ACTIVITY LOG: report.status_changed ===
        ActivityLog::record(
            action: 'report.status_changed',
            description: 'Admin mengubah status laporan menjadi accepted',
            context: 'report',
            detail: [
                'report_id' => $report->id,
                'post_id'   => $report->post_id,
                'from'      => $oldStatus,
                'to'        => 'accepted',
            ]
        );

        return redirect()
            ->route('admin.reports.index')
            ->with('success', 'Laporan diterima, postingan telah dihapus.');
    }

    // ADMIN MENOLAK LAPORAN -> TIDAK HAPUS POST
    public function reject(Report $report)
    {
        $oldStatus = $report->status;

        $report->status     = 'rejected';
        $report->handled_by = Auth::id();
        $report->save();

        // === ACTIVITY LOG: report.status_changed ===
        ActivityLog::record(
            action: 'report.status_changed',
            description: 'Admin mengubah status laporan menjadi rejected',
            context: 'report',
            detail: [
                'report_id' => $report->id,
                'post_id'   => $report->post_id,
                'from'      => $oldStatus,
                'to'        => 'rejected',
            ]
        );

        return redirect()
            ->route('admin.reports.index')
            ->with('success', 'Laporan ditolak, postingan tetap ada.');
    }
}
