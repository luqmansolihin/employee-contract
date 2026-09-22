@extends('layouts.app')

@section('title', 'Tambah Pengguna Baru')

@section('content')
    <div class="max-w-3xl mx-auto space-y-6">
        <!-- Top Breadcrumb -->
        <div class="flex items-center gap-2 text-xs text-[#8A99AD]">
            <a href="{{ route('dashboard') }}" class="hover:text-[#3C50E0] transition">Dashboard</a>
            <span>/</span>
            <a href="{{ route('users.index') }}" class="hover:text-[#3C50E0] transition">Pengguna</a>
            <span>/</span>
            <span class="text-slate-800 font-medium">Tambah Pengguna Baru</span>
        </div>

        <!-- Form Card -->
        <div class="rounded-2xl bg-white border border-[#E2E8F0] shadow-xs overflow-hidden">
            <div class="p-6 border-b border-[#E2E8F0]">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-xl bg-indigo-50 text-[#3C50E0] flex items-center justify-center font-bold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-[#1C2434]">Form Pendaftaran Pengguna</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Daftarkan akun operator baru untuk mengakses sistem
                            SI-KONTRAK.</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('users.store') }}" method="POST" class="p-6 space-y-6" autocomplete="off">
                @csrf

                <!-- Section 1: Informasi Pengguna -->
                <div class="space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-[#3C50E0] pb-2 border-b border-[#E2E8F0]">
                        1. Informasi Akun & Hak Akses
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Nama Lengkap -->
                        <div class="sm:col-span-2">
                            <label for="name" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Nama Lengkap <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                autocomplete="off" placeholder="Contoh: Budi Santoso, S.Kom"
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('name') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] outline-hidden transition">
                            @error('name')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Alamat Email <span class="text-rose-500">*</span>
                            </label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                autocomplete="off" placeholder="user@company.com"
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('email') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] outline-hidden transition">
                            @error('email')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Peran (Role) -->
                        <div>
                            <label for="role" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Peran Akses (Role) <span class="text-rose-500">*</span>
                            </label>
                            <select name="role" id="role" required autocomplete="off"
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('role') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] outline-hidden transition">
                                <option value="staff" {{ old('role', 'staff') === 'staff' ? 'selected' : '' }}>
                                    Staff HRD (Akses Operasional Kontrak)
                                </option>
                                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>
                                    Super Admin (Akses Penuh & Kelola Pengguna)
                                </option>
                            </select>
                            @error('role')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 2: Keamanan / Kata Sandi -->
                <div class="space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-[#3C50E0] pb-2 border-b border-[#E2E8F0]">
                        2. Kata Sandi (Password)
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Kata Sandi Baru <span class="text-rose-500">*</span>
                            </label>
                            <input type="password" name="password" id="password" required placeholder="Minimal 8 karakter"
                                autocomplete="off"
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('password') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] outline-hidden transition">
                            @error('password')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password Confirmation -->
                        <div>
                            <label for="password_confirmation" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Konfirmasi Kata Sandi <span class="text-rose-500">*</span>
                            </label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required
                                autocomplete="off" placeholder="Ulangi kata sandi di atas"
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border border-[#E2E8F0] focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] outline-hidden transition">
                        </div>
                    </div>
                </div>

                <!-- Form Action Buttons -->
                <div class="pt-4 border-t border-[#E2E8F0] flex items-center justify-end gap-3">
                    <a href="{{ route('users.index') }}"
                        class="px-4 py-2.5 rounded-xl border border-[#E2E8F0] text-slate-600 hover:bg-slate-50 text-xs font-semibold transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-5 py-2.5 rounded-xl bg-[#3C50E0] text-white hover:bg-[#2F40BD] text-xs font-bold shadow-lg shadow-[#3C50E0]/25 transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Simpan Pengguna</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
