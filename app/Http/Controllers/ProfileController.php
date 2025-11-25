<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ActivityLog; // <<< TAMBAH INI
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display the user's posts (My Posts page).
     */
    public function myPosts()
    {
        $user = Auth::user();
        $posts = $user->posts()
            ->withCount('comments')
            ->latest()
            ->paginate(10);

        return view('my-posts.index', compact('user', 'posts'));
    }

    /**
     * Show the form for editing the user profile.
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Update the user profile.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Tentukan field apa saja yang dikirim user
        // (hanya yang relevan, sesuai request form)
        $updatedFields = array_keys($request->only(['name', 'email', 'profile_picture']));

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $validated['profile_picture'] = $path;
        } else {
            $validated['profile_picture'] = $user->profile_picture;
        }

        $user->update($validated);

        // === ACTIVITY LOG: profile.updated ===
        ActivityLog::record(
            action: 'profile.updated',
            description: 'User memperbarui profil',
            context: 'profile',
            detail: [
                'user_id' => $user->id,
                'fields'  => $updatedFields, // <--- sesuai permintaan
            ]
        );

        return redirect()->route('profile.edit')
            ->with('success', 'Profile berhasil diperbarui!');
    }
}
