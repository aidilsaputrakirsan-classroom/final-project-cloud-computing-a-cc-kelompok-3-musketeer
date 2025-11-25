<?php

namespace App\Http\Controllers;

use App\Models\User;
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

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            // Store new profile picture
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $validated['profile_picture'] = $path;
        } else {
            // Keep existing profile picture
            $validated['profile_picture'] = $user->profile_picture;
        }

        $user->update($validated);

        return redirect()->route('profile.edit')
            ->with('success', 'Profile berhasil diperbarui!');
    }

    /**
     * Show a public profile page for a given user.
     *
     * This is a public view (no auth required). It displays the user's info
     * and their posts (paginated). This method is safe to add and won't affect
     * edit/update/myPosts behavior.
     */
    public function show(User $user)
    {
        // Eager load counts for posts to reduce queries
        $posts = $user->posts()
            ->withCount('comments')
            ->latest()
            ->paginate(10);

        // We can show a lightweight public profile view. Create resource/views/profile/show.blade.php
        // If you prefer another view name, update route accordingly.
        return view('profile.show', compact('user', 'posts'));
    }
}
