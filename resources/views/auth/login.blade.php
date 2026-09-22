<!DOCTYPE html>
<html lang="id" class="h-full bg-[#F1F5F9]">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - SI-KONTRAK TailAdmin</title>

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

<body class="h-full flex items-center justify-center p-4 text-slate-800 antialiased">
    <div class="w-full max-w-md">
        <!-- Logo & Branding -->
        <div class="text-center mb-8">
            <div
                class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-[#3C50E0] text-white shadow-lg shadow-[#3C50E0]/30 mb-3">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
            </div>
            <h1 class="text-2xl font-extrabold text-[#1C2434] tracking-tight">SI-KONTRAK</h1>
            <p class="text-xs font-medium text-slate-500 mt-1">Sistem Pencatatan & Perpanjangan Karyawan Kontrak</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-2xl border border-[#E2E8F0] shadow-sm p-8 space-y-6">
            <div>
                <h2 class="text-lg font-bold text-[#1C2434]">Masuk ke Akun Anda</h2>
            </div>

            <!-- Flash Error -->
            @if ($errors->any())
                <div
                    class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-start gap-3">
                    <svg class="w-4 h-4 text-rose-500 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        @foreach ($errors->all() as $error)
                            <p class="font-medium">{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            @if (session('success'))
                <div id="flash-success"
                    class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium flex items-center justify-between gap-2 transition-all duration-500 ease-in-out">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button" onclick="dismissAlert('flash-success')"
                        class="text-emerald-700 hover:text-emerald-950 p-0.5 rounded-sm">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            @endif

            <form action="{{ route('login.submit') }}" method="POST" class="space-y-4" autocomplete="off">
                @csrf

                <!-- Email Input -->
                <div>
                    <label for="email"
                        class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Alamat Email
                    </label>
                    <div class="relative">
                        <span
                            class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207">
                                </path>
                            </svg>
                        </span>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                            autofocus placeholder="nama@perusahaan.com" autocomplete="off"
                            class="w-full pl-10 pr-3.5 py-2.5 text-sm rounded-xl border border-[#E2E8F0] focus:border-[#3C50E0] focus:ring-2 focus:ring-[#3C50E0]/20 outline-hidden transition bg-[#F7F9FC]">
                    </div>
                </div>

                <!-- Password Input -->
                <div>
                    <label for="password"
                        class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Kata Sandi
                    </label>
                    <div class="relative">
                        <span
                            class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                </path>
                            </svg>
                        </span>
                        <input type="password" name="password" id="password" required
                            placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;" autocomplete="off"
                            class="w-full pl-10 pr-3.5 py-2.5 text-sm rounded-xl border border-[#E2E8F0] focus:border-[#3C50E0] focus:ring-2 focus:ring-[#3C50E0]/20 outline-hidden transition bg-[#F7F9FC]">
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 cursor-pointer text-xs font-medium text-slate-600">
                        <input type="checkbox" name="remember" value="1"
                            class="w-4 h-4 rounded-sm border-slate-300 text-[#3C50E0] focus:ring-[#3C50E0]">
                        <span>Ingat saya di perangkat ini</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="w-full py-2.5 px-4 rounded-xl text-sm font-semibold bg-[#3C50E0] text-white hover:bg-[#2F40BD] shadow-sm shadow-[#3C50E0]/30 transition flex items-center justify-center gap-2 mt-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1">
                        </path>
                    </svg>
                    <span>Masuk ke Sistem</span>
                </button>
            </form>
        </div>
    </div>

    <script>
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

        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('form').forEach(form => form.setAttribute('autocomplete', 'off'));
            document.querySelectorAll(
                    'input:not([type="hidden"]):not([type="submit"]):not([type="checkbox"]):not([type="radio"])')
                .forEach(input => {
                    input.setAttribute('autocomplete', 'off');
                });

            const flashSuccess = document.getElementById('flash-success');
            if (flashSuccess) {
                setTimeout(() => {
                    dismissAlert('flash-success');
                }, 4000);
            }
        });
    </script>
</body>

</html>
