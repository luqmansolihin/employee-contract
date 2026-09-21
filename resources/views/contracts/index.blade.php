@extends('layouts.app')

@section('title', 'Daftar Kontrak Kerja')

@section('content')
    <div class="space-y-6">
        <!-- Top Breadcrumb & Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs text-[#8A99AD] mb-1">
                    <a href="{{ route('employees.index') }}" class="hover:text-[#3C50E0] transition">Dashboard</a>
                    <span>/</span>
                    <span class="text-slate-800 font-medium">Kontrak Kerja</span>
                </div>
                <h1 class="text-2xl font-extrabold tracking-tight text-[#1C2434]">
                    Kontrak Kerja Karyawan
                </h1>
                <p class="text-xs text-slate-500 mt-1">
                    Pengelolaan surat perjanjian kerja resmi (PKWT, Management Trainee, dan Magang).
                </p>
            </div>

            <div class="flex items-center gap-3 shrink-0">
                <a href="{{ route('contracts.create') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#3C50E0] text-white text-xs font-semibold hover:bg-[#2F40BD] shadow-lg shadow-[#3C50E0]/25 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Terbitkan Kontrak Baru</span>
                </a>
            </div>
        </div>

        <!-- KPI Tipe Kontrak Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <a href="{{ route('contracts.index') }}"
                class="p-4 rounded-2xl bg-white border border-[#E2E8F0] shadow-xs hover:border-[#3C50E0] transition {{ !request('type') ? 'ring-2 ring-[#3C50E0]' : '' }}">
                <p class="text-xs font-medium text-slate-400">Semua Kontrak</p>
                <h3 class="text-2xl font-bold text-[#1C2434] mt-1">{{ $counts['total'] }}</h3>
            </a>

            <a href="{{ route('contracts.index', ['type' => 'PKWT']) }}"
                class="p-4 rounded-2xl bg-white border border-[#E2E8F0] shadow-xs hover:border-indigo-500 transition {{ request('type') === 'PKWT' ? 'ring-2 ring-indigo-500' : '' }}">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-medium text-indigo-500">PKWT</p>
                    <span class="text-[10px] text-indigo-400 font-semibold">Waktu Tertentu</span>
                </div>
                <h3 class="text-2xl font-bold text-indigo-700 mt-1">{{ $counts['pkwt'] }}</h3>
            </a>

            <a href="{{ route('contracts.index', ['type' => 'MT']) }}"
                class="p-4 rounded-2xl bg-white border border-[#E2E8F0] shadow-xs hover:border-purple-500 transition {{ request('type') === 'MT' ? 'ring-2 ring-purple-500' : '' }}">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-medium text-purple-500">MT</p>
                    <span class="text-[10px] text-purple-400 font-semibold">Management Trainee</span>
                </div>
                <h3 class="text-2xl font-bold text-purple-700 mt-1">{{ $counts['mt'] }}</h3>
            </a>

            <a href="{{ route('contracts.index', ['type' => 'MAGANG']) }}"
                class="p-4 rounded-2xl bg-white border border-[#E2E8F0] shadow-xs hover:border-amber-500 transition {{ request('type') === 'MAGANG' ? 'ring-2 ring-amber-500' : '' }}">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-medium text-amber-500">MAGANG</p>
                    <span class="text-[10px] text-amber-400 font-semibold">Internship</span>
                </div>
                <h3 class="text-2xl font-bold text-amber-700 mt-1">{{ $counts['magang'] }}</h3>
            </a>
        </div>

        <!-- Filter & Search Bar -->
        <div
            class="p-4 rounded-2xl bg-white border border-[#E2E8F0] shadow-xs flex flex-col sm:flex-row items-center justify-between gap-4">
            <form action="{{ route('contracts.index') }}" method="GET" class="w-full sm:w-80 relative">
                @if (request('type'))
                    <input type="hidden" name="type" value="{{ request('type') }}">
                @endif
                @if (request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari nomor kontrak, nama karyawan..."
                    class="w-full pl-9 pr-4 py-2 text-xs rounded-xl bg-[#F8FAFC] border border-[#E2E8F0] focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] outline-hidden transition">
            </form>

            <div class="flex items-center gap-2">
                <span class="text-xs text-slate-400">Filter Status:</span>
                <a href="{{ route('contracts.index', array_merge(request()->query(), ['status' => null])) }}"
                    class="px-2.5 py-1 text-xs rounded-lg {{ !request('status') ? 'bg-[#1C2434] text-white font-semibold' : 'text-slate-600 hover:bg-slate-100' }}">
                    Semua
                </a>
                <a href="{{ route('contracts.index', array_merge(request()->query(), ['status' => 'active'])) }}"
                    class="px-2.5 py-1 text-xs rounded-lg {{ request('status') === 'active' ? 'bg-emerald-600 text-white font-semibold' : 'text-slate-600 hover:bg-slate-100' }}">
                    Aktif
                </a>
                <a href="{{ route('contracts.index', array_merge(request()->query(), ['status' => 'expiring_soon'])) }}"
                    class="px-2.5 py-1 text-xs rounded-lg {{ request('status') === 'expiring_soon' ? 'bg-amber-500 text-white font-semibold' : 'text-slate-600 hover:bg-slate-100' }}">
                    Segera Habis
                </a>
                <a href="{{ route('contracts.index', array_merge(request()->query(), ['status' => 'expired'])) }}"
                    class="px-2.5 py-1 text-xs rounded-lg {{ request('status') === 'expired' ? 'bg-rose-600 text-white font-semibold' : 'text-slate-600 hover:bg-slate-100' }}">
                    Expired
                </a>
            </div>
        </div>

        <!-- Table Contracts -->
        <div class="bg-white rounded-2xl border border-[#E2E8F0] shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="border-b border-[#E2E8F0] bg-[#F8FAFC] text-[11px] font-bold text-[#8A99AD] uppercase tracking-wider">
                            <th class="py-3.5 px-4">No. Kontrak & Tipe</th>
                            <th class="py-3.5 px-4">Karyawan</th>
                            <th class="py-3.5 px-4">Jabatan & Cabang</th>
                            <th class="py-3.5 px-4">Masa Berlaku</th>
                            <th class="py-3.5 px-4">Status & Sisa</th>
                            <th class="py-3.5 px-4">Adendum</th>
                            <th class="py-3.5 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E2E8F0] text-xs">
                        @forelse($contracts as $c)
                            <tr class="hover:bg-slate-50/75 transition">
                                <td class="py-3.5 px-4">
                                    <span
                                        class="font-mono font-bold text-xs text-[#1C2434] block">{{ $c->contract_number ?: 'Nomor Belum Diatur' }}</span>
                                    <div class="flex items-center gap-1.5 mt-0.5">
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold border {{ $c->contract_type_badge_class }}">
                                            {{ $c->contract_type }}
                                        </span>
                                        <span class="text-[10px] text-slate-400">Seq #{{ $c->contract_sequence }}</span>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <a href="{{ route('employees.show', $c->employee) }}"
                                        class="font-bold text-[#1C2434] hover:text-[#3C50E0] transition block truncate max-w-[150px]">
                                        {{ $c->employee->name }}
                                    </a>
                                    <span class="text-[10px] text-slate-400 font-mono">NIK:
                                        {{ $c->employee->ktp_number }}</span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="font-medium text-slate-800 block">{{ $c->position }}</span>
                                    <span class="text-[11px] text-slate-400 block">{{ $c->branch }}</span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="font-medium text-[#1C2434] block">
                                        {{ $c->start_date->format('d/m/Y') }} s/d {{ $c->end_date->format('d/m/Y') }}
                                    </span>
                                    <span class="text-[11px] text-slate-400 block">{{ $c->duration_in_months }} Bulan
                                        ({{ $c->duration_in_days }} Hari)</span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold border {{ $c->status_badge_class }}">
                                        {{ $c->status_label }}
                                    </span>
                                    <span class="text-[11px] text-slate-500 block mt-0.5 font-medium">
                                        {{ $c->remaining_days_text }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4">
                                    @if ($c->addendums->isNotEmpty())
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold bg-purple-50 text-purple-700 border border-purple-200">
                                            {{ $c->addendums->count() }} Adendum
                                        </span>
                                    @else
                                        <span class="text-[11px] text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <a href="{{ route('contracts.show', $c) }}"
                                            class="p-1.5 rounded-lg text-[#8A99AD] hover:text-[#3C50E0] hover:bg-[#3C50E0]/10 transition"
                                            title="Lihat Detail Kontrak">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                        </a>

                                        <a href="{{ route('contracts.print', $c) }}" target="_blank"
                                            class="p-1.5 rounded-lg text-[#8A99AD] hover:text-emerald-600 hover:bg-emerald-50 transition"
                                            title="Cetak Surat Perjanjian Kerja">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                                </path>
                                            </svg>
                                        </a>

                                        <a href="{{ route('addendums.create', $c) }}"
                                            class="px-2.5 py-1 rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-[10px] font-semibold border border-indigo-200 transition"
                                            title="Buat Adendum Kontrak">
                                            + Adendum
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-slate-400">
                                    Belum ada data Kontrak Kerja.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($contracts->hasPages())
                <div class="p-4 border-t border-[#E2E8F0]">
                    {{ $contracts->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
