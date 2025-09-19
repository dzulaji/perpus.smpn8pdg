@extends('admin.layouts.main')

@section('style')
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    <style>
        /* Kelas untuk memotong teks menjadi satu baris dengan elipsis (...) */
        .truncate-1-line {
            display: block;
            /* atau inline-block */
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
@endsection

@section('main-content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Daftar Pengguna</h1>
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalCreate">
                <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Pengguna Baru
            </button>
        </div>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="myTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama</th>
                                <th>NIS/NIP</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td style="max-width: 200px;" title="{{ $user->name }}">
                                        <span class="truncate-1-line">{{ $user->name }}</span>
                                    </td>
                                    <td>{{ $user->nis_nip }}</td>
                                    <td>{{ $user->username }}</td>
                                    <td style="max-width: 200px;" title="{{ $user->email }}">
                                        <span class="truncate-1-line">{{ $user->email }}</span>
                                    </td>
                                    <td>{{ $user->role }}</td>
                                    <td class="d-flex flex-row align-items-start gap-1">
                                        <a href="/admin/users/{{ $user->id }}" class="btn btn-info"><i
                                                class="bi bi-eye"></i></a>
                                        <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                            data-bs-target="#modalEdit{{ $user->id }}"><i
                                                class="bi bi-pencil-square"></i>
                                        </button>
                                        <!-- Delete Button -->
                                        @if (auth()->user()->id !== $user->id)
                                            <!-- Check if the current user is not the user being displayed -->
                                            <form id="delete-form-{{ $user->id }}"
                                                action="/admin/users/{{ $user->id }}" method="post"
                                                style="display: inline;">
                                                @csrf
                                                @method('delete')
                                                <button type="button" class="btn btn-danger delete-button"
                                                    data-id="{{ $user->id }}">
                                                    <i class="bi bi-x-circle"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>

                                <!-- Modal Edit Pengguna -->
                                <div class="modal fade" id="modalEdit{{ $user->id }}" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form action="/admin/users/{{ $user->id }}" method="post"
                                            enctype="multipart/form-data">
                                            @csrf
                                            @method('put')
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Pengguna</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="name" class="form-label">Nama <small>(minimal 3
                                                                karakter)</small></label>
                                                        <input type="text" class="form-control" id="name"
                                                            name="name" value="{{ $user->name }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="username" class="form-label">Username <small>(minimal 5
                                                                karakter)</small></label>
                                                        <input type="text" class="form-control" id="username"
                                                            name="username" value="{{ $user->username }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="email" class="form-label">Email</label>
                                                        <input type="email" class="form-control" id="email"
                                                            name="email" value="{{ $user->email }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="nis_nip" class="form-label">NIS/NIP <small>(minimal 10
                                                                karakter)</small></label>
                                                        <input type="text" class="form-control" id="nis_nip"
                                                            name="nis_nip" value="{{ $user->nis_nip }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="old_password" class="form-label">Password Lama</label>
                                                        <input type="password" class="form-control" id="old_password"
                                                            name="old_password">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="new_password" class="form-label">Password Baru
                                                            <small>(minimal 5
                                                                karakter)</small></label>
                                                        <input type="password" class="form-control" id="new_password"
                                                            name="new_password">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="photo" class="form-label">Foto Pengguna</label>
                                                        <input class="form-control" type="file" id="photo"
                                                            name="photo">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('script')
    <script src="//cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Initialize DataTable
        $('#myTable').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'print'
            ]
        });

        // SweetAlert for delete confirmation
        document.querySelectorAll('.delete-button').forEach(button => {
            button.addEventListener('click', function() {
                const bookId = this.getAttribute('data-id');
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Anda tidak akan dapat mengembalikan ini!",
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
                })
            });
        });

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

<!-- Modal Create -->
<div class="modal fade" id="modalCreate" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="/admin/users" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Pengguna</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama <small>(minimal 3 karakter)</small></label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="username" class="form-label">Username <small>(minimal 5 karakter)</small></label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="nis_nip" class="form-label">NIS/NIP <small>(minimal 10 karakter)</small></label>
                        <input type="text" class="form-control" id="nis_nip" name="nis_nip" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password <small>(minimal 5 karakter)</small></label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select id="role" name="role" required>
                            <option value="user">User</option>
                            <option value="librarian">Librarian</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="photo" class="form-label">Foto</label>
                        <input class="form-control" type="file" id="photo" name="photo">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </div>
        </form>
    </div>
</div>
