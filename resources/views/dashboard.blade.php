@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="space-y-6">
        <!-- Dashboard Header -->
        <div>
            <h1 class="text-xl font-extrabold tracking-tight text-[#1C2434]">
                <span class="text-slate-400">SI-KONTRAK /</span> DASHBOARD
            </h1>
        </div>


        <!-- TailAdmin KPI Analytic Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- 1. Employee Card -->
            <a href="{{ route('employees.index') }}"
                class="p-5 rounded-2xl bg-white border border-[#E2E8F0] shadow-xs hover:border-[#3C50E0] hover:shadow-md transition group">
                <div class="flex items-center justify-between">
                    <div
                        class="w-11 h-11 rounded-xl bg-indigo-50 text-[#3C50E0] flex items-center justify-center group-hover:scale-105 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <p class="text-3xl font-extrabold text-[#1C2434]">{{ number_format($totalEmployees) }}</p>
                    <p class="text-xs font-medium text-slate-500 mt-1">Total Karyawan Terdaftar</p>
                </div>
            </a>

            <!-- 2. Offering Letter Card -->
            <a href="{{ route('offering-letters.index') }}"
                class="p-5 rounded-2xl bg-white border border-[#E2E8F0] shadow-xs hover:border-amber-400 hover:shadow-md transition group">
                <div class="flex items-center justify-between">
                    <div
                        class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center group-hover:scale-105 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <p class="text-3xl font-extrabold text-[#1C2434]">{{ number_format($totalOfferingLetters) }}</p>
                    <p class="text-xs font-medium text-slate-500 mt-1">
                        Offering Letter
                        @if ($pendingOfferingLetters > 0)
                            <span class="text-amber-600 font-semibold">({{ $pendingOfferingLetters }} proses)</span>
                        @endif
                    </p>
                </div>
            </a>

            <!-- 3. Kontrak Aktif Card -->
            <a href="{{ route('contracts.index') }}"
                class="p-5 rounded-2xl bg-white border border-[#E2E8F0] shadow-xs hover:border-emerald-500 hover:shadow-md transition group">
                <div class="flex items-center justify-between">
                    <div
                        class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:scale-105 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <p class="text-3xl font-extrabold text-emerald-800">{{ number_format($activeContractsCount) }}</p>
                    <p class="text-xs font-medium text-slate-500 mt-1">Kontrak Kerja Aktif</p>
                </div>
            </a>

            <!-- 4. Adendum Kontrak Card -->
            <a href="{{ route('addendums.index') }}"
                class="p-5 rounded-2xl bg-white border border-[#E2E8F0] shadow-xs hover:border-purple-500 hover:shadow-md transition group">
                <div class="flex items-center justify-between">
                    <div
                        class="w-11 h-11 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center group-hover:scale-105 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <p class="text-3xl font-extrabold text-purple-900">{{ number_format($totalAddendums) }}</p>
                    <p class="text-xs font-medium text-slate-500 mt-1">Total Adendum Diterbitkan</p>
                </div>
            </a>
        </div>

        <!-- Breakdown Tipe Kontrak & Peringatan Masa Berlaku -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Distribusi Tipe Kontrak -->
            <div class="p-6 rounded-2xl bg-white border border-[#E2E8F0] shadow-xs flex flex-col justify-between">
                <div>
                    <h3 class="text-sm font-bold text-[#1C2434] mb-1">Distribusi Tipe Kontrak Aktif</h3>
                    <p class="text-xs text-slate-400 mb-4">Klasifikasi jenis perjanjian kerja karyawan berjalan.</p>

                    <div class="space-y-3">
                        <!-- PKWT -->
                        <a href="{{ route('contracts.index', ['type' => 'PKWT']) }}"
                            class="flex items-center justify-between p-3 rounded-xl bg-slate-50 hover:bg-indigo-50/50 border border-transparent hover:border-indigo-200 transition group">
                            <div class="flex items-center gap-3">
                                <span class="w-3 h-3 rounded-full bg-indigo-500 shrink-0"></span>
                                <div>
                                    <p class="text-xs font-bold text-slate-800 group-hover:text-indigo-600 transition">PKWT
                                    </p>
                                    <p class="text-[10px] text-slate-400">Perjanjian Kerja Waktu Tertentu</p>
                                </div>
                            </div>
                            <span
                                class="text-sm font-extrabold text-[#1C2434] group-hover:text-indigo-600 transition">{{ $pkwtCount }}</span>
                        </a>

                        <!-- MT -->
                        <a href="{{ route('contracts.index', ['type' => 'MT']) }}"
                            class="flex items-center justify-between p-3 rounded-xl bg-slate-50 hover:bg-purple-50/50 border border-transparent hover:border-purple-200 transition group">
                            <div class="flex items-center gap-3">
                                <span class="w-3 h-3 rounded-full bg-purple-500 shrink-0"></span>
                                <div>
                                    <p class="text-xs font-bold text-slate-800 group-hover:text-purple-600 transition">
                                        Management Trainee (MT)</p>
                                    <p class="text-[10px] text-slate-400">Program Pelatihan Manajemen</p>
                                </div>
                            </div>
                            <span
                                class="text-sm font-extrabold text-[#1C2434] group-hover:text-purple-600 transition">{{ $mtCount }}</span>
                        </a>

                        <!-- MAGANG -->
                        <a href="{{ route('contracts.index', ['type' => 'MAGANG']) }}"
                            class="flex items-center justify-between p-3 rounded-xl bg-slate-50 hover:bg-amber-50/50 border border-transparent hover:border-amber-200 transition group">
                            <div class="flex items-center gap-3">
                                <span class="w-3 h-3 rounded-full bg-amber-500 shrink-0"></span>
                                <div>
                                    <p class="text-xs font-bold text-slate-800 group-hover:text-amber-600 transition">
                                        MAGANG</p>
                                    <p class="text-[10px] text-slate-400">Program Internship & Prakerin</p>
                                </div>
                            </div>
                            <span
                                class="text-sm font-extrabold text-[#1C2434] group-hover:text-amber-600 transition">{{ $magangCount }}</span>
                        </a>
                    </div>
                </div>

                <div class="pt-4 mt-4 border-t border-[#E2E8F0] flex items-center justify-between text-xs">
                    <span class="text-slate-400">Total Berjalan:</span>
                    <span class="font-extrabold text-[#1C2434]">{{ $pkwtCount + $mtCount + $magangCount }} Kontrak</span>
                </div>
            </div>

            <!-- Peringatan Kontrak Segera Berakhir (<= 30 Hari) -->
            <div class="lg:col-span-2 p-6 rounded-2xl bg-white border border-[#E2E8F0] shadow-xs">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <div class="w-2.5 h-2.5 rounded-full bg-amber-400 animate-pulse"></div>
                        <h3 class="text-sm font-bold text-[#1C2434]">Peringatan: Kontrak Segera Berakhir (&le; 30 Hari)
                        </h3>
                    </div>
                    <span
                        class="text-xs font-bold px-2.5 py-0.5 rounded-full {{ $expiringContractsCount > 0 ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800' }}">
                        {{ $expiringContractsCount }} Karyawan
                    </span>
                </div>

                @if ($expiringContracts->isEmpty())
                    <div class="py-8 text-center bg-emerald-50/50 rounded-xl border border-emerald-100 mt-3">
                        <div
                            class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto mb-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                        </div>
                        <p class="text-xs font-bold text-emerald-900">Semua Kontrak Aman</p>
                        <p class="text-[11px] text-emerald-700 mt-0.5">Tidak ada kontrak karyawan yang berakhir dalam kurun
                            waktu 30 hari ke depan.</p>
                    </div>
                @else
                    <div class="overflow-x-auto mt-3">
                        <table class="w-full text-left text-xs">
                            <thead
                                class="bg-slate-50 border-y border-[#E2E8F0] text-slate-500 font-bold uppercase tracking-wider text-[10px]">
                                <tr>
                                    <th class="py-2.5 px-3">Karyawan</th>
                                    <th class="py-2.5 px-3">Tipe</th>
                                    <th class="py-2.5 px-3">Berakhir</th>
                                    <th class="py-2.5 px-3">Sisa Waktu</th>
                                    <th class="py-2.5 px-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#E2E8F0]">
                                @foreach ($expiringContracts as $contract)
                                    @php
                                        $daysRemaining = (int) now()
                                            ->startOfDay()
                                            ->diffInDays($contract->end_date->startOfDay(), false);
                                    @endphp
                                    <tr class="hover:bg-slate-50/80 transition">
                                        <td class="py-2.5 px-3">
                                            <a href="{{ route('employees.show', $contract->employee_id) }}"
                                                class="font-bold text-[#1C2434] hover:text-[#3C50E0]">
                                                {{ $contract->employee->name }}
                                            </a>
                                            <p class="text-[10px] text-slate-400">{{ $contract->position }} &bull;
                                                {{ $contract->branch }}</p>
                                        </td>
                                        <td class="py-2.5 px-3">
                                            <span
                                                class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold {{ $contract->contract_type_badge_color }}">
                                                {{ $contract->contract_type_label }}
                                            </span>
                                        </td>
                                        <td class="py-2.5 px-3 font-semibold text-slate-700">
                                            {{ $contract->end_date->format('d/m/Y') }}
                                        </td>
                                        <td class="py-2.5 px-3">
                                            <span
                                                class="font-bold {{ $daysRemaining <= 7 ? 'text-rose-600' : 'text-amber-600' }}">
                                                {{ $daysRemaining }} Hari Lagi
                                            </span>
                                        </td>
                                        <td class="py-2.5 px-3 text-right">
                                            <a href="{{ route('addendums.create', $contract) }}"
                                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-purple-50 text-purple-700 hover:bg-purple-100 font-bold text-[10px] transition border border-purple-200">
                                                <span>+ Adendum</span>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <!-- Dokumen Terbaru Grid: Offering Letters & Kontrak Kerja -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Offering Letter Terbaru -->
            <div class="p-6 rounded-2xl bg-white border border-[#E2E8F0] shadow-xs">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-sm font-bold text-[#1C2434]">Offering Letter Terbaru</h3>
                        <p class="text-xs text-slate-400">Surat penawaran kerja yang diterbitkan baru-baru ini.</p>
                    </div>
                    <a href="{{ route('offering-letters.index') }}"
                        class="text-xs font-bold text-[#3C50E0] hover:underline">
                        Lihat Semua &rarr;
                    </a>
                </div>

                @if ($recentOfferingLetters->isEmpty())
                    <p class="text-xs text-slate-400 text-center py-6">Belum ada data Offering Letter yang diterbitkan.</p>
                @else
                    <div class="space-y-3">
                        @foreach ($recentOfferingLetters as $ol)
                            <div
                                class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-[#E2E8F0] hover:border-[#3C50E0] transition">
                                <div class="min-w-0 pr-2">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('offering-letters.show', $ol) }}"
                                            class="text-xs font-bold text-[#1C2434] hover:text-[#3C50E0] truncate">
                                            {{ $ol->letter_number ?: 'OL #' . $ol->id }}
                                        </a>
                                        <span
                                            class="inline-block px-1.5 py-0.5 rounded text-[9px] font-bold {{ $ol->status_badge_color }}">
                                            {{ $ol->status_label }}
                                        </span>
                                    </div>
                                    <p class="text-[11px] text-slate-500 mt-0.5 truncate">
                                        {{ $ol->employee->name }} &bull; {{ $ol->position }}
                                    </p>
                                </div>
                                <span class="text-[10px] text-slate-400 font-medium shrink-0">
                                    {{ $ol->offer_date->format('d/m/Y') }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Kontrak Kerja Terbaru -->
            <div class="p-6 rounded-2xl bg-white border border-[#E2E8F0] shadow-xs">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-sm font-bold text-[#1C2434]">Kontrak Kerja Terbaru</h3>
                        <p class="text-xs text-slate-400">Dokumen kontrak kerja yang tercatat di sistem.</p>
                    </div>
                    <a href="{{ route('contracts.index') }}" class="text-xs font-bold text-[#3C50E0] hover:underline">
                        Lihat Semua &rarr;
                    </a>
                </div>

                @if ($recentContracts->isEmpty())
                    <p class="text-xs text-slate-400 text-center py-6">Belum ada kontrak kerja yang tercatat.</p>
                @else
                    <div class="space-y-3">
                        @foreach ($recentContracts as $contract)
                            <div
                                class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-[#E2E8F0] hover:border-[#3C50E0] transition">
                                <div class="min-w-0 pr-2">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('contracts.show', $contract) }}"
                                            class="text-xs font-bold text-[#1C2434] hover:text-[#3C50E0] truncate">
                                            {{ $contract->contract_number ?: 'Kontrak #' . $contract->id }}
                                        </a>
                                        <span
                                            class="inline-block px-1.5 py-0.5 rounded text-[9px] font-bold {{ $contract->contract_type_badge_color }}">
                                            {{ $contract->contract_type_label }}
                                        </span>
                                    </div>
                                    <p class="text-[11px] text-slate-500 mt-0.5 truncate">
                                        {{ $contract->employee->name }} &bull; {{ $contract->position }}
                                    </p>
                                </div>
                                <span class="text-[10px] text-slate-400 font-medium shrink-0">
                                    {{ $contract->start_date->format('d/m/Y') }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
