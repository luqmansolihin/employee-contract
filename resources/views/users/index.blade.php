@extends('layouts.app')

@section('title', 'Manajemen Pengguna')

@section('content')
    <div class="space-y-6">
        <!-- Top Breadcrumb & Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs text-[#8A99AD] mb-1">
                    <a href="{{ route('dashboard') }}" class="hover:text-[#3C50E0] transition">Dashboard</a>
                    <span>/</span>
                    <span class="text-slate-800 font-medium">Pengguna</span>
                </div>
                <h1 class="text-2xl font-extrabold tracking-tight text-[#1C2434]">
                    Manajemen Pengguna
                </h1>
                <p class="text-xs text-slate-500 mt-1">
                    Kelola akun operator sistem, pemberian hak akses (Super Admin / Staff HRD), dan kredensial login.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('users.create') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold bg-[#3C50E0] text-white hover:bg-[#2F40BD] shadow-sm shadow-[#3C50E0]/30 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Tambah Pengguna</span>
                </a>
            </div>
        </div>

        <!-- Metric KPI Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <!-- Total Pengguna -->
            <div class="p-5 rounded-2xl bg-white border border-[#E2E8F0] shadow-xs">
                <div class="flex items-center justify-between">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                    </div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total</span>
                </div>
                <div class="mt-3">
                    <p class="text-2xl font-extrabold text-[#1C2434]">{{ number_format($totalCount) }}</p>
                    <p class="text-xs text-slate-400 mt-0.5">Semua Akun Pengguna</p>
                </div>
            </div>

            <!-- Super Admin -->
            <div class="p-5 rounded-2xl bg-white border border-[#E2E8F0] shadow-xs">
                <div class="flex items-center justify-between">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                            </path>
                        </svg>
                    </div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-600">Admin</span>
                </div>
                <div class="mt-3">
                    <p class="text-2xl font-extrabold text-emerald-900">{{ number_format($adminCount) }}</p>
                    <p class="text-xs text-slate-400 mt-0.5">Super Admin (Akses Penuh)</p>
                </div>
            </div>

            <!-- Staff HRD -->
            <div class="p-5 rounded-2xl bg-white border border-[#E2E8F0] shadow-xs">
                <div class="flex items-center justify-between">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                            </path>
                        </svg>
                    </div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-indigo-600">Staff</span>
                </div>
                <div class="mt-3">
                    <p class="text-2xl font-extrabold text-indigo-900">{{ number_format($staffCount) }}</p>
                    <p class="text-xs text-slate-400 mt-0.5">Staff HRD Operasional</p>
                </div>
            </div>
        </div>

        <!-- Filter & Search Bar -->
        <div class="p-4 rounded-2xl bg-white border border-[#E2E8F0] shadow-xs">
            <form action="{{ route('users.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-3">
                <div class="relative grow w-full sm:w-auto">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari berdasarkan nama atau alamat email..."
                        class="w-full pl-10 pr-4 py-2 text-xs rounded-xl border border-[#E2E8F0] focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] outline-hidden transition bg-[#F8FAFC]">
                </div>

                <div class="w-full sm:w-48">
                    <select name="role" onchange="this.form.submit()"
                        class="w-full px-3 py-2 text-xs rounded-xl border border-[#E2E8F0] focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] outline-hidden transition bg-[#F8FAFC]">
                        <option value="">Semua Peran (Role)</option>
                        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Super Admin</option>
                        <option value="staff" {{ request('role') === 'staff' ? 'selected' : '' }}>Staff HRD</option>
                    </select>
                </div>

                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <button type="submit"
                        class="px-4 py-2 rounded-xl bg-[#1C2434] text-white text-xs font-semibold hover:bg-slate-800 transition w-full sm:w-auto">
                        Filter
                    </button>
                    @if (request()->hasAny(['search', 'role']))
                        <a href="{{ route('users.index') }}"
                            class="px-3 py-2 rounded-xl border border-[#E2E8F0] text-slate-500 hover:text-slate-800 text-xs font-semibold hover:bg-slate-50 transition"
                            title="Reset Filter">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Users Table -->
        <div class="rounded-2xl bg-white border border-[#E2E8F0] shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr
                            class="border-b border-[#E2E8F0] bg-[#F8FAFC] text-slate-500 font-bold uppercase tracking-wider text-[10px]">
                            <th class="py-3.5 px-4">Pengguna</th>
                            <th class="py-3.5 px-4">Email</th>
                            <th class="py-3.5 px-4">Peran (Role)</th>
                            <th class="py-3.5 px-4">Terdaftar Sejak</th>
                            <th class="py-3.5 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E2E8F0]">
                        @forelse ($users as $user)
                            <tr class="hover:bg-slate-50/80 transition">
                                <!-- Pengguna & Avatar -->
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-9 h-9 rounded-xl {{ $user->isAdmin() ? 'bg-emerald-600' : 'bg-[#3C50E0]' }} text-white font-bold flex items-center justify-center shrink-0 text-xs shadow-xs">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <span class="font-bold text-[#1C2434]">{{ $user->name }}</span>
                                                @if (auth()->id() === $user->id)
                                                    <span
                                                        class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                                        Akun Anda
                                                    </span>
                                                @endif
                                            </div>
                                            <span class="text-[10px] text-slate-400">ID #{{ $user->id }}</span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Email -->
                                <td class="py-3.5 px-4 font-mono text-slate-700">
                                    {{ $user->email }}
                                </td>

                                <!-- Peran / Role Badge -->
                                <td class="py-3.5 px-4">
                                    @if ($user->isAdmin())
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Super Admin
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                            Staff HRD
                                        </span>
                                    @endif
                                </td>

                                <!-- Tanggal Dibuat -->
                                <td class="py-3.5 px-4 text-slate-500">
                                    {{ $user->created_at ? $user->created_at->translatedFormat('d M Y, H:i') : '-' }}
                                </td>

                                <!-- Aksi -->
                                <td class="py-3.5 px-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- Edit -->
                                        <a href="{{ route('users.edit', $user) }}"
                                            class="p-1.5 rounded-lg text-slate-600 hover:text-[#3C50E0] hover:bg-slate-100 transition"
                                            title="Edit Pengguna">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </a>

                                        <!-- Hapus (kecuali diri sendiri) -->
                                        @if (auth()->id() !== $user->id)
                                            <form action="{{ route('users.destroy', $user) }}" method="POST"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun pengguna \'{{ $user->name }}\'? Tindakan ini tidak dapat dibatalkan.');"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition"
                                                    title="Hapus Pengguna">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                        </path>
                                                    </svg>
                                                </button>
                                            </form>
                                        @else
                                            <span class="p-1.5 text-slate-300 cursor-not-allowed"
                                                title="Anda tidak dapat menghapus akun Anda sendiri">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-slate-400">
                                    <svg class="w-10 h-10 mx-auto mb-2 text-slate-300" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                        </path>
                                    </svg>
                                    <p class="font-medium text-xs text-slate-500">Tidak ada data pengguna yang ditemukan.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($users->hasPages())
                <div class="px-6 py-4 border-t border-[#E2E8F0]">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
