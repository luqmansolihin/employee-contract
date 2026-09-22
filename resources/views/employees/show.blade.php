@extends('layouts.app')

@section('title', $employee->name . ' - Detail Karyawan')

@section('content')
    <div class="space-y-6">
        <!-- Header & Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl font-extrabold tracking-tight text-[#1C2434]">
                    <span class="text-slate-400">SI-KONTRAK /</span> DETAIL EMPLOYEE
                </h1>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <a href="{{ route('employees.index') }}"
                    class="px-4 py-2 text-xs font-bold rounded-xl border border-[#E2E8F0] text-slate-700 hover:bg-slate-100 transition bg-white shadow-xs">
                    &larr; Kembali
                </a>

                <!-- Edit Biodata -->
                <a href="{{ route('employees.edit', $employee) }}"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-bold rounded-xl border border-[#E2E8F0] bg-white text-slate-700 hover:bg-slate-50 shadow-xs transition"
                    title="Ubah Biodata Pribadi">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>
                    <span>Edit</span>
                </a>

                <!-- Delete (Super Admin only) -->
                @can('delete', $employee)
                    <form action="{{ route('employees.destroy', $employee) }}" method="POST"
                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus data karyawan {{ $employee->name }} beserta seluruh riwayat dokumennya?')"
                        class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="p-2 text-xs font-bold rounded-xl border border-rose-200 text-rose-600 hover:bg-rose-50 transition bg-white shadow-xs"
                            title="Hapus Karyawan (Super Admin)">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                </path>
                            </svg>
                        </button>
                    </form>
                @endcan
            </div>
        </div>

        <!-- Profile Header Card -->
        <div class="p-6 rounded-2xl bg-white border border-[#E2E8F0] shadow-xs">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div
                        class="w-16 h-16 rounded-2xl {{ $employee->gender === 'Laki-laki' ? 'bg-[#3C50E0]' : 'bg-pink-600' }} text-white font-extrabold text-xl flex items-center justify-center shadow-lg shadow-[#3C50E0]/20 shrink-0">
                        {{ strtoupper(substr($employee->name, 0, 2)) }}
                    </div>
                    <div>
                        <div class="flex items-center gap-3 flex-wrap">
                            <h2 class="text-xl font-bold text-[#1C2434]">{{ $employee->name }}</h2>
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $employee->status_badge_class }}">
                                {{ $employee->status_label }}
                            </span>
                            @if ($employee->latestContract)
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $employee->latestContract->contract_type_badge_class }}">
                                    {{ $employee->latestContract->contract_type }}
                                </span>
                            @endif
                        </div>
                        <div class="flex items-center gap-4 text-xs text-slate-500 mt-1.5 flex-wrap">
                            <span
                                class="font-bold text-slate-800">{{ $employee->current_position ?: 'Belum Ada Kontrak' }}</span>
                            <span>&bull;</span>
                            <span>{{ $employee->current_branch ? 'Cabang ' . $employee->current_branch : '-' }}</span>
                            <span>&bull;</span>
                            <span class="font-mono bg-slate-100 px-2 py-0.5 rounded-md text-slate-700">NIK:
                                {{ $employee->ktp_number }}</span>
                        </div>
                    </div>
                </div>

                <div class="text-right sm:border-l sm:border-[#E2E8F0] sm:pl-6 w-full sm:w-auto">
                    <p class="text-xs uppercase font-bold text-slate-400 tracking-wider">Sisa Durasi Kontrak</p>
                    <p
                        class="text-lg font-extrabold mt-0.5 {{ $employee->status === 'expired' ? 'text-rose-600' : ($employee->status === 'expiring_soon' ? 'text-amber-700' : 'text-emerald-700') }}">
                        {{ $employee->remaining_days_text }}
                    </p>
                </div>
            </div>
        </div>

        <!-- 2-Column Biodata & Detail Kepegawaian -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Section 1: Data Pribadi -->
            <div class="bg-white rounded-2xl border border-[#E2E8F0] shadow-xs overflow-hidden">
                <div class="px-6 py-4 border-b border-[#E2E8F0] bg-[#F7F9FC] flex items-center gap-2">
                    <div class="w-6 h-6 rounded-lg bg-indigo-50 text-[#3C50E0] flex items-center justify-center">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xs font-bold text-[#1C2434] uppercase tracking-wider">Biodata Pribadi</h3>
                </div>

                <div class="p-6 divide-y divide-slate-100 text-xs">
                    <div class="py-2.5 flex justify-between gap-4">
                        <span class="text-slate-500">Nama Lengkap</span>
                        <span class="font-bold text-slate-900 text-right">{{ $employee->name }}</span>
                    </div>
                    <div class="py-2.5 flex justify-between gap-4">
                        <span class="text-slate-500">Nomor KTP (NIK)</span>
                        <span class="font-mono font-bold text-slate-900 text-right">{{ $employee->ktp_number }}</span>
                    </div>
                    <div class="py-2.5 flex justify-between gap-4">
                        <span class="text-slate-500">Jenis Kelamin</span>
                        <span class="font-semibold text-slate-900 text-right">{{ $employee->gender }}</span>
                    </div>
                    <div class="py-2.5 flex justify-between gap-4">
                        <span class="text-slate-500">Tempat, Tanggal Lahir</span>
                        <span class="font-semibold text-slate-900 text-right">
                            {{ $employee->birth_place }}, {{ $employee->birth_date->format('d M Y') }}
                            <span class="text-slate-400 font-normal">({{ $employee->age }} tahun)</span>
                        </span>
                    </div>
                    <div class="pt-2.5">
                        <span class="text-slate-500 block mb-1">Alamat Lengkap Domisili</span>
                        <p
                            class="font-medium text-slate-800 bg-[#F7F9FC] p-3 rounded-xl border border-[#E2E8F0] leading-relaxed">
                            {{ $employee->address }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Section 2: Data Kepegawaian & Penempatan -->
            <div class="bg-white rounded-2xl border border-[#E2E8F0] shadow-xs overflow-hidden">
                <div class="px-6 py-4 border-b border-[#E2E8F0] bg-[#F7F9FC] flex items-center gap-2">
                    <div class="w-6 h-6 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xs font-bold text-[#1C2434] uppercase tracking-wider">Penempatan & Status</h3>
                </div>

                <div class="p-6 divide-y divide-slate-100 text-xs">
                    <div class="py-2.5 flex justify-between gap-4">
                        <span class="text-slate-500">Jabatan Terkini</span>
                        <span class="font-bold text-slate-900 text-right">{{ $employee->current_position ?: '-' }}</span>
                    </div>
                    <div class="py-2.5 flex justify-between gap-4">
                        <span class="text-slate-500">Cabang Penempatan</span>
                        <span class="font-semibold text-slate-900 text-right">{{ $employee->current_branch ?: '-' }}</span>
                    </div>
                    <div class="py-2.5 flex justify-between gap-4">
                        <span class="text-slate-500">Tanggal Pertama Bergabung</span>
                        <span
                            class="font-semibold text-slate-900 text-right">{{ $employee->first_join_date?->format('d F Y') ?: '-' }}</span>
                    </div>
                    <div class="py-2.5 flex justify-between gap-4">
                        <span class="text-slate-500">Akhir Kontrak Terkini</span>
                        <span
                            class="font-semibold text-slate-900 text-right">{{ $employee->current_contract_end_date?->format('d F Y') ?: '-' }}</span>
                    </div>
                    <div class="py-2.5 flex justify-between gap-4 text-slate-400">
                        <span>Terdaftar pada</span>
                        <span>{{ $employee->created_at->format('d M Y, H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section: Riwayat Offering Letter (Surat Penawaran) -->
        <div class="bg-white rounded-2xl border border-[#E2E8F0] shadow-xs overflow-hidden">
            <div class="px-6 py-4 border-b border-[#E2E8F0] bg-[#F7F9FC] flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xs font-bold text-[#1C2434] uppercase tracking-wider">
                        Surat Penawaran Kerja (Offering Letter)
                    </h3>
                </div>
                <a href="{{ route('offering-letters.create', $employee) }}"
                    class="text-xs font-bold text-[#3C50E0] hover:underline">
                    + Buat Offering Letter
                </a>
            </div>

            <div class="p-6">
                @if ($employee->offeringLetters->isNotEmpty())
                    <div class="divide-y divide-[#E2E8F0] border border-[#E2E8F0] rounded-xl overflow-hidden text-xs">
                        @foreach ($employee->offeringLetters as $ol)
                            <div
                                class="p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 hover:bg-slate-50/50 transition">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span
                                            class="font-mono font-bold text-xs text-[#1C2434]">{{ $ol->letter_number }}</span>
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold border {{ $ol->contract_type_badge_class }}">
                                            {{ $ol->contract_type }}
                                        </span>
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold border {{ $ol->status_badge_class }}">
                                            {{ $ol->status_label }}
                                        </span>
                                    </div>
                                    <p class="text-slate-600">
                                        Jabatan: <strong>{{ $ol->position }}</strong> ({{ $ol->branch }}) &bull;
                                        Kompensasi: <strong>{{ $ol->formatted_total_compensation }}</strong>
                                    </p>
                                    <p class="text-[11px] text-slate-400">
                                        Tgl Penawaran: {{ $ol->offer_date->format('d M Y') }} &bull; Rencana Mulai:
                                        {{ $ol->proposed_start_date->format('d M Y') }}
                                    </p>
                                </div>

                                <div class="flex items-center gap-2 shrink-0">
                                    <a href="{{ route('offering-letters.print', $ol) }}" target="_blank"
                                        class="px-2.5 py-1.5 rounded-lg border border-[#E2E8F0] text-slate-700 hover:text-emerald-600 hover:border-emerald-300 text-xs font-semibold transition flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                            </path>
                                        </svg>
                                        <span>Cetak</span>
                                    </a>

                                    <a href="{{ route('offering-letters.show', $ol) }}"
                                        class="px-2.5 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition">
                                        Detail
                                    </a>

                                    @if (!$ol->contract)
                                        <a href="{{ route('contracts.create', ['offering_letter_id' => $ol->id]) }}"
                                            class="px-3 py-1.5 rounded-lg bg-[#3C50E0] hover:bg-[#2F40BD] text-white text-xs font-bold transition shadow-xs">
                                            Terbitkan Kontrak
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div
                        class="text-center py-6 text-slate-400 bg-[#F8FAFC] rounded-xl border border-dashed border-slate-200 text-xs">
                        Belum ada Offering Letter diterbitkan untuk karyawan ini.
                    </div>
                @endif
            </div>
        </div>

        <!-- Section: Riwayat Kontrak Kerja & Adendum -->
        <div class="bg-white rounded-2xl border border-[#E2E8F0] shadow-xs overflow-hidden">
            <div class="px-6 py-4 border-b border-[#E2E8F0] bg-[#F7F9FC] flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-[#3C50E0] text-white flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xs font-bold text-[#1C2434] uppercase tracking-wider">
                        Riwayat Seluruh Kontrak Kerja
                    </h3>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('contracts.create', ['employee_id' => $employee->id]) }}"
                        class="text-xs font-bold text-[#3C50E0] hover:underline">
                        + Terbitkan Kontrak
                    </a>
                </div>
            </div>

            <div class="p-6">
                <div class="relative border-l-2 border-[#E2E8F0] ml-4 pl-6 space-y-6">
                    @forelse ($employee->contracts as $contract)
                        <div class="relative group">
                            <!-- Timeline bullet node -->
                            <div
                                class="absolute -left-[31px] top-1 w-4 h-4 rounded-full border-2 border-white {{ $loop->first ? 'bg-[#3C50E0] ring-4 ring-[#3C50E0]/20' : 'bg-slate-300' }}">
                            </div>

                            <div
                                class="p-4 rounded-xl border border-[#E2E8F0] bg-[#F7F9FC]/60 hover:bg-[#F7F9FC] transition space-y-3">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="font-bold text-[#1C2434] text-sm">
                                            {{ $contract->sequence_label }}
                                        </span>
                                        @if ($contract->contract_number)
                                            <span class="font-mono text-xs text-slate-500 font-semibold">
                                                ({{ $contract->contract_number }})
                                            </span>
                                        @endif
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold border {{ $contract->contract_type_badge_class }}">
                                            {{ $contract->contract_type }}
                                        </span>
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold {{ $contract->status_badge_class }}">
                                            {{ $contract->status_label }}
                                        </span>
                                        @if ($loop->first)
                                            <span
                                                class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-indigo-100 text-[#3C50E0]">
                                                Kontrak Terkini
                                            </span>
                                        @endif
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('contracts.print', $contract) }}" target="_blank"
                                            class="px-2.5 py-1 rounded-lg border border-[#E2E8F0] hover:border-emerald-300 text-slate-700 hover:text-emerald-600 text-xs font-semibold transition flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                                </path>
                                            </svg>
                                            <span>Cetak Kontrak</span>
                                        </a>

                                        <a href="{{ route('addendums.create', $contract) }}"
                                            class="px-2.5 py-1 rounded-lg bg-purple-50 hover:bg-purple-100 text-purple-700 border border-purple-200 text-xs font-semibold transition">
                                            + Adendum
                                        </a>

                                        <a href="{{ route('contracts.show', $contract) }}"
                                            class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition">
                                            Detail
                                        </a>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs text-slate-600 pt-1">
                                    <div>
                                        <span class="text-slate-400 block text-[11px]">Periode Masa Kerja:</span>
                                        <strong class="text-slate-800">{{ $contract->start_date->format('d M Y') }}
                                            &mdash; {{ $contract->end_date->format('d M Y') }}</strong>
                                        <span
                                            class="text-slate-400 block mt-0.5 text-[11px]">(~{{ $contract->duration_in_months }}
                                            Bulan)</span>
                                    </div>
                                    <div>
                                        <span class="text-slate-400 block text-[11px]">Jabatan & Lokasi:</span>
                                        <strong class="text-slate-800">{{ $contract->position }}</strong>
                                        <span class="text-slate-500 block mt-0.5 text-[11px]">Cabang
                                            {{ $contract->branch }}</span>
                                    </div>
                                    <div>
                                        <span class="text-slate-400 block text-[11px]">Gaji & Tunjangan:</span>
                                        <strong
                                            class="text-slate-800">{{ $contract->formatted_salary ?: 'Rp 0' }}</strong>
                                        <span
                                            class="block mt-0.5 font-medium {{ $contract->calculated_status === 'expired' ? 'text-rose-600' : 'text-emerald-600' }}">
                                            {{ $contract->remaining_days_text }}
                                        </span>
                                    </div>
                                </div>

                                @if ($contract->notes)
                                    <div
                                        class="mt-2 pt-2 border-t border-[#E2E8F0] text-xs text-slate-600 bg-white p-2.5 rounded-lg border border-slate-100">
                                        <span class="font-bold text-slate-700">Catatan:</span> {{ $contract->notes }}
                                    </div>
                                @endif

                                <!-- Child Addendums Sub-List -->
                                @if ($contract->addendums->isNotEmpty())
                                    <div class="mt-3 pt-3 border-t border-[#E2E8F0] space-y-2">
                                        <span class="text-[11px] font-bold uppercase tracking-wider text-purple-700 block">
                                            Adendum Kontrak Terbit ({{ $contract->addendums->count() }}):
                                        </span>
                                        @foreach ($contract->addendums as $ad)
                                            <div
                                                class="p-2.5 rounded-lg bg-white border border-purple-100 flex items-center justify-between gap-2 text-xs">
                                                <div>
                                                    <span
                                                        class="font-mono font-bold text-slate-800">{{ $ad->addendum_number }}</span>
                                                    <span
                                                        class="text-[10px] font-semibold text-purple-700 bg-purple-50 px-1.5 py-0.5 rounded-sm ml-1">{{ $ad->sequence_label }}</span>
                                                    <span class="text-slate-500 block text-[11px] mt-0.5">
                                                        {{ $ad->amendment_reason }} &bull; Perpanjangan s/d
                                                        <strong>{{ $ad->new_end_date->format('d/m/Y') }}</strong>
                                                    </span>
                                                </div>
                                                <div class="flex items-center gap-1 shrink-0">
                                                    <a href="{{ route('addendums.print', $ad) }}" target="_blank"
                                                        class="p-1 rounded text-slate-400 hover:text-emerald-600"
                                                        title="Cetak Adendum">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                                            </path>
                                                        </svg>
                                                    </a>
                                                    <a href="{{ route('addendums.show', $ad) }}"
                                                        class="p-1 rounded text-slate-400 hover:text-[#3C50E0]"
                                                        title="Lihat Detail">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                        </svg>
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="p-4 text-center text-slate-400 text-xs">
                            Belum ada kontrak resmi yang diterbitkan untuk karyawan ini.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
