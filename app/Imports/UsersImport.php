<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class UsersImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $password = $row['password'];

        // Cek apakah password hashing aktif di .env
        if (config('app.use_password_hashing', true)) {
            $password = Hash::make($password);
        }

        return new User([
            'name'     => $row['name'],
            'username' => $row['username'],
            'email'    => $row['email'],
            'nis_nip'  => $row['nis_nip'],
            'password' => $password,
            'role'     => strtolower($row['role']) ?: 'user',
        ]);
    }

    public function rules(): array
    {
        return [
            'name'     => 'required|min:3',
            'username' => 'required|min:5|unique:users,username',
            'email'    => 'required|email|unique:users,email',
            'nis_nip'  => 'required|min:10|unique:users,nis_nip',
            'password' => 'required|min:5',
            'role'     => 'nullable|in:user,librarian,admin,User,Librarian,Admin',
        ];
    }
}
