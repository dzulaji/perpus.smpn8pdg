<!doctype html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>E-Katalog Pepustakaan SMP Negeri 8 Padang</title>
    <link rel="icon" type="image/webp" href="{{ asset('img/logo.webp') }}">
    <link rel="apple-touch-icon" href="{{ asset('img/logo.webp') }}">

    {{-- Font - Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- AOS CSS --}}
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    {{-- Sweet Alert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Vite / Tailwind CSS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- General Style --}}
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }
    </style>

    {{-- Page Style --}}
    @yield('style')
</head>

<body class="bg-[#ffffff] text-[#15171A] antialiased min-h-screen flex flex-col selection:bg-[#FF9400] selection:text-white">
    
    <!-- Navbar -->
    <nav class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-gray-200 transition-all duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Penyesuaian tinggi (h-[60px] setara items-center padat seperti referensi) -->
            <div class="flex justify-between items-center h-[60px]">
                
                <!-- Logo -->
                <a href="/" class="flex-shrink-0 flex items-center gap-3 cursor-pointer">
                    <img src="{{ asset('img/logo.webp') }}" alt="Logo SMPN 8 Padang"
                        class="w-12 h-12 object-contain">
                    <div class="hidden sm:flex flex-col">
                        <span class="font-bold text-lg tracking-tight text-[#15171A] leading-none mb-1">Perpustakaan SMPN 8 Padang</span>
                        <span class="text-[10px] text-[#738A94] uppercase tracking-wider font-semibold">Smart & Good Attitude</span>
                    </div>
                </a>

                <!-- Desktop Menu -->
                <!-- Penyesuaian spacing (space-x-6) dan ukuran font (text-sm) -->
                <div class="hidden md:flex items-center space-x-6 h-full">
                    <a href="/" class="{{ Request::is('/') ? 'text-[#FF9400]' : 'text-[#738A94] hover:text-[#15171A]' }} font-medium transition-colors duration-200 text-sm">Beranda</a>
                    <a href="/books" class="{{ Request::is('books*') ? 'text-[#FF9400]' : 'text-[#738A94] hover:text-[#15171A]' }} font-medium transition-colors duration-200 text-sm">Koleksi</a>
                    @auth
                        <a href="/booking" class="{{ Request::is('booking*') ? 'text-[#FF9400]' : 'text-[#738A94] hover:text-[#15171A]' }} font-medium transition-colors duration-200 text-sm">Peminjaman</a>
                        <a href="/rekomendasi" class="{{ Request::is('rekomendasi*') ? 'text-[#FF9400]' : 'text-[#738A94] hover:text-[#15171A]' }} font-medium transition-colors duration-200 text-sm">Rekomendasi</a>
                    @endauth

                    <!-- Auth / Profile -->
                    @auth
                        <div class="relative ml-2 h-full flex items-center">
                            <button id="user-menu-button" type="button" class="flex items-center gap-1 text-sm font-medium text-[#738A94] hover:text-[#15171A] transition-colors duration-200 py-4 focus:outline-none">
                                {{ auth()->user()->name }}
                                <svg class="h-4 w-4 transition-transform duration-200" id="user-menu-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>

                            <div id="user-menu" class="hidden absolute top-full right-0 -mt-2 w-48 bg-white border border-gray-100 shadow-lg rounded-b-lg py-1 transition-all duration-200 z-50">
                                <a href="/profile" class="block px-4 py-3 text-sm text-[#15171A] hover:bg-gray-50 hover:text-[#FF9400] border-b border-gray-100/50">Profil</a>
                                <form action="/logout" method="post" class="block w-full">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-3 text-sm text-[#15171A] hover:bg-gray-50 hover:text-[#FF9400]">Logout</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <!-- Penyesuaian ukuran tombol (px-5 py-2.5 text-sm) -->
                        <div class="flex items-center ml-2">
                            <a href="/login" class="bg-[#FF9400] hover:bg-[#E88200] text-white px-5 py-2.5 rounded-md font-medium text-sm transition-colors duration-200 shadow-sm flex items-center gap-2">
                                Login
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="h-4 w-4" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M6 3.5a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-2a.5.5 0 0 0-1 0v2A1.5 1.5 0 0 0 6.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-8A1.5 1.5 0 0 0 5 3.5v2a.5.5 0 0 0 1 0v-2z"/>
                                    <path fill-rule="evenodd" d="M11.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H1.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/>
                                </svg>
                            </a>
                        </div>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden flex items-center">
                    <button id="mobile-menu-btn" class="text-[#738A94] hover:text-[#15171A] focus:outline-none p-2 rounded-md hover:bg-gray-50 transition-colors">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path id="menu-icon-bars" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path id="menu-icon-close" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Panel (Mengambang seperti gaya referensi) -->
        <div id="mobile-menu" class="hidden md:hidden border-t border-gray-200 bg-white absolute w-full left-0 top-[60px] shadow-lg pb-4 max-h-[80vh] overflow-y-auto z-40">
            <div class="px-4 pt-4 pb-3 space-y-1">
                <a href="/" class="block px-3 py-2 rounded-md text-base font-medium {{ Request::is('/') ? 'text-[#FF9400] bg-orange-50' : 'text-[#15171A] hover:text-[#FF9400] hover:bg-gray-50' }}">Beranda</a>
                <a href="/books" class="block px-3 py-2 rounded-md text-base font-medium {{ Request::is('books*') ? 'text-[#FF9400] bg-orange-50' : 'text-[#15171A] hover:text-[#FF9400] hover:bg-gray-50' }}">Koleksi</a>
                @auth
                    <a href="/booking" class="block px-3 py-2 rounded-md text-base font-medium {{ Request::is('booking*') ? 'text-[#FF9400] bg-orange-50' : 'text-[#15171A] hover:text-[#FF9400] hover:bg-gray-50' }}">Peminjaman</a>
                    <a href="/rekomendasi" class="block px-3 py-2 rounded-md text-base font-medium {{ Request::is('rekomendasi*') ? 'text-[#FF9400] bg-orange-50' : 'text-[#15171A] hover:text-[#FF9400] hover:bg-gray-50' }}">Rekomendasi</a>
                @endauth
            </div>

            @auth
                <div class="pt-4 pb-3 border-t border-gray-100">
                    <div class="px-7">
                        <div class="text-base font-medium text-[#15171A] mb-1">{{ auth()->user()->name }}</div>
                        <div class="text-xs text-[#738A94]">Menu Pengguna</div>
                    </div>
                    <div class="mt-3 px-4 space-y-1">
                        <a href="/profile" class="block px-3 py-2 rounded-md text-base font-medium text-[#15171A] hover:text-[#FF9400] hover:bg-gray-50">Profil</a>
                        <form action="/logout" method="post" class="block w-full">
                            @csrf
                            <button type="submit" class="w-full text-left px-3 py-2 rounded-md text-base font-medium text-[#15171A] hover:text-[#FF9400] hover:bg-gray-50">Logout</button>
                        </form>
                    </div>
                </div>
            @else
                <div class="pt-4 pb-3 border-t border-gray-100 px-4">
                    <a href="/login" class="block w-full text-center bg-[#FF9400] hover:bg-[#E88200] text-white px-5 py-2.5 rounded-md font-medium text-sm transition-colors shadow-sm">
                        Login
                    </a>
                </div>
            @endauth
        </div>
    </nav>

    <!-- Konten Utama -->
    <main class="flex-grow">
        @yield('main-content')
    </main>
    
    <!-- Footer -->
    <footer class="bg-[#15171A] text-white pt-16 pb-8 border-t-[4px] border-[#FF9400]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-12">
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center gap-3 mb-4">
                        <img src="{{ asset('img/logo.webp') }}" alt="Logo SMPN 8 Padang"
                            class="w-10 h-10 object-contain">
                        <span class="font-bold text-xl tracking-tight text-white">SMPN 8 Padang</span>
                    </div>
                    <p class="text-[#738A94] text-sm mb-6 leading-relaxed max-w-sm">
                        Smart and Good Attitude. Mewujudkan generasi berkarakter, berprestasi, dan berdaya saing global
                        sejak tahun 1977.
                    </p>
                    <div class="flex space-x-4">
                        <!-- Social Icons -->
                        <a href="https://youtube.com/@SPENDELTV" target="_blank"
                            class="text-[#738A94] hover:text-[#FF9400] transition-colors">
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
                            </svg>
                        </a>
                        <a href="https://instagram.com/kaba_spendel/" target="_blank"
                            class="text-[#738A94] hover:text-[#FF9400] transition-colors">
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z" />
                            </svg>
                        </a>
                        <a href="https://facebook.com/smpnegeri8padang" target="_blank"
                            class="text-[#738A94] hover:text-[#FF9400] transition-colors">
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.325-1.325z" />
                            </svg>
                        </a>
                    </div>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-4">Kontak Kami</h4>
                    <ul class="space-y-3 text-sm text-[#738A94]">
                        <li class="flex items-start gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 text-[#FF9400]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>Jl. Dr. Sutomo, Padang Timur, Padang, Sumbar</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 text-[#FF9400] mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <span class="flex flex-col">
                                <span>(0751) 31764</span>
                                <span>(0751) 811708</span>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="pt-8 border-t border-gray-800 text-sm text-[#738A94] text-center">
                <p>&copy; {{ date('Y') }} SMP Negeri 8 Padang. Dikembangkan oleh <a href="https://instagram.com/dzulaji" target="_blank" rel="noopener noreferrer" class="text-[#FF9400] hover:underline font-medium">Dzul Fauzi, S. Kom</a>.</p>
            </div>
        </div>
    </footer>

    {{-- animejs --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.2/anime.min.js"
        integrity="sha512-aNMyYYxdIxIaot0Y1/PLuEu3eipGCmsEUBrUq+7aVyPGMFH8z0eTP0tkqAvv34fzN6z+201d3T8HPb1svWSKHQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    {{-- aos --}}
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
    
    <!-- JavaScript for Menu and Dropdown -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile menu toggle
            const mobileBtn = document.getElementById('mobile-menu-btn');
            const mobileMenu = document.getElementById('mobile-menu');
            const iconBars = document.getElementById('menu-icon-bars');
            const iconClose = document.getElementById('menu-icon-close');
            
            if (mobileBtn && mobileMenu) {
                mobileBtn.addEventListener('click', () => {
                    mobileMenu.classList.toggle('hidden');
                    if (iconBars && iconClose) {
                        iconBars.classList.toggle('hidden');
                        iconClose.classList.toggle('hidden');
                    }
                });
            }

            // Profile dropdown toggle (Desktop)
            const userBtn = document.getElementById('user-menu-button');
            const userMenu = document.getElementById('user-menu');
            const userIcon = document.getElementById('user-menu-icon');
            
            if (userBtn && userMenu) {
                userBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    userMenu.classList.toggle('hidden');
                    if(userIcon) userIcon.classList.toggle('rotate-180');
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', (e) => {
                    if (!userMenu.contains(e.target) && !userBtn.contains(e.target)) {
                        userMenu.classList.add('hidden');
                        if(userIcon) userIcon.classList.remove('rotate-180');
                    }
                });
            }
        });
    </script>

    {{-- Page Script --}}
    @yield('script')
</body>
</html>