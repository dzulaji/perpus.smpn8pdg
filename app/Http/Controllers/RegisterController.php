<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function index()
    {
        return view('pages.register');
    }

    public function store(Request $request)
    {
        // 1. Validasi data yang diterima (tetap sama)
        $validatedData = $request->validate([
            'name' => 'required|min:3',
            'username' => 'required|min:5|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'nis_nip' => 'required|min:10|unique:users,nis_nip',
            'password' => 'required|min:5',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // ==========================================================
        // REVISI: Logika Hashing Fleksibel ditambahkan di sini
        // ==========================================================
        // Memeriksa "saklar" di file config/app.php
        if (config('app.use_password_hashing', true)) {
            // Jika saklar ON (true), lakukan hashing
            $validatedData['password'] = Hash::make($validatedData['password']);
        }
        // Jika saklar OFF (false), maka $validatedData['password'] akan
        // berisi teks biasa dari form, dan langsung disimpan.
        // ==========================================================
        // AKHIR REVISI
        // ==========================================================

        // 3. Atur role default (tetap sama)
        $validatedData['role'] = 'user';

        // 4. Handle file upload (tetap sama)
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos', 'public');
            $validatedData['photo'] = $photoPath;
        }

        // 5. Simpan data user baru (tetap sama)
        User::create($validatedData);

        // 6. Redirect setelah menyimpan data (tetap sama)
        return redirect('/login')->with('success', 'Registrasi berhasil, silakan login.');
    }
}
