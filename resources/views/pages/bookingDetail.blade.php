@extends('layouts.main')

@section('style')
    <style>
        /* Kelas kustom untuk merapatkan jarak baris tabel detail */
        .table-detail-peminjaman td,
        .table-detail-peminjaman th {
            padding-top: 0.3rem;
            padding-bottom: 0.3rem;
            padding-left: 0;
            /* Menghapus padding kiri agar rata kiri */
            padding-right: 0;
            /* Menghapus padding kanan */
        }

        .table-detail-peminjaman th {
            border-bottom: 1px solid #e3e6f0;
            /* Memberi garis bawah tipis pada header bagian */
        }
    </style>
@endsection

@section('main-content')
    <div class="container mt-4" style="margin-bottom: 6rem">
        {{-- breadcrumb --}}
        <nav class="my-4" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/" class="text-decoration-none">Beranda</a></li>
                <li class="breadcrumb-item"><a href="/booking" class="text-decoration-none">Daftar Peminjaman</a></li>
                <li class="breadcrumb-item active" aria-current="page">Peminjaman {{ $booking->code }}</li>
            </ol>
        </nav>

        {{-- card --}}
        <div class="row">
            <!-- Cover Image -->
            <div class="col-md-3">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        @if ($booking->book->cover)
                            <img class="card-img-top" src="{{ asset('storage/' . $booking->book->cover) }}"
                                alt="Card image cap">
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
                        <h6 class="m-0 fw-bold">Detail Peminjaman</h6>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        {{-- Menggunakan satu tabel dengan dua bagian thead untuk pemisah --}}
                        <table class="table table-borderless table-detail-peminjaman">
                            <thead>
                                <tr>
                                    <th colspan="3" class="ps-0">
                                        <h6 class="m-0 fw-bold">Detail Peminjaman</h6>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="fw-medium" style="width: 170px;">Kode Peminjaman</td>
                                    <td style="width: 10px;">:</td>
                                    <td>{{ $booking->code }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-medium">Status</td>
                                    <td>:</td>
                                    <td>
                                        @if ($booking->status == 'Dikembalikan')
                                            @if (isset($booking->expired_at) && \Carbon\Carbon::parse($booking->updated_at)->gt($booking->expired_at))
                                                <span class="badge text-bg-danger">Dikembalikan terlambat</span>
                                            @else
                                                <span class="badge text-bg-secondary">{{ $booking->status }}</span>
                                            @endif
                                        @elseif(isset($booking->expired_at) && $booking->expired_at < now()->startOfDay() && $booking->status != 'Dikembalikan')
                                            <span class="badge text-bg-danger">Terlambat</span>
                                        @else
                                            <span
                                                class="badge {{ match ($booking->status) {
                                                    'Diajukan' => 'text-bg-warning',
                                                    'Disetujui' => 'text-bg-success',
                                                    'Ditolak' => 'text-bg-dark',
                                                    default => 'text-bg-light',
                                                } }}">{{ $booking->status }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-medium">Waktu Pinjam</td>
                                    <td>:</td>
                                    <td>{{ \Carbon\Carbon::parse($booking->created_at)->translatedFormat('d F Y') }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-medium">Tenggat Kembali</td>
                                    <td>:</td>
                                    <td>{{ $booking->expired_at ? \Carbon\Carbon::parse($booking->expired_at)->translatedFormat('d F Y') : '-' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-medium" style="vertical-align: top;">Alasan Pinjam</td>
                                    <td style="vertical-align: top;">:</td>
                                    <td>{{ $booking->alasan }}</td>
                                </tr>
                            </tbody>
                            <thead>
                                <tr>
                                    <th colspan="3" class="pt-4 ps-0">
                                        <h6 class="m-0 fw-bold">Informasi Buku</h6>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="fw-medium">Judul Buku</td>
                                    <td>:</td>
                                    <td>{{ $booking->book->title }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-medium" style="vertical-align: top;">Penulis</td>
                                    <td style="vertical-align: top;">:</td>
                                    <td>{{ $booking->book->author }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-medium">Penerbit</td>
                                    <td>:</td>
                                    <td>{{ $booking->book->publisher }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-medium">Stok Buku</td>
                                    <td>:</td>
                                    <td>{{ $booking->book->stock == 0 ? 'Buku Digital (Tersedia)' : $booking->book->stock }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>
    @endsection
