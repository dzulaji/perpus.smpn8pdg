@extends('layouts.main')

@section('main-content')
<div class="bg-[#F9FAFB] min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-10 text-center">
            <h1 class="text-3xl font-bold text-[#15171A] mb-3 font-['Inter']">Riwayat Rekomendasi Terakhir</h1>
            <p class="text-[#738A94] text-lg max-w-2xl mx-auto">Daftar **Top 25** buku yang paling direkomendasikan berdasarkan pengisian kuesioner terakhir Anda.</p>
        </div>

        @if (isset($error_message))
            <div class="max-w-3xl mx-auto mb-10 bg-amber-50 border border-amber-200 rounded-xl p-6 text-center shadow-sm text-amber-800 font-medium">
                {{ $error_message }}
            </div>
        @elseif (empty($dataHasilRekomendasi))
            <div class="max-w-3xl mx-auto mb-10 bg-blue-50 border border-blue-200 rounded-xl p-6 text-center shadow-sm text-blue-800 font-medium">
                Tidak ada data riwayat yang ditemukan. Anda mungkin belum pernah mengisi kuesioner.
            </div>
        @else
            <!-- Grid Rekomendasi -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 xl:gap-10 mt-8">
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
        @endif
    </div>
</div>
@endsection
