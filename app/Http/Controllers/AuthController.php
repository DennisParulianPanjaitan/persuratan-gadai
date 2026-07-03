<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
    /**
     * Tampilkan halaman lupa password (input username)
     */
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    /**
     * Cek username untuk reset password
     */
    public function checkUsername(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
        ]);

        $user = \App\Models\UserModels::where('username', $request->username)->first();

        if (!$user) {
            return back()->with('error', 'Username tidak ditemukan di sistem.');
        }

        // Simpan id_user di session untuk reset
        $request->session()->put('reset_user_id', $user->id_user);

        return redirect()->route('password.reset.form');
    }

    /**
     * Tampilkan halaman form reset password
     */
    public function showResetPassword(Request $request)
    {
        $userId = $request->session()->get('reset_user_id');
        if (!$userId) {
            return redirect()->route('password.forgot');
        }

        $user = \App\Models\UserModels::find($userId);
        if (!$user) {
            return redirect()->route('password.forgot')->with('error', 'User tidak ditemukan.');
        }

        $pelanggan = null;
        if ($user->role === 'pelanggan') {
            $pelanggan = \App\Models\PelangganModels::where('id_user', $user->id_user)->first();
        }

        return view('auth.reset-password', compact('user', 'pelanggan'));
    }

    /**
     * Proses reset password
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6',
        ]);

        $userId = $request->session()->get('reset_user_id');
        if (!$userId) {
            return redirect()->route('password.forgot')->with('error', 'Sesi Anda telah berakhir. Silakan ulangi proses lupa sandi.');
        }

        $user = \App\Models\UserModels::find($userId);
        if (!$user) {
            return redirect()->route('password.forgot')->with('error', 'User tidak ditemukan.');
        }

        $user->password = Hash::make($request->password);
        $user->save();

        $request->session()->forget('reset_user_id');

        return redirect()->route('login')->with('success', 'Kata sandi berhasil direset! Silakan login dengan kata sandi baru Anda.');
    }

    /**
     * Tampilkan halaman register
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Proses register pelanggan baru
     */
    public function register(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'no_hp' => 'required|string|max:20|unique:m_pelanggan,no_hp',
            'username' => 'required|string|unique:users,username',
            'password' => 'required|min:6|confirmed',
            'alamat' => 'required|string|max:500',
            'email' => 'nullable|email|max:100|unique:m_pelanggan,email',
        ]);

        // Buat user baru
        $user = \App\Models\UserModels::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => 'pelanggan',
            'status_aktif' => 1,
        ]);

        // Buat data pelanggan baru
        \App\Models\PelangganModels::create([
            'id_user' => $user->id_user,
            'nama' => $request->nama,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'email' => $request->email,
            'keterangan' => 'Pendaftar online',
        ]);

        return redirect()->route('login')->with('success', 'Akun berhasil dibuat! Silakan login.');
    }
}
