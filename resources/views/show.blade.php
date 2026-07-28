@extends('layouts.main')

@section('main-content')
<div class="bg-[#F9FAFB] min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Breadcrumb -->
        <nav class="flex text-sm text-[#738A94] mb-8 font-medium" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="/" class="hover:text-[#FF9400] transition-colors flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
                        Beranda
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-[#DDE1E5]" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                        <span class="ml-1 md:ml-2 text-[#15171A]">Profil: {{ $user->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Profile Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-[#DDE1E5] overflow-hidden">
            <div class="flex flex-col md:flex-row">
                <!-- Cover Image -->
                <div class="w-full md:w-1/3 bg-[#F0F2F3] p-8 flex flex-col justify-center items-center border-b md:border-b-0 md:border-r border-[#DDE1E5]">
                    <div class="w-48 h-48 rounded-full overflow-hidden border-4 border-white shadow-lg bg-white mb-6">
                        @if ($user->photo)
                            <img class="w-full h-full object-cover" src="{{ asset('storage/' . $user->photo) }}" alt="{{ $user->name }}">
                        @else
                            <img class="w-full h-full object-cover p-4" src="{{ asset('template/img/undraw_profile.svg') }}" alt="Default Photo">
                        @endif
                    </div>
                    <div class="text-center">
                        <h2 class="text-2xl font-bold text-[#15171A] mb-1">{{ $user->name }}</h2>
                        <span class="inline-block px-3 py-1 bg-gray-100 text-[#738A94] text-xs font-bold rounded-full tracking-wider uppercase">User</span>
                    </div>
                </div>

                <!-- Information -->
                <div class="w-full md:w-2/3 p-8 flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-center mb-6 pb-4 border-b border-[#F0F2F3]">
                            <h3 class="text-xl font-bold text-[#15171A]">Detail Informasi</h3>
                        </div>
                        
                        <div class="space-y-6">
                            <div class="flex flex-col sm:flex-row sm:items-center">
                                <div class="w-full sm:w-1/3 text-sm font-bold text-[#738A94] uppercase tracking-wider mb-1 sm:mb-0">Username</div>
                                <div class="w-full sm:w-2/3 text-[#15171A] font-medium text-lg">{{ $user->username }}</div>
                            </div>
                            <div class="flex flex-col sm:flex-row sm:items-center">
                                <div class="w-full sm:w-1/3 text-sm font-bold text-[#738A94] uppercase tracking-wider mb-1 sm:mb-0">NIS/NIP</div>
                                <div class="w-full sm:w-2/3 text-[#15171A] font-medium text-lg">{{ $user->nis_nip }}</div>
                            </div>
                            <div class="flex flex-col sm:flex-row sm:items-center">
                                <div class="w-full sm:w-1/3 text-sm font-bold text-[#738A94] uppercase tracking-wider mb-1 sm:mb-0">Email</div>
                                <div class="w-full sm:w-2/3 text-[#15171A] font-medium text-lg">{{ $user->email }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <div class="mt-10 pt-6 border-t border-[#F0F2F3] flex justify-end">
                        <button onclick="document.getElementById('editModal').classList.remove('hidden')" class="inline-flex items-center px-6 py-3 bg-[#FF9400] hover:bg-[#E88200] text-white font-bold rounded-lg transition-colors shadow-sm group">
                            <svg class="mr-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            Ubah Profil
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Profil -->
<div id="editModal" class="fixed inset-0 z-50 hidden bg-[#15171A]/60 backdrop-blur-sm overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <!-- Modal Panel -->
        <div class="relative bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-2xl w-full border border-[#DDE1E5]">
            <!-- Header -->
            <div class="bg-gray-50 px-6 py-4 border-b border-[#DDE1E5] flex justify-between items-center">
                <h3 class="text-xl font-bold text-[#15171A]" id="modal-title">Ubah Profil</h3>
                <button onclick="document.getElementById('editModal').classList.add('hidden')" type="button" class="text-[#738A94] hover:text-[#FF9400] transition-colors focus:outline-none">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <!-- Form -->
            <form action="{{ route('profile.update', $user->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="px-6 py-6 sm:p-8 space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-bold text-[#15171A] mb-1">Nama <span class="text-[#738A94] font-normal text-xs">(minimal 3 karakter)</span></label>
                        <input type="text" id="name" name="name" value="{{ $user->name }}" class="w-full px-4 py-2 border border-[#DDE1E5] rounded-lg focus:ring-2 focus:ring-[#FF9400] focus:border-[#FF9400] outline-none transition-all text-[#15171A]">
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="username" class="block text-sm font-bold text-[#15171A] mb-1">Username</label>
                            <input type="text" id="username" name="username" value="{{ $user->username }}" readonly class="w-full px-4 py-2 bg-gray-100 border border-[#DDE1E5] rounded-lg text-[#738A94] cursor-not-allowed">
                        </div>
                        <div>
                            <label for="nis_nip" class="block text-sm font-bold text-[#15171A] mb-1">NIS/NIP</label>
                            <input type="text" id="nis_nip" name="nis_nip" value="{{ $user->nis_nip }}" readonly class="w-full px-4 py-2 bg-gray-100 border border-[#DDE1E5] rounded-lg text-[#738A94] cursor-not-allowed">
                        </div>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-bold text-[#15171A] mb-1">Email</label>
                        <input type="email" id="email" name="email" value="{{ $user->email }}" readonly class="w-full px-4 py-2 bg-gray-100 border border-[#DDE1E5] rounded-lg text-[#738A94] cursor-not-allowed">
                    </div>

                    <hr class="border-[#F0F2F3]">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="old_password" class="block text-sm font-bold text-[#15171A] mb-1">Password Lama</label>
                            <input type="password" id="old_password" name="old_password" placeholder="Kosongkan jika tidak diubah" class="w-full px-4 py-2 border border-[#DDE1E5] rounded-lg focus:ring-2 focus:ring-[#FF9400] focus:border-[#FF9400] outline-none transition-all text-[#15171A]">
                        </div>
                        <div>
                            <label for="new_password" class="block text-sm font-bold text-[#15171A] mb-1">Password Baru <span class="text-[#738A94] font-normal text-xs">(minimal 5 char)</span></label>
                            <input type="password" id="new_password" name="new_password" class="w-full px-4 py-2 border border-[#DDE1E5] rounded-lg focus:ring-2 focus:ring-[#FF9400] focus:border-[#FF9400] outline-none transition-all text-[#15171A]">
                        </div>
                    </div>

                    <div>
                        <label for="photo" class="block text-sm font-bold text-[#15171A] mb-1">Foto Pengguna Baru</label>
                        <input class="w-full px-4 py-2 border border-[#DDE1E5] rounded-lg focus:ring-2 focus:ring-[#FF9400] focus:border-[#FF9400] outline-none transition-all text-[#738A94] file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100" type="file" id="photo" name="photo">
                    </div>
                </div>
                
                <!-- Footer -->
                <div class="bg-gray-50 px-6 py-4 border-t border-[#DDE1E5] flex justify-end gap-3 sm:px-8">
                    <button onclick="document.getElementById('editModal').classList.add('hidden')" type="button" class="px-5 py-2.5 bg-white border border-[#DDE1E5] text-[#15171A] font-semibold rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-[#FF9400] hover:bg-[#E88200] text-white font-bold rounded-lg transition-colors shadow-sm">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Check if there are any success or error messages
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Sukses!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#FF9400',
                confirmButtonText: 'Tutup'
            });
        @endif

        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ $errors->first() }}',
                confirmButtonColor: '#15171A',
                confirmButtonText: 'Kembali'
            });
        @endif
        
        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            var modal = document.getElementById('editModal');
            if (event.target == modal) {
                modal.classList.add('hidden');
            }
        });
    </script>
@endsection
