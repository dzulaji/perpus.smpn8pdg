@extends('layouts.main')

@section('main-content')
<div class="bg-[#F9FAFB] min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-10 text-center">
            <h1 class="text-3xl font-bold text-[#15171A] mb-3 font-['Inter']">Kuisioner Rekomendasi Buku</h1>
            <p class="text-[#738A94] text-lg max-w-2xl mx-auto">Isi preferensi Anda di bawah ini dan biarkan sistem membantu menemukan buku yang paling sesuai untuk Anda.</p>
        </div>

        @php
            $isBobotSempurna = round($totalBobot ?? 0, 2) == 1.0;
        @endphp

        <!-- Pesan Kesalahan Bobot -->
        @unless ($isBobotSempurna)
            <div class="max-w-3xl mx-auto mb-10 bg-amber-50 border border-amber-200 rounded-xl p-6 text-center shadow-sm">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-amber-100 text-amber-600 mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-amber-800 mb-2">Fitur Rekomendasi Belum Siap</h3>
                <p class="text-amber-700 mb-4">Saat ini fitur rekomendasi belum dapat digunakan karena konfigurasi bobot kriteria oleh admin belum sempurna. Silakan hubungi pustakawan untuk informasi lebih lanjut.</p>
                <div class="pt-4 border-t border-amber-200/60 text-amber-800 font-semibold">
                    Total bobot yang terkonfigurasi: {{ number_format($totalBobot ?? 0, 2) }} / 1.00
                </div>
            </div>
        @endunless

        @if ($isBobotSempurna)
            <form method="POST" action="{{ route('rekomendasi.proses') }}">
                @csrf
                <!-- Grid Pertanyaan -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
                    @foreach ($pertanyaan as $pertanyaanItem)
                        <div class="bg-white p-6 rounded-xl shadow-sm border border-[#DDE1E5] hover:shadow-md transition-shadow group">
                            <label class="block text-[#15171A] font-semibold mb-3 group-hover:text-[#FF9400] transition-colors">
                                {{ $pertanyaanItem->pertanyaan }}
                            </label>
                            <div class="relative">
                                <select name="jawaban[{{ $pertanyaanItem->kriteria->id_kriteria }}]" class="block w-full rounded-md border border-[#DDE1E5] bg-white px-4 py-2.5 text-[#15171A] shadow-sm focus:border-[#FF9400] focus:outline-none focus:ring-1 focus:ring-[#FF9400] appearance-none" required>
                                    <option value="" disabled selected>-- Pilih Preferensi Anda --</option>
                                    @if ($pertanyaanItem->kriteria && $pertanyaanItem->kriteria->subKriteria)
                                        @foreach ($pertanyaanItem->kriteria->subKriteria as $sub)
                                            <option value="{{ $sub->nilai }}" @selected(old('jawaban.' . $pertanyaanItem->kriteria->id_kriteria) == $sub->nilai)>
                                                {{ $sub->nama_tampilan }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-[#738A94]">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="flex justify-center">
                    <button type="submit" class="inline-flex items-center px-8 py-3.5 bg-[#FF9400] hover:bg-[#E88200] text-white text-lg font-bold rounded-lg transition-colors shadow-sm group">
                        Cari Rekomendasi Buku 
                        <svg class="ml-3 w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path></svg>
                    </button>
                </div>
            </form>
        @else
            <!-- Tombol Nonaktif Jika Bobot Tidak Sempurna -->
            <div class="flex justify-center">
                <button type="button" class="inline-flex items-center px-8 py-3.5 bg-gray-200 text-gray-500 text-lg font-bold rounded-lg cursor-not-allowed">
                    Cari Rekomendasi Buku 
                    <svg class="ml-3 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path></svg>
                </button>
            </div>
        @endif

    </div>
</div>
@endsection
