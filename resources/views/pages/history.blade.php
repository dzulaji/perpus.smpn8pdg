@extends('layouts.main')

@section('style')
    <style>
        /* Gaya umum kartu */
        .card-wrapper {
            margin-bottom: 1.5rem;
        }

        .card {
            margin-top: 20px;
            height: 100%;
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
        }

        .card h5 {
            font-weight: 600;
            margin-top: -4px;
        }

        .card p {
            font-size: 14px;
            font-weight: 300;
        }

        /* Gaya untuk badge skor persentase (kiri atas) */
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

        /* === REVISI CSS: GAYA PERINGKAT HIJAU DI DALAM KARTU === */
        .ranking-badge {
            position: absolute;
            top: 0;
            right: 0;
            /* Posisikan di pojok kanan atas */
            background-color: #198754;
            /* Warna hijau */
            color: #fff;
            padding: 5px 10px;
            border-radius: 0 0 0 5px;
            font-weight: bold;
        }
    </style>
@endsection

@section('main-content')
    <div class="container my-4 py-4">
        <h2 class="mb-4 text-center">Riwayat Rekomendasi Terakhir</h2>

        @if (isset($error_message))
            <div class="alert alert-warning text-center">{{ $error_message }}</div>
        @elseif (empty($dataHasilRekomendasi))
            <div class="alert alert-info text-center">Tidak ada data riwayat yang ditemukan.</div>
        @else
            {{-- REVISI: Mengembalikan ke 5 kartu per baris agar lebih kecil --}}
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-5 mt-4 g-4">
                @foreach ($dataHasilRekomendasi as $item)
                    @php
                        $book = $item['book'];
                    @endphp

                    {{-- REVISI: Mengembalikan ke struktur HTML kartu yang lebih sederhana --}}
                    <a href="{{ url('/books/' . $book->id) }}" class="card-wrapper col text-decoration-none">
                        <div class="card position-relative">
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

                                {{-- Peringkat dengan gaya badge hijau --}}
                                <div class="ranking-badge">
                                    #{{ $loop->iteration }}
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <h5 class="card-title">{{ Str::limit($book->title, 50) }}</h5>
                                <p class="card-text">{{ Str::limit($book->description, 70) }}</p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
@endsection
