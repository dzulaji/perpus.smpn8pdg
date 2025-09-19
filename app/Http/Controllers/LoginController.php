<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('pages.login');
    }

    public function authenticate(Request $request)
    {
        // 1. Validasi input dari form login
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // 2. Baca nilai "saklar" dari file .env
        // config('app.use_password_hashing') akan membaca variabel USE_PASSWORD_HASHING
        // Jika tidak ada, default-nya adalah 'true' (mode aman)
        $useHashing = config('app.use_password_hashing', true);


        // =================================================================
        // Logika Fleksibel Dimulai di Sini
        // =================================================================

        $loginSuccess = false;

        if ($useHashing) {
            // MODE AMAN (DENGAN HASH)
            // Jika saklar ON, gunakan Auth::attempt() bawaan Laravel
            if (Auth::attempt($credentials)) {
                $loginSuccess = true;
            }
        } else {
            // MODE TIDAK AMAN (TANPA HASH)
            // Jika saklar OFF, lakukan verifikasi manual
            $user = User::where('username', $credentials['username'])->first();
            if ($user && $credentials['password'] === $user->password) {
                Auth::login($user);
                $loginSuccess = true;
            }
        }

        // =================================================================
        // Akhir dari Logika Fleksibel
        // =================================================================


        // 3. Jika login berhasil (baik dengan hash maupun tanpa hash)
        if ($loginSuccess) {
            $request->session()->regenerate();

            // Arahkan pengguna berdasarkan rolenya
            if (auth()->user()->role === 'admin' || auth()->user()->role === 'librarian') {
                return redirect()->intended('/admin');
            }
            return redirect()->intended('/');
        }

        // 4. Jika login gagal, kembalikan dengan pesan error
        return back()->withErrors(['login' => 'Username atau password salah, atau pengguna belum terdaftar.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
