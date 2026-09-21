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

    <!-- Sidebar Backdrop Overlay (Mobile only) -->
    <div id="sidebar-backdrop" onclick="toggleSidebar()"
        class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-xs hidden lg:hidden transition-opacity"></div>

    <!-- TailAdmin Dark Sidebar (#1C2434) - Mini Collapsed by Default -->
    <aside id="sidebar"
        class="fixed inset-y-0 left-0 z-50 w-72 is-collapsed bg-[#1C2434] text-[#DEE4EE] flex flex-col shrink-0 border-r border-[#2E3A47] shadow-xl translate-x-0">
        <!-- Brand Header with Toggle Button -->
        <div class="h-20 flex items-center border-b border-[#2E3A47] px-4">
            <!-- Expanded View: Logo + Title + Collapse Button -->
            <div class="sidebar-expanded-header flex items-center justify-between w-full">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 min-w-0" title="SI-KONTRAK TailAdmin">
                    <div
                        class="w-10 h-10 rounded-xl bg-[#3C50E0] text-white flex items-center justify-center shadow-lg shadow-[#3C50E0]/30 shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <span class="text-base font-extrabold tracking-tight text-white truncate">
                        SI-KONTRAK
                    </span>
                </a>

                <button type="button" onclick="toggleSidebar()"
                    class="p-2 rounded-xl text-[#8A99AD] hover:text-white hover:bg-[#333A48] transition shrink-0 ml-2"
                    title="Ciutkan Menu (Collapse)">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
                    </svg>
                </button>
            </div>

            <!-- Collapsed View: Centered Toggle Button -->
            <div class="sidebar-collapsed-header hidden w-full justify-center">
                <button type="button" onclick="toggleSidebar()"
                    class="w-10 h-10 rounded-xl bg-[#24303F] hover:bg-[#3C50E0] text-[#DEE4EE] hover:text-white border border-[#2E3A47] flex items-center justify-center transition shadow-sm"
                    title="Buka Menu Sidebar (Expand)">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Sidebar Navigation Menu -->
        <div class="grow overflow-y-auto px-3 py-6">
            <nav class="space-y-1.5">
                <!-- 1. Dashboard -->
                <a href="{{ route('dashboard') }}" title="Dashboard"
                    class="sidebar-item flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('dashboard') || (request()->routeIs('home') && !request()->routeIs('employees.*')) ? 'bg-[#333A48] text-white font-semibold' : 'text-[#8A99AD] hover:bg-[#333A48]/60 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        </path>
                    </svg>
                    <span class="sidebar-text truncate">Dashboard</span>
                </a>

                <!-- 2. Employee -->
                <a href="{{ route('employees.index') }}" title="Employee"
                    class="sidebar-item flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('employees.*') ? 'bg-[#333A48] text-white font-semibold' : 'text-[#8A99AD] hover:bg-[#333A48]/60 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                    <span class="sidebar-text truncate">Employee</span>
                </a>

                <!-- 3. Offering Letter -->
                <a href="{{ route('offering-letters.index') }}" title="Offering Letter"
                    class="sidebar-item flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('offering-letters.*') ? 'bg-[#333A48] text-white font-semibold' : 'text-[#8A99AD] hover:bg-[#333A48]/60 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                        </path>
                    </svg>
                    <span class="sidebar-text truncate">Offering Letter</span>
                </a>

                <!-- 4. Kontrak -->
                <a href="{{ route('contracts.index') }}" title="Kontrak"
                    class="sidebar-item flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('contracts.*') ? 'bg-[#333A48] text-white font-semibold' : 'text-[#8A99AD] hover:bg-[#333A48]/60 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    <span class="sidebar-text truncate">Kontrak</span>
                </a>

                <!-- 5. Adendum -->
                <a href="{{ route('addendums.index') }}" title="Adendum"
                    class="sidebar-item flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('addendums.*') ? 'bg-[#333A48] text-white font-semibold' : 'text-[#8A99AD] hover:bg-[#333A48]/60 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>
                    <span class="sidebar-text truncate">Adendum</span>
                </a>

                @if (auth()->user()?->isAdmin())
                    <!-- Mini Divider -->
                    <div class="sidebar-divider w-full h-[1px] bg-[#2E3A47] my-2"></div>

                    <!-- 6. Kelola Pengguna (Admin Only) -->
                    <a href="{{ route('users.index') }}" title="Kelola Pengguna"
                        class="sidebar-item flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('users.*') ? 'bg-[#333A48] text-white font-semibold' : 'text-[#8A99AD] hover:bg-[#333A48]/60 hover:text-white' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                        <span class="sidebar-text truncate">Kelola User</span>
                    </a>
                @endif
            </nav>
        </div>

        <!-- Sidebar User Badge & Logout Form (Bottom) -->
        @auth
            <div class="p-3 border-t border-[#2E3A47] bg-[#171E2B]/60">
                <!-- Expanded View -->
                <div
                    class="sidebar-user-expanded flex items-center justify-between gap-3 p-2.5 rounded-xl bg-[#24303F] border border-[#2E3A47]">
                    <div class="flex items-center gap-3 min-w-0">
                        <div
                            class="w-9 h-9 rounded-xl bg-[#3C50E0] text-white font-bold flex items-center justify-center shrink-0 text-xs shadow-xs">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-white truncate">{{ auth()->user()->name }}</p>
                            <span
                                class="inline-block text-[10px] font-semibold px-2 py-0.5 rounded-full {{ auth()->user()->isAdmin() ? 'bg-emerald-500/20 text-emerald-300' : 'bg-indigo-500/20 text-indigo-300' }}">
                                {{ auth()->user()->role_label }}
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center gap-1 shrink-0">
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('users.index') }}"
                                class="p-2 rounded-lg text-[#8A99AD] hover:text-[#3C50E0] hover:bg-[#3C50E0]/10 transition"
                                title="Kelola Pengguna">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </a>
                        @endif

                        <!-- Logout Button (Expanded) -->
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="p-2 rounded-lg text-[#8A99AD] hover:text-rose-400 hover:bg-rose-500/10 transition"
                                title="Keluar / Logout">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                    </path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Collapsed View: Stacked Centered Avatar and Logout Button -->
                <div class="sidebar-user-collapsed hidden flex-col items-center gap-2">
                    <div class="w-9 h-9 rounded-xl bg-[#3C50E0] text-white font-bold flex items-center justify-center shrink-0 text-xs shadow-xs"
                        title="{{ auth()->user()->name }} ({{ auth()->user()->role_label }})">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>

                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('users.index') }}"
                            class="w-9 h-9 rounded-xl text-[#8A99AD] hover:text-[#3C50E0] hover:bg-[#3C50E0]/10 flex items-center justify-center transition"
                            title="Kelola Pengguna">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                </path>
                            </svg>
                        </a>
                    @endif

                    <form action="{{ route('logout') }}" method="POST" class="w-full flex justify-center">
                        @csrf
                        <button type="submit"
                            class="w-9 h-9 rounded-xl text-[#8A99AD] hover:text-rose-400 hover:bg-rose-500/10 flex items-center justify-center transition"
                            title="Keluar / Logout ({{ auth()->user()->name }})">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                </path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        @endauth
    </aside>

    <!-- Main Content Area Wrapper (No Topbar Header) -->
    <div id="main-content" class="grow flex flex-col min-w-0 is-sidebar-collapsed">
        <!-- Main Body Content -->
        <main class="grow p-4 sm:p-6 lg:p-8 max-w-7xl w-full mx-auto space-y-6">
            <!-- Flash Message Success -->
            @if (session('success'))
                <div id="flash-success"
                    class="flex items-center justify-between p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 shadow-xs transition-all duration-500 ease-in-out">
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
                    <button type="button" onclick="dismissAlert('flash-success')"
                        class="text-emerald-700 hover:text-emerald-950 p-1 rounded-lg transition" title="Tutup">
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
                    class="flex items-center justify-between p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-900 shadow-xs transition-all duration-500 ease-in-out">
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
                    <button type="button" onclick="dismissAlert('flash-error')"
                        class="text-rose-700 hover:text-rose-950 p-1 rounded-lg transition" title="Tutup">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const backdrop = document.getElementById('sidebar-backdrop');
            const isDesktop = window.innerWidth >= 1024;

            const isCollapsed = sidebar.classList.toggle('is-collapsed');
            mainContent.classList.toggle('is-sidebar-collapsed', isCollapsed);

            if (!isDesktop) {
                backdrop.classList.toggle('hidden', isCollapsed);
            } else {
                backdrop.classList.add('hidden');
            }
        }

        function dismissAlert(elementId) {
            const el = document.getElementById(elementId);
            if (!el) return;
            el.style.transition = 'all 0.5s ease-out';
            el.style.opacity = '0';
            el.style.transform = 'translateY(-8px)';
            setTimeout(() => {
                el.remove();
            }, 500);
        }

        // Auto-dismiss notification after 4 seconds
        document.addEventListener('DOMContentLoaded', () => {
            const flashSuccess = document.getElementById('flash-success');
            if (flashSuccess) {
                setTimeout(() => {
                    dismissAlert('flash-success');
                }, 4000);
            }
        });

        // Close/collapse sidebar on Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                const sidebar = document.getElementById('sidebar');
                const mainContent = document.getElementById('main-content');
                const backdrop = document.getElementById('sidebar-backdrop');
                sidebar.classList.add('is-collapsed');
                mainContent.classList.add('is-sidebar-collapsed');
                backdrop.classList.add('hidden');
            }
        });
    </script>
</body>

</html>
