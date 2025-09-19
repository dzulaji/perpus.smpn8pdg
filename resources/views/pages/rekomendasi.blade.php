@extends('layouts.main')

@section('style')
    <style>
        .form-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(450px, 1fr));
            gap: 30px;
        }

        .form-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease-in-out;
        }

        .form-card:hover {
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
        }

        .btn-center {
            display: flex;
            justify-content: center;
            margin-top: 40px;
        }

        .form-label {
            font-weight: 600;
            margin-bottom: 8px;
            display: block;
        }

        select.form-select {
            border-radius: 8px;
            padding: 10px;
            font-size: 16px;
        }

        .custom-explore-btn {
            padding: .2rem .6rem .2rem 1.2rem;
            border-radius: 10px;
            background: #f5cc44;
            color: #2d2d2d;
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
            color: #2d2d2d;
            transition: .3s all ease-in-out;
        }

        .custom-explore-btn:hover {
            background: #ffc720;
        }

        .custom-explore-btn:hover i {
            margin-left: .25rem;
            color: #3d3d3d;
        }
    </style>
@endsection

@section('main-content')
    <div class="container py-5">
        <h2 class="mb-4 text-center">Kuisioner Rekomendasi Buku</h2>

        @php
            // Bulatkan total bobot untuk perbandingan yang aman (menghindari masalah float)
            $isBobotSempurna = round($totalBobot ?? 0, 2) == 1.0;
        @endphp

        {{-- 1. Tampilkan Peringatan Jika Bobot Tidak Sempurna --}}
        @unless ($isBobotSempurna)
            <div class="alert alert-warning text-center" role="alert">
                <h4 class="alert-heading">Fitur Rekomendasi Belum Siap</h4>
                <p>Saat ini fitur rekomendasi belum dapat digunakan karena konfigurasi bobot kriteria oleh admin belum sempurna.
                    Silakan hubungi pustakawan untuk informasi lebih lanjut.</p>
                <hr>
                <p class="mb-0">Total bobot yang terkonfigurasi: <strong>{{ number_format($totalBobot ?? 0, 2) }} /
                        1.00</strong></p>
            </div>
        @endunless

        {{-- 2. Tampilkan Form HANYA JIKA Bobot Sudah Sempurna --}}
        @if ($isBobotSempurna)
            <form method="POST" action="{{ route('rekomendasi.proses') }}">
                @csrf
                <div class="form-section">
                    @foreach ($pertanyaan as $pertanyaanItem)
                        <div class="form-card">
                            <label class="form-label"><strong>{{ $pertanyaanItem->pertanyaan }}</strong></label>
                            <select name="jawaban[{{ $pertanyaanItem->kriteria->id_kriteria }}]" class="form-select"
                                required>
                                <option value="">-- Pilih Preferensi Anda --</option>
                                @if ($pertanyaanItem->kriteria && $pertanyaanItem->kriteria->subKriteria)
                                    @foreach ($pertanyaanItem->kriteria->subKriteria as $sub)
                                        <option value="{{ $sub->nilai }}" @selected(old('jawaban.' . $pertanyaanItem->kriteria->id_kriteria) == $sub->nilai)>
                                            {{ $sub->nama_tampilan }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    @endforeach
                </div>

                <div class="btn-center">
                    <button type="submit" class="custom-explore-btn">
                        Cari Rekomendasi Buku <i class="bi bi-arrow-right-square-fill fs-2"></i>
                    </button>
                </div>
            </form>
        @else
            {{-- Tampilkan Tombol Nonaktif Jika Bobot Tidak Sempurna --}}
            <div class="btn-center">
                <button type="button" class="custom-explore-btn" disabled
                    style="background-color: #e9ecef; color: #6c757d; cursor: not-allowed;">
                    Cari Rekomendasi Buku <i class="bi bi-arrow-right-square-fill fs-2"></i>
                </button>
            </div>
        @endif
    </div>
@endsection
