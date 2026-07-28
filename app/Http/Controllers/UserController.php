<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Menampilkan daftar pengguna (hanya untuk admin).
     */
    public function index()
    {
        if (Auth::user()->role == 'admin') {
            $users = User::all();
            return view('admin.pages.users.index', compact('users'));
        } else {
            return redirect()->route('profile.show', Auth::user()->id);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Biasanya tidak digunakan jika penambahan user melalui modal
    }

    /**
     * Menyimpan pengguna baru ke database dengan logika hashing yang fleksibel.
     */
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        // 1. Validasi data input
        $validatedData = $request->validate([
            'name' => 'required|min:3',
            'username' => 'required|min:5|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'nis_nip' => 'required|min:10|unique:users,nis_nip',
            'password' => 'required|min:5',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'role' => 'required|in:user,librarian,admin',
        ], [
            'username.unique' => 'Oops, Username tersebut sudah digunakan orang lain.',
            'email.unique' => 'Oops, Email tersebut sudah terdaftar.',
            'nis_nip.unique' => 'Oops, NIS/NIP tersebut sudah terdaftar.',
            'name.required' => 'Nama wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.min' => 'Username minimal 5 karakter.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 5 karakter.',
            'nis_nip.required' => 'NIS/NIP wajib diisi.',
            'nis_nip.min' => 'NIS/NIP minimal 10 karakter.',
        ]);

        // 2. Logika Hashing Fleksibel
        // Memeriksa "saklar" di file config/app.php (yang membaca .env)
        if (config('app.use_password_hashing', true)) {
            // Jika saklar ON (true), lakukan hashing
            $validatedData['password'] = Hash::make($validatedData['password']);
        }
        // Jika saklar OFF (false), password akan disimpan sebagai teks biasa
        // karena kita tidak melakukan apa-apa terhadap $validatedData['password'].

        // 3. Proses upload foto (jika ada)
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $photoPath = $file->store('photos', 'public');
            $validatedData['photo'] = $photoPath;
        }

        // 4. Simpan data user baru
        User::create($validatedData);

        return redirect()->back()->with('success', 'Pengguna berhasil ditambahkan');
    }

    /**
     * Menampilkan profil atau detail pengguna.
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        if (Auth::user()->role == 'admin') {
            return view('admin.pages.users.show', compact('user'));
        } elseif (Auth::user()->id == $id) {
            return view('show', compact('user'));
        } else {
            return abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Biasanya tidak digunakan jika edit user melalui modal
    }

    /**
     * Memperbarui data pengguna di database dengan logika hashing yang fleksibel.
     */
    public function update(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin' && Auth::id() != $id) {
            abort(403, 'Unauthorized action.');
        }

        // 1. Validasi data input
        $validatedData = $request->validate([
            'name' => 'required|min:3',
            'username' => 'required|min:5',
            'email' => 'required|email',
            'nis_nip' => 'required|min:10',
            'old_password' => 'nullable',
            'new_password' => 'nullable|min:5',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.min' => 'Username minimal 5 karakter.',
            'email.required' => 'Email wajib diisi.',
            'nis_nip.required' => 'NIS/NIP wajib diisi.',
            'nis_nip.min' => 'NIS/NIP minimal 10 karakter.',
            'new_password.min' => 'Password baru minimal 5 karakter.',
        ]);

        $user = User::findOrFail($id);

        // 2. Logika Hashing Fleksibel untuk Update Password
        if (!empty($request->old_password) && !empty($request->new_password)) {
            $useHashing = config('app.use_password_hashing', true);
            $passwordCocok = false;

            if ($useHashing) {
                // Mode HASH: Cek password lama dengan Hash::check()
                if (Hash::check($request->old_password, $user->password)) {
                    $passwordCocok = true;
                    // Hash password baru sebelum disimpan
                    $validatedData['password'] = Hash::make($request->new_password);
                }
            } else {
                // Mode Non-HASH: Cek password lama dengan perbandingan teks biasa
                if ($request->old_password === $user->password) {
                    $passwordCocok = true;
                    // Simpan password baru sebagai teks biasa
                    $validatedData['password'] = $request->new_password;
                }
            }

            // Jika password lama tidak cocok, kembalikan error
            if (!$passwordCocok) {
                return redirect()->back()->withErrors(['old_password' => 'Password lama tidak sesuai']);
            }
        }

        // 3. Proses upload foto (jika ada)
        if ($request->hasFile('photo')) {
            if ($user->photo && Storage::exists('public/' . $user->photo)) {
                Storage::delete('public/' . $user->photo);
            }
            $file = $request->file('photo');
            $photoPath = $file->store('photos', 'public');
            $validatedData['photo'] = $photoPath;
        }

        // 4. Update data pengguna
        $user->update($validatedData);

        // 5. Redirect berdasarkan role
        if (Auth::user()->role == 'admin') {
            return redirect()->back()->with('success', 'User berhasil diperbarui.');
        } elseif (Auth::user()->id == $id) {
            return redirect()->route('profile.show', $id)->with('success', 'Profil berhasil diperbarui.');
        } else {
            return abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Menghapus pengguna dari database.
     */
    public function destroy($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $user = User::findOrFail($id);

        // Hapus fisik foto profil dari server jika ada
        if ($user->photo) {
            if (Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            } elseif (Storage::exists('public/' . $user->photo)) {
                Storage::delete('public/' . $user->photo);
            }
        }

        $user->delete();
        return redirect('/admin/users')->with('success', 'User berhasil dihapus');
    }

    /**
     * REVISI: Tambahkan metode baru ini untuk mereset password pengguna.
     */
    public function resetPassword(Request $request, User $user)
    {
        // 1. Validasi input
        $validated = $request->validate([
            'password' => 'required|min:5|confirmed', // 'confirmed' akan mencocokkan dengan 'password_confirmation'
        ]);

        // 2. Siapkan data password baru
        $newPassword = $validated['password'];

        // 3. Logika Hashing Fleksibel (menggunakan "saklar" dari .env)
        if (config('app.use_password_hashing', true)) {
            // Jika mode hash aktif, hash password baru
            $newPassword = Hash::make($newPassword);
        }
        // Jika mode hash nonaktif, password disimpan sebagai teks biasa

        // 4. Update password pengguna di database
        $user->update([
            'password' => $newPassword
        ]);

        // 5. Kembalikan ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('success', 'Password untuk pengguna ' . $user->name . ' berhasil direset.');
    }

    /**
     * Import data pengguna dari file Excel.
     */
    public function import(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048'
        ], [
            'file.required' => 'Pilih file terlebih dahulu.',
            'file.mimes' => 'Format file harus berupa Excel (.xlsx, .xls, .csv).',
            'file.max' => 'Ukuran file maksimal 2MB.'
        ]);

        try {
            $import = new \App\Imports\UsersImport;
            \Maatwebsite\Excel\Facades\Excel::import($import, $request->file('file'));

            if ($import->failures()->isNotEmpty()) {
                $errorMessages = [];
                foreach ($import->failures() as $failure) {
                    $rowNumber = $failure->row();
                    $errors = implode(', ', $failure->errors());
                    $errorMessages[] = "Baris ke-$rowNumber gagal diimpor: $errors";
                }
                
                // Jika ada baris yang gagal, kirim pesan error spesifik menggunakan session array
                return redirect()->back()->with('import_errors', $errorMessages);
            }

            return redirect()->back()->with('success', 'Data pengguna berhasil diimpor sepenuhnya!');
            
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['file' => 'Terjadi kesalahan sistem saat membaca file Excel. Pastikan format tabel sesuai dengan template. Error detail: ' . $e->getMessage()]);
        }
    }
}
