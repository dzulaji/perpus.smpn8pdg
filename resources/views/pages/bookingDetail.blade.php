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
                        <a href="/booking" class="hover:text-[#FF9400] transition-colors ml-1 md:ml-2">Riwayat Peminjaman</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mx-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                        <span class="text-[#15171A] ml-1 md:ml-2 font-bold line-clamp-1">Peminjaman {{ $booking->code }}</span>
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
                        @if ($booking->book->cover)
                            <img class="w-full h-full object-cover" src="{{ asset('storage/' . $booking->book->cover) }}" alt="Cover Buku" onerror="this.onerror=null;this.src='{{ asset('img/bookCoverDefault.png') }}';">
                        @else
                            <img class="w-full h-full object-cover" src="{{ asset('img/bookCoverDefault.png') }}" alt="Cover Buku">
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column: Details -->
            <div class="w-full lg:w-2/3 xl:w-3/4 flex flex-col gap-6">
                
                <!-- Booking Details Card -->
                <div class="bg-white rounded-xl shadow-sm border border-[#DDE1E5] overflow-hidden">
                    <div class="px-6 py-4 bg-[#F9FAFB] border-b border-[#DDE1E5]">
                        <h2 class="text-lg font-bold text-[#15171A]">Detail Peminjaman</h2>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4">
                            <!-- Kode Peminjaman -->
                            <div class="flex flex-col border-b border-[#F0F2F3] pb-3 sm:border-0 sm:pb-0">
                                <dt class="text-sm font-medium text-[#738A94]">Kode Peminjaman</dt>
                                <dd class="mt-1 text-sm text-[#15171A] font-semibold">{{ $booking->code }}</dd>
                            </div>
                            
                            <!-- Status -->
                            <div class="flex flex-col border-b border-[#F0F2F3] pb-3 sm:border-0 sm:pb-0">
                                <dt class="text-sm font-medium text-[#738A94]">Status</dt>
                                <dd class="mt-1 text-sm font-semibold">
                                    @if ($booking->status == 'Dikembalikan')
                                        @if (isset($booking->expired_at) && \Carbon\Carbon::parse($booking->updated_at)->gt($booking->expired_at))
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                                Dikembalikan Terlambat
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                                {{ $booking->status }}
                                            </span>
                                        @endif
                                    @elseif(isset($booking->expired_at) && $booking->expired_at < now()->startOfDay() && $booking->status != 'Dikembalikan')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                            Terlambat
                                        </span>
                                    @else
                                        @php
                                            $statusClass = 'bg-gray-100 text-gray-800 border-gray-200';
                                            if ($booking->status == 'Diajukan') {
                                                $statusClass = 'bg-amber-100 text-amber-800 border-amber-200';
                                            } elseif ($booking->status == 'Disetujui') {
                                                $statusClass = 'bg-green-100 text-green-800 border-green-200';
                                            } elseif ($booking->status == 'Ditolak') {
                                                $statusClass = 'bg-slate-800 text-slate-100 border-slate-900';
                                            }
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium border {{ $statusClass }}">
                                            {{ $booking->status }}
                                        </span>
                                    @endif
                                </dd>
                            </div>
                            
                            <!-- Waktu Pinjam -->
                            <div class="flex flex-col border-b border-[#F0F2F3] pb-3 sm:border-0 sm:pb-0">
                                <dt class="text-sm font-medium text-[#738A94]">Waktu Pinjam</dt>
                                <dd class="mt-1 text-sm text-[#15171A] font-semibold">{{ \Carbon\Carbon::parse($booking->created_at)->translatedFormat('d F Y') }}</dd>
                            </div>
                            
                            <!-- Tenggat Kembali -->
                            <div class="flex flex-col border-b border-[#F0F2F3] pb-3 sm:border-0 sm:pb-0">
                                <dt class="text-sm font-medium text-[#738A94]">Tenggat Kembali</dt>
                                <dd class="mt-1 text-sm text-[#15171A] font-semibold">
                                    {{ $booking->expired_at ? \Carbon\Carbon::parse($booking->expired_at)->translatedFormat('d F Y') : '-' }}
                                </dd>
                            </div>

                            <!-- Alasan Pinjam -->
                            <div class="flex flex-col sm:col-span-2 pt-3 sm:pt-4 border-t border-[#F0F2F3]">
                                <dt class="text-sm font-medium text-[#738A94]">Alasan Pinjam</dt>
                                <dd class="mt-1 text-sm text-[#15171A] leading-relaxed">{{ $booking->alasan ?: '-' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Book Information Card -->
                <div class="bg-white rounded-xl shadow-sm border border-[#DDE1E5] overflow-hidden">
                    <div class="px-6 py-4 bg-[#F9FAFB] border-b border-[#DDE1E5]">
                        <h2 class="text-lg font-bold text-[#15171A]">Informasi Buku</h2>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4">
                            <!-- Judul Buku -->
                            <div class="flex flex-col border-b border-[#F0F2F3] pb-3 sm:col-span-2 sm:border-0 sm:pb-0">
                                <dt class="text-sm font-medium text-[#738A94]">Judul Buku</dt>
                                <dd class="mt-1 text-sm text-[#15171A] font-bold">{{ $booking->book->title }}</dd>
                            </div>
                            
                            <!-- Penulis -->
                            <div class="flex flex-col border-b border-[#F0F2F3] pb-3 sm:border-0 sm:pb-0">
                                <dt class="text-sm font-medium text-[#738A94]">Penulis</dt>
                                <dd class="mt-1 text-sm text-[#15171A] font-semibold">{{ $booking->book->author }}</dd>
                            </div>
                            
                            <!-- Penerbit -->
                            <div class="flex flex-col border-b border-[#F0F2F3] pb-3 sm:border-0 sm:pb-0">
                                <dt class="text-sm font-medium text-[#738A94]">Penerbit</dt>
                                <dd class="mt-1 text-sm text-[#15171A] font-semibold">{{ $booking->book->publisher }}</dd>
                            </div>
                            
                            <!-- Stok Buku -->
                            <div class="flex flex-col">
                                <dt class="text-sm font-medium text-[#738A94]">Stok Buku Saat Ini</dt>
                                <dd class="mt-1 text-sm text-[#15171A] font-semibold">
                                    {{ $booking->book->stock == 0 ? 'Buku Digital (Tersedia)' : $booking->book->stock }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
