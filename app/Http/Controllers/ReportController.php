<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use App\Notification\GeneralNotification;

class ReportController extends Controller
{
    // USER KIRIM LAPORAN
    public function store(Request $request, $postId)
    {
        $request->validate([
            'reason'  => 'required|string|max:255',
            'details' => 'nullable|string',
        ]);

        // Cek jika post sudah dihapus (soft delete)
        $post = Post::findOrFail($postId);
        if ($post->trashed()) {
            return redirect()->back()->with('error', 'Postingan tidak dapat dilaporkan karena telah dihapus.');
        }

        Report::create([
            'user_id' => Auth::id(),
            'post_id' => $post->id,
            'reason'  => $request->reason,
            'details' => $request->details,
        ]);

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

        $report->status     = 'accepted';
        $report->handled_by = Auth::id();
        $report->save();

        $owner = $report->post->user ?? null;
        if ($owner) {
            $adminUrl = "https://wa.me/6283140266116?text=" . urlencode("Halo admin, saya ingin menanyakan tentang laporan postingan saya yang dihapus.");
            $notifMsg = "Postingan Anda telah dihapus oleh admin. <a href='{$adminUrl}' style='color:#46B6B0;' target='_blank'>Hubungi admin untuk informasi lebih lanjut</a>";

            $extra = [
                'post_id' => $report->post_id,
                'type' => 'report',
            ];
            $owner->notify(new GeneralNotification('report', $notifMsg, $extra));
        }

    return redirect()
        ->route('admin.reports.index')
        ->with('success', 'Laporan diterima, postingan telah dihapus.');
    }

    // ADMIN MENOLAK LAPORAN -> TIDAK HAPUS POST
    public function reject(Report $report)
    {
        $report->status     = 'rejected';
        $report->handled_by = Auth::id();
        $report->save();

        return redirect()
            ->route('admin.reports.index')
            ->with('success', 'Laporan ditolak, postingan tetap ada.');
    }
}
