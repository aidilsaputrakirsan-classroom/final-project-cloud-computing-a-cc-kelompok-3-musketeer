<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\Post;
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
