@extends('layouts.app')

@section('title', 'Daftar Karyawan Kontrak')

@section('content')
    <div class="space-y-6">
        <!-- Header & Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl font-extrabold tracking-tight text-[#1C2434]">
                    <span class="text-slate-400">SI-KONTRAK /</span> EMPLOYEE
                </h1>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('employees.create') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold bg-[#3C50E0] text-white hover:bg-[#2F40BD] shadow-sm shadow-[#3C50E0]/30 transition uppercase tracking-wider">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Tambah Karyawan</span>
                </a>
            </div>
        </div>

        <!-- Filter & Search Toolbar (TailAdmin Style) -->
        <div class="p-5 rounded-2xl bg-white border border-[#E2E8F0] shadow-xs">
            <form method="GET" action="{{ route('employees.index') }}" autocomplete="off"
                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                <!-- Search Keyword -->
                <div class="lg:col-span-2">
                    <label for="search" class="block text-xs font-bold text-[#1C2434] uppercase tracking-wider mb-1">Cari
                        Karyawan</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </span>
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                            placeholder="Nama, NIK, jabatan, cabang..." autocomplete="off"
                            class="w-full pl-9 pr-3 py-2 text-xs rounded-xl border border-[#E2E8F0] focus:border-[#3C50E0] focus:ring-2 focus:ring-[#3C50E0]/20 outline-hidden transition bg-[#F7F9FC]">
                    </div>
                </div>

                <!-- Branch Filter -->
                <div>
                    <label for="branch"
                        class="block text-xs font-bold text-[#1C2434] uppercase tracking-wider mb-1">Cabang</label>
                    <select name="branch" id="branch"
                        class="w-full px-3 py-2 text-xs rounded-xl border border-[#E2E8F0] focus:border-[#3C50E0] focus:ring-2 focus:ring-[#3C50E0]/20 outline-hidden transition bg-[#F7F9FC]">
                        <option value="">Semua Cabang</option>
                        @foreach ($branches as $branch)
                            <option value="{{ $branch }}" {{ request('branch') === $branch ? 'selected' : '' }}>
                                {{ $branch }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <label for="status"
                        class="block text-xs font-bold text-[#1C2434] uppercase tracking-wider mb-1">Status Kontrak</label>
                    <select name="status" id="status"
                        class="w-full px-3 py-2 text-xs rounded-xl border border-[#E2E8F0] focus:border-[#3C50E0] focus:ring-2 focus:ring-[#3C50E0]/20 outline-hidden transition bg-[#F7F9FC]">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif (&gt; 30 Hari)
                        </option>
                        <option value="expiring_soon" {{ request('status') === 'expiring_soon' ? 'selected' : '' }}>Segera
                            Habis (&le; 30 Hari)</option>
                        <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Habis Kontrak
                        </option>
                    </select>
                </div>

                <!-- Filter Action Buttons -->
                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="grow px-4 py-2 text-xs font-bold rounded-xl bg-[#3C50E0] text-white hover:bg-[#2F40BD] transition shadow-xs flex items-center justify-center gap-1.5 uppercase tracking-wider">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                            </path>
                        </svg>
                        <span>Filter</span>
                    </button>
                    @if (request()->hasAny(['search', 'branch', 'position', 'status']))
                        <a href="{{ route('employees.index') }}"
                            class="px-3 py-2 text-xs font-semibold rounded-xl border border-[#E2E8F0] text-slate-600 hover:bg-slate-100 transition"
                            title="Reset Filter">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- TailAdmin Data Table -->
        <div class="bg-white rounded-2xl border border-[#E2E8F0] shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr
                            class="bg-[#F7F9FC] border-b border-[#E2E8F0] font-bold text-slate-700 uppercase tracking-wider text-[11px]">
                            <th class="py-4 px-4 w-12 text-center">No</th>
                            <th class="py-4 px-4">Karyawan & NIK</th>
                            <th class="py-4 px-4">Jabatan & Cabang</th>
                            <th class="py-4 px-4 text-center">Riwayat</th>
                            <th class="py-4 px-4">Kontrak Terkini</th>
                            <th class="py-4 px-4">Status & Sisa Waktu</th>
                            <th class="py-4 px-4 text-center w-36">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E2E8F0]">
                        @forelse ($employees as $employee)
                            <tr class="hover:bg-[#F7F9FC]/70 transition">
                                <!-- Number -->
                                <td class="py-4 px-4 text-center font-semibold text-slate-400">
                                    {{ $employees->firstItem() + $loop->index }}
                                </td>

                                <!-- Employee Name & KTP -->
                                <td class="py-4 px-4">
                                    <a href="{{ route('employees.show', $employee) }}"
                                        class="font-bold text-[#1C2434] hover:text-[#3C50E0] transition text-sm block">
                                        {{ $employee->name }}
                                    </a>
                                    <span
                                        class="inline-flex items-center gap-1 text-[11px] text-slate-500 font-mono mt-0.5">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2">
                                            </path>
                                        </svg>
                                        {{ $employee->ktp_number }}
                                    </span>
                                </td>

                                <!-- Position & Branch -->
                                <td class="py-4 px-4">
                                    <span
                                        class="font-semibold text-slate-800 block text-xs">{{ $employee->current_position ?: 'Belum Ada Kontrak' }}</span>
                                    <span class="inline-flex items-center gap-1 text-[11px] text-slate-500 mt-0.5">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                            </path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        {{ $employee->current_branch ?: '-' }}
                                    </span>
                                </td>

                                <!-- Contract Sequence Badge -->
                                <td class="py-4 px-4 text-center">
                                    @php
                                        $contractCount = $employee->contracts->count();
                                    @endphp
                                    @if ($contractCount > 1)
                                        <span
                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-indigo-50 text-[#3C50E0] border border-indigo-200"
                                            title="{{ $contractCount }} kali kontrak">
                                            <svg class="w-3 h-3 text-[#3C50E0]" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                                </path>
                                            </svg>
                                            PKWT #{{ $contractCount }}
                                        </span>
                                    @elseif ($contractCount === 1)
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-medium bg-slate-100 text-slate-600">
                                            Kontrak Awal
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-medium bg-slate-100 text-slate-400">
                                            Belum Ada
                                        </span>
                                    @endif
                                </td>

                                <!-- Current Contract Period -->
                                <td class="py-4 px-4 text-xs">
                                    @if ($employee->current_contract_end_date)
                                        <div class="font-medium text-slate-700">
                                            s/d <span
                                                class="font-bold text-[#1C2434]">{{ $employee->current_contract_end_date->format('d M Y') }}</span>
                                        </div>
                                        <span class="text-[11px] text-slate-400 block mt-0.5">Mulai:
                                            {{ $employee->first_join_date?->format('d M Y') ?: '-' }}</span>
                                    @else
                                        <span class="text-slate-400 italic">Belum ada kontrak</span>
                                    @endif
                                </td>

                                <!-- Status & Remaining Days -->
                                <td class="py-4 px-4">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold {{ $employee->status_badge_class }}">
                                        {{ $employee->status_label }}
                                    </span>
                                    <span
                                        class="block text-[11px] font-semibold mt-1 {{ $employee->status === 'expired' ? 'text-rose-600' : ($employee->status === 'expiring_soon' ? 'text-amber-700 font-bold' : 'text-slate-500') }}">
                                        {{ $employee->remaining_days_text }}
                                    </span>
                                </td>

                                <!-- Actions Toolbar -->
                                <td class="py-4 px-4 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <!-- View Detail -->
                                        <a href="{{ route('employees.show', $employee) }}"
                                            class="p-1.5 rounded-lg text-slate-500 hover:text-[#3C50E0] hover:bg-indigo-50 transition"
                                            title="Lihat Detail & Riwayat">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                        </a>

                                        @if ($contractCount > 0)
                                            <!-- Renew Contract -->
                                            <a href="{{ route('employees.renew', $employee) }}"
                                                class="p-1.5 rounded-lg text-[#3C50E0] hover:text-[#2F40BD] hover:bg-indigo-50 transition"
                                                title="Perpanjang Kontrak">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                                    </path>
                                                </svg>
                                            </a>
                                        @else
                                            <!-- Create Offering Letter -->
                                            <a href="{{ route('offering-letters.create', $employee) }}"
                                                class="p-1.5 rounded-lg text-blue-600 hover:text-blue-800 hover:bg-blue-50 transition"
                                                title="Terbitkan Offering Letter">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                            </a>
                                        @endif

                                        <!-- Edit Biodata -->
                                        <a href="{{ route('employees.edit', $employee) }}"
                                            class="p-1.5 rounded-lg text-slate-500 hover:text-amber-600 hover:bg-amber-50 transition"
                                            title="Ubah Biodata">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </a>

                                        <!-- Delete (AUTHORIZATION: Only Super Admin can delete) -->
                                        @can('delete', $employee)
                                            <form action="{{ route('employees.destroy', $employee) }}" method="POST"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus data karyawan {{ $employee->name }} beserta seluruh riwayat kontraknya?')"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="p-1.5 rounded-lg text-slate-500 hover:text-rose-600 hover:bg-rose-50 transition"
                                                    title="Hapus Karyawan (Super Admin)">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                        </path>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 px-4 text-center">
                                    <div class="max-w-sm mx-auto">
                                        <div
                                            class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                                </path>
                                            </svg>
                                        </div>
                                        <h3 class="text-sm font-bold text-[#1C2434]">Tidak ada data karyawan ditemukan</h3>
                                        <p class="text-xs text-slate-500 mt-1">Coba sesuaikan kata kunci pencarian atau
                                            filter yang dipilih.</p>
                                        @if (request()->hasAny(['search', 'branch', 'position', 'status']))
                                            <a href="{{ route('employees.index') }}"
                                                class="inline-block mt-3 text-xs font-bold text-[#3C50E0] hover:underline">
                                                Bersihkan Semua Filter
                                            </a>
                                        @else
                                            <a href="{{ route('employees.create') }}"
                                                class="inline-block mt-3 text-xs font-bold text-[#3C50E0] hover:underline">
                                                + Tambah Karyawan Pertama
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Footer -->
            @if ($employees->hasPages())
                <div class="p-4 border-t border-[#E2E8F0] bg-[#F7F9FC]">
                    {{ $employees->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
