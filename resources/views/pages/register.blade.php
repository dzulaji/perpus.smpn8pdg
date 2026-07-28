@extends('layouts.main')

@section('main-content')
<div class="bg-[#F9FAFB] min-h-screen py-16 flex flex-col justify-center sm:py-24">
    <div class="relative py-3 sm:max-w-2xl sm:mx-auto w-full px-4 sm:px-0">
        <!-- Floating decorative shape -->
        <div class="absolute inset-0 bg-gradient-to-r from-[#FF9400] to-[#E88200] shadow-lg transform -skew-y-6 sm:skew-y-0 sm:-rotate-6 sm:rounded-3xl opacity-20 hidden sm:block"></div>
        
        <div class="relative bg-white shadow-xl sm:rounded-3xl border border-[#DDE1E5] px-4 py-10 sm:p-16">
            <div class="max-w-xl mx-auto">
                <div class="text-center mb-10">
                    <h1 class="text-3xl font-bold text-[#15171A] font-['Inter'] mb-2">Registrasi Akun</h1>
                    <p class="text-[#738A94] text-sm">Bergabung dengan E-Katalog Perpustakaan SMP Negeri 8 Padang</p>
                </div>
                
                <div class="divide-y divide-[#F0F2F3]">
                    <div class="py-4 text-base leading-6 space-y-4 text-[#4A5568] sm:text-lg sm:leading-7">
                        <form action='/register' method="post" enctype="multipart/form-data" class="space-y-6">
                            @csrf
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div class="relative group">
                                    <label for="name" class="block text-sm font-bold text-[#15171A] mb-2">Nama Lengkap</label>
                                    <input type="text" name="name" id="name" class="w-full px-4 py-3 border @error('name') border-red-500 @else border-[#DDE1E5] @enderror rounded-lg focus:ring-2 focus:ring-[#FF9400] focus:border-[#FF9400] outline-none transition-all text-[#15171A] bg-gray-50 focus:bg-white" placeholder="Nama Anda" value="{{ old('name') }}" required>
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div class="relative group">
                                    <label for="username" class="block text-sm font-bold text-[#15171A] mb-2">Username</label>
                                    <input type="text" name="username" id="username" class="w-full px-4 py-3 border @error('username') border-red-500 @else border-[#DDE1E5] @enderror rounded-lg focus:ring-2 focus:ring-[#FF9400] focus:border-[#FF9400] outline-none transition-all text-[#15171A] bg-gray-50 focus:bg-white" placeholder="username_anda" value="{{ old('username') }}" required>
                                    @error('username')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div class="relative group">
                                    <label for="nis_nip" class="block text-sm font-bold text-[#15171A] mb-2">NIS / NIP</label>
                                    <input type="number" name="nis_nip" id="nis_nip" class="w-full px-4 py-3 border @error('nis_nip') border-red-500 @else border-[#DDE1E5] @enderror rounded-lg focus:ring-2 focus:ring-[#FF9400] focus:border-[#FF9400] outline-none transition-all text-[#15171A] bg-gray-50 focus:bg-white" placeholder="Nomor Induk" value="{{ old('nis_nip') }}" required>
                                    @error('nis_nip')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div class="relative group">
                                    <label for="email" class="block text-sm font-bold text-[#15171A] mb-2">Email Address</label>
                                    <input type="email" name="email" id="email" class="w-full px-4 py-3 border @error('email') border-red-500 @else border-[#DDE1E5] @enderror rounded-lg focus:ring-2 focus:ring-[#FF9400] focus:border-[#FF9400] outline-none transition-all text-[#15171A] bg-gray-50 focus:bg-white" placeholder="email@example.com" value="{{ old('email') }}" required>
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="relative group">
                                <label for="password" class="block text-sm font-bold text-[#15171A] mb-2">Password</label>
                                <input type="password" name="password" id="password" class="w-full px-4 py-3 border @error('password') border-red-500 @else border-[#DDE1E5] @enderror rounded-lg focus:ring-2 focus:ring-[#FF9400] focus:border-[#FF9400] outline-none transition-all text-[#15171A] bg-gray-50 focus:bg-white" placeholder="Minimal 5 karakter" required>
                                @error('password')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="relative group">
                                <label for="photo" class="block text-sm font-bold text-[#15171A] mb-2">Foto Profil <span class="text-[#738A94] font-normal text-xs">(Opsional)</span></label>
                                <input type="file" name="photo" id="photo" class="w-full px-4 py-3 border @error('photo') border-red-500 @else border-[#DDE1E5] @enderror rounded-lg focus:ring-2 focus:ring-[#FF9400] focus:border-[#FF9400] outline-none transition-all text-[#738A94] bg-gray-50 focus:bg-white file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100">
                                @error('photo')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="pt-4">
                                <button type="submit" class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-[#15171A] hover:bg-[#313539] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#15171A] transition-colors">
                                    Daftar Sekarang
                                    <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <div class="pt-6 text-center text-sm font-medium">
                        <p class="text-[#738A94]">
                            Sudah terdaftar? 
                            <a href="/login" class="text-[#FF9400] hover:text-[#E88200] font-bold transition-colors">Masuk disini</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
