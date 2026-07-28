@extends('layouts.main')

@section('main-content')
<div class="bg-[#F9FAFB] min-h-screen py-10 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        
        <!-- Breadcrumb -->
        <nav class="mb-8 flex text-[#738A94] text-sm font-medium" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="/" class="hover:text-[#FF9400] transition-colors">Beranda</a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mx-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                        <a href="/koleksi" class="hover:text-[#FF9400] transition-colors ml-1 md:ml-2">Koleksi Buku</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mx-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                        <span class="text-[#15171A] ml-1 md:ml-2 font-bold line-clamp-1">{{ $book->title }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Main Content Grid -->
        <div class="flex flex-col lg:flex-row gap-8">
            
            <!-- Left Column: Cover Image -->
            <div class="w-full lg:w-1/3 xl:w-1/4">
                <div class="bg-white p-4 rounded-xl shadow-sm border border-[#DDE1E5] top-8">
                    <div class="relative w-full aspect-[3/4] rounded-lg overflow-hidden bg-[#F0F2F3] shadow-inner">
                        @if ($book->cover)
                            <img class="w-full h-full object-cover" src="{{ asset('storage/' . $book->cover) }}" alt="{{ $book->title }}" onerror="this.onerror=null;this.src='{{ asset('img/bookCoverDefault.png') }}';">
                        @else
                            <img class="w-full h-full object-cover" src="{{ asset('img/bookCoverDefault.png') }}" alt="{{ $book->title }}">
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column: Details & Action -->
            <div class="w-full lg:w-2/3 xl:w-3/4 flex flex-col gap-6">
                
                <!-- Title and Actions -->
                <div class="bg-white rounded-xl shadow-sm border border-[#DDE1E5] p-6 lg:p-8">
                    <h1 class="text-3xl md:text-4xl font-bold text-[#15171A] mb-2 font-['Inter']">{{ $book->title }}</h1>
                    <p class="text-[#738A94] text-lg mb-6">{{ $book->author }}</p>
                    
                    <div class="pt-6 border-t border-[#DDE1E5] flex flex-wrap gap-4">
                        @if ($book->media_type === 'Buku Elektronik')
                            <a href="{{ filter_var($book->link, FILTER_VALIDATE_URL) ? $book->link : asset('storage/' . $book->link) }}" target="_blank" class="bg-[#FF9400] hover:bg-[#E88200] text-white px-8 py-3 rounded-md font-semibold transition-colors shadow-sm inline-flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                Lihat Buku
                            </a>
                        @else
                            @auth
                                <button type="button" onclick="openBorrowModal()" class="bg-[#FF9400] hover:bg-[#E88200] text-white px-8 py-3 rounded-md font-semibold transition-colors shadow-sm inline-flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    Pinjam Buku
                                </button>
                            @else
                                <a href="/login" class="bg-[#FF9400] hover:bg-[#E88200] text-white px-8 py-3 rounded-md font-semibold transition-colors shadow-sm inline-flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    Pinjam Buku
                                </a>
                            @endauth
                        @endif
                    </div>
                </div>

                <!-- Book Details -->
                <div class="bg-white rounded-xl shadow-sm border border-[#DDE1E5] overflow-hidden">
                    <div class="px-6 py-4 bg-[#F9FAFB] border-b border-[#DDE1E5]">
                        <h2 class="text-lg font-bold text-[#15171A]">Informasi Detail</h2>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4">
                            <!-- Kode -->
                            <div class="flex flex-col border-b border-[#F0F2F3] pb-3 sm:border-0 sm:pb-0">
                                <dt class="text-sm font-medium text-[#738A94]">Kode Buku</dt>
                                <dd class="mt-1 text-sm text-[#15171A] font-semibold">{{ $book->code }}</dd>
                            </div>
                            <!-- Kategori -->
                            <div class="flex flex-col border-b border-[#F0F2F3] pb-3 sm:border-0 sm:pb-0">
                                <dt class="text-sm font-medium text-[#738A94]">Kategori</dt>
                                <dd class="mt-1 text-sm text-[#15171A] font-semibold">{{ $book->category }}</dd>
                            </div>
                            <!-- Penerbit -->
                            <div class="flex flex-col border-b border-[#F0F2F3] pb-3 sm:border-0 sm:pb-0">
                                <dt class="text-sm font-medium text-[#738A94]">Penerbit</dt>
                                <dd class="mt-1 text-sm text-[#15171A] font-semibold">{{ $book->publisher }}</dd>
                            </div>
                            <!-- Tahun -->
                            <div class="flex flex-col border-b border-[#F0F2F3] pb-3 sm:border-0 sm:pb-0">
                                <dt class="text-sm font-medium text-[#738A94]">Tahun Terbit</dt>
                                <dd class="mt-1 text-sm text-[#15171A] font-semibold">{{ $book->year }}</dd>
                            </div>
                            <!-- Jumlah Halaman -->
                            <div class="flex flex-col border-b border-[#F0F2F3] pb-3 sm:border-0 sm:pb-0">
                                <dt class="text-sm font-medium text-[#738A94]">Jumlah Halaman</dt>
                                <dd class="mt-1 text-sm text-[#15171A] font-semibold">{{ $book->pages }}</dd>
                            </div>
                            <!-- Bahasa -->
                            <div class="flex flex-col border-b border-[#F0F2F3] pb-3 sm:border-0 sm:pb-0">
                                <dt class="text-sm font-medium text-[#738A94]">Bahasa</dt>
                                <dd class="mt-1 text-sm text-[#15171A] font-semibold">{{ $book->language }}</dd>
                            </div>
                            <!-- ISBN -->
                            <div class="flex flex-col border-b border-[#F0F2F3] pb-3 sm:border-0 sm:pb-0">
                                <dt class="text-sm font-medium text-[#738A94]">ISBN/ISSN</dt>
                                <dd class="mt-1 text-sm text-[#15171A] font-semibold">{{ $book->isbn_issn ?: '-' }}</dd>
                            </div>
                            <!-- Stok -->
                            <div class="flex flex-col border-b border-[#F0F2F3] pb-3 sm:border-0 sm:pb-0">
                                <dt class="text-sm font-medium text-[#738A94]">Stok</dt>
                                <dd class="mt-1 text-sm text-[#15171A] font-semibold">
                                    @if($book->stock == 0)
                                        <span class="text-green-600 bg-green-50 px-2 py-0.5 rounded-full text-xs border border-green-200">Buku Digital (Tersedia)</span>
                                    @else
                                        {{ $book->stock }}
                                    @endif
                                </dd>
                            </div>
                            <!-- Tipe Isi -->
                            <div class="flex flex-col border-b border-[#F0F2F3] pb-3 sm:border-0 sm:pb-0">
                                <dt class="text-sm font-medium text-[#738A94]">Tipe Isi</dt>
                                <dd class="mt-1 text-sm text-[#15171A] font-semibold">{{ $book->content_type }}</dd>
                            </div>
                            <!-- Tipe Media -->
                            <div class="flex flex-col border-b border-[#F0F2F3] pb-3 sm:border-0 sm:pb-0">
                                <dt class="text-sm font-medium text-[#738A94]">Tipe Media</dt>
                                <dd class="mt-1 text-sm text-[#15171A] font-semibold">{{ $book->media_type }}</dd>
                            </div>
                            <!-- Tipe Pembawa -->
                            <div class="flex flex-col border-b border-[#F0F2F3] pb-3 sm:border-0 sm:pb-0">
                                <dt class="text-sm font-medium text-[#738A94]">Tipe Pembawa</dt>
                                <dd class="mt-1 text-sm text-[#15171A] font-semibold">{{ $book->carrier_type }}</dd>
                            </div>
                            <!-- Edisi -->
                            <div class="flex flex-col">
                                <dt class="text-sm font-medium text-[#738A94]">Edisi</dt>
                                <dd class="mt-1 text-sm text-[#15171A] font-semibold">{{ $book->edition ?: '-' }}</dd>
                            </div>
                            <!-- Subjek -->
                            <div class="flex flex-col sm:col-span-2 pt-3 sm:pt-4 border-t border-[#F0F2F3]">
                                <dt class="text-sm font-medium text-[#738A94]">Subjek</dt>
                                <dd class="mt-1 text-sm text-[#15171A] font-semibold">{{ $book->subject }}</dd>
                            </div>
                            <!-- Deskripsi -->
                            <div class="flex flex-col sm:col-span-2 pt-3 sm:pt-4 border-t border-[#F0F2F3]">
                                <dt class="text-sm font-medium text-[#738A94]">Deskripsi</dt>
                                <dd class="mt-1 text-sm text-[#15171A] leading-relaxed">{{ $book->description ?: 'Belum ada deskripsi untuk buku ini.' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Peminjaman Kustom (Tailwind CSS) -->
<div id="borrowModal" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Background backdrop -->
    <div class="fixed inset-0 bg-[#15171A]/40 backdrop-blur-sm transition-opacity" onclick="closeBorrowModal()"></div>

    <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
        <!-- Modal panel -->
        <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-[#DDE1E5]">
            <!-- Close button -->
            <button type="button" onclick="closeBorrowModal()" class="absolute right-4 top-4 text-[#738A94] hover:text-[#15171A] transition-colors focus:outline-none">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            
            <form action="/booking" method="post">
                @csrf
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                            <h3 class="text-xl font-bold leading-6 text-[#15171A] mb-4 pr-6" id="modal-title">Pinjam Buku</h3>
                            
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label for="alasan" class="block text-sm font-medium text-[#15171A] text-left">Alasan Pinjam</label>
                                    <textarea id="alasan" name="alasan" rows="3" class="mt-1 block w-full rounded-md border border-[#DDE1E5] px-3 py-2 text-[#15171A] shadow-sm focus:border-[#FF9400] focus:outline-none focus:ring-1 focus:ring-[#FF9400]" required></textarea>
                                </div>
                                <div>
                                    <label for="tgl_kembali" class="block text-sm font-medium text-[#15171A] text-left">Tanggal Pengembalian</label>
                                    <input type="date" id="tgl_kembali" name="expired_at" class="mt-1 block w-full rounded-md border border-[#DDE1E5] px-3 py-2 text-[#15171A] shadow-sm focus:border-[#FF9400] focus:outline-none focus:ring-1 focus:ring-[#FF9400]" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-[#F9FAFB] px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-[#DDE1E5]">
                    <input type="hidden" name="book_id" value="{{ $book->id }}">
                    @auth
                        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                    @endauth
                    <input type="hidden" name="status" value="Diajukan">
                    <input type="hidden" name="is_denda" value="0">
                    
                    <button type="submit" class="inline-flex w-full justify-center rounded-md bg-[#FF9400] px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-[#E88200] sm:ml-3 sm:w-auto transition-colors">
                        Setuju Pinjam
                    </button>
                    <button type="button" onclick="closeBorrowModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-[#15171A] shadow-sm ring-1 ring-inset ring-[#DDE1E5] hover:bg-gray-50 sm:mt-0 sm:w-auto transition-colors">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openBorrowModal() {
        document.getElementById('borrowModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeBorrowModal() {
        document.getElementById('borrowModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
</script>
@endsection
