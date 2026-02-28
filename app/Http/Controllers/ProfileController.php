<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    // Tampilkan halaman profile
    public function index()
    {
        $user = Auth::user(); // pasti ada karena route pakai middleware auth
        return view('profile.index', compact('user'));
    }

    // Update profile
    public function update(Request $request)
    {
        $user = Auth::user(); // pastikan route pakai auth

        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6|confirmed',
        ]);

        // Update user
        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save(); // Aman, ini method bawaan Eloquent

        return redirect()->back()->with('success', 'Profile berhasil diperbarui!');
    }
}
