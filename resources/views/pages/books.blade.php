@extends('layouts.main')

@section('main-content')
<!-- Header & Search Section -->
<section class="bg-[#15171A] py-16 px-4 sm:px-6 lg:px-8 border-b border-[#313539]">
    <div class="max-w-3xl mx-auto text-center">
        <h1 class="text-3xl md:text-5xl font-bold text-white mb-4 font-['Inter']">Koleksi Buku</h1>
        <p class="text-[#738A94] text-lg mb-8">Cari dan temukan buku yang Anda butuhkan di e-Katalog Perpustakaan SMPN 8 Padang.</p>
        
        <!-- Search Form -->
        <form action="/koleksi" method="get" class="relative">
            <div class="flex items-center bg-white rounded-lg overflow-hidden p-1 shadow-md focus-within:ring-2 focus-within:ring-[#FF9400] transition-all">
                <div class="pl-4 text-[#738A94]">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 256 256"><path d="M229.66,218.34l-50.07-50.06a88.11,88.11,0,1,0-11.31,11.31l50.06,50.07a8,8,0,0,0,11.32-11.32ZM40,112a72,72,0,1,1,72,72A72.08,72.08,0,0,1,40,112Z"></path></svg>
                </div>
                <input type="text" class="w-full px-4 py-3 outline-none text-[#15171A] text-lg" placeholder="Cari judul buku, penulis, atau deskripsi..." name="searchKeyword" value="{{ request('searchKeyword') }}">
                <button type="submit" class="bg-[#FF9400] hover:bg-[#E88200] text-white px-6 py-3 rounded-md transition-colors whitespace-nowrap font-semibold">
                    Cari
                </button>
            </div>
        </form>
    </div>
</section>

<!-- Books Collection Section -->
<section class="bg-[#F9FAFB] py-12 px-4 sm:px-6 lg:px-8 min-h-screen">
    <div class="max-w-7xl mx-auto">
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <h2 class="text-xl font-bold text-[#15171A]">
                @if(request('searchKeyword'))
                    Pencarian: <span class="text-[#FF9400]">"{{ request('searchKeyword') }}"</span>
                @elseif(request('category'))
                    Kategori: <span class="text-[#FF9400]">{{ request('category') }}</span>
                @else
                    Semua Koleksi
                @endif
            </h2>
            <span class="text-sm font-medium text-[#738A94] px-3 py-1 bg-white border border-[#DDE1E5] rounded-full shadow-sm">{{ method_exists($books, 'total') ? $books->total() : count($books) }} Buku Ditemukan</span>
        </div>

        @if ($books->isEmpty() && request('searchKeyword'))
            <!-- SweetAlert2 Trigger untuk hasil pencarian kosong -->
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Buku Tidak Ditemukan',
                        text: 'Coba gunakan kata kunci pencarian yang lain!',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#FF9400'
                    });
                });
            </script>
            <!-- Tampilkan Semua Buku Jika Kosong -->
            @php
                $books = \App\Models\Book::paginate(25);
            @endphp
        @endif

        @if($books->isEmpty())
            <div class="text-center py-20 bg-white border border-[#DDE1E5] rounded-xl shadow-sm">
                <div class="w-16 h-16 bg-[#F0F2F3] text-[#738A94] rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" viewBox="0 0 256 256"><path d="M216,40H40A16,16,0,0,0,24,56V200a16,16,0,0,0,16,16H216a16,16,0,0,0,16-16V56A16,16,0,0,0,216,40ZM40,56H216V88H40ZM216,200H40V104H216V200Zm-40-64a8,8,0,0,1-8,8H88a8,8,0,0,1,0-16h80A8,8,0,0,1,176,136Zm0,32a8,8,0,0,1-8,8H88a8,8,0,0,1,0-16h80A8,8,0,0,1,176,168Z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-[#15171A] mb-2">Koleksi Masih Kosong</h3>
                <p class="text-[#738A94]">Belum ada buku yang ditambahkan ke dalam e-katalog.</p>
            </div>
        @else
            <!-- Grid Buku -->
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
                @foreach ($books as $book)
                    <a href="/books/{{ $book->id }}" class="group flex flex-col bg-white border border-[#DDE1E5] rounded-lg overflow-hidden hover:shadow-md hover:border-[#738A94] transition-all duration-300">
                        <div class="relative aspect-[3/4] overflow-hidden bg-[#F0F2F3]">
                            @if ($book->cover)
                                <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="{{ asset('storage/' . $book->cover) }}" alt="{{ $book->title }}" onerror="this.onerror=null;this.src='{{ asset('img/bookCoverDefault.png') }}';">
                            @else
                                <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="{{ asset('img/bookCoverDefault.png') }}" alt="{{ $book->title }}">
                            @endif
                        </div>
                        <div class="p-4 flex-grow flex flex-col justify-between">
                            <div>
                                <h3 class="text-sm font-bold text-[#15171A] line-clamp-2 leading-tight mb-2 group-hover:text-[#FF9400] transition-colors">{{ $book->title }}</h3>
                                <p class="text-xs text-[#738A94] line-clamp-2 mb-2">{{ $book->description }}</p>
                            </div>
                            <span class="text-xs text-[#738A94] font-medium border-t border-[#DDE1E5] pt-2 mt-2">{{ $book->created_at ? $book->created_at->diffForHumans() : '' }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
            
            <!-- Pagination Links -->
            @if(method_exists($books, 'links') && $books->hasPages())
                <div class="mt-12 flex justify-center">
                    {{ $books->withQueryString()->links('vendor.pagination.ghost') }}
                </div>
            @endif
        @endif
    </div>
</section>
@endsection
