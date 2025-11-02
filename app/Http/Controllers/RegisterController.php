<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function index()
    {
        return view('auth_manual.register');
    }

    public function store(Request $request)
    {
    $validated = $request->validate([
        'name' => 'required|string|max:50',
        'email' => 'required|email|unique:users', // Validasi email standar
        'password' => [
            'required',
            'confirmed',
            Password::min(8)
                ->mixedCase()    // Wajib ada uppercase & lowercase
                ->numbers(),     // Wajib ada angka/numerik
        ]
    ]);

    $validated['password'] = Hash::make($validated['password']);
    User::create($validated);

    return redirect('/login')->with('success', 'Registrasi berhasil. Silakan login.');
    }
}
