@extends('layouts.app')

@section('title', 'Edit Offering Letter - ' . $offeringLetter->letter_number)

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Breadcrumb -->
        <div class="flex items-center gap-2 text-xs text-[#8A99AD]">
            <a href="{{ route('employees.index') }}" class="hover:text-[#3C50E0] transition">Karyawan</a>
            <span>/</span>
            <a href="{{ route('offering-letters.index') }}" class="hover:text-[#3C50E0] transition">Offering Letter</a>
            <span>/</span>
            <a href="{{ route('offering-letters.show', $offeringLetter) }}"
                class="hover:text-[#3C50E0] transition">{{ $offeringLetter->letter_number }}</a>
            <span>/</span>
            <span class="text-slate-800 font-medium">Ubah Data</span>
        </div>

        <!-- Form Card -->
        <div class="p-6 sm:p-8 rounded-2xl bg-white border border-[#E2E8F0] shadow-xs">
            <form action="{{ route('offering-letters.update', $offeringLetter) }}" method="POST" class="space-y-6"
                autocomplete="off">
                @csrf
                @method('PUT')

                <!-- Section 1: Karyawan Penerima Penawaran -->
                <div>
                    <div class="flex items-center justify-between mb-4 pb-2 border-b border-[#E2E8F0]">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-[#3C50E0]">
                            1. Karyawan Penerima Penawaran
                        </h4>
                        @if ($offeringLetter->status === 'draft')
                            <span
                                class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                Draft (Dapat Diganti)
                            </span>
                        @else
                            <span
                                class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-500 border border-slate-200">
                                Terkunci (Status: {{ $offeringLetter->status_label }})
                            </span>
                        @endif
                    </div>

                    @if ($offeringLetter->status === 'draft')
                        <div class="space-y-3">
                            <div class="relative" id="employee-combobox-wrapper">
                                <label for="employee_search_input" class="block text-xs font-bold text-[#1C2434] mb-1">
                                    Pilih / Ganti Karyawan <span class="text-rose-500">*</span>
                                </label>

                                <!-- Hidden input for form submission -->
                                <input type="hidden" name="employee_id" id="employee_id"
                                    value="{{ old('employee_id', $offeringLetter->employee_id) }}">

                                <!-- Search Input with Icons -->
                                <div class="relative">
                                    <div
                                        class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </div>
                                    <input type="text" id="employee_search_input" autocomplete="off"
                                        placeholder="Ketik nama, NIK, atau posisi karyawan..."
                                        class="w-full pl-10 pr-16 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('employee_id') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] outline-hidden transition placeholder:text-slate-400">
                                    <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center gap-1">
                                        <button type="button" id="employee_clear_btn"
                                            class="hidden text-slate-400 hover:text-rose-600 p-1 rounded-full hover:bg-slate-200 transition"
                                            title="Hapus pilihan">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                        <button type="button" id="employee_dropdown_toggle"
                                            class="text-slate-400 hover:text-slate-600 p-1" tabindex="-1"
                                            title="Buka daftar">
                                            <svg class="w-4 h-4 transition-transform duration-200"
                                                id="employee_chevron_icon" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Dropdown Menu List -->
                                <div id="employee_dropdown_menu"
                                    class="hidden mt-2 w-full bg-white rounded-xl shadow-xs border border-slate-200 max-h-56 overflow-y-auto divide-y divide-slate-100">
                                    @foreach ($employees as $emp)
                                        <div class="employee-item px-3.5 py-2.5 cursor-pointer hover:bg-indigo-50/70 transition flex items-center justify-between text-xs"
                                            data-id="{{ $emp->id }}"
                                            data-search="{{ strtolower($emp->name . ' ' . $emp->ktp_number . ' ' . $emp->current_position . ' ' . $emp->current_branch) }}">
                                            <div>
                                                <div class="font-semibold text-slate-800">{{ $emp->name }}</div>
                                                <div
                                                    class="text-[11px] text-slate-500 flex flex-wrap items-center gap-x-2 gap-y-0.5 mt-0.5">
                                                    <span class="font-mono text-slate-400">NIK:
                                                        {{ $emp->ktp_number }}</span>
                                                    @if ($emp->current_position)
                                                        <span class="text-slate-300">&bull;</span>
                                                        <span
                                                            class="font-medium text-slate-600">{{ $emp->current_position }}</span>
                                                    @endif
                                                    @if ($emp->current_branch)
                                                        <span class="text-slate-300">&bull;</span>
                                                        <span class="text-slate-400">{{ $emp->current_branch }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="employee-check-icon hidden text-[#3C50E0] pl-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                        d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    @endforeach
                                    <div id="employee_no_results"
                                        class="hidden px-4 py-4 text-center text-xs text-slate-400">
                                        Tidak ada karyawan yang cocok dengan pencarian
                                    </div>
                                </div>

                                @error('employee_id')
                                    <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Selected Employee Detail Card -->
                            <div id="selected_employee_card"
                                class="p-4 rounded-xl bg-slate-50 border border-slate-200 transition-all duration-200">
                                <div class="flex items-start justify-between gap-3 pb-3 border-b border-slate-200">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-[#3C50E0]/10 text-[#3C50E0] flex items-center justify-center font-bold text-sm shrink-0"
                                            id="detail_emp_avatar">
                                            {{ strtoupper(substr($offeringLetter->employee->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <h5 class="text-sm font-bold text-[#1C2434]" id="detail_emp_name">
                                                {{ $offeringLetter->employee->name }}
                                            </h5>
                                            <p class="text-xs text-slate-500 font-mono" id="detail_emp_ktp">
                                                NIK: {{ $offeringLetter->employee->ktp_number }}
                                            </p>
                                        </div>
                                    </div>
                                    <span
                                        class="px-2.5 py-1 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-600 border border-emerald-200">
                                        Karyawan Terpilih
                                    </span>
                                </div>
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-3 text-xs">
                                    <div>
                                        <span class="text-slate-400 block text-[10px] uppercase font-semibold">Posisi /
                                            Jabatan</span>
                                        <span class="font-bold text-slate-700" id="detail_emp_position">
                                            {{ $offeringLetter->employee->current_position ?: '-' }}
                                        </span>
                                    </div>
                                    <div>
                                        <span class="text-slate-400 block text-[10px] uppercase font-semibold">Cabang /
                                            Lokasi</span>
                                        <span class="font-bold text-slate-700" id="detail_emp_branch">
                                            {{ $offeringLetter->employee->current_branch ?: '-' }}
                                        </span>
                                    </div>
                                    <div>
                                        <span class="text-slate-400 block text-[10px] uppercase font-semibold">Jenis
                                            Kelamin</span>
                                        <span class="font-bold text-slate-700" id="detail_emp_gender">
                                            {{ in_array(strtolower($offeringLetter->employee->gender ?? ''), ['laki-laki', 'male', 'l']) ? 'Laki-laki' : (in_array(strtolower($offeringLetter->employee->gender ?? ''), ['perempuan', 'female', 'p']) ? 'Perempuan' : ($offeringLetter->employee->gender ?: '-')) }}
                                        </span>
                                    </div>
                                    <div>
                                        <span class="text-slate-400 block text-[10px] uppercase font-semibold">Tgl Pertama
                                            Masuk</span>
                                        <span class="font-bold text-slate-700" id="detail_emp_join_date">
                                            {{ $offeringLetter->employee->first_join_date ? $offeringLetter->employee->first_join_date->format('d M Y') : '-' }}
                                        </span>
                                    </div>
                                    <div class="sm:col-span-2">
                                        <span class="text-slate-400 block text-[10px] uppercase font-semibold">Tempat,
                                            Tanggal Lahir</span>
                                        <span class="font-medium text-slate-700" id="detail_emp_birth">
                                            {{ $offeringLetter->employee->birth_place ? $offeringLetter->employee->birth_place . ($offeringLetter->employee->birth_date ? ', ' . $offeringLetter->employee->birth_date->format('d M Y') : '') : '-' }}
                                        </span>
                                    </div>
                                    <div class="sm:col-span-2">
                                        <span
                                            class="text-slate-400 block text-[10px] uppercase font-semibold">Alamat</span>
                                        <span class="font-medium text-slate-700 truncate block" id="detail_emp_address"
                                            title="{{ $offeringLetter->employee->address }}">{{ $offeringLetter->employee->address ?: '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Locked Employee Card for Sent / Accepted / Rejected -->
                        <input type="hidden" name="employee_id" value="{{ $offeringLetter->employee_id }}">

                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                            <div class="flex items-start justify-between gap-4 pb-3 border-b border-slate-200">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-[#3C50E0]/10 text-[#3C50E0] flex items-center justify-center font-bold text-sm shrink-0">
                                        {{ strtoupper(substr($offeringLetter->employee->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <h5 class="text-sm font-bold text-[#1C2434]">{{ $offeringLetter->employee->name }}
                                        </h5>
                                        <p class="text-xs text-slate-500 font-mono">NIK:
                                            {{ $offeringLetter->employee->ktp_number }}</p>
                                    </div>
                                </div>
                                <span
                                    class="px-2.5 py-1 rounded-full text-[10px] font-semibold bg-slate-200 text-slate-600">
                                    Terkunci
                                </span>
                            </div>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-3 text-xs">
                                <div>
                                    <span class="text-slate-400 block text-[10px] uppercase font-semibold">Posisi /
                                        Jabatan</span>
                                    <span
                                        class="font-bold text-slate-700">{{ $offeringLetter->employee->current_position ?: '-' }}</span>
                                </div>
                                <div>
                                    <span class="text-slate-400 block text-[10px] uppercase font-semibold">Cabang /
                                        Lokasi</span>
                                    <span
                                        class="font-bold text-slate-700">{{ $offeringLetter->employee->current_branch ?: '-' }}</span>
                                </div>
                                <div>
                                    <span class="text-slate-400 block text-[10px] uppercase font-semibold">Jenis
                                        Kelamin</span>
                                    <span class="font-bold text-slate-700">
                                        {{ in_array(strtolower($offeringLetter->employee->gender ?? ''), ['laki-laki', 'male', 'l']) ? 'Laki-laki' : (in_array(strtolower($offeringLetter->employee->gender ?? ''), ['perempuan', 'female', 'p']) ? 'Perempuan' : ($offeringLetter->employee->gender ?: '-')) }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-slate-400 block text-[10px] uppercase font-semibold">Tgl Pertama
                                        Masuk</span>
                                    <span class="font-bold text-slate-700">
                                        {{ $offeringLetter->employee->first_join_date ? $offeringLetter->employee->first_join_date->format('d M Y') : '-' }}
                                    </span>
                                </div>
                                <div class="sm:col-span-2">
                                    <span class="text-slate-400 block text-[10px] uppercase font-semibold">Tempat, Tanggal
                                        Lahir</span>
                                    <span class="font-medium text-slate-700">
                                        {{ $offeringLetter->employee->birth_place ? $offeringLetter->employee->birth_place . ($offeringLetter->employee->birth_date ? ', ' . $offeringLetter->employee->birth_date->format('d M Y') : '') : '-' }}
                                    </span>
                                </div>
                                <div class="sm:col-span-2">
                                    <span class="text-slate-400 block text-[10px] uppercase font-semibold">Alamat</span>
                                    <span class="font-medium text-slate-700 truncate block"
                                        title="{{ $offeringLetter->employee->address }}">{{ $offeringLetter->employee->address ?: '-' }}</span>
                                </div>
                            </div>
                            <p
                                class="text-[11px] text-slate-500 mt-3 pt-2 border-t border-slate-200/80 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Karyawan penerima penawaran tidak dapat diganti karena status surat penawaran sudah
                                    <strong>{{ $offeringLetter->status_label }}</strong>.</span>
                            </p>
                        </div>
                    @endif
                </div>

                <!-- Section 2: Informasi Surat & Rencana Kontrak -->
                <div>
                    <h4
                        class="text-xs font-bold uppercase tracking-wider text-[#3C50E0] mb-4 pb-2 border-b border-[#E2E8F0]">
                        2. Informasi Surat & Rencana Kontrak
                    </h4>

                    @php
                        $rawLetterNumber = old('letter_number', $offeringLetter->letter_number);
                        $currentKode = old('kode', $offeringLetter->kode);
                        $cleanKode = strtoupper(trim((string) $currentKode));
                        if ($cleanKode !== '' && str_ends_with($rawLetterNumber, '/' . $cleanKode)) {
                            $displayLetterNumber = substr($rawLetterNumber, 0, -strlen('/' . $cleanKode));
                        } else {
                            $displayLetterNumber = $rawLetterNumber;
                        }
                    @endphp

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Nomor Surat -->
                        <div>
                            <label for="letter_number" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Nomor Surat Penawaran <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="letter_number" id="letter_number"
                                value="{{ $displayLetterNumber }}" required autocomplete="off"
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('letter_number') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] font-mono outline-hidden transition">
                            <p class="text-[11px] text-slate-500 mt-1 font-mono">
                                Nomor Surat Lengkap: <span id="full_number_preview"
                                    class="font-bold text-[#3C50E0]">{{ $rawLetterNumber }}</span>
                            </p>
                            @error('letter_number')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kode -->
                        <div>
                            <label for="kode" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Kode <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="kode" id="kode"
                                value="{{ old('kode', $offeringLetter->kode) }}" required autocomplete="off"
                                placeholder="Contoh: HRD, CKU, OPS"
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('kode') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] font-mono uppercase outline-hidden transition">
                            @error('kode')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal Surat -->
                        <div>
                            <label for="offer_date" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Tanggal Surat <span class="text-rose-500">*</span>
                            </label>
                            <input type="date" name="offer_date" id="offer_date"
                                value="{{ old('offer_date', $offeringLetter->offer_date->format('Y-m-d')) }}" required
                                autocomplete="off"
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('offer_date') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] outline-hidden transition">
                            @error('offer_date')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Bidang -->
                        <div>
                            <label for="bidang" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Bidang <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="bidang" id="bidang"
                                value="{{ old('bidang', $offeringLetter->bidang) }}" required autocomplete="off"
                                placeholder="Contoh: Operasional, IT, Keuangan"
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('bidang') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] outline-hidden transition">
                            @error('bidang')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Posisi / Jabatan -->
                        <div>
                            <label for="position" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Posisi / Jabatan Ditawarkan <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="position" id="position"
                                value="{{ old('position', $offeringLetter->position) }}" required autocomplete="off"
                                placeholder="Contoh: Staff Akuntansi"
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('position') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] outline-hidden transition">
                            @error('position')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Cabang / Lokasi Penempatan -->
                        <div>
                            <label for="branch" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Cabang / Lokasi Penempatan <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="branch" id="branch"
                                value="{{ old('branch', $offeringLetter->branch) }}" required autocomplete="off"
                                placeholder="Contoh: Jakarta Pusat"
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('branch') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] outline-hidden transition">
                            @error('branch')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal Mulai Kontrak -->
                        <div>
                            <label for="proposed_start_date" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Tanggal Awal Kontrak <span class="text-rose-500">*</span>
                            </label>
                            <input type="date" name="proposed_start_date" id="proposed_start_date"
                                value="{{ old('proposed_start_date', $offeringLetter->proposed_start_date->format('Y-m-d')) }}"
                                required autocomplete="off"
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('proposed_start_date') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] outline-hidden transition">
                            @error('proposed_start_date')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal Akhir Kontrak -->
                        <div>
                            <label for="proposed_end_date" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Tanggal Akhir Kontrak <span class="text-rose-500">*</span>
                            </label>
                            <input type="date" name="proposed_end_date" id="proposed_end_date"
                                value="{{ old('proposed_end_date', $offeringLetter->proposed_end_date->format('Y-m-d')) }}"
                                required autocomplete="off"
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('proposed_end_date') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] outline-hidden transition">
                            @error('proposed_end_date')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nama Atasan -->
                        <div>
                            <label for="supervisor_name" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Nama Atasan <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="supervisor_name" id="supervisor_name"
                                value="{{ old('supervisor_name', $offeringLetter->supervisor_name) }}" required
                                autocomplete="off" placeholder="Contoh: Hendra Wijaya, S.Psi."
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('supervisor_name') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] outline-hidden transition">
                            @error('supervisor_name')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Jabatan Atasan -->
                        <div>
                            <label for="supervisor_position" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Jabatan Atasan <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="supervisor_position" id="supervisor_position"
                                value="{{ old('supervisor_position', $offeringLetter->supervisor_position) }}" required
                                autocomplete="off" placeholder="Contoh: Human Resources Manager"
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('supervisor_position') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] outline-hidden transition">
                            @error('supervisor_position')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Alamat Kantor -->
                        <div class="sm:col-span-2">
                            <label for="office_address" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Alamat Kantor <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="office_address" id="office_address"
                                value="{{ old('office_address', $offeringLetter->office_address) }}" required
                                autocomplete="off"
                                placeholder="Contoh: Gedung Perkantoran Sudirman Central, Lantai 12, Jakarta Pusat"
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('office_address') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] outline-hidden transition">
                            @error('office_address')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="pt-4 border-t border-[#E2E8F0] flex items-center justify-end gap-3">
                    <a href="{{ route('offering-letters.show', $offeringLetter) }}"
                        class="px-4 py-2.5 rounded-xl border border-[#E2E8F0] text-slate-600 hover:bg-slate-50 text-xs font-semibold transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-5 py-2.5 rounded-xl bg-[#3C50E0] text-white hover:bg-[#2F40BD] text-xs font-bold shadow-lg shadow-[#3C50E0]/25 transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Live helper preview for full letter number
            const letterNumberInput = document.getElementById('letter_number');
            const kodeInput = document.getElementById('kode');
            const previewEl = document.getElementById('full_number_preview');

            function updateFullNumberPreview() {
                if (!letterNumberInput || !previewEl) return;
                const rawNumber = letterNumberInput.value.trim();
                const rawKode = kodeInput ? kodeInput.value.trim().toUpperCase() : '';

                if (rawNumber && rawKode && !rawNumber.endsWith('/' + rawKode)) {
                    previewEl.textContent = rawNumber + '/' + rawKode;
                } else {
                    previewEl.textContent = rawNumber || '-';
                }
            }

            if (kodeInput) {
                kodeInput.addEventListener('input', function() {
                    this.value = this.value.toUpperCase();
                    updateFullNumberPreview();
                });
            }

            if (letterNumberInput) {
                letterNumberInput.addEventListener('input', updateFullNumberPreview);
            }

            updateFullNumberPreview();

            @if ($offeringLetter->status === 'draft')
                const employeesData = @json($employees);
                const employeeMap = {};
                employeesData.forEach(e => {
                    employeeMap[e.id] = e;
                });

                function formatDateIndo(dateStr) {
                    if (!dateStr) return '-';
                    try {
                        const parts = dateStr.substring(0, 10).split('-');
                        if (parts.length === 3) {
                            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt',
                                'Nov', 'Des'
                            ];
                            const day = parts[2];
                            const month = months[parseInt(parts[1], 10) - 1] || parts[1];
                            const year = parts[0];
                            return `${day} ${month} ${year}`;
                        }
                        return dateStr;
                    } catch (e) {
                        return dateStr;
                    }
                }

                function formatGender(genderStr) {
                    if (!genderStr) return '-';
                    const val = String(genderStr).trim().toLowerCase();
                    if (val === 'laki-laki' || val === 'male' || val === 'l') return 'Laki-laki';
                    if (val === 'perempuan' || val === 'female' || val === 'p') return 'Perempuan';
                    return genderStr;
                }

                function updateEmployeeDetailCard(emp) {
                    const card = document.getElementById('selected_employee_card');
                    if (!card) return;

                    if (!emp) {
                        card.classList.add('hidden');
                        return;
                    }

                    const avatar = document.getElementById('detail_emp_avatar');
                    const name = document.getElementById('detail_emp_name');
                    const ktp = document.getElementById('detail_emp_ktp');
                    const position = document.getElementById('detail_emp_position');
                    const branch = document.getElementById('detail_emp_branch');
                    const gender = document.getElementById('detail_emp_gender');
                    const joinDate = document.getElementById('detail_emp_join_date');
                    const birth = document.getElementById('detail_emp_birth');
                    const address = document.getElementById('detail_emp_address');

                    if (avatar) avatar.textContent = (emp.name || '').substring(0, 2).toUpperCase();
                    if (name) name.textContent = emp.name || '-';
                    if (ktp) ktp.textContent = 'NIK: ' + (emp.ktp_number || '-');
                    if (position) position.textContent = emp.current_position || '-';
                    if (branch) branch.textContent = emp.current_branch || '-';
                    if (gender) gender.textContent = formatGender(emp.gender);
                    if (joinDate) joinDate.textContent = formatDateIndo(emp.first_join_date);

                    let birthText = '-';
                    if (emp.birth_place && emp.birth_date) {
                        birthText = emp.birth_place + ', ' + formatDateIndo(emp.birth_date);
                    } else if (emp.birth_place) {
                        birthText = emp.birth_place;
                    } else if (emp.birth_date) {
                        birthText = formatDateIndo(emp.birth_date);
                    }
                    if (birth) birth.textContent = birthText;

                    if (address) {
                        address.textContent = emp.address || '-';
                        address.title = emp.address || '';
                    }

                    card.classList.remove('hidden');
                }

                const wrapper = document.getElementById('employee-combobox-wrapper');
                if (wrapper) {
                    const hiddenInput = document.getElementById('employee_id');
                    const searchInput = document.getElementById('employee_search_input');
                    const clearBtn = document.getElementById('employee_clear_btn');
                    const toggleBtn = document.getElementById('employee_dropdown_toggle');
                    const chevronIcon = document.getElementById('employee_chevron_icon');
                    const dropdownMenu = document.getElementById('employee_dropdown_menu');
                    const noResults = document.getElementById('employee_no_results');
                    const items = dropdownMenu ? dropdownMenu.querySelectorAll('.employee-item') : [];

                    let currentSelectedId = hiddenInput && hiddenInput.value ? String(hiddenInput.value) : '';
                    let selectedLabel = '';

                    function openDropdown() {
                        if (!dropdownMenu) return;
                        dropdownMenu.classList.remove('hidden');
                        if (chevronIcon) chevronIcon.classList.add('rotate-180');
                    }

                    function closeDropdown() {
                        if (!dropdownMenu) return;
                        dropdownMenu.classList.add('hidden');
                        if (chevronIcon) chevronIcon.classList.remove('rotate-180');
                    }

                    function isDropdownOpen() {
                        return dropdownMenu && !dropdownMenu.classList.contains('hidden');
                    }

                    function selectEmployee(id, triggerFill) {
                        const emp = employeeMap[id];
                        if (!emp) {
                            clearSelection();
                            return;
                        }

                        currentSelectedId = String(id);
                        if (hiddenInput) hiddenInput.value = currentSelectedId;
                        selectedLabel = `${emp.name} (NIK: ${emp.ktp_number})`;
                        if (searchInput) {
                            searchInput.value = selectedLabel;
                            searchInput.setCustomValidity('');
                        }

                        if (clearBtn) clearBtn.classList.remove('hidden');

                        items.forEach(item => {
                            const check = item.querySelector('.employee-check-icon');
                            if (item.dataset.id === String(id)) {
                                item.classList.add('bg-indigo-50/70', 'font-semibold');
                                if (check) check.classList.remove('hidden');
                            } else {
                                item.classList.remove('bg-indigo-50/70', 'font-semibold');
                                if (check) check.classList.add('hidden');
                            }
                        });

                        updateEmployeeDetailCard(emp);

                        if (triggerFill) {
                            const posInput = document.getElementById('position');
                            const branchInput = document.getElementById('branch');

                            if (posInput && emp.current_position) posInput.value = emp.current_position;
                            if (branchInput && emp.current_branch) branchInput.value = emp.current_branch;
                        }

                        closeDropdown();
                    }

                    function clearSelection() {
                        currentSelectedId = '';
                        if (hiddenInput) hiddenInput.value = '';
                        selectedLabel = '';
                        if (searchInput) searchInput.value = '';
                        if (clearBtn) clearBtn.classList.add('hidden');

                        items.forEach(item => {
                            item.classList.remove('bg-indigo-50/70', 'font-semibold');
                            const check = item.querySelector('.employee-check-icon');
                            if (check) check.classList.add('hidden');
                        });
                        if (noResults) noResults.classList.add('hidden');

                        updateEmployeeDetailCard(null);
                    }

                    function filterItems(query) {
                        const q = query.trim().toLowerCase();
                        let visibleCount = 0;

                        items.forEach(item => {
                            const haystack = (item.dataset.search || '').toLowerCase();
                            if (!q || haystack.includes(q)) {
                                item.classList.remove('hidden');
                                visibleCount++;
                            } else {
                                item.classList.add('hidden');
                            }
                        });

                        if (noResults) {
                            noResults.classList.toggle('hidden', visibleCount > 0);
                        }
                    }

                    if (searchInput) {
                        searchInput.addEventListener('focus', function() {
                            openDropdown();
                            if (searchInput.value === selectedLabel) {
                                filterItems('');
                            } else {
                                filterItems(searchInput.value);
                            }
                        });

                        searchInput.addEventListener('input', function() {
                            searchInput.setCustomValidity('');
                            openDropdown();
                            filterItems(searchInput.value);

                            if (searchInput.value.trim() === '') {
                                if (clearBtn) clearBtn.classList.add('hidden');
                                if (currentSelectedId) {
                                    currentSelectedId = '';
                                    if (hiddenInput) hiddenInput.value = '';
                                    updateEmployeeDetailCard(null);
                                }
                            } else {
                                if (clearBtn) clearBtn.classList.remove('hidden');
                            }
                        });

                        searchInput.addEventListener('blur', function() {
                            setTimeout(() => {
                                if (currentSelectedId && employeeMap[currentSelectedId]) {
                                    searchInput.value = selectedLabel;
                                } else if (!currentSelectedId) {
                                    searchInput.value = '';
                                    if (clearBtn) clearBtn.classList.add('hidden');
                                }
                            }, 200);
                        });
                    }

                    if (clearBtn) {
                        clearBtn.addEventListener('click', function(e) {
                            e.stopPropagation();
                            clearSelection();
                            if (searchInput) searchInput.focus();
                        });
                    }

                    if (toggleBtn) {
                        toggleBtn.addEventListener('click', function(e) {
                            e.stopPropagation();
                            if (isDropdownOpen()) {
                                closeDropdown();
                            } else {
                                if (searchInput) searchInput.focus();
                                openDropdown();
                                filterItems('');
                            }
                        });
                    }

                    items.forEach(item => {
                        item.addEventListener('click', function() {
                            selectEmployee(this.dataset.id, true);
                        });
                    });

                    document.addEventListener('click', function(e) {
                        if (wrapper && !wrapper.contains(e.target)) {
                            closeDropdown();
                        }
                    });

                    const form = wrapper.closest('form');
                    if (form) {
                        form.addEventListener('submit', function(e) {
                            if (hiddenInput && !hiddenInput.value) {
                                if (searchInput) {
                                    searchInput.setCustomValidity(
                                        'Silakan pilih karyawan dari daftar yang tersedia.');
                                    searchInput.reportValidity();
                                }
                                e.preventDefault();
                            } else if (searchInput) {
                                searchInput.setCustomValidity('');
                            }
                        });
                    }

                    // Pre-select current employee on load
                    if (currentSelectedId && employeeMap[currentSelectedId]) {
                        selectEmployee(currentSelectedId, false);
                    }
                }
            @endif
        });
    </script>
@endsection
