<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | FORM LOGIN
    |--------------------------------------------------------------------------
    */
    public function loginForm()
    {
        return view('auth.login');
    }

    /*
    |--------------------------------------------------------------------------
    | PROSES LOGIN
    |--------------------------------------------------------------------------
    */
    public function login(Request $request)
    {
        // Validasi input login_id (email atau NIM/NIP) dan password
        $request->validate([
            'login_id' => 'required|string',
            'password' => 'required|string'
        ]);

        $login_id = $request->login_id;
        $password = $request->password;

        // Tentukan field untuk login
        $fieldType = filter_var($login_id, FILTER_VALIDATE_EMAIL) ? 'email' : 'nim_nip';

        // Attempt login menggunakan Auth::attempt
        if (Auth::attempt([$fieldType => $login_id, 'password' => $password])) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Redirect berdasarkan role
            switch ($user->role) {
                case 'admin':
                    return redirect()->route('admin.surat_izin.index');
                case 'dosen':
                    return redirect()->route('dosen.dashboard');
                case 'mahasiswa':
                    return redirect()->route('mahasiswa.surat_izin.index');
                default:
                    Auth::logout();
                    return back()->with('error', 'Role tidak dikenali!');
            }
        }

        return back()->with('error', 'Email / NIM-NIP atau password salah!');
    }

    /*
    |--------------------------------------------------------------------------
    | FORM REGISTER (Mahasiswa)
    |--------------------------------------------------------------------------
    */
    public function registerForm()
    {
        return view('auth.register');
    }

    /*
    |--------------------------------------------------------------------------
    | PROSES REGISTER (Default Mahasiswa)
    |--------------------------------------------------------------------------
    */
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'nim_nip'  => 'required|unique:users,nim_nip',
            'prodi'    => 'required|string',
            'kelas'    => 'required|string',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'role'     => 'mahasiswa', // otomatis mahasiswa
            'nim_nip'  => $request->nim_nip,
            'prodi'    => $request->prodi,
            'kelas'    => $request->kelas,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login.form')
            ->with('success', 'Akun berhasil dibuat, silakan login.');
    }

    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.form')
            ->with('success', 'Berhasil logout!');
    }
}
