@extends('layouts.main')

@section('main-content')
<div class="bg-[#F9FAFB] min-h-screen py-16 flex flex-col justify-center sm:py-24">
    <div class="relative py-3 sm:max-w-xl sm:mx-auto w-full px-4 sm:px-0">
        <!-- Floating decorative shape (optional subtle touch) -->
        <div class="absolute inset-0 bg-gradient-to-r from-[#FF9400] to-[#E88200] shadow-lg transform -skew-y-6 sm:skew-y-0 sm:-rotate-6 sm:rounded-3xl opacity-20 hidden sm:block"></div>
        
        <div class="relative bg-white shadow-xl sm:rounded-3xl border border-[#DDE1E5] px-4 py-10 sm:p-16">
            <div class="max-w-md mx-auto">
                <div class="text-center mb-10">
                    <h1 class="text-3xl font-bold text-[#15171A] font-['Inter'] mb-2">Selamat Datang</h1>
                    <p class="text-[#738A94] text-sm">Masuk ke E-Katalog Perpustakaan SMP Negeri 8 Padang</p>
                </div>
                
                <div class="divide-y divide-[#F0F2F3]">
                    <div class="py-4 text-base leading-6 space-y-4 text-[#4A5568] sm:text-lg sm:leading-7">
                        <form action='/login' method="post" class="space-y-6">
                            @csrf
                            <div class="relative group">
                                <label for="username" class="block text-sm font-bold text-[#15171A] mb-2">Username</label>
                                <input type="text" name="username" id="username" class="w-full px-4 py-3 border border-[#DDE1E5] rounded-lg focus:ring-2 focus:ring-[#FF9400] focus:border-[#FF9400] outline-none transition-all text-[#15171A] bg-gray-50 focus:bg-white" placeholder="Masukkan username Anda" required>
                            </div>
                            
                            <div class="relative group">
                                <label for="password" class="block text-sm font-bold text-[#15171A] mb-2">Password</label>
                                <input type="password" name="password" id="password" class="w-full px-4 py-3 border border-[#DDE1E5] rounded-lg focus:ring-2 focus:ring-[#FF9400] focus:border-[#FF9400] outline-none transition-all text-[#15171A] bg-gray-50 focus:bg-white" placeholder="Masukkan password Anda" required>
                            </div>
                            
                            <div class="pt-4">
                                <button type="submit" class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-[#15171A] hover:bg-[#313539] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#15171A] transition-colors">
                                    Masuk ke Akun
                                    <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <div class="pt-6 text-center text-sm font-medium">
                        <p class="text-[#738A94]">
                            Belum memiliki akun? 
                            <a href="/register" class="text-[#FF9400] hover:text-[#E88200] font-bold transition-colors">Daftar sekarang</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- SweetAlert Triggers -->
@if (session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'Sukses',
                text: "{{ session('success') }}",
                confirmButtonColor: '#FF9400',
                confirmButtonText: 'OK'
            });
        });
    </script>
@endif

@if ($errors->has('login'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Login Gagal',
                text: "{{ $errors->first('login') }}",
                confirmButtonColor: '#15171A',
                confirmButtonText: 'Kembali'
            });
        });
    </script>
@endif
@endsection
