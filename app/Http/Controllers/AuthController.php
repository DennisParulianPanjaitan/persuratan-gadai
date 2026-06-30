<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman form login
     */
    public function showLoginForm()
    {
        // Jika user sudah login, langsung arahkan ke dashboard
        if (Auth::check()) {
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->route('pelanggan.dashboard');
            }
        }
        
        return view('auth.login');
    }

    /**
     * Proses autentikasi user
     */
    public function login(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        // Fitur "Ingat Saya" (Remember Me)
        $remember = $request->has('remember');

        // Proses pengecekan oleh Auth (Pak Satpam)
        if (Auth::attempt($credentials, $remember)) {
            // Jika berhasil masuk, regenerasi session untuk mencegah session fixation attack
            $request->session()->regenerate();

            // Arahkan berdasarkan role
            if (Auth::user()->role === 'admin') {
                return redirect()->intended('/admin/dashboard')->with('success', 'Selamat datang kembali, Administrator!');
            } else {
                $pelanggan = \App\Models\PelangganModels::where('id_user', Auth::id())->first();
                $namaSapaan = $pelanggan ? $pelanggan->nama : Auth::user()->username;
                return redirect()->intended('/pelanggan/dashboard')->with('success', 'Berhasil masuk! Selamat datang, ' . $namaSapaan . '!');
            }
        }

        // Jika username atau password salah
        return back()->withErrors([
            'username' => 'Username atau kata sandi yang Anda masukkan salah.',
        ])->onlyInput('username');
    }

    /**
     * Proses logout user
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
