@extends('layouts.main')

@section('main-content')
<div class="bg-[#F9FAFB] min-h-screen py-10 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-[#15171A] mb-2 font-['Inter']">Riwayat Peminjaman</h1>
            <p class="text-[#738A94] text-lg">Daftar buku yang pernah Anda pinjam atau sedang diajukan.</p>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-xl shadow-sm border border-[#DDE1E5] overflow-hidden">
            @if ($bookings->isEmpty())
                <div class="text-center py-20 px-4">
                    <div class="w-16 h-16 bg-[#F0F2F3] text-[#738A94] rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#15171A] mb-2">Belum Ada Riwayat</h3>
                    <p class="text-[#738A94]">Anda belum pernah meminjam buku. Silakan jelajahi koleksi kami!</p>
                    <a href="/koleksi" class="mt-6 inline-flex items-center px-6 py-3 bg-[#FF9400] hover:bg-[#E88200] text-white font-semibold rounded-md transition-colors shadow-sm">
                        Jelajahi Koleksi
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left whitespace-nowrap border-collapse">
                        <thead>
                            <tr class="bg-[#F0F2F3] border-b border-[#DDE1E5] text-[#15171A] text-sm uppercase tracking-wider font-bold">
                                <th scope="col" class="px-6 py-4 w-16">#</th>
                                <th scope="col" class="px-6 py-4">Kode Buku</th>
                                <th scope="col" class="px-6 py-4 w-full">Judul Buku</th>
                                <th scope="col" class="px-6 py-4">Status</th>
                                <th scope="col" class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#DDE1E5]">
                            @foreach ($bookings as $booking)
                                <tr class="hover:bg-[#F9FAFB] transition-colors">
                                    <td class="px-6 py-4 text-sm text-[#738A94]">{{ $loop->iteration }}</td>
                                    <td class="px-6 py-4 text-sm font-medium text-[#15171A]">{{ $booking->code }}</td>
                                    <td class="px-6 py-4 text-sm text-[#15171A]">
                                        <p class="truncate max-w-[200px] md:max-w-md" title="{{ $booking->book->title }}">
                                            {{ $booking->book->title }}
                                        </p>
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        @if ($booking->status == 'Dikembalikan')
                                            @if ($booking->expired_at < now())
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                                    Dikembalikan Terlambat
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                                    Dikembalikan
                                                </span>
                                            @endif
                                        @elseif($booking->expired_at < now())
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
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="/booking/{{ $booking->id }}" class="inline-flex items-center justify-center w-8 h-8 rounded-md bg-[#F0F2F3] text-[#738A94] hover:bg-[#FF9400] hover:text-white transition-colors" title="Lihat Detail">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
