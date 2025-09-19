@extends('admin.layouts.main')

@section('style')
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">

    {{-- TAMBAHKAN STYLE INI --}}
    <style>
        .truncate-2-lines {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: normal;
        }
    </style>
@endsection

@section('main-content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Daftar Buku</h1>
            <div>
                <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalCreate">
                    <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Buku Baru
                </button>
                {{-- REVISI: Tombol Import Buku --}}
                <button type="button" class="btn btn-success shadow-sm" data-bs-toggle="modal"
                    data-bs-target="#modalImport">
                    <i class="fas fa-file-excel fa-sm text-white-50"></i> Import Buku
                </button>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('import_errors'))
            <div class="alert alert-danger">
                <strong>Oops! Terjadi kesalahan saat mengimpor data:</strong>
                <ul class="mb-0">
                    @foreach (session('import_errors') as $error)
                        <li>{!! $error !!}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="myTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Kode</th>
                                <th>Judul</th>
                                <th>ISBN/ISSN</th>
                                <th>Penulis</th>
                                <th>Penerbit</th>
                                <th>Kategori</th>
                                <th>Stock</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($books as $book)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $book->code }}</td>
                                    {{-- REVISI KOLOM JUDUL --}}
                                    <td style="max-width: 250px;" title="{{ $book->title }}">
                                        <span class="truncate-2-lines">
                                            {{ $book->title }}
                                        </span>
                                    </td>
                                    <td>{{ $book->isbn_issn }}</td>
                                    {{-- REVISI KOLOM PENULIS --}}
                                    <td style="max-width: 200px;" title="{{ $book->author }}">
                                        <span class="truncate-2-lines">
                                            {{ $book->author }}
                                        </span>
                                    </td>
                                    {{-- REVISI KOLOM PENERBIT --}}
                                    <td style="max-width: 200px;" title="{{ $book->publisher }}">
                                        <span class="truncate-2-lines">
                                            {{ $book->publisher }}
                                        </span>
                                    </td>
                                    <td>{{ $book->category }}</td>
                                    <td>{{ $book->stock == 0 ? 'Digital' : $book->stock }}</td>
                                    <td style="min-width: 120px;">
                                        {{-- REVISI: Ganti align-items-start menjadi align-items-stretch --}}
                                        <div class="d-flex flex-row align-items-stretch gap-1">
                                            <a href="/admin/books/{{ $book->id }}"
                                                class="btn btn-info d-flex align-items-center" title="Lihat Detail"><i
                                                    class="bi bi-eye"></i></a>
                                            <button type="button" class="btn btn-warning d-flex align-items-center"
                                                data-bs-toggle="modal" data-bs-target="#modalEdit{{ $book->id }}"
                                                title="Edit"><i class="bi bi-pencil-square"></i></button>
                                            <button type="button"
                                                class="btn btn-danger d-flex align-items-center delete-button"
                                                data-id="{{ $book->id }}" title="Hapus"><i
                                                    class="bi bi-x-circle"></i></button>
                                            <form id="delete-form-{{ $book->id }}"
                                                action="/admin/books/{{ $book->id }}" method="post"
                                                style="display: none;">
                                                @csrf
                                                @method('delete')
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal Edit -->
                                <div class="modal fade" id="modalEdit{{ $book->id }}" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form action="/admin/books/{{ $book->id }}" method="post"
                                            enctype="multipart/form-data">
                                            @csrf
                                            @method('put')
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Buku</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="title" class="form-label">Judul <small>(minimal 3
                                                                karakter)</small></label>
                                                        <input type="text" class="form-control" id="title"
                                                            name="title" value="{{ $book->title }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="code" class="form-label">Kode <small>(minimal 5
                                                                karakter)</small></label>
                                                        <input type="text" class="form-control" id="code"
                                                            name="code" value="{{ $book->code }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="cover" class="form-label">Cover Buku</label>
                                                        <input class="form-control" type="file" id="cover"
                                                            name="cover">
                                                        @if ($book->cover)
                                                            <img src="{{ asset('storage/' . $book->cover) }}"
                                                                alt="Cover" class="mt-2" style="max-width: 100px;">
                                                        @endif
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="category" class="form-label">Kategori</label>
                                                        <select class="form-select" id="category" name="category"
                                                            required>
                                                            <option value="Fiksi" @selected($book->category == 'Fiksi')>Fiksi
                                                            </option>
                                                            <option value="Non-Fiksi" @selected($book->category == 'Non-Fiksi')>
                                                                Non-Fiksi
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="publisher" class="form-label">Penerbit</label>
                                                        <input type="text" class="form-control" id="publisher"
                                                            name="publisher" value="{{ $book->publisher }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="year" class="form-label">Tahun</label>
                                                        <input type="number" class="form-control" id="year"
                                                            name="year" value="{{ $book->year }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="author" class="form-label">Penulis</label>
                                                        <input type="text" class="form-control" id="author"
                                                            name="author" value="{{ $book->author }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="pages" class="form-label">Jumlah Halaman</label>
                                                        <input type="number" class="form-control" id="pages"
                                                            name="pages" value="{{ $book->pages }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="language" class="form-label">Bahasa</label>
                                                        <select class="form-select" id="language" name="language"
                                                            required>
                                                            <option value="Indonesia" @selected($book->language == 'Indonesia')>
                                                                Indonesia</option>
                                                            <option value="Inggris" @selected($book->language == 'Inggris')>Inggris
                                                            </option>
                                                            <option value="Lainnya" @selected(old('language', $book->language) == 'Lainnya')>Lainnya
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="isbn_issn" class="form-label">ISBN/ISSN</label>
                                                        <input type="text" class="form-control" id="isbn_issn"
                                                            name="isbn_issn" value="{{ $book->isbn_issn }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="content_type" class="form-label">Tipe Isi</label>
                                                        <select class="form-select" id="content_type" name="content_type"
                                                            required>
                                                            <option value="Teks" @selected($book->content_type == 'Teks')>Teks
                                                            </option>
                                                            <option value="Gambar" @selected($book->content_type == 'Gambar')>Gambar
                                                            </option>
                                                            <option value="Campuran" @selected($book->content_type == 'Campuran')>Campuran
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="media_type" class="form-label">Tipe Media</label>
                                                        <select class="form-select media-type-select"
                                                            id="media_type{{ $book->id }}" name="media_type"
                                                            required>
                                                            <option value="Buku Cetak" @selected($book->media_type == 'Buku Cetak')>Buku
                                                                Cetak</option>
                                                            <option value="Buku Elektronik" @selected($book->media_type == 'Buku Elektronik')>
                                                                Buku Elektronik</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3 file-pdf-group"
                                                        id="file_upload_section{{ $book->id }}"
                                                        style="display: none;">
                                                        <label for="link{{ $book->id }}" class="form-label">Upload
                                                            File Buku (PDF)</label>
                                                        <input type="file" class="form-control"
                                                            id="link{{ $book->id }}" name="link"
                                                            accept="application/pdf">
                                                        @if ($book->link)
                                                            <p class="mt-2">File sebelumnya: <a
                                                                    href="{{ asset('storage/' . $book->link) }}"
                                                                    target="_blank">Lihat PDF</a></p>
                                                        @endif
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="carrier_type" class="form-label">Tipe Pembawa</label>
                                                        <select class="form-select" id="carrier_type" name="carrier_type"
                                                            required>
                                                            <option value="Volume" @selected($book->carrier_type == 'Volume')>Volume
                                                            </option>
                                                            <option value="Tunggal" @selected($book->carrier_type == 'Tunggal')>Tunggal
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="edition" class="form-label">Edisi</label>
                                                        <select class="form-select" id="edition" name="edition"
                                                            required>
                                                            <option value="Edisi Pertama" @selected($book->edition == 'Edisi Pertama')>
                                                                Edisi Pertama</option>
                                                            <option value="Edisi Kedua" @selected($book->edition == 'Edisi Kedua')>Edisi
                                                                Kedua</option>
                                                            <option value="Edisi Ketiga" @selected($book->edition == 'Edisi Ketiga')>
                                                                Edisi Ketiga</option>
                                                            <option value="Edisi Keempat" @selected($book->edition == 'Edisi Keempat')>
                                                                Edisi Keempat</option>
                                                            <option value="Edisi Kelima" @selected($book->edition == 'Edisi Kelima')>
                                                                Edisi Kelima</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="subject" class="form-label">Subjek</label>
                                                        <input type="text" class="form-control" id="subject"
                                                            name="subject" value="{{ $book->subject }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="stock{{ $book->id }}"
                                                            class="form-label">Stok</label>
                                                        <input type="number" class="form-control stock-input"
                                                            id="stock{{ $book->id }}" name="stock"
                                                            value="{{ old('stock', $book->stock) }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="description">Deskripsi <small>(minimal 10
                                                                karakter)</small></label>
                                                        <textarea class="form-control" id="description" name="description" required>{{ old('description', $book->description) }}</textarea>
                                                        @error('description')
                                                            <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                        {{-- <textarea class="form-control" id="description" name="description" required>{{ $book->description }}</textarea> --}}
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Edit</button>
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

        document.addEventListener("DOMContentLoaded", function() {
            const modals = document.querySelectorAll('#modalCreate, [id^="modalEdit"]');

            modals.forEach(modal => {
                const textarea = modal.querySelector('textarea[name="description"]');
                const submitBtn = modal.querySelector('button[type="submit"]');

                // Buat elemen pesan error
                const errorText = document.createElement('small');
                errorText.classList.add('text-danger');
                textarea.parentNode.appendChild(errorText);

                const validate = () => {
                    const value = textarea.value.trim();
                    if (value.length < 10) {
                        errorText.textContent = "Deskripsi harus minimal 10 karakter.";
                        submitBtn.disabled = true;
                    } else {
                        errorText.textContent = "";
                        submitBtn.disabled = false;
                    }
                };

                // Inisialisasi validasi saat modal dibuka
                modal.addEventListener('shown.bs.modal', validate);

                // Validasi setiap perubahan
                textarea.addEventListener("input", validate);
            });
        });
        // Untuk modal Tambah
        document.getElementById('media_type').addEventListener('change', function() {
            let tipe = this.value;
            let fileUploadSection = document.getElementById('file_upload_section');
            let stockInput = document.getElementById('stock');

            if (tipe === 'Buku Elektronik') {
                fileUploadSection.style.display = 'block';
                document.getElementById('link').setAttribute('required', 'required');
                stockInput.value = 0;
                stockInput.readOnly = true;
            } else {
                fileUploadSection.style.display = 'none';
                document.getElementById('link').removeAttribute('required');
                stockInput.value = '';
                stockInput.readOnly = false;
            }
        });

        document.addEventListener("DOMContentLoaded", function() {
            // Inisialisasi untuk setiap modal edit
            @foreach ($books as $book)
                const mediaTypeSelect{{ $book->id }} = document.getElementById(
                    'media_type{{ $book->id }}');
                const fileUploadSection{{ $book->id }} = document.getElementById(
                    'file_upload_section{{ $book->id }}');
                const linkInput{{ $book->id }} = document.getElementById('link{{ $book->id }}');
                const stockInput{{ $book->id }} = document.getElementById('stock{{ $book->id }}');

                function toggleFileUpload{{ $book->id }}() {
                    if (mediaTypeSelect{{ $book->id }}.value === 'Buku Elektronik') {
                        fileUploadSection{{ $book->id }}.style.display = 'block';
                        linkInput{{ $book->id }}.setAttribute('required', 'required');
                        stockInput{{ $book->id }}.value = 0;
                        stockInput{{ $book->id }}.readOnly = true;
                    } else {
                        fileUploadSection{{ $book->id }}.style.display = 'none';
                        linkInput{{ $book->id }}.removeAttribute('required');
                        stockInput{{ $book->id }}.readOnly = false;
                        if (stockInput{{ $book->id }}.value === '0') {
                            stockInput{{ $book->id }}.value = '';
                        }
                    }
                }

                // Event listener untuk perubahan pada select "Tipe Media"
                mediaTypeSelect{{ $book->id }}.addEventListener('change',
                    toggleFileUpload{{ $book->id }});

                // Inisialisasi saat halaman dimuat
                toggleFileUpload{{ $book->id }}();
            @endforeach
        });
    </script>
@endsection

<!-- Modal Create -->
<div class="modal fade" id="modalCreate" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="/admin/books" method="post" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Buku</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="title" class="form-label">Judul <small>(minimal 3 karakter)</small></label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="code" class="form-label">Kode <small>(minimal 5 karakter)</small></label>
                        <input type="text" class="form-control" id="code" name="code" required>
                    </div>
                    <div class="mb-3">
                        <label for="cover" class="form-label">Cover Buku</label>
                        <input class="form-control" type="file" id="cover" name="cover">
                    </div>
                    <div class="mb-3">
                        <label for="category" class="form-label">Kategori</label>
                        <select class="form-select" id="category" name="category" value="{{ $book->category }}"
                            required>
                            <option value="Fiksi">Fiksi</option>
                            <option value="Non-Fiksi">Non-Fiksi</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="publisher" class="form-label">Penerbit</label>
                        <input type="text" class="form-control" id="publisher" name="publisher" required>
                    </div>
                    <div class="mb-3">
                        <label for="year" class="form-label">Tahun</label>
                        <input type="number" class="form-control" id="year" name="year" required>
                    </div>
                    <div class="mb-3">
                        <label for="author" class="form-label">Penulis</label>
                        <input type="text" class="form-control" id="author" name="author" required>
                    </div>
                    <div class="mb-3">
                        <label for="pages" class="form-label">Jumlah Halaman</label>
                        <input type="number" class="form-control" id="pages" name="pages">
                    </div>
                    <div class="mb-3">
                        <label for="language" class="form-label">Bahasa</label>
                        <select class="form-select" id="language" name="language" value="{{ $book->language }}"
                            required>
                            <option value="Indonesia">Indonesia</option>
                            <option value="Inggris">Inggris</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="isbn_issn" class="form-label">ISBN/ISSN</label>
                        <input type="text" class="form-control" id="isbn_issn" name="isbn_issn">
                    </div>
                    <div class="mb-3">
                        <label for="content_type" class="form-label">Tipe Isi</label>
                        <select class="form-select" id="content_type" name="content_type"
                            value="{{ $book->content_type }}" required>
                            <option value="Teks">Teks</option>
                            <option value="Gambar">Gambar</option>
                            <option value="Campuran">Campuran</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="media_type" class="form-label">Tipe Media</label>
                        <select class="form-select" id="media_type" name="media_type"
                            value="{{ $book->media_type }}" required>
                            <option value="Buku Cetak">Buku Cetak</option>
                            <option value="Buku Elektronik">Buku Elektronik</option>
                        </select>
                    </div>
                    <div class="mb-3" id="file_upload_section" style="display: none;">
                        <label for="link" class="form-label">Upload File Buku (PDF)</label>
                        <input type="file" name="link" id="link" class="form-control"
                            accept="application/pdf">
                    </div>
                    <div class="mb-3">
                        <label for="carrier_type" class="form-label">Tipe Pembawa</label>
                        <select class="form-select" id="carrier_type" name="carrier_type"
                            value="{{ $book->carrier_type }}" required>
                            <option value="Volume">Volume</option>
                            <option value="Tunggal">Tunggal</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edition" class="form-label">Edisi</label>
                        <select class="form-select" id="edition" name="edition" value="{{ $book->edition }}"
                            required>
                            <option value="Edisi Pertama">Edisi Pertama</option>
                            <option value="Edisi Kedua">Edisi Kedua</option>
                            <option value="Edisi Ketiga">Edisi Ketiga</option>
                            <option value="Edisi Keempat">Edisi Keempat</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="subject" class="form-label">Subjek</label>
                        <input type="text" class="form-control" id="subject" name="subject">
                    </div>
                    <div class="mb-3">
                        <label for="stock" class="form-label">Stok</label>
                        <input type="number" class="form-control" id="stock" name="stock" required>
                    </div>
                    <div class="mb-3">
                        <label for="description">Deskripsi <small>(minimal 10 karakter)</small></label>
                        <textarea class="form-control" id="description" name="description" required></textarea>
                        @error('description')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                        {{-- <textarea class="form-control" id="description" name="description" required></textarea> --}}
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

<div class="modal fade" id="modalImport" tabindex="-1" aria-labelledby="modalImportLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.books.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalImportLabel">Import Data Buku dari Excel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>
                        Silakan upload file <strong>.xlsx</strong> atau <strong>.xls</strong>. Pastikan baris pertama
                        (heading) di file Excel Anda sama persis dengan daftar di bawah ini untuk pemetaan kolom yang
                        benar.
                    </p>
                    <p class="font-monospace bg-light p-2 rounded">
                        judul, penulis, tahun_terbit, penerbit, deskripsi, kategori, stok, jumlah_halaman, bahasa,
                        isbn_issn, kode_buku
                    </p>
                    <hr>
                    <div class="mb-3">
                        <label for="file" class="form-label"><strong>Pilih File Excel Anda</strong></label>
                        <input class="form-control" type="file" id="file" name="file" required
                            accept=".xlsx, .xls">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Mulai Impor</button>
                </div>
            </div>
        </form>
    </div>
</div>
