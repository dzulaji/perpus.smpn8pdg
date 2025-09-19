@extends('admin.layouts.main')

@section('style')
    <style>
        /* Kelas kustom untuk merapatkan jarak baris tabel detail */
        .table-detail-user td {
            padding-top: 0.3rem;
            padding-bottom: 0.3rem;
            padding-left: 0;
            padding-right: 0.5rem;
        }
    </style>
@endsection

@section('main-content')
    <div class="container mt-4" style="margin-bottom: 6rem">
        {{-- breadcrumb --}}
        <nav class="my-4" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/admin" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="/admin/users" class="text-decoration-none">Users</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $user->name }}</li>
            </ol>
        </nav>

        {{-- card --}}
        <div class="row">
            <div class="col-md-3">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        @if ($user->photo)
                            <img src="{{ asset('storage/' . $user->photo) }}" alt="User Photo" class="img-fluid rounded">
                        @else
                            <img src="{{ asset('template/img/undraw_profile.svg') }}" alt="Default Photo" class="img-fluid">
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 fw-bold">Detail Pengguna</h6>
                    </div>
                    <div class="card-body">
                        {{-- REVISI: Menggunakan struktur tabel standar untuk kerapian --}}
                        <table class="table table-borderless table-detail-user">
                            <tbody>
                                <tr>
                                    <td class="fw-medium" style="width: 170px;">Nama</td>
                                    <td style="width: 10px;">:</td>
                                    <td>{{ $user->name }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-medium">NIS/NIP</td>
                                    <td>:</td>
                                    <td>{{ $user->nis_nip }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-medium">Email</td>
                                    <td>:</td>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-medium">Password</td>
                                    <td>:</td>
                                    <td>(Terenkripsi)</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- Aksi --}}
                    <div class="card-header py-3">
                        <h6 class="m-0 fw-bold">Aksi</h6>
                    </div>
                    <div class="card-body d-flex align-items-start gap-2">
                        {{-- Tombol Edit yang memicu modal (dikembalikan) --}}
                        <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                            data-bs-target="#modalEdit{{ $user->id }}">
                            <i class="bi bi-pencil-square"></i> Edit
                        </button>

                        {{-- Tombol Reset Password yang baru --}}
                        <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                            data-bs-target="#modalResetPassword">
                            <i class="fas fa-key"></i> Reset Password
                        </button>

                        {{-- Tombol Hapus dengan SweetAlert --}}
                        @if (auth()->user()->id !== $user->id)
                            <button type="button" class="btn btn-danger delete-button" data-id="{{ $user->id }}">
                                <i class="bi bi-x-circle"></i> Delete
                            </button>
                            <form id="delete-form-{{ $user->id }}" action="/admin/users/{{ $user->id }}"
                                method="post" style="display: none;">
                                @csrf
                                @method('delete')
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // SweetAlert untuk konfirmasi hapus
        document.querySelectorAll('.delete-button').forEach(button => {
            button.addEventListener('click', function() {
                const userId = this.getAttribute('data-id');
                Swal.fire({
                    title: 'Anda yakin?',
                    text: "Pengguna ini akan dihapus secara permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('delete-form-' + userId).submit();
                    }
                });
            });
        });

        // Notifikasi sukses atau error
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Sukses!',
                text: '{{ session('success') }}',
                timer: 2500,
                showConfirmButton: false
            });
        @endif

        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ $errors->first() }}',
            });
        @endif
    </script>
@endsection

<div class="modal fade" id="modalEdit{{ $user->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <form action="/admin/users/{{ $user->id }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('put')
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Pengguna</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- Semua field untuk edit profil pengguna ada di sini --}}
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" class="form-control" name="name" value="{{ $user->name }}">
                    </div>
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" name="username" value="{{ $user->username }}">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" value="{{ $user->email }}">
                    </div>
                    <div class="mb-3">
                        <label for="nis_nip" class="form-label">NIS/NIP</label>
                        <input type="text" class="form-control" name="nis_nip" value="{{ $user->nis_nip }}">
                    </div>
                    <div class="mb-3">
                        <label for="old_password" class="form-label">Password Lama</label>
                        <input type="password" class="form-control" name="old_password">
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">Password Baru</label>
                        <input type="password" class="form-control" name="new_password">
                    </div>
                    <div class="mb-3">
                        <label for="photo" class="form-label">Foto Pengguna</label>
                        <input class="form-control" type="file" name="photo">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalResetPassword" tabindex="-1" aria-labelledby="modalResetPasswordLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.users.reset_password', $user) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalResetPasswordLabel">Reset Password untuk {{ $user->name }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Masukkan password sementara yang baru untuk pengguna ini.</p>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password Baru</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" class="form-control" id="password_confirmation"
                            name="password_confirmation" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Password Baru</button>
                </div>
            </div>
        </form>
    </div>
</div>
