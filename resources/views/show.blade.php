@extends('layouts.main')

@section('main-content')
    <div class="container mt-4" style="margin-bottom: 6rem">
        {{-- breadcrumb --}}
        <nav class="my-4" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/" class="text-decoration-none">Beranda</a></li>
                <li class="breadcrumb-item"><a href="/books" class="text-decoration-none">Profil</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $user->name }}</li>
            </ol>
        </nav>

        {{-- card --}}
        <div class="row">
            <!-- Cover Image -->
            <div class="col-md-3">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        @if ($user->photo)
                            <img class="card-img-top" src="{{ asset('storage/' . $user->photo) }}" alt="Card image cap">
                        @else
                            <img class="card-img-top" src="{{ asset('template/img/undraw_profile.svg') }}"
                                alt="Default Photo">
                        @endif
                    </div>
                </div>
            </div>

            <!-- Information -->
            <div class="col-md-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 fw-bold">Detail Profil</h6>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <table>
                            <tr class="d-flex gap-4">
                                <td class="fw-medium" style="width: 100px;">Nama </td>
                                <td>: {{ $user->name }}</td>
                            </tr>
                            <tr class="d-flex gap-4">
                                <td class="fw-medium" style="width: 100px;">NIS/NIP</td>
                                <td>: {{ $user->nis_nip }}</td>
                            </tr>
                            <tr class="d-flex gap-4">
                                <td class="fw-medium" style="width: 100px;">Email </td>
                                <td>: {{ $user->email }}</td>
                            </tr>
                        </table>
                    </div>

                    {{-- proses --}}
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 fw-bold">Aksi</h6>
                    </div>
                    <div class="card-body">
                        <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                            data-bs-target="#exampleModal">
                            Edit
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // SweetAlert for delete confirmation
        function confirmDelete(bookId) {
            Swal.fire({
                title: 'Anda yakin?',
                text: "Anda tidak akan dapat mengembalikan data ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + bookId).submit();
                }
            });
        }

        // Check if there are any success or error messages
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Sukses!',
                text: '{{ session('success') }}',
                confirmButtonText: 'Ok'
            });
        @endif

        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ $errors->first() }}',
                confirmButtonText: 'Ok'
            });
        @endif
    </script>
@endsection

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('profile.update', $user->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Ubah Profil</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama <small>(minimal 3 karakter)</small></label>
                        <input type="text" class="form-control" id="name" name="name"
                            value="{{ $user->name }}">
                    </div>
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username"
                            value="{{ $user->username }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email"
                            value="{{ $user->email }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="nis_nip" class="form-label">NIS/NIP</label>
                        <input type="text" class="form-control" id="nis_nip" name="nis_nip"
                            value="{{ $user->nis_nip }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="old_password" class="form-label">Password Lama</label>
                        <input type="password" class="form-control" id="old_password" name="old_password">
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">Password Baru <small>(minimal 5
                                karakter)</small></label>
                        <input type="password" class="form-control" id="new_password" name="new_password">
                    </div>
                    <div class="mb-3">
                        <label for="photo" class="form-label">Foto Pengguna</label>
                        <input class="form-control" type="file" id="photo" name="photo">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
