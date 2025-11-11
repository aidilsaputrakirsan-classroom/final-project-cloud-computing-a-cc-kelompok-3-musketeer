<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function store(Request $request, $postId)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
            'details' => 'nullable|string',
        ]);

        $post = Post::findOrFail($postId);

        Report::create([
            'user_id' => Auth::id(),
            'post_id' => $post->id,
            'reason' => $request->reason,
            'details' => $request->details,
        ]);

        return redirect()->back()->with('success', 'Laporan Anda telah dikirim. Terima kasih!');
    }
}
