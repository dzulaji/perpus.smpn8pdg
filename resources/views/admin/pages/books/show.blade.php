@extends('admin.layouts.main')

@section('style')
    <style>
        /* Kelas kustom untuk merapatkan jarak baris tabel detail */
        .table-detail-buku td {
            padding-top: 0.2rem;
            padding-bottom: 0.2rem;
            padding-left: 0;
            padding-right: 0.5rem;
            /* Beri sedikit jarak kanan pada sel label */
        }
    </style>
@endsection

@section('main-content')
    <div class="container mt-4" style="margin-bottom: 6rem">
        {{-- breadcrumb --}}
        <nav class="my-4" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/admin" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="/admin/books" class="text-decoration-none">Koleksi Buku</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $book->title }}</li>
            </ol>
        </nav>

        {{-- card --}}
        <div class="row">

            <!-- Cover Image -->
            <div class="col-md-3">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        @if ($book->cover)
                            <img class="card-img-top" src="{{ asset('storage/' . $book->cover) }}" alt="Card image cap">
                        @else
                            <img class="card-img-top" src="{{ asset('img/bookCoverDefault.png') }}" alt="Card image cap">
                        @endif
                    </div>
                </div>
            </div>

            <!-- Information -->
            <div class="col-md-9">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 fw-bold">Detail Buku</h6>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <table class="table table-borderless table-detail-buku">
                            <tbody>
                                <tr>
                                    <td class="fw-medium" style="width: 170px; vertical-align: top;">Judul</td>
                                    <td style="width: 10px; vertical-align: top;">:</td>
                                    <td>{{ $book->title }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-medium">Kode</td>
                                    <td>:</td>
                                    <td>{{ $book->code }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-medium">Kategori</td>
                                    <td>:</td>
                                    <td>{{ $book->category }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-medium">Penerbit</td>
                                    <td>:</td>
                                    <td>{{ $book->publisher }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-medium">Tahun</td>
                                    <td>:</td>
                                    <td>{{ $book->year }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-medium" style="vertical-align: top;">Penulis</td>
                                    <td style="vertical-align: top;">:</td>
                                    <td>{{ $book->author }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-medium">Jumlah Halaman</td>
                                    <td>:</td>
                                    <td>{{ $book->pages }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-medium">Bahasa</td>
                                    <td>:</td>
                                    <td>{{ $book->language }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-medium">ISBN/ISSN</td>
                                    <td>:</td>
                                    <td>{{ $book->isbn_issn }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-medium">Tipe Isi</td>
                                    <td>:</td>
                                    <td>{{ $book->content_type }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-medium">Tipe Media</td>
                                    <td>:</td>
                                    <td>
                                        {{ $book->media_type }}
                                        @if ($book->media_type === 'Buku Elektronik' && $book->link)
                                            | <a href="{{ asset('storage/' . $book->link) }}" target="_blank">Buka PDF</a>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-medium">Tipe Pembawa</td>
                                    <td>:</td>
                                    <td>{{ $book->carrier_type }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-medium">Edisi</td>
                                    <td>:</td>
                                    <td>{{ $book->edition }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-medium">Subjek</td>
                                    <td>:</td>
                                    <td>{{ $book->subject }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-medium">Stok</td>
                                    <td>:</td>
                                    <td>{{ $book->stock == 0 ? 'Buku Digital (Tersedia)' : $book->stock }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-medium" style="vertical-align: top;">Deskripsi</td>
                                    <td style="vertical-align: top;">:</td>
                                    <td>{{ $book->description }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- proses --}}
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 fw-bold">Aksi</h6>
                    </div>
                    <div class="card-body d-flex align-items-start gap-2">
                        <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                            data-bs-target="#modalEditSingleBook"><i class="bi bi-pencil-square"></i>
                            Edit</button>
                        <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $book->id }})"><i
                                class="bi bi-x-circle"></i> Delete</button>
                        <form id="delete-form-{{ $book->id }}" action="{{ url('/admin/books/' . $book->id) }}"
                            method="post" style="display: none;">
                            @csrf
                            @method('delete')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEditSingleBook" tabindex="-1" aria-labelledby="modalEditSingleBookLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ url('/admin/books/' . $book->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalEditSingleBookLabel">Edit Buku: {{ $book->title }}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        {{-- Field-field lengkap seperti di index.blade.php, disesuaikan untuk satu buku --}}
                        <div class="mb-3">
                            <label for="title_edit" class="form-label">Judul <small>(minimal 3 karakter)</small></label>
                            <input type="text" class="form-control" id="title_edit" name="title"
                                value="{{ old('title', $book->title) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="code_edit" class="form-label">Kode <small>(minimal 5 karakter)</small></label>
                            <input type="text" class="form-control" id="code_edit" name="code"
                                value="{{ old('code', $book->code) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="cover_edit" class="form-label">Cover Buku</label>
                            <input class="form-control" type="file" id="cover_edit" name="cover">
                            @if ($book->cover)
                                <img src="{{ asset('storage/' . $book->cover) }}" alt="Cover Saat Ini" class="mt-2"
                                    style="max-width: 100px;">
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="category_edit" class="form-label">Kategori</label>
                            <select class="form-select" id="category_edit" name="category" required>
                                <option value="Fiksi" @selected(old('category', $book->category) == 'Fiksi')>Fiksi</option>
                                <option value="Non-Fiksi" @selected(old('category', $book->category) == 'Non-Fiksi')>Non-Fiksi</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="publisher_edit" class="form-label">Penerbit</label>
                            <input type="text" class="form-control" id="publisher_edit" name="publisher"
                                value="{{ old('publisher', $book->publisher) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="year_edit" class="form-label">Tahun</label>
                            <input type="number" class="form-control" id="year_edit" name="year"
                                value="{{ old('year', $book->year) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="author_edit" class="form-label">Penulis</label>
                            <input type="text" class="form-control" id="author_edit" name="author"
                                value="{{ old('author', $book->author) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="pages_edit" class="form-label">Jumlah Halaman</label>
                            <input type="number" class="form-control" id="pages_edit" name="pages"
                                value="{{ old('pages', $book->pages) }}">
                        </div>
                        <div class="mb-3">
                            <label for="language_edit" class="form-label">Bahasa</label>
                            <select class="form-select" id="language_edit" name="language" required>
                                <option value="Indonesia" @selected(old('language', $book->language) == 'Indonesia')>Indonesia</option>
                                <option value="Inggris" @selected(old('language', $book->language) == 'Inggris')>Inggris</option>
                                <option value="Lainnya" @selected(old('language', $book->language) == 'Lainnya')>Lainnya</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="isbn_issn_edit" class="form-label">ISBN/ISSN</label>
                            <input type="text" class="form-control" id="isbn_issn_edit" name="isbn_issn"
                                value="{{ old('isbn_issn', $book->isbn_issn) }}">
                        </div>
                        <div class="mb-3">
                            <label for="content_type_edit" class="form-label">Tipe Isi</label>
                            <select class="form-select" id="content_type_edit" name="content_type" required>
                                <option value="Teks" @selected(old('content_type', $book->content_type) == 'Teks')>Teks</option>
                                <option value="Gambar" @selected(old('content_type', $book->content_type) == 'Gambar')>Gambar</option>
                                <option value="Campuran" @selected(old('content_type', $book->content_type) == 'Campuran')>Campuran</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="media_type_edit" class="form-label">Tipe Media</label>
                            <select class="form-select" id="media_type_edit" name="media_type" required>
                                <option value="Buku Cetak" @selected(old('media_type', $book->media_type) == 'Buku Cetak')>Buku Cetak</option>
                                <option value="Buku Elektronik" @selected(old('media_type', $book->media_type) == 'Buku Elektronik')>Buku Elektronik</option>
                            </select>
                        </div>
                        <div class="mb-3" id="file_upload_section_edit" style="display: none;">
                            <label for="link_edit" class="form-label">Upload File Buku (PDF Pengganti)</label>
                            <input type="file" class="form-control" id="link_edit" name="link"
                                accept="application/pdf">
                            @if ($book->link)
                                <p class="mt-2 mb-0">File PDF saat ini: <a href="{{ asset('storage/' . $book->link) }}"
                                        target="_blank">Lihat/Unduh</a></p>
                                <small class="text-muted">Mengupload file baru akan menggantikan file lama.</small>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="carrier_type_edit" class="form-label">Tipe Pembawa</label>
                            <select class="form-select" id="carrier_type_edit" name="carrier_type" required>
                                <option value="Volume" @selected(old('carrier_type', $book->carrier_type) == 'Volume')>Volume</option>
                                <option value="Tunggal" @selected(old('carrier_type', $book->carrier_type) == 'Tunggal')>Tunggal</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edition_edit" class="form-label">Edisi</label>
                            <select class="form-select" id="edition_edit" name="edition" required>
                                <option value="Edisi Pertama" @selected(old('edition', $book->edition) == 'Edisi Pertama')>Edisi Pertama</option>
                                <option value="Edisi Kedua" @selected(old('edition', $book->edition) == 'Edisi Kedua')>Edisi Kedua</option>
                                <option value="Edisi Ketiga" @selected(old('edition', $book->edition) == 'Edisi Ketiga')>Edisi Ketiga</option>
                                <option value="Edisi Keempat" @selected(old('edition', $book->edition) == 'Edisi Keempat')>Edisi Keempat</option>
                                <option value="Edisi Kelima" @selected(old('edition', $book->edition) == 'Edisi Kelima')>Edisi Kelima</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="subject_edit" class="form-label">Subjek</label>
                            <input type="text" class="form-control" id="subject_edit" name="subject"
                                value="{{ old('subject', $book->subject) }}">
                        </div>
                        <div class="mb-3">
                            <label for="stock_edit" class="form-label">Stok</label>
                            <input type="text" class="form-control" id="stock_edit" name="stock"
                                value="{{ old('stock', $book->stock) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="description_edit">Deskripsi <small>(minimal 10 karakter)</small></label>
                            <textarea class="form-control" id="description_edit" name="description" required rows="4">{{ old('description', $book->description) }}</textarea>
                            <small class="text-danger" id="description_error_edit"></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="submit_button_edit">Simpan Perubahan</button>
                    </div>
                </div>
            </form>
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

        // JavaScript untuk Modal Edit di halaman show.blade.php
        document.addEventListener("DOMContentLoaded", function() {
            const editModalInstance = document.getElementById('modalEditSingleBook');
            if (editModalInstance) {
                const mediaTypeSelect = editModalInstance.querySelector('#media_type_edit');
                const fileUploadSection = editModalInstance.querySelector('#file_upload_section_edit');
                const linkInput = editModalInstance.querySelector(
                    '#link_edit'); // Pastikan ID ini ada di HTML jika Anda menggunakannya
                const stockInput = editModalInstance.querySelector('#stock_edit');
                const originalStockValue = '{{ $book->stock === 'BukuDigital' ? '' : $book->stock }}';

                const textareaDescription = editModalInstance.querySelector('#description_edit');
                const submitButton = editModalInstance.querySelector('#submit_button_edit');
                const descriptionError = editModalInstance.querySelector('#description_error_edit');

                function toggleFileUploadAndStock() {
                    if (mediaTypeSelect.value === 'Buku Elektronik') {
                        fileUploadSection.style.display = 'block';
                        // linkInput.setAttribute('required', 'required'); // Opsional untuk edit
                        stockInput.value = 'BukuDigital';
                        stockInput.readOnly = true;
                    } else {
                        fileUploadSection.style.display = 'none';
                        // linkInput.removeAttribute('required');
                        if (stockInput.value === 'BukuDigital' || stockInput.value ===
                            'BukuDigital') { // Penyesuaian untuk case
                            stockInput.value = originalStockValue;
                        }
                        stockInput.readOnly = false;
                    }
                }

                function validateDescription() {
                    if (textareaDescription && descriptionError && submitButton) { // Pastikan semua elemen ada
                        const value = textareaDescription.value.trim();
                        if (value.length < 10) {
                            descriptionError.textContent = "Deskripsi harus minimal 10 karakter.";
                            submitButton.disabled = true;
                        } else {
                            descriptionError.textContent = "";
                            submitButton.disabled = false;
                        }
                    }
                }

                if (mediaTypeSelect && fileUploadSection && stockInput) { // Pastikan elemen utama ada
                    mediaTypeSelect.addEventListener('change', toggleFileUploadAndStock);

                    editModalInstance.addEventListener('show.bs.modal', function() {
                        // Set nilai media_type dari data buku yang ada, atau dari old() jika ada error validasi
                        mediaTypeSelect.value = "{{ old('media_type', $book->media_type) }}";
                        // Set nilai stok dari data buku yang ada (setelah old() diproses oleh value="" di input)
                        // Ini penting jika ada error validasi dan user sudah mengubah tipe media
                        if ("{{ old('media_type') }}") {
                            if ("{{ old('media_type') }}" === "Buku Elektronik") {
                                stockInput.value = 'BukuDigital';
                            } else {
                                stockInput.value =
                                    "{{ old('stock', $book->stock) }}"; // Ambil old stock jika ada
                            }
                        } else {
                            // Jika tidak ada old value, set berdasarkan $book->media_type
                            if ("{{ $book->media_type }}" === "Buku Elektronik") {
                                stockInput.value = 'BukuDigital';
                            } else {
                                stockInput.value =
                                    originalStockValue; // Atau $book->stock jika tidak "BukuDigital"
                            }
                        }
                        toggleFileUploadAndStock();
                        validateDescription();
                    });
                }

                if (textareaDescription) {
                    textareaDescription.addEventListener("input", validateDescription);
                }
            }
        });
    </script>
@endsection

{{-- <!-- Modal Edit -->
<div class="modal fade" id="modalEdit{{ $book->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <form action="/admin/books/{{ $book->id }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('put')
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Buku</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="title" class="form-label">Judul <small>(minimal 3 karakter)</small></label>
                        <input type="text" class="form-control" id="title" name="title"
                            value="{{ $book->title }}">
                    </div>
                    <div class="mb-3">
                        <label for="code" class="form-label">Kode <small>(minimal 5 karakter)</small></label>
                        <input type="text" class="form-control" id="code" name="code"
                            value="{{ $book->code }}">
                    </div>
                    <div class="mb-3">
                        <label for="cover" class="form-label">Cover Buku</label>
                        <input class="form-control" type="file" id="cover" name="cover">
                    </div>
                    <div class="mb-3">
                        <label for="category" class="form-label">Kategori</label>
                        <select class="form-select" id="category" name="category" value="{{ $book->category }}"
                            required>
                            <option value="fiksi">Fiksi</option>
                            <option value="non-fiksi">Non-Fiksi</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="publisher" class="form-label">Penerbit</label>
                        <input type="text" class="form-control" id="publisher" name="publisher"
                            value="{{ $book->publisher }}">
                    </div>
                    <div class="mb-3">
                        <label for="year" class="form-label">Tahun</label>
                        <input type="number" class="form-control" id="year" name="year"
                            value="{{ $book->year }}">
                    </div>
                    <div class="mb-3">
                        <label for="author" class="form-label">Penulis</label>
                        <input type="text" class="form-control" id="author" name="author"
                            value="{{ $book->author }}">
                    </div>
                    <div class="mb-3">
                        <label for="stock" class="form-label">Stok</label>
                        <input type="number" class="form-control" id="stock" name="stock"
                            value="{{ $book->stock }}">
                    </div>
                    <div class="mb-3">
                        <label for="description">Deskripsi <small>(minimal 10 karakter)</small></label>
                        <textarea class="form-control" id="description" name="description">{{ $book->description }}</textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Edit</button>
                </div>

            </div>
        </form>
    </div>
</div> --}}
