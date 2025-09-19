@extends('admin.layouts.main')

@section('main-content')
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.kriteria.index') }}">Manajemen Kriteria</a></li>
                <li class="breadcrumb-item active" aria-current="page">Kelola Sub Kriteria</li>
            </ol>
        </nav>

        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Kelola: {{ $kriterium->kriteria }}</h1>
        </div>

        {{-- Menampilkan pesan sukses dari session --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Tambah Pilihan Jawaban (Sub Kriteria) Baru</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.subkriteria.store', $kriterium) }}" method="POST">
                    @csrf
                    <div class="row align-items-end">
                        <div class="col-md-4 mb-3">
                            <label for="nama_tampilan">Teks Tampilan (Untuk User)</label>
                            <input type="text" class="form-control" name="nama_tampilan"
                                value="{{ old('nama_tampilan') }}" placeholder="Contoh: > 350 Halaman" required>
                        </div>

                        @if ($kriterium->tipe_aturan == 'TEKS')
                            <div class="col-md-4 mb-3">
                                <label for="nilai_teks">Teks Aturan (Untuk Sistem)</label>
                                <input type="text" class="form-control" name="nilai_teks" value="{{ old('nilai_teks') }}"
                                    placeholder="Contoh: Non-Fiksi" required>
                            </div>
                        @else
                            {{-- Tipe Aturan ANGKA --}}
                            <div class="col-md-2 mb-3">
                                <label for="operator">Operator</label>
                                <select name="operator" class="form-select operator-select" required>
                                    <option value="=" @selected(old('operator') == '=')>=</option>
                                    <option value=">=" @selected(old('operator') == '>=')>&gt;=</option>
                                    <option value="<=" @selected(old('operator') == '<=')>&lt;=</option>
                                    <option value=">" @selected(old('operator') == '>')>&gt;</option>
                                    <option value="<" @selected(old('operator') == '<')>&lt;</option>
                                    <option value="hingga" @selected(old('operator') == 'hingga')>hingga</option>
                                </select>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="nilai_angka_1">Nilai 1</label>
                                <input type="number" step="any" class="form-control" name="nilai_angka_1"
                                    value="{{ old('nilai_angka_1') }}" required>
                            </div>
                            <div class="col-md-2 mb-3 nilai_angka_2_wrapper"
                                style="display: {{ old('operator') == 'hingga' ? 'block' : 'none' }};">
                                <label for="nilai_angka_2">Nilai 2 (jika hingga)</label>
                                <input type="number" step="any" class="form-control" name="nilai_angka_2"
                                    value="{{ old('nilai_angka_2') }}">
                            </div>
                        @endif

                        <div class="col-md-2 mb-3">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-primary w-100">Tambah</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Pilihan Jawaban (Sub Kriteria) Saat Ini</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nama Tampilan</th>
                                <th>Nilai Otomatis</th>
                                <th>Aturan Sistem</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($kriterium->subKriteria as $sub)
                                <tr>
                                    <td>{{ $sub->nama_tampilan }}</td>
                                    <td><span class="badge bg-primary text-white fs-6">{{ $sub->nilai }}</span></td>
                                    <td class="font-monospace">
                                        @if ($kriterium->tipe_aturan == 'TEKS')
                                            Cocokkan Teks: <strong>"{{ $sub->nilai_teks }}"</strong>
                                        @else
                                            @if ($sub->operator == 'hingga')
                                                Nilai di antara <strong>{{ $sub->nilai_angka_1 }}</strong> dan
                                                <strong>{{ $sub->nilai_angka_2 }}</strong>
                                            @else
                                                Nilai <strong>{{ $sub->operator }} {{ $sub->nilai_angka_1 }}</strong>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#modalEditSub{{ $sub->id_sub_kriteria }}" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm delete-button-sub"
                                            data-id="{{ $sub->id_sub_kriteria }}" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <form id="delete-form-sub-{{ $sub->id_sub_kriteria }}"
                                            action="{{ route('admin.subkriteria.destroy', $sub) }}" method="POST"
                                            style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Belum ada sub-kriteria. Silakan tambahkan melalui
                                        form di atas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.kriteria.index') }}" class="btn btn-secondary">Kembali ke Daftar Kriteria</a>
            </div>
        </div>
    </div>

    @foreach ($kriterium->subKriteria as $sub)
        <div class="modal fade" id="modalEditSub{{ $sub->id_sub_kriteria }}" tabindex="-1"
            aria-labelledby="modalEditSubLabel{{ $sub->id_sub_kriteria }}" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('admin.subkriteria.update', $sub) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalEditSubLabel{{ $sub->id_sub_kriteria }}">Edit Sub Kriteria
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            {{-- Pesan error validasi khusus untuk modal edit ini jika diperlukan --}}
                            {{-- @if ($errors->any() && old('form_id') == $sub->id_sub_kriteria) ... @endif --}}

                            <div class="mb-3">
                                <label for="nama_tampilan_{{ $sub->id_sub_kriteria }}">Teks Tampilan</label>
                                <input type="text" class="form-control" name="nama_tampilan"
                                    id="nama_tampilan_{{ $sub->id_sub_kriteria }}"
                                    value="{{ old('nama_tampilan', $sub->nama_tampilan) }}" required>
                            </div>

                            @if ($kriterium->tipe_aturan == 'TEKS')
                                <div class="mb-3">
                                    <label for="nilai_teks_{{ $sub->id_sub_kriteria }}">Teks Aturan</label>
                                    <input type="text" class="form-control" name="nilai_teks"
                                        id="nilai_teks_{{ $sub->id_sub_kriteria }}"
                                        value="{{ old('nilai_teks', $sub->nilai_teks) }}" required>
                                </div>
                            @else
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="operator_{{ $sub->id_sub_kriteria }}">Operator</label>
                                        <select name="operator" class="form-select operator-select"
                                            id="operator_{{ $sub->id_sub_kriteria }}" required>
                                            <option value="=" @selected(old('operator', $sub->operator) == '=')>=</option>
                                            <option value=">=" @selected(old('operator', $sub->operator) == '>=')>&gt;=</option>
                                            <option value="<=" @selected(old('operator', $sub->operator) == '<=')>&lt;=</option>
                                            <option value=">" @selected(old('operator', $sub->operator) == '>')>&gt;</option>
                                            <option value="<" @selected(old('operator', $sub->operator) == '<')>&lt;</option>
                                            <option value="hingga" @selected(old('operator', $sub->operator) == 'hingga')>hingga</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="nilai_angka_1_{{ $sub->id_sub_kriteria }}">Nilai 1</label>
                                        <input type="number" step="any" class="form-control" name="nilai_angka_1"
                                            id="nilai_angka_1_{{ $sub->id_sub_kriteria }}"
                                            value="{{ old('nilai_angka_1', $sub->nilai_angka_1) }}" required>
                                    </div>
                                </div>
                                <div class="mb-3 nilai_angka_2_wrapper"
                                    style="display: {{ old('operator', $sub->operator) == 'hingga' ? 'block' : 'none' }};">
                                    <label for="nilai_angka_2_{{ $sub->id_sub_kriteria }}">Nilai 2 (jika hingga)</label>
                                    <input type="number" step="any" class="form-control" name="nilai_angka_2"
                                        id="nilai_angka_2_{{ $sub->id_sub_kriteria }}"
                                        value="{{ old('nilai_angka_2', $sub->nilai_angka_2) }}">
                                </div>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Menampilkan notifikasi SUKSES dari session
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    timer: 2500,
                    showConfirmButton: false
                });
            @endif

            // ==========================================================
            // REVISI BARU: Menampilkan notifikasi ERROR dari validasi
            // ==========================================================
            @if ($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Oops! Terjadi Kesalahan',
                    // Menampilkan pesan error pertama yang paling relevan
                    text: '{{ $errors->first() }}',
                });
            @endif
            // ==========================================================
            // AKHIR REVISI
            // ==========================================================


            // Konfirmasi HAPUS dengan SweetAlert untuk Sub-Kriteria
            document.querySelectorAll('.delete-button-sub').forEach(button => {
                button.addEventListener('click', function() {
                    const subKriteriaId = this.getAttribute('data-id');
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Sub-kriteria ini akan dihapus secara permanen!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('delete-form-sub-' + subKriteriaId)
                                .submit();
                        }
                    })
                });
            });

            // (Kode JavaScript untuk operator 'hingga' Anda tetap di sini)
            function handleOperatorChange(selectElement) {
                // ...
            }
            const allOperatorSelects = document.querySelectorAll('.operator-select');
            allOperatorSelects.forEach(select => {
                handleOperatorChange(select);
                select.addEventListener('change', function() {
                    handleOperatorChange(this);
                });
            });
        });
    </script>
@endsection
