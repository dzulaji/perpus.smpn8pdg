@extends('layouts.main')

@section('main-content')
<div class="bg-[#F9FAFB] min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-10 text-center">
            <h1 class="text-3xl font-bold text-[#15171A] mb-3 font-['Inter']">Hasil Rekomendasi Buku</h1>
            <p class="text-[#738A94] text-lg max-w-2xl mx-auto">Berikut adalah <b>Top 25</b> buku yang paling sesuai dengan kriteria yang Anda berikan.</p>
        </div>

        @if (isset($error_message))
            <div class="max-w-3xl mx-auto mb-10 bg-amber-50 border border-amber-200 rounded-xl p-6 text-center shadow-sm text-amber-800 font-medium">
                {{ $error_message }}
            </div>
        @elseif (empty($dataHasilRekomendasi))
            <div class="max-w-3xl mx-auto mb-10 bg-blue-50 border border-blue-200 rounded-xl p-6 text-center shadow-sm text-blue-800 font-medium">
                Tidak ada hasil rekomendasi yang ditemukan untuk kriteria Anda.
            </div>
        @else
            <!-- Grid Rekomendasi -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 xl:gap-10">
                @foreach ($dataHasilRekomendasi as $item)
                    @php
                        $book = $item['book'];
                    @endphp
                    <div class="relative pt-6">
                        <!-- Ranking Badge -->
                        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-12 h-12 bg-[#FF9400] text-white rounded-full border-4 border-[#F9FAFB] shadow-md flex items-center justify-center text-xl font-bold z-10">
                            {{ $loop->iteration }}
                        </div>

                        <a href="{{ url('/books/' . $book->id) }}" class="block h-full group">
                            <div class="bg-white h-full rounded-2xl shadow-sm border border-[#DDE1E5] overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-2 flex flex-col">
                                <div class="relative w-full aspect-[3/4] bg-[#F0F2F3] overflow-hidden">
                                    @if ($book->cover)
                                        <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" src="{{ asset('storage/' . $book->cover) }}" alt="{{ $book->title }}">
                                    @else
                                        <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" src="{{ asset('img/bookCoverDefault.png') }}" alt="{{ $book->title }}">
                                    @endif
                                    
                                    <!-- Skor Badge -->
                                    <div class="absolute bottom-0 right-0 bg-[#15171A]/80 backdrop-blur-sm text-white px-3 py-1.5 rounded-tl-xl font-semibold text-sm">
                                        {{ number_format($item['utilities_persen'], 2) }}% Kecocokan
                                    </div>
                                </div>
                                <div class="p-5 flex-grow flex flex-col">
                                    <h5 class="text-lg font-bold text-[#15171A] mb-2 line-clamp-2 group-hover:text-[#FF9400] transition-colors">{{ $book->title }}</h5>
                                    <p class="text-sm text-[#738A94] line-clamp-3 mb-4">{{ $book->description ?: 'Tidak ada deskripsi.' }}</p>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row justify-center items-center gap-4 mt-12">
                <button onclick="document.getElementById('rincianPerhitungan').classList.toggle('hidden')" class="inline-flex items-center px-6 py-3 bg-white border border-[#DDE1E5] hover:bg-gray-50 text-[#15171A] font-semibold rounded-lg transition-colors shadow-sm group">
                    Tampilkan Rincian Perhitungan
                    <svg class="ml-2 w-5 h-5 text-[#738A94] group-hover:text-[#15171A] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                
                <a href="{{ route('rekomendasi.kuisioner') }}" class="inline-flex items-center px-6 py-3 bg-[#FF9400] hover:bg-[#E88200] text-white font-bold rounded-lg transition-colors shadow-sm group">
                    <svg class="mr-2 w-5 h-5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z"></path></svg>
                    Ulangi Kuesioner
                </a>
            </div>

            <!-- Rincian Perhitungan -->
            <div id="rincianPerhitungan" class="hidden mt-8">
                <div class="bg-white rounded-2xl shadow-sm border border-[#DDE1E5] p-6 lg:p-8">
                    <h3 class="text-xl font-bold text-[#15171A] mb-8 text-center border-b border-[#F0F2F3] pb-4">Logika Perhitungan SMART</h3>
                    
                    @foreach ($dataHasilRekomendasi as $item)
                        @php
                            $bookDisplay = $item['book'];
                            $skorMentahDisplay = $item['skor_mentah_buku'];
                            $rincianKriteriaDisplay = $item['rincian_per_kriteria'];
                            $normalisasiDisplayRincian = $skorMentahDisplay / $_maxSkorMentahOverall;
                            $utilitiesDisplayRincian = $normalisasiDisplayRincian * 100;
                        @endphp

                        <div class="mb-10 last:mb-0">
                            <h4 class="text-lg font-bold text-[#FF9400] mb-4">Peringkat {{ $loop->iteration }} - {{ $bookDisplay->title }}</h4>
                            
                            <div class="mb-6">
                                <h5 class="text-sm font-bold text-[#738A94] uppercase tracking-wider mb-3">1. Utility Per Kriteria</h5>
                                <div class="overflow-x-auto rounded-lg border border-[#DDE1E5]">
                                    <table class="min-w-full divide-y divide-[#DDE1E5] text-sm">
                                        <thead class="bg-[#F9FAFB]">
                                            <tr>
                                                <th class="px-4 py-3 text-left font-semibold text-[#15171A]">Kriteria</th>
                                                <th class="px-4 py-3 text-left font-semibold text-[#15171A]">Jawaban User</th>
                                                <th class="px-4 py-3 text-left font-semibold text-[#15171A]">Nilai Buku</th>
                                                <th class="px-4 py-3 text-left font-semibold text-[#15171A]">Selisih</th>
                                                <th class="px-4 py-3 text-left font-semibold text-[#15171A]">Utility</th>
                                                <th class="px-4 py-3 text-left font-semibold text-[#15171A]">Bobot</th>
                                                <th class="px-4 py-3 text-left font-semibold text-[#15171A]">Skor x Bobot</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-[#F0F2F3] bg-white">
                                            @foreach ($rincianKriteriaDisplay as $rincian)
                                                <tr class="hover:bg-gray-50 transition-colors">
                                                    <td class="px-4 py-2 text-[#4A5568]">{{ $rincian['nama_kriteria'] }}</td>
                                                    <td class="px-4 py-2 text-[#4A5568]">{{ $rincian['jawaban_user'] }}</td>
                                                    <td class="px-4 py-2 text-[#4A5568]">{{ $rincian['nilai_buku'] }}</td>
                                                    <td class="px-4 py-2 text-[#4A5568]">{{ $rincian['selisih'] }}</td>
                                                    <td class="px-4 py-2 text-[#4A5568]">{{ number_format($rincian['utility'], 2) }}</td>
                                                    <td class="px-4 py-2 text-[#4A5568]">{{ $rincian['bobot'] }}</td>
                                                    <td class="px-4 py-2 font-medium text-[#15171A]">{{ number_format($rincian['skor_bobot'], 4) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot class="bg-[#F9FAFB]">
                                            <tr>
                                                <th colspan="6" class="px-4 py-3 text-right font-bold text-[#15171A]">Skor Mentah Akhir</th>
                                                <th class="px-4 py-3 font-bold text-[#FF9400]">{{ number_format($skorMentahDisplay, 4) }}</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <div>
                                <h5 class="text-sm font-bold text-[#738A94] uppercase tracking-wider mb-3">2. Normalisasi Hasil Akhir</h5>
                                <div class="overflow-x-auto rounded-lg border border-[#DDE1E5]">
                                    <table class="min-w-full divide-y divide-[#DDE1E5] text-sm">
                                        <thead class="bg-[#F9FAFB]">
                                            <tr>
                                                <th class="px-4 py-3 text-left font-semibold text-[#15171A]">Skor Mentah Buku</th>
                                                <th class="px-4 py-3 text-left font-semibold text-[#15171A]">Max Skor Mentah (Global)</th>
                                                <th class="px-4 py-3 text-left font-semibold text-[#15171A]">Normalisasi</th>
                                                <th class="px-4 py-3 text-left font-semibold text-[#15171A]">Kecocokan Akhir (%)</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white">
                                            <tr>
                                                <td class="px-4 py-3 text-[#4A5568]">{{ number_format($skorMentahDisplay, 4) }}</td>
                                                <td class="px-4 py-3 text-[#4A5568]">{{ number_format($_maxSkorMentahOverall, 4) }}</td>
                                                <td class="px-4 py-3 text-[#4A5568]">{{ number_format($normalisasiDisplayRincian, 4) }}</td>
                                                <td class="px-4 py-3 font-bold text-[#15171A]">{{ number_format($utilitiesDisplayRincian, 2) }}%</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            @if (!$loop->last)
                                <hr class="my-8 border-[#F0F2F3]">
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
