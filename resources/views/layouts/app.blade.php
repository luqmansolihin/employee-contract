<!DOCTYPE html>
<html lang="id" class="h-full bg-[#F1F5F9]">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - SI-KONTRAK TailAdmin</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-full flex text-slate-800 antialiased selection:bg-[#3C50E0] selection:text-white">

    <!-- Mobile Sidebar Backdrop Overlay -->
    <div id="sidebar-backdrop" onclick="toggleSidebar()"
        class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-xs hidden lg:hidden transition-opacity"></div>

    <!-- TailAdmin Dark Sidebar (#1C2434) -->
    <aside id="sidebar"
        class="fixed inset-y-0 left-0 z-50 w-72 bg-[#1C2434] text-[#DEE4EE] flex flex-col transition-transform duration-300 -translate-x-full lg:translate-x-0 shrink-0 border-r border-[#2E3A47]">
        <!-- Brand Header -->
        <div class="flex items-center justify-between h-20 px-6 border-b border-[#2E3A47]">
            <a href="{{ route('employees.index') }}" class="flex items-center gap-3 group">
                <div
                    class="w-10 h-10 rounded-xl bg-[#3C50E0] text-white flex items-center justify-center shadow-lg shadow-[#3C50E0]/30 group-hover:bg-[#2F40BD] transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                </div>
                <div>
                    <span class="text-lg font-extrabold tracking-tight text-white flex items-center gap-1.5">
                        SI-KONTRAK
                    </span>
                    <span class="text-[11px] font-semibold text-[#8A99AD] block">TailAdmin HR Suite</span>
                </div>
            </a>

            <!-- Close button on mobile -->
            <button type="button" onclick="toggleSidebar()"
                class="lg:hidden text-[#8A99AD] hover:text-white p-1 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>

        <!-- Sidebar Navigation Menu -->
        <div class="grow overflow-y-auto px-4 py-6 space-y-6">
            <!-- Section: Menu Utama -->
            <div>
                <p class="px-3 text-xs font-bold uppercase tracking-wider text-[#8A99AD] mb-3">
                    Menu Utama
                </p>
                <nav class="space-y-1">
                    <a href="{{ route('employees.index') }}"
                        class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('employees.index') && !request('status') ? 'bg-[#333A48] text-white font-semibold' : 'text-[#8A99AD] hover:bg-[#333A48]/60 hover:text-white' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                        <span>Daftar Karyawan</span>
                    </a>

                    <a href="{{ route('employees.create') }}"
                        class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('employees.create') ? 'bg-[#333A48] text-white font-semibold' : 'text-[#8A99AD] hover:bg-[#333A48]/60 hover:text-white' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z">
                            </path>
                        </svg>
                        <span>Tambah Karyawan Baru</span>
                    </a>
                </nav>
            </div>

            <!-- Section: Filter Status Kontrak -->
            <div>
                <p class="px-3 text-xs font-bold uppercase tracking-wider text-[#8A99AD] mb-3">
                    Filter Status Kontrak
                </p>
                <nav class="space-y-1">
                    <!-- Semua -->
                    <a href="{{ route('employees.index') }}"
                        class="flex items-center justify-between px-3.5 py-2 rounded-xl text-xs font-medium transition {{ !request('status') ? 'bg-[#333A48] text-white' : 'text-[#8A99AD] hover:bg-[#333A48]/50 hover:text-white' }}">
                        <span class="flex items-center gap-2.5">
                            <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                            <span>Semua Kontrak</span>
                        </span>
                    </a>

                    <!-- Aktif -->
                    <a href="{{ route('employees.index', ['status' => 'active']) }}"
                        class="flex items-center justify-between px-3.5 py-2 rounded-xl text-xs font-medium transition {{ request('status') === 'active' ? 'bg-[#333A48] text-white' : 'text-[#8A99AD] hover:bg-[#333A48]/50 hover:text-white' }}">
                        <span class="flex items-center gap-2.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                            <span>Kontrak Aktif</span>
                        </span>
                        <span class="text-[10px] text-emerald-400 font-semibold">&gt; 30 Hari</span>
                    </a>

                    <!-- Segera Habis -->
                    <a href="{{ route('employees.index', ['status' => 'expiring_soon']) }}"
                        class="flex items-center justify-between px-3.5 py-2 rounded-xl text-xs font-medium transition {{ request('status') === 'expiring_soon' ? 'bg-[#333A48] text-white' : 'text-[#8A99AD] hover:bg-[#333A48]/50 hover:text-white' }}">
                        <span class="flex items-center gap-2.5">
                            <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                            <span>Segera Habis</span>
                        </span>
                        <span class="text-[10px] text-amber-400 font-semibold">&le; 30 Hari</span>
                    </a>

                    <!-- Habis Kontrak -->
                    <a href="{{ route('employees.index', ['status' => 'expired']) }}"
                        class="flex items-center justify-between px-3.5 py-2 rounded-xl text-xs font-medium transition {{ request('status') === 'expired' ? 'bg-[#333A48] text-white' : 'text-[#8A99AD] hover:bg-[#333A48]/50 hover:text-white' }}">
                        <span class="flex items-center gap-2.5">
                            <span class="w-2 h-2 rounded-full bg-rose-400"></span>
                            <span>Habis Kontrak</span>
                        </span>
                        <span class="text-[10px] text-rose-400 font-semibold">Expired</span>
                    </a>
                </nav>
            </div>
        </div>

        <!-- Sidebar User Badge Card (Bottom) -->
        @auth
            <div class="p-4 m-4 rounded-xl bg-[#24303F] border border-[#2E3A47]">
                <div class="flex items-center gap-3">
                    <div
                        class="w-9 h-9 rounded-xl bg-[#3C50E0] text-white font-bold flex items-center justify-center shrink-0">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                    <div class="min-w-0 grow">
                        <p class="text-xs font-bold text-white truncate">{{ auth()->user()->name }}</p>
                        <span
                            class="inline-block text-[10px] font-semibold px-2 py-0.5 rounded-full {{ auth()->user()->isAdmin() ? 'bg-emerald-500/20 text-emerald-300' : 'bg-indigo-500/20 text-indigo-300' }}">
                            {{ auth()->user()->role_label }}
                        </span>
                    </div>
                </div>
            </div>
        @endauth
    </aside>

    <!-- Main Content Area Wrapper -->
    <div class="grow flex flex-col min-w-0 lg:pl-72">
        <!-- TailAdmin Topbar Header -->
        <header class="bg-white border-b border-[#E2E8F0] sticky top-0 z-30 shadow-xs">
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-20">
                    <!-- Left: Sidebar Toggle & Search Input -->
                    <div class="flex items-center gap-4 grow max-w-lg">
                        <!-- Mobile Hamburger Button -->
                        <button type="button" onclick="toggleSidebar()"
                            class="lg:hidden p-2 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>

                        <!-- Header Search Bar -->
                        <form action="{{ route('employees.index') }}" method="GET"
                            class="w-full relative hidden sm:block">
                            <span
                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </span>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Pencarian cepat karyawan, NIK, jabatan..."
                                class="w-full pl-9 pr-4 py-2 text-xs rounded-xl border border-[#E2E8F0] focus:border-[#3C50E0] focus:ring-2 focus:ring-[#3C50E0]/20 outline-hidden transition bg-[#F7F9FC]">
                        </form>
                    </div>

                    <!-- Right Toolbar & User Menu -->
                    <div class="flex items-center gap-4">
                        <!-- Date Badge -->
                        <div
                            class="hidden md:flex items-center gap-2 text-xs font-medium text-slate-500 bg-[#F7F9FC] px-3.5 py-2 rounded-xl border border-[#E2E8F0]">
                            <svg class="w-4 h-4 text-[#3C50E0]" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            <span>{{ now()->translatedFormat('l, d F Y') }}</span>
                        </div>

                        <!-- User Profile Pill & Logout -->
                        @auth
                            <div class="relative flex items-center gap-3 pl-4 border-l border-[#E2E8F0]">
                                <div class="text-right hidden sm:block">
                                    <p class="text-xs font-bold text-[#1C2434] leading-tight">{{ auth()->user()->name }}
                                    </p>
                                    <p class="text-[11px] font-semibold text-[#3C50E0]">{{ auth()->user()->role_label }}
                                    </p>
                                </div>

                                <div class="relative">
                                    <div
                                        class="w-10 h-10 rounded-full bg-[#3C50E0] text-white font-extrabold flex items-center justify-center text-sm shadow-sm">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                    </div>
                                    <span
                                        class="absolute bottom-0 right-0 w-3 h-3 rounded-full bg-emerald-500 border-2 border-white"></span>
                                </div>

                                <!-- Logout Button Form -->
                                <form action="{{ route('logout') }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                        class="p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition"
                                        title="Keluar / Logout">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                            </path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Body Content -->
        <main class="grow p-4 sm:p-6 lg:p-8 max-w-7xl w-full mx-auto space-y-6">
            <!-- Flash Message Success -->
            @if (session('success'))
                <div id="flash-success"
                    class="flex items-center justify-between p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 shadow-xs">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-8 h-8 rounded-lg bg-emerald-600 text-white flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <p class="text-xs font-semibold">{{ session('success') }}</p>
                    </div>
                    <button type="button" onclick="document.getElementById('flash-success').remove()"
                        class="text-emerald-700 hover:text-emerald-950 p-1 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            @endif

            <!-- Flash Message Error -->
            @if (session('error'))
                <div id="flash-error"
                    class="flex items-center justify-between p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-900 shadow-xs">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-8 h-8 rounded-lg bg-rose-600 text-white flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                </path>
                            </svg>
                        </div>
                        <p class="text-xs font-semibold">{{ session('error') }}</p>
                    </div>
                    <button type="button" onclick="document.getElementById('flash-error').remove()"
                        class="text-rose-700 hover:text-rose-950 p-1 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            @endif

            @yield('content')
        </main>

        <!-- TailAdmin Footer -->
        <footer class="mt-auto border-t border-[#E2E8F0] bg-white py-5 px-6">
            <div
                class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-slate-500">
                <p>&copy; {{ date('Y') }} SI-KONTRAK &bull; TailAdmin Dashboard &bull; Laravel
                    v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})</p>
                <p class="font-medium text-slate-400">Hak Akses: <strong
                        class="text-[#1C2434]">{{ auth()->user()?->role_label ?? 'Guest' }}</strong></p>
            </div>
        </footer>
    </div>

    <!-- Toggle Sidebar Script -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            sidebar.classList.toggle('-translate-x-full');
            backdrop.classList.toggle('hidden');
        }
    </script>
</body>

</html>
