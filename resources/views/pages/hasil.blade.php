@extends('layouts.main')

@section('style')
    <style>
        .card-wrapper {
            margin-bottom: 1.5rem;
        }

        .card {
            margin-top: 20px;
            height: 100%;
        }

        .card .btn {
            border-radius: 2px;
            text-transform: uppercase;
            font-size: 12px;
            padding: 7px 20px;
        }

        .card .card-img-block {
            width: 91%;
            margin: 0 auto;
            position: relative;
            top: -20px;
            transition: .3s all ease-in-out;
        }

        .card:hover .card-img-block {
            top: -30px;
        }

        .card .card-img-block img {
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.43);
            /* max-height: 200px;
                                                                                object-fit: cover;
                                                                                width: 100%; */
        }

        .card h5 {
            font-weight: 600;
            margin-top: -4px;
        }

        .card p {
            font-size: 14px;
            font-weight: 300;
        }

        .skor-badge {
            position: absolute;
            top: 0;
            left: 0;
            background-color: #ffc107;
            color: #000;
            padding: 5px 10px;
            border-radius: 0 0 5px 0;
            font-weight: bold;
        }

        .ranking-item-wrapper {
            position: relative;
            padding-top: 25px;
            /* Memberi ruang di atas kartu untuk badge peringkat */
        }

        .ranking-badge-lg {
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            /* Membuat posisi horizontal menjadi tengah */

            width: 50px;
            /* Ukuran lingkaran badge */
            height: 50px;

            background-color: #0d6efd;
            /* Warna biru primary Bootstrap */
            color: white;
            border-radius: 50%;
            /* Membuatnya menjadi lingkaran sempurna */
            border: 3px solid white;
            /* Memberi bingkai putih agar menonjol */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            /* Memberi efek bayangan */

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 1.75rem;
            /* Ukuran angka peringkat yang besar */
            font-weight: 700;
            /* Font tebal */
            z-index: 10;
            /* Memastikan badge berada di lapisan paling atas */
        }

        .skor-badge {
            position: absolute;
            top: 0;
            left: 0;
            background-color: #ffc107;
            color: #000;
            padding: 5px 10px;
            border-radius: 0 0 8px 0;
            font-weight: bold;
        }

        .card-img-block img {
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.43);
        }

        .custom-explore-btn {
            padding: .2rem .6rem .2rem 1.2rem;
            border-radius: 10px;
            background: #6c757d;
            color: #ffffff;
            font-weight: 600;
            text-decoration: none;
            width: fit-content;
            display: flex;
            gap: 1rem;
            align-items: center;
            justify-content: center;
            border: none;
            font-size: 15px;
            transition: .3s all ease-in-out;
        }

        .custom-explore-btn i {
            color: #ffffff;
            transition: .3s all ease-in-out;
        }

        .custom-explore-btn:hover {
            background: #5c636a;
        }

        .custom-explore-btn:hover i {
            margin-left: .25rem;
            color: #dadada;
        }
    </style>
@endsection

@section('main-content')
    <div class="container my-4 py-4">
        <h2 class="mb-4 text-center">Hasil Rekomendasi Buku</h2>

        @if (isset($error_message))
            <div class="alert alert-warning text-center">{{ $error_message }}</div>
        @elseif (empty($dataHasilRekomendasi))
            <div class="alert alert-info text-center">Tidak ada hasil rekomendasi yang ditemukan untuk kriteria Anda.</div>
        @else
            {{-- REVISI UTAMA: Sesuaikan kelas grid agar konsisten --}}
            {{-- 'row-cols-lg-5' untuk 5 kartu per baris di layar besar dan 'g-4' untuk jarak --}}
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-5 g-4">
                @foreach ($dataHasilRekomendasi as $item)
                    @php
                        $book = $item['book'];
                    @endphp
                    {{-- REVISI: Struktur kolom disederhanakan dan ditambahkan 'd-flex' untuk tinggi yang sama --}}
                    <div class="col d-flex">
                        <div class="ranking-item-wrapper w-100">

                            <div class="ranking-badge-lg">
                                {{ $loop->iteration }}
                            </div>

                            <a href="{{ url('/books/' . $book->id) }}" class="text-decoration-none d-block h-100">
                                <div class="card h-100 shadow-sm d-flex flex-column">
                                    <div class="card-img-block">
                                        @if ($book->cover)
                                            <img class="card-img-top" src="{{ asset('storage/' . $book->cover) }}"
                                                alt="{{ $book->title }}">
                                        @else
                                            <img class="card-img-top" src="{{ asset('img/bookCoverDefault.png') }}"
                                                alt="{{ $book->title }}">
                                        @endif
                                        <div class="skor-badge">
                                            {{ number_format($item['utilities_persen'], 2) }}%
                                        </div>
                                    </div>
                                    <div class="card-body pt-0 d-flex flex-column">
                                        <h5 class="card-title">{{ Str::limit($book->title, 50) }}</h5>
                                        <p class="card-text mb-auto">{{ Str::limit($book->description, 70) }}</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Bagian tombol yang tidak dihilangkan --}}
            <div class="btn-center mt-5">
                <button class="custom-explore-btn" type="button" data-bs-toggle="collapse"
                    data-bs-target="#rincianPerhitungan" aria-expanded="false" aria-controls="rincianPerhitungan">
                    Tampilkan Rincian Perhitungan <i class="bi bi-arrow-down-square-fill fs-3"></i>
                </button>
                <a href="{{ route('rekomendasi.kuisioner') }}" class="custom-explore-btn mt-2">
                    <i class="bi bi-arrow-left-square-fill fs-3"></i> Ulangi Kuesioner
                </a>
            </div>

            {{-- Bagian rincian perhitungan yang tidak dihilangkan --}}
            <div class="collapse mt-4" id="rincianPerhitungan">
                <div class="card card-body">
                    @foreach ($dataHasilRekomendasi as $item)
                        @php
                            $bookDisplay = $item['book'];
                            $skorMentahDisplay = $item['skor_mentah_buku'];
                            $rincianKriteriaDisplay = $item['rincian_per_kriteria'];
                            $normalisasiDisplayRincian = $skorMentahDisplay / $_maxSkorMentahOverall;
                            $utilitiesDisplayRincian = $normalisasiDisplayRincian * 100;
                        @endphp

                        <h4 class="mt-4">{{ $bookDisplay->title }}</h4>
                        {{-- ... (Seluruh isi tabel rincian Anda tetap di sini) ... --}}
                        <h5>1. Utility Per Kriteria</h5>
                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th>Kriteria</th>
                                    <th>Jawaban User</th>
                                    <th>Nilai Buku</th>
                                    <th>Selisih</th>
                                    <th>Utility</th>
                                    <th>Bobot</th>
                                    <th>Skor x Bobot</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rincianKriteriaDisplay as $rincian)
                                    <tr>
                                        <td>{{ $rincian['nama_kriteria'] }}</td>
                                        <td>{{ $rincian['jawaban_user'] }}</td>
                                        <td>{{ $rincian['nilai_buku'] }}</td>
                                        <td>{{ $rincian['selisih'] }}</td>
                                        <td>{{ number_format($rincian['utility'], 2) }}</td>
                                        <td>{{ $rincian['bobot'] }}</td>
                                        <td>{{ number_format($rincian['skor_bobot'], 4) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="6" class="text-end">Skor Mentah Akhir</th>
                                    <th>{{ number_format($skorMentahDisplay, 4) }}</th>
                                </tr>
                            </tfoot>
                        </table>

                        <h5>2. Normalisasi (untuk Rincian)</h5>
                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th>Skor Mentah Buku</th>
                                    <th>Max Skor Mentah (Global)</th>
                                    <th>Normalisasi</th>
                                    <th>Utilities (%)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ number_format($skorMentahDisplay, 4) }}</td>
                                    <td>{{ number_format($_maxSkorMentahOverall, 4) }}</td>
                                    <td>{{ number_format($normalisasiDisplayRincian, 4) }}</td>
                                    <td>{{ number_format($utilitiesDisplayRincian, 2) }}%</td>
                                </tr>
                            </tbody>
                        </table>
                        <hr>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection
