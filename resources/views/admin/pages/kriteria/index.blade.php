@extends('admin.layouts.main')

@section('style')
    {{-- Anda bisa menambahkan style khusus jika perlu, misalnya untuk pesan error --}}
    <style>
        .modal-body .alert {
            margin-bottom: 1rem;
        }
    </style>
@endsection

@section('main-content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Manajemen Kriteria & Pertanyaan</h1>
            <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalCreate">
                <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Kriteria Baru
            </button>
        </div>

        {{-- Menampilkan pesan sukses dari session --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (round($totalBobot, 2) < 1.0)
            <div class="alert alert-warning d-flex align-items-center" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i> {{-- Ikon Peringatan --}}
                <div>
                    <strong>Peringatan:</strong> Total bobot kriteria saat ini adalah
                    <strong>{{ number_format($totalBobot, 2) }}</strong>. Harap sesuaikan agar totalnya menjadi 1.00 untuk
                    hasil rekomendasi yang akurat.
                </div>
            </div>
        @endif

        {{-- Menampilkan error validasi umum (jika ada, terutama untuk bobot) --}}
        @if ($errors->any() && !$errors->has('kriteria') && !$errors->has('pertanyaan') && !$errors->has('tipe_aturan'))
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Kriteria</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Kriteria</th>
                                <th>Pertanyaan Terkait</th>
                                <th>Bobot</th>
                                <th>Tipe Aturan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($kriteria as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->kriteria }}</td>
                                    <td>{{ Str::limit($item->pertanyaan->pertanyaan ?? 'N/A', 50) }}</td>
                                    <td>{{ number_format($item->bobot, 2) }}</td>
                                    <td>
                                        @if ($item->tipe_aturan == 'TEKS')
                                            <span class="badge bg-success text-white">TEKS</span>
                                        @else
                                            <span class="badge bg-info text-white">ANGKA</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.kriteria.show', $item) }}"
                                            class="btn btn-success btn-sm mb-1" title="Kelola Sub Kriteria">
                                            <i class="fas fa-cog"></i>
                                        </a>
                                        <button type="button" class="btn btn-warning btn-sm mb-1" data-bs-toggle="modal"
                                            data-bs-target="#modalEdit{{ $item->id_kriteria }}" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm delete-button-kriteria"
                                            data-id="{{ $item->id_kriteria }}" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <form id="delete-form-kriteria-{{ $item->id_kriteria }}"
                                            action="{{ route('admin.kriteria.destroy', $item) }}" method="POST"
                                            style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Belum ada data kriteria. Silakan tambahkan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end">Total Bobot:</th>
                                <th colspan="3" class="fw-bold">
                                    <span id="totalBobotValue"
                                        data-total="{{ $totalBobot }}">{{ number_format($totalBobot, 2) }}</span> / 1.00
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalCreate" tabindex="-1" aria-labelledby="modalCreateLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('admin.kriteria.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCreateLabel">Tambah Kriteria Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        {{-- Menampilkan error validasi khusus untuk modal ini --}}
                        @if ($errors->any() && old('form_type') == 'create')
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <input type="hidden" name="form_type" value="create">

                        <div class="mb-3">
                            <label for="kolom_buku" class="form-label">Pilih Kriteria (Berdasarkan Kolom Buku)</label>
                            <select class="form-select" id="kolom_buku" name="kolom_buku" required>
                                <option value="">-- Pilih Kolom --</option>
                                {{-- $kolomUntukForm dikirim dari controller --}}
                                @foreach ($kolomUntukForm as $namaKolom => $namaTampilan)
                                    <option value="{{ $namaKolom }}" @selected(old('kolom_buku') == $namaKolom)>{{ $namaTampilan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="pertanyaan" class="form-label">Teks Pertanyaan Terkait</label>
                            <textarea class="form-control" id="pertanyaan" name="pertanyaan" rows="3" required>{{ old('pertanyaan') }}</textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="bobot" class="form-label">Bobot</label>
                                <input type="number" step="0.01" class="form-control" id="bobot" name="bobot"
                                    value="{{ old('bobot') }}" required>
                                <small class="form-text text-muted">Contoh: 0.15. Total bobot tidak boleh > 1.</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="tipe_aturan" class="form-label">Tipe Aturan</label>
                                <select class="form-select" id="tipe_aturan" name="tipe_aturan" required>
                                    <option value="TEKS" @selected(old('tipe_aturan') == 'TEKS')>Teks Persis</option>
                                    <option value="ANGKA" @selected(old('tipe_aturan') == 'ANGKA')>Rentang Angka</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    @foreach ($kriteria as $item)
        <div class="modal fade" id="modalEdit{{ $item->id_kriteria }}" tabindex="-1"
            aria-labelledby="modalEditLabel{{ $item->id_kriteria }}" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('admin.kriteria.update', $item) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalEditLabel{{ $item->id_kriteria }}">Edit Kriteria</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            {{-- Menampilkan error validasi khusus untuk modal ini --}}
                            @if ($errors->any() && old('form_id') == $item->id_kriteria)
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <input type="hidden" name="form_id" value="{{ $item->id_kriteria }}">

                            <div class="mb-3">
                                <label for="kriteria_{{ $item->id_kriteria }}" class="form-label">Nama Kriteria</label>
                                {{-- $kolomBukuTersedia dikirim dari controller --}}
                                <input type="text" class="form-control" id="kriteria_{{ $item->id_kriteria }}"
                                    value="{{ $kolomBukuTersedia[$item->kolom_buku] ?? $item->kriteria }}" readonly>
                                <small class="form-text text-muted">Nama kriteria dan pemetaan kolom tidak dapat
                                    diubah.</small>
                            </div>
                            <div class="mb-3">
                                <label for="pertanyaan_{{ $item->id_kriteria }}" class="form-label">Teks Pertanyaan
                                    Terkait</label>
                                <textarea class="form-control" id="pertanyaan_{{ $item->id_kriteria }}" name="pertanyaan" rows="3" required>{{ old('pertanyaan', optional($item->pertanyaan)->pertanyaan) }}</textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="bobot_{{ $item->id_kriteria }}" class="form-label">Bobot</label>
                                    <input type="number" step="0.01" class="form-control"
                                        id="bobot_{{ $item->id_kriteria }}" name="bobot"
                                        value="{{ old('bobot', $item->bobot) }}" required>
                                    <small class="form-text text-muted">Contoh: 0.15</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="tipe_aturan_{{ $item->id_kriteria }}" class="form-label">Tipe
                                        Aturan</label>
                                    <select class="form-select" id="tipe_aturan_{{ $item->id_kriteria }}"
                                        name="tipe_aturan" required>
                                        <option value="TEKS" @selected(old('tipe_aturan', $item->tipe_aturan) == 'TEKS')>Teks Persis</option>
                                        <option value="ANGKA" @selected(old('tipe_aturan', $item->tipe_aturan) == 'ANGKA')>Rentang Angka</option>
                                    </select>
                                </div>
                            </div>
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
            // Logika untuk menampilkan kembali modal yang error (tetap sama)
            @if ($errors->any())
                @if (old('form_type') == 'create')
                    var createModal = new bootstrap.Modal(document.getElementById('modalCreate'));
                    createModal.show();
                @elseif (old('form_id'))
                    var editModal = new bootstrap.Modal(document.getElementById('modalEdit{{ old('form_id') }}'));
                    editModal.show();
                @endif
            @endif

            // Menampilkan notifikasi SUKSES dari session (tetap sama)
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
            // REVISI BARU: Logika Peringatan Bobot
            // ==========================================================

            // Fungsi untuk menampilkan peringatan jika total bobot tidak sama dengan 1
            function checkTotalBobot() {
                const totalBobotElement = document.getElementById('totalBobotValue');
                if (totalBobotElement) {
                    const totalBobot = parseFloat(totalBobotElement.dataset.total);
                    // Gunakan toleransi kecil untuk perbandingan float
                    if (Math.abs(totalBobot - 1.0) > 0.001) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Peringatan Bobot!',
                            text: `Total bobot saat ini adalah ${totalBobot.toFixed(2)}. Harap sesuaikan agar totalnya menjadi 1.00 untuk hasil rekomendasi yang akurat.`,
                            confirmButtonText: 'Saya Mengerti'
                        });
                    }
                }
            }

            // Panggil fungsi pengecekan saat halaman pertama kali dimuat
            checkTotalBobot();

            // Konfirmasi HAPUS dengan SweetAlert (dengan tambahan pengecekan bobot)
            document.querySelectorAll('.delete-button-kriteria').forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault(); // Mencegah aksi default
                    const kriteriaId = this.getAttribute('data-id');
                    const form = document.getElementById('delete-form-kriteria-' + kriteriaId);

                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Kriteria akan dihapus permanen. Menghapus kriteria ini akan membuat total bobot menjadi kurang dari 1.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    })
                });
            });
        });
    </script>
@endsection
