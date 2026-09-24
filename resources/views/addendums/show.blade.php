@extends('layouts.app')

@section('title', 'Detail Adendum - ' . $addendum->addendum_number)

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Top Breadcrumb & Status -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs text-[#8A99AD] mb-1">
                    <a href="{{ route('employees.index') }}" class="hover:text-[#3C50E0] transition">Karyawan</a>
                    <span>/</span>
                    <a href="{{ route('employees.show', $addendum->employee) }}"
                        class="hover:text-[#3C50E0] transition">{{ $addendum->employee->name }}</a>
                    <span>/</span>
                    <a href="{{ route('addendums.index') }}" class="hover:text-[#3C50E0] transition">Adendum Kontrak</a>
                    <span>/</span>
                    <span class="text-slate-800 font-medium font-mono">{{ $addendum->addendum_number }}</span>
                </div>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-extrabold tracking-tight text-[#1C2434]">
                        Detail Adendum Perjanjian Kerja
                    </h1>
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-purple-50 text-purple-700 border border-purple-200">
                        {{ $addendum->sequence_label }}
                    </span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('addendums.print', $addendum) }}" target="_blank"
                    class="px-3.5 py-2 rounded-xl bg-white border border-[#E2E8F0] hover:border-[#3C50E0] text-slate-700 hover:text-[#3C50E0] text-xs font-semibold transition flex items-center gap-2 shadow-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                        </path>
                    </svg>
                    <span>Cetak Dokumen Adendum</span>
                </a>

                <a href="{{ route('contracts.show', $addendum->contract) }}"
                    class="px-4 py-2 rounded-xl bg-[#3C50E0] text-white hover:bg-[#2F40BD] text-xs font-bold transition flex items-center gap-1.5 shadow-xs">
                    <span>Lihat Kontrak Induk</span>
                </a>
            </div>
        </div>

        <!-- Detail Information Card -->
        <div class="p-6 sm:p-8 rounded-2xl bg-white border border-[#E2E8F0] shadow-xs space-y-6">
            <!-- Header Section -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pb-6 border-b border-[#E2E8F0]">
                <div>
                    <p class="text-xs text-slate-400 font-medium">Informasi Karyawan</p>
                    <h3 class="text-lg font-bold text-[#1C2434] mt-0.5">{{ $addendum->employee->name }}</h3>
                    <p class="text-xs text-slate-500 mt-1 font-mono">NIK: {{ $addendum->employee->ktp_number }}</p>
                    <a href="{{ route('employees.show', $addendum->employee) }}"
                        class="text-xs text-[#3C50E0] hover:underline mt-2 inline-block font-semibold">
                        Lihat Profil Karyawan &rarr;
                    </a>
                </div>

                <div class="sm:text-right">
                    <p class="text-xs text-slate-400 font-medium">Nomor Surat Adendum</p>
                    <p class="text-base font-bold font-mono text-[#3C50E0] mt-0.5">{{ $addendum->addendum_number }}</p>
                    <div class="mt-1 space-y-0.5 text-xs text-slate-600 sm:text-right">
                        @if ($addendum->kode)
                            <p><span class="text-slate-400">Kode Surat:</span> <span
                                    class="font-bold font-mono text-[#3C50E0]">{{ $addendum->kode }}</span></p>
                        @endif
                        <p><span class="text-slate-400">Tanggal Surat:</span> <strong
                                class="text-slate-800">{{ $addendum->issue_date->translatedFormat('d F Y') }}</strong>
                        </p>
                    </div>
                    <p class="text-xs text-slate-500 mt-1">
                        Kontrak Induk: <a href="{{ route('contracts.show', $addendum->contract) }}"
                            class="font-mono text-indigo-600 hover:underline font-bold">#{{ $addendum->contract->contract_number ?: $addendum->contract->id }}</a>
                    </p>
                </div>
            </div>

            <!-- Position, Bidang, Branch, Extension Period -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 pb-6 border-b border-[#E2E8F0]">
                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                    <span class="text-[10px] uppercase font-semibold text-slate-400 block">Posisi / Jabatan</span>
                    <span
                        class="text-xs font-bold text-slate-800 block mt-1">{{ $addendum->new_position ?: ($addendum->previous_position ?: '-') }}</span>
                </div>

                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                    <span class="text-[10px] uppercase font-semibold text-slate-400 block">Bidang</span>
                    <span class="text-xs font-bold text-slate-800 block mt-1">{{ $addendum->bidang ?: '-' }}</span>
                </div>

                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                    <span class="text-[10px] uppercase font-semibold text-slate-400 block">Cabang / Penempatan</span>
                    <span class="text-xs font-bold text-slate-800 block mt-1">{{ $addendum->branch ?: '-' }}</span>
                </div>

                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                    <span class="text-[10px] uppercase font-semibold text-slate-400 block">Periode Perpanjangan</span>
                    <span class="text-xs font-bold text-emerald-700 block mt-1">
                        {{ $addendum->effective_date->translatedFormat('d M Y') }} s/d
                        {{ $addendum->new_end_date->translatedFormat('d M Y') }}
                    </span>
                    <span class="text-[11px] text-slate-400 block mt-0.5">Sebelumnya:
                        {{ $addendum->previous_end_date->translatedFormat('d M Y') }}</span>
                </div>
            </div>

            <!-- Penandatangan & Alamat Kantor -->
            <div class="pb-6 border-b border-[#E2E8F0]">
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4">Penandatangan & Alamat Kantor
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-[10px] uppercase font-semibold text-slate-400 block">Nama & Jabatan Atasan</span>
                        <span
                            class="text-sm font-bold text-slate-800 block mt-1">{{ $addendum->supervisor_name ?: '-' }}</span>
                        <span
                            class="text-xs text-slate-500 block mt-0.5">{{ $addendum->supervisor_position ?: '-' }}</span>
                    </div>

                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-[10px] uppercase font-semibold text-slate-400 block">Alamat Kantor</span>
                        <span
                            class="text-xs text-slate-700 block mt-1 leading-relaxed">{{ $addendum->office_address ?: '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- Comparison Table: Sebelum vs Sesudah -->
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">
                    Perbandingan Perubahan Ketentuan Kontrak
                </h4>

                <div class="overflow-x-auto border border-[#E2E8F0] rounded-xl">
                    <table class="w-full text-left text-xs">
                        <thead
                            class="bg-[#F8FAFC] text-[#8A99AD] uppercase font-bold text-[10px] border-b border-[#E2E8F0]">
                            <tr>
                                <th class="py-2.5 px-4">Klausul Perubahan</th>
                                <th class="py-2.5 px-4 text-slate-500">Ketentuan Sebelumnya</th>
                                <th class="py-2.5 px-4 text-emerald-700 bg-emerald-50/50">Ketentuan Baru (Adendum)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#E2E8F0]">
                            <!-- Masa Berlaku -->
                            <tr>
                                <td class="py-3 px-4 font-bold text-[#1C2434]">Masa Berakhir Kontrak</td>
                                <td class="py-3 px-4 text-slate-500 line-through">
                                    {{ $addendum->previous_end_date->format('d F Y') }}</td>
                                <td class="py-3 px-4 font-bold text-emerald-700 bg-emerald-50/25">
                                    {{ $addendum->new_end_date->format('d F Y') }}
                                    <span class="block text-[10px] font-normal text-slate-500">Efektif sejak
                                        {{ $addendum->effective_date->format('d/m/Y') }}</span>
                                </td>
                            </tr>

                            <!-- Jabatan -->
                            <tr>
                                <td class="py-3 px-4 font-bold text-[#1C2434]">Jabatan / Posisi</td>
                                <td class="py-3 px-4 text-slate-500">{{ $addendum->previous_position ?: '-' }}</td>
                                <td class="py-3 px-4 font-bold text-slate-800 bg-emerald-50/25">
                                    {{ $addendum->new_position ?: $addendum->previous_position }}
                                </td>
                            </tr>

                            <!-- Gaji Pokok -->
                            <tr>
                                <td class="py-3 px-4 font-bold text-[#1C2434]">Gaji Pokok / Saku</td>
                                <td class="py-3 px-4 text-slate-500">
                                    {{ $addendum->formatted_previous_salary ?: 'Belum diatur' }}</td>
                                <td class="py-3 px-4 font-bold text-emerald-700 bg-emerald-50/25">
                                    {{ $addendum->formatted_new_salary ?: ($addendum->formatted_previous_salary ?: '-') }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Amendment Reason -->
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Pokok Alasan Perubahan</h4>
                <p class="text-sm font-semibold text-[#1C2434] bg-slate-50 p-3 rounded-xl border border-slate-200">
                    {{ $addendum->amendment_reason }}
                </p>
            </div>

            <!-- Clause Changes -->
            @if ($addendum->clause_changes)
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Klausul / Pasal yang Diubah
                    </h4>
                    <div
                        class="p-4 rounded-xl bg-[#F8FAFC] border border-[#E2E8F0] text-xs text-slate-700 whitespace-pre-line leading-relaxed">
                        {{ $addendum->clause_changes }}
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
