@extends('layouts.main')

@section('style')
    <style>
        /* Tambahkan kelas ini untuk merapatkan jarak baris tabel */
        .table-detail-buku td {
            padding-top: 0.2rem; /* Mengurangi padding atas */
            padding-bottom: 0.2rem; /* Mengurangi padding bawah */
        }
    </style>
@endsection

@section('main-content')
    <div class="container mt-4" style="margin-bottom: 6rem">
        {{-- breadcrumb --}}
        <nav class="my-4" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/" class="text-decoration-none">Beranda</a></li>
                <li class="breadcrumb-item"><a href="/books" class="text-decoration-none">Koleksi Buku</a></li>
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
            <div class="col-md-8">
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
                                    <td>{{ $book->media_type }}</td>
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
                        <h6 class="m-0 fw-bold">
                            {{ $book->media_type === 'Buku Elektronik' ? 'Lihat Buku' : 'Peminjaman' }}
                        </h6>
                    </div>
                    <div class="card-body">
                        @if ($book->media_type === 'Buku Elektronik')
                            <a href="{{ asset('storage/' . $book->link) }}" target="_blank" class="btn btn-warning">
                                Lihat Buku
                            </a>
                        @else
                            @auth
                                <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal">
                                    Pinjam Buku
                                </button>
                            @else
                                <a href="/login" class="btn btn-warning">Pinjam Buku</a>
                            @endauth
                        @endif
                    </div>
                    {{-- <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 fw-bold">Peminjaman</h6>
                    </div>
                    <div class="card-body">
                        @auth
                            <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                data-bs-target="#exampleModal">
                                Pinjam Buku
                            </button>
                        @else
                            <a href="/login" class="btn btn-warning">Pinjam Buku</a>
                        @endauth
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="/booking" method="post">
                @csrf
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Pinjam Buku {{ $book->title }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="alasan" class="form-label">Alasan Pinjam</label>
                        <textarea class="form-control" id="alasan" rows="3" name="alasan"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="tgl_kembali" class="form-label">Tanggal Pengembalian</label>
                        <input type="date" class="form-control" id="tgl_kembali" name="expired_at">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <input type="text" name="book_id" value="{{ $book->id }}" hidden>
                    @auth
                        <input type="text" name="user_id" value="{{ auth()->user()->id }}" hidden>
                    @endauth
                    <input type="text" name="status" value="Diajukan" hidden>
                    <input type="text" name="is_denda" value="0" hidden>
                    <button type="submit" class="btn btn-warning">Setuju Pinjam</button>
                </div>
            </form>
        </div>
    </div>
</div>
