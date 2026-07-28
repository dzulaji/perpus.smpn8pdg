@extends('layouts.main')

@section('main-content')

<!-- Hero Section -->
<section class="bg-[#15171A] py-24 px-4 sm:px-6 lg:px-8 relative overflow-hidden" data-aos="fade-up" data-aos-duration="800">
    <div class="max-w-7xl mx-auto text-center relative z-10">
        <h1 class="text-4xl md:text-6xl font-bold text-white leading-tight mb-6 font-['Inter']">
            Jelajahi Dunia Ilmu di <br class="hidden md:block" /> SMP Negeri 8 Padang
        </h1>
        <p class="text-lg md:text-xl text-[#738A94] mb-10 max-w-2xl mx-auto">
            Temukan ribuan koleksi buku digital dan fisik. Jelajahi topik favorit dan buka pintu menuju dunia pengetahuan yang lebih luas!
        </p>
        
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="{{ auth()->check() ? route('rekomendasi.history') : url('/login') }}" 
               class="inline-flex justify-center items-center gap-2 bg-[#FF9400] hover:bg-[#E88200] text-white px-8 py-4 rounded-md font-medium text-lg transition-colors shadow-sm">
                {{ auth()->check() ? 'Lihat Rekomendasi Anda' : 'Mulai Membaca Sekarang' }}
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
                </svg>
            </a>
            <a href="/koleksi" class="inline-flex justify-center items-center gap-2 bg-[#1F2022] hover:bg-[#272A2D] text-white border border-[#313539] px-8 py-4 rounded-md font-medium text-lg transition-colors">
                Lihat Semua Koleksi
            </a>
        </div>
    </div>
    
    <!-- Background Decor -->
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-[#FF9400] opacity-5 rounded-full blur-[100px] pointer-events-none"></div>
</section>

<!-- Stats Section -->
<section class="bg-[#1F2022] border-t border-[#313539] py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center divide-y md:divide-y-0 md:divide-x divide-[#313539]">
            <div class="py-4 md:py-0" data-aos="fade-up" data-aos-delay="100">
                <h3 class="text-4xl font-bold text-white mb-2">{{ $bookCount }}+</h3>
                <p class="text-[#738A94] uppercase tracking-widest text-sm font-semibold">Koleksi Buku</p>
            </div>
            <div class="py-4 md:py-0" data-aos="fade-up" data-aos-delay="200">
                <h3 class="text-4xl font-bold text-white mb-2">{{ $userCount }}+</h3>
                <p class="text-[#738A94] uppercase tracking-widest text-sm font-semibold">Anggota Aktif</p>
            </div>
            <div class="py-4 md:py-0" data-aos="fade-up" data-aos-delay="300">
                <h3 class="text-4xl font-bold text-white mb-2">{{ $bookingCount }}+</h3>
                <p class="text-[#738A94] uppercase tracking-widest text-sm font-semibold">Total Peminjaman</p>
            </div>
        </div>
    </div>
</section>

<!-- Fitur Section -->
<section class="bg-white py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16" data-aos="fade-up">
            <span class="text-xs font-bold tracking-widest text-[#FF9400] uppercase mb-2 block">Keunggulan</span>
            <h2 class="text-3xl font-bold text-[#15171A]">Mengapa e-Katalog Kami?</h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-[#F9FAFB] p-8 rounded-xl border border-[#DDE1E5] hover:shadow-md transition-shadow" data-aos="fade-up" data-aos-delay="100">
                <div class="w-14 h-14 bg-[#FF9400]/10 text-[#FF9400] rounded-full flex items-center justify-center mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-[#15171A] mb-3">Rekomendasi Cerdas</h3>
                <p class="text-[#738A94] leading-relaxed">Sistem SMART kami akan menganalisis preferensi Anda dan memberikan rekomendasi buku yang paling cocok untuk dibaca selanjutnya.</p>
            </div>
            
            <div class="bg-[#F9FAFB] p-8 rounded-xl border border-[#DDE1E5] hover:shadow-md transition-shadow" data-aos="fade-up" data-aos-delay="200">
                <div class="w-14 h-14 bg-[#FF9400]/10 text-[#FF9400] rounded-full flex items-center justify-center mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-[#15171A] mb-3">Katalog Lengkap</h3>
                <p class="text-[#738A94] leading-relaxed">Akses mudah ke seluruh koleksi perpustakaan, dari buku pelajaran, fiksi, hingga referensi ilmiah hanya dengan beberapa klik.</p>
            </div>
            
            <div class="bg-[#F9FAFB] p-8 rounded-xl border border-[#DDE1E5] hover:shadow-md transition-shadow" data-aos="fade-up" data-aos-delay="300">
                <div class="w-14 h-14 bg-[#FF9400]/10 text-[#FF9400] rounded-full flex items-center justify-center mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-[#15171A] mb-3">Peminjaman Cepat</h3>
                <p class="text-[#738A94] leading-relaxed">Proses booking buku secara online yang efisien. Dapatkan kode dan ambil buku Anda tanpa harus mengantre lama.</p>
            </div>
        </div>
    </div>
</section>

<!-- Recommended Books Section (Auth Only) -->
@if (auth()->check() && count($topRecommendedBooks) > 0)
<section class="bg-white py-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto border-t border-[#DDE1E5]" data-aos="fade-up">
    <div class="flex flex-col md:flex-row md:justify-between md:items-end mb-10 gap-4">
        <div>
            <span class="text-xs font-bold tracking-widest text-[#FF9400] uppercase mb-2 block">Untuk Anda</span>
            <h2 class="text-3xl font-bold text-[#15171A]">Rekomendasi Teratas</h2>
        </div>
        <a href="{{ route('rekomendasi.history') }}" class="hidden sm:inline-flex items-center gap-1 text-[#15171A] font-semibold hover:text-[#FF9400] transition-colors">
            Lihat Riwayat
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/></svg>
        </a>
    </div>
    
    <div class="grid grid-cols-2 md:grid-cols-3 lg:flex lg:justify-center gap-6">
        @foreach ($topRecommendedBooks as $detail)
            @php
                $book = $detail->book;
                $skorPersen = $detail->normalisasi ? $detail->normalisasi->utilities : 0;
            @endphp
            <a href="{{ url('/books/' . $book->id) }}" class="group flex flex-col bg-white border border-[#DDE1E5] rounded-lg overflow-hidden hover:shadow-md hover:border-[#738A94] transition-all duration-300 lg:w-[220px]">
                <div class="relative aspect-[3/4] overflow-hidden bg-[#F0F2F3]">
                    @if ($book->cover)
                        <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="{{ asset('storage/' . $book->cover) }}" alt="{{ $book->title }}" onerror="this.onerror=null;this.src='{{ asset('img/bookCoverDefault.png') }}';">
                    @else
                        <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="{{ asset('img/bookCoverDefault.png') }}" alt="{{ $book->title }}">
                    @endif
                    <div class="absolute top-2 right-2 bg-[#15171A] text-white text-xs font-bold px-2 py-1 rounded shadow-sm border border-[#313539]">
                        Cocok <span class="text-[#FF9400]">{{ number_format($skorPersen, 0) }}%</span>
                    </div>
                </div>
                <div class="p-4 flex-grow flex flex-col justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-[#15171A] line-clamp-2 leading-tight mb-2 group-hover:text-[#FF9400] transition-colors">{{ $book->title }}</h3>
                        <p class="text-xs text-[#738A94] line-clamp-2">{{ $book->description }}</p>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</section>
@endif

<!-- Latest Books Section -->
<section class="bg-white py-20 {{ (auth()->check() && count($topRecommendedBooks) > 0) ? 'border-t border-[#DDE1E5]' : '' }}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12" data-aos="fade-up">
            <span class="text-xs font-bold tracking-widest text-[#FF9400] uppercase mb-2 block">Terbaru</span>
            <h2 class="text-3xl font-bold text-[#15171A]">Baru Ditambahkan</h2>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-3 lg:flex lg:justify-center gap-6" data-aos="fade-up" data-aos-delay="100">
            @forelse ($latestBooks as $book)
                <a href="/books/{{ $book->id }}" class="group flex flex-col bg-white border border-[#DDE1E5] rounded-lg overflow-hidden hover:shadow-md hover:border-[#738A94] transition-all duration-300 lg:w-[220px]">
                    <div class="relative aspect-[3/4] overflow-hidden bg-[#F0F2F3]">
                        @if ($book->cover)
                            <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="/storage/{{ $book->cover }}" alt="{{ $book->title }}" onerror="this.onerror=null;this.src='{{ asset('img/bookCoverDefault.png') }}';">
                        @else
                            <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="{{ asset('img/bookCoverDefault.png') }}" alt="{{ $book->title }}">
                        @endif
                    </div>
                    <div class="p-4 flex-grow flex flex-col justify-between">
                        <div>
                            <h3 class="text-sm font-bold text-[#15171A] line-clamp-2 leading-tight mb-2 group-hover:text-[#FF9400] transition-colors">{{ $book->title }}</h3>
                            <p class="text-xs text-[#738A94] line-clamp-2 mb-2">{{ $book->description }}</p>
                        </div>
                        <span class="text-xs text-[#738A94] font-medium border-t border-[#DDE1E5] pt-2 mt-2">{{ $book->created_at->diffForHumans() }}</span>
                    </div>
                </a>
            @empty
                <div class="col-span-full text-center py-10 bg-[#F9FAFB] rounded-lg border border-[#DDE1E5]">
                    <p class="text-[#738A94]">Belum ada buku terbaru.</p>
                </div>
            @endforelse
        </div>
        
        <div class="mt-12 text-center">
            <a href="/koleksi" class="inline-block bg-[#F9FAFB] border border-[#DDE1E5] text-[#15171A] hover:bg-[#15171A] hover:text-white hover:border-[#15171A] px-6 py-2 rounded-md font-medium transition-colors">
                Jelajahi Semua Koleksi
            </a>
        </div>
    </div>
</section>

<!-- Panduan Peminjaman Section -->
<section class="bg-[#F9FAFB] py-20 px-4 sm:px-6 lg:px-8 border-t border-[#DDE1E5]">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-12" data-aos="fade-up">
            <span class="text-xs font-bold tracking-widest text-[#FF9400] uppercase mb-2 block">Informasi</span>
            <h2 class="text-3xl font-bold text-[#15171A]">Panduan Peminjaman</h2>
        </div>
        
        <div class="max-w-4xl mx-auto bg-white border border-[#DDE1E5] rounded-xl shadow-sm overflow-hidden relative" data-aos="fade-up" data-aos-delay="100">
            <!-- Stamp Icon Decoration -->
            <div class="absolute -top-6 -right-6 text-[#F9FAFB] opacity-80 pointer-events-none hidden sm:block">
                <svg xmlns="http://www.w3.org/2000/svg" width="160" height="160" fill="currentColor" viewBox="0 0 256 256" class="transform rotate-12">
                    <path d="M224,224a8,8,0,0,1-8,8H40a8,8,0,0,1,0-16H216A8,8,0,0,1,224,224Zm0-80v40a16,16,0,0,1-16,16H48a16,16,0,0,1-16-16V144a16,16,0,0,1,16-16h56.43L88.72,54.71A32,32,0,0,1,120,16h16a32,32,0,0,1,31.29,38.71L151.57,128H208A16,16,0,0,1,224,144ZM120.79,128h14.42l16.43-76.65A16,16,0,0,0,136,32H120a16,16,0,0,0-15.65,19.35ZM208,184V144H48v40H208Z"></path>
                </svg>
            </div>
            
            <div class="p-8 sm:p-12 relative z-10">
                <div class="space-y-8">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-[#FF9400]/10 text-[#FF9400] flex items-center justify-center font-bold text-lg">1</div>
                        <div>
                            <h4 class="text-lg font-bold text-[#15171A] mb-1">Daftar & Login</h4>
                            <p class="text-[#738A94]">Daftar dengan data pribadi siswa yang valid lalu login untuk mulai mengakses koleksi.</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-[#FF9400]/10 text-[#FF9400] flex items-center justify-center font-bold text-lg">2</div>
                        <div>
                            <h4 class="text-lg font-bold text-[#15171A] mb-1">Cari Buku</h4>
                            <p class="text-[#738A94]">Cari dan pilih buku yang ingin dipinjam, pastikan buku tersedia untuk dipinjam.</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-[#FF9400]/10 text-[#FF9400] flex items-center justify-center font-bold text-lg">3</div>
                        <div>
                            <h4 class="text-lg font-bold text-[#15171A] mb-1">Dapatkan Kode Booking</h4>
                            <p class="text-[#738A94]">Setujui syarat dan ketentuan untuk mendapatkan kode peminjaman <span class="bg-[#F0F2F3] text-[#15171A] font-mono px-2 py-1 rounded border border-[#DDE1E5] text-sm">XX-XXXXXX</span>.</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-[#FF9400]/10 text-[#FF9400] flex items-center justify-center font-bold text-lg">4</div>
                        <div>
                            <h4 class="text-lg font-bold text-[#15171A] mb-1">Ambil Buku & Kembalikan</h4>
                            <p class="text-[#738A94]">Berikan kode kepada pustakawan di perpustakaan. Jangan lupa kembalikan tepat waktu untuk menghindari denda.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="bg-[#15171A] py-20 px-4 sm:px-6 lg:px-8 text-center" data-aos="fade-up">
    <div class="max-w-3xl mx-auto">
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">Siap untuk Memulai Membaca?</h2>
        <p class="text-[#738A94] text-lg mb-10">Bergabung dengan pembaca lainnya dan temukan ribuan cerita serta ilmu pengetahuan baru di e-Katalog SMP Negeri 8 Padang.</p>
        <a href="{{ auth()->check() ? '/koleksi' : '/register' }}" class="inline-block bg-[#FF9400] hover:bg-[#E88200] text-white px-8 py-4 rounded-md font-bold text-lg transition-colors shadow-sm">
            {{ auth()->check() ? 'Jelajahi Koleksi Sekarang' : 'Daftar Sekarang' }}
        </a>
    </div>
</section>

@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Halaman ini bergantung pada aos.js untuk animasi
    });
</script>
@endsection
