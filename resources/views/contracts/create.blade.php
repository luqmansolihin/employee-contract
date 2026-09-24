@extends('layouts.app')

@section('title', 'Buat Kontrak Kerja Baru')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Breadcrumb -->
        <div class="flex items-center gap-2 text-xs text-[#8A99AD]">
            <a href="{{ route('contracts.index') }}" class="hover:text-[#3C50E0] transition">Kontrak</a>
            <span>/</span>
            <span class="text-slate-800 font-medium">Buat Kontrak Baru</span>
            @if ($employee)
                <span>/</span>
                <a href="{{ route('employees.show', $employee) }}"
                    class="hover:text-[#3C50E0] transition">{{ $employee->name }}</a>
            @endif
        </div>

        <!-- Form Card -->
        <div class="p-6 sm:p-8 rounded-2xl bg-white border border-[#E2E8F0] shadow-xs">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-[#E2E8F0] mb-6">
                <div>
                    <h3 class="text-lg font-bold text-[#1C2434]">Penerbitan Kontrak Kerja Baru</h3>
                    <p class="text-xs text-slate-400 mt-1">Lengkapi rincian masa berlaku, posisi, dan klausul kontrak kerja
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <span
                        class="px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-[#3C50E0] border border-indigo-100 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#3C50E0]"></span>
                        Formulir Terstandar
                    </span>
                </div>
            </div>

            <!-- Pre-filled info from Offering Letter if any -->
            @if ($offeringLetter)
                <div class="mb-6 p-4 rounded-xl bg-[#3C50E0]/5 border border-[#3C50E0]/20 flex items-start gap-3">
                    <div
                        class="w-8 h-8 rounded-lg bg-[#3C50E0]/10 text-[#3C50E0] flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-[#3C50E0]">Merujuk ke Offering Letter
                            #{{ $offeringLetter->letter_number }}</h4>
                        <h4 class="text-xs font-bold text-[#3C50E0]">Merujuk ke Offering Letter #{{ $offeringLetter->letter_number }}</h4>
                        <p class="text-[11px] text-slate-500 mt-0.5">
                            Data kandidat, penempatan, dan remunerasi telah dimuat secara otomatis dari penawaran kerja yang
                            telah diterima.
                        </p>
                    </div>
                </div>
            @endif

            <form action="{{ route('contracts.store') }}" method="POST" class="space-y-6" autocomplete="off">
                @csrf

                @if ($offeringLetter)
                    <input type="hidden" name="offering_letter_id" value="{{ $offeringLetter->id }}">
                @endif

                @if ($employee && $offeringLetter)
                    <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                @endif

                <!-- Section: Pilih Karyawan & Tipe Kontrak -->
                <div>
                    <h4
                        class="text-xs font-bold uppercase tracking-wider text-[#3C50E0] mb-4 pb-2 border-b border-[#E2E8F0]">
                        1. Pihak Karyawan & Klasifikasi Kontrak
                    </h4>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @if (!$offeringLetter)
                            <!-- Searchable Combobox Pilih Karyawan -->
                            <div class="relative" id="employee-combobox-wrapper">
                                <label for="employee_search_input" class="block text-xs font-bold text-[#1C2434] mb-1">
                                    Pilih Karyawan <span class="text-rose-500">*</span>
                                </label>

                                <!-- Hidden input for form submission -->
                                <input type="hidden" name="employee_id" id="employee_id"
                                    value="{{ old('employee_id', $employee?->id) }}">

                                <!-- Search Input with Icons -->
                                <div class="relative">
                                    <div
                                        class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 1114 0z"></path>
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
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2.5" d="M5 13l4 4L19 7"></path>
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
                        @else
                            <div>
                                <label class="block text-xs font-bold text-[#1C2434] mb-1">Karyawan Terpilih</label>
                                <div
                                    class="px-3.5 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 text-slate-700 font-bold flex items-center justify-between">
                                    <span>{{ $employee->name }} (NIK: {{ $employee->ktp_number }})</span>
                                    <span
                                        class="text-[10px] text-slate-400 font-mono">{{ $employee->current_position }}</span>
                                </div>
                            </div>
                        @endif

                        <!-- Tipe Kontrak -->
                        <div>
                            <label for="contract_type" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Tipe Kontrak Kerja <span class="text-rose-500">*</span>
                            </label>
                            <select name="contract_type" id="contract_type" required autocomplete="off"
                                onchange="updateContractNumberSuggestion()"
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('contract_type') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] outline-hidden transition">
                                <option value="PKWT"
                                    {{ old('contract_type', $selectedType) == 'PKWT' ? 'selected' : '' }}>PKWT (Perjanjian
                                    Kerja Waktu Tertentu)</option>
                                <option value="MT"
                                    {{ old('contract_type', $selectedType) == 'MT' ? 'selected' : '' }}>
                                    MT (Management Trainee)</option>
                                <option value="MAGANG"
                                    {{ old('contract_type', $selectedType) == 'MAGANG' ? 'selected' : '' }}>MAGANG
                                    (Internship / Pemagangan)</option>
                            </select>
                            @error('contract_type')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Selected Employee Detail Card (Full Width / Span 2 cols) -->
                        @if ($employee && $offeringLetter)
                            <div class="sm:col-span-2 p-4 rounded-xl bg-slate-50 border border-slate-200">
                                <div class="flex items-start justify-between gap-3 pb-3 border-b border-slate-200">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-xl bg-[#3C50E0]/10 text-[#3C50E0] flex items-center justify-center font-bold text-sm shrink-0">
                                            {{ strtoupper(substr($employee->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <h5 class="text-sm font-bold text-[#1C2434]">{{ $employee->name }}</h5>
                                            <p class="text-xs text-slate-500 font-mono">NIK: {{ $employee->ktp_number }}
                                            </p>
                                        </div>
                                    </div>
                                    <span
                                        class="px-2.5 py-1 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-600 border border-emerald-200">
                                        Merujuk Offering Letter
                                    </span>
                                </div>
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-3 text-xs">
                                    <div>
                                        <span class="text-slate-400 block text-[10px] uppercase font-semibold">Posisi /
                                            Jabatan</span>
                                        <span
                                            class="font-bold text-slate-700">{{ $employee->current_position ?: '-' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-slate-400 block text-[10px] uppercase font-semibold">Cabang /
                                            Lokasi</span>
                                        <span
                                            class="font-bold text-slate-700">{{ $employee->current_branch ?: '-' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-slate-400 block text-[10px] uppercase font-semibold">Jenis
                                            Kelamin</span>
                                        <span
                                            class="font-bold text-slate-700">{{ in_array(strtolower($employee->gender ?? ''), ['laki-laki', 'male', 'l']) ? 'Laki-laki' : (in_array(strtolower($employee->gender ?? ''), ['perempuan', 'female', 'p']) ? 'Perempuan' : ($employee->gender ?: '-')) }}</span>
                                    </div>
                                    <div>
                                        <span class="text-slate-400 block text-[10px] uppercase font-semibold">Tgl Pertama
                                            Masuk</span>
                                        <span
                                            class="font-bold text-slate-700">{{ $employee->first_join_date ? $employee->first_join_date->format('d M Y') : '-' }}</span>
                                    </div>
                                    <div class="sm:col-span-2">
                                        <span class="text-slate-400 block text-[10px] uppercase font-semibold">Tempat,
                                            Tanggal Lahir</span>
                                        <span class="font-medium text-slate-700">
                                            {{ $employee->birth_place ? $employee->birth_place . ($employee->birth_date ? ', ' . $employee->birth_date->format('d M Y') : '') : '-' }}
                                        </span>
                                    </div>
                                    <div class="sm:col-span-2">
                                        <span
                                            class="text-slate-400 block text-[10px] uppercase font-semibold">Alamat</span>
                                        <span class="font-medium text-slate-700 truncate block"
                                            title="{{ $employee->address }}">{{ $employee->address ?: '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        @elseif (!$offeringLetter)
                            <div id="selected_employee_card"
                                class="hidden sm:col-span-2 p-4 rounded-xl bg-slate-50 border border-slate-200 transition-all duration-200">
                                <div class="flex items-start justify-between gap-3 pb-3 border-b border-slate-200">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-[#3C50E0]/10 text-[#3C50E0] flex items-center justify-center font-bold text-sm shrink-0"
                                            id="detail_emp_avatar">
                                            --
                                        </div>
                                        <div>
                                            <h5 class="text-sm font-bold text-[#1C2434]" id="detail_emp_name">-</h5>
                                            <p class="text-xs text-slate-500 font-mono" id="detail_emp_ktp">NIK: -</p>
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
                                        <span class="font-bold text-slate-700" id="detail_emp_position">-</span>
                                    </div>
                                    <div>
                                        <span class="text-slate-400 block text-[10px] uppercase font-semibold">Cabang /
                                            Lokasi</span>
                                        <span class="font-bold text-slate-700" id="detail_emp_branch">-</span>
                                    </div>
                                    <div>
                                        <span class="text-slate-400 block text-[10px] uppercase font-semibold">Jenis
                                            Kelamin</span>
                                        <span class="font-bold text-slate-700" id="detail_emp_gender">-</span>
                                    </div>
                                    <div>
                                        <span class="text-slate-400 block text-[10px] uppercase font-semibold">Tgl Pertama
                                            Masuk</span>
                                        <span class="font-bold text-slate-700" id="detail_emp_join_date">-</span>
                                    </div>
                                    <div class="sm:col-span-2">
                                        <span class="text-slate-400 block text-[10px] uppercase font-semibold">Tempat,
                                            Tanggal Lahir</span>
                                        <span class="font-medium text-slate-700" id="detail_emp_birth">-</span>
                                    </div>
                                    <div class="sm:col-span-2">
                                        <span
                                            class="text-slate-400 block text-[10px] uppercase font-semibold">Alamat</span>
                                        <span class="font-medium text-slate-700 truncate block"
                                            id="detail_emp_address">-</span>
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>

                <!-- Section 2: Informasi Surat & Ketentuan Kontrak -->
                <div>
                    <h4
                        class="text-xs font-bold uppercase tracking-wider text-[#3C50E0] mb-4 pb-2 border-b border-[#E2E8F0]">
                        2. Informasi Surat & Ketentuan Kontrak
                    </h4>

                    @php
                        $rawContractNumber = old('contract_number', $suggestedNumber);
                        $currentKode = old('kode', $offeringLetter?->kode);
                        $cleanKode = strtoupper(trim((string) $currentKode));
                        if ($cleanKode !== '' && str_ends_with($rawContractNumber, '/' . $cleanKode)) {
                            $displayContractNumber = substr($rawContractNumber, 0, -strlen('/' . $cleanKode));
                        } else {
                            $displayContractNumber = $rawContractNumber;
                        }
                    @endphp

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Nomor Kontrak -->
                        <div>
                            <label for="contract_number" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Nomor Kontrak Kerja <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="contract_number" id="contract_number"
                                value="{{ $displayContractNumber }}" required autocomplete="off"
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('contract_number') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] font-mono outline-hidden transition">
                            <p class="text-[11px] text-slate-500 mt-1 font-mono">
                                Nomor Kontrak Lengkap: <span id="full_number_preview"
                                    class="font-bold text-[#3C50E0]">{{ $rawContractNumber }}</span>
                            </p>
                            @error('contract_number')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kode -->
                        <div>
                            <label for="kode" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Kode <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="kode" id="kode"
                                value="{{ old('kode', $offeringLetter?->kode) }}" required autocomplete="off"
                                placeholder="Contoh: HRD, CKU, OPS"
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('kode') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] font-mono uppercase outline-hidden transition">
                            @error('kode')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal Surat -->
                        <div>
                            <label for="contract_date" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Tanggal Surat <span class="text-rose-500">*</span>
                            </label>
                            <input type="date" name="contract_date" id="contract_date"
                                value="{{ old('contract_date', date('Y-m-d')) }}" required autocomplete="off"
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('contract_date') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] outline-hidden transition">
                            @error('contract_date')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Bidang -->
                        <div>
                            <label for="bidang" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Bidang <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="bidang" id="bidang"
                                value="{{ old('bidang', $offeringLetter?->bidang) }}" required autocomplete="off"
                                placeholder="Contoh: Operasional, IT, Keuangan"
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('bidang') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] outline-hidden transition">
                            @error('bidang')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Posisi / Jabatan -->
                        <div>
                            <label for="position" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Posisi / Jabatan Kerja <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="position" id="position"
                                value="{{ old('position', $offeringLetter ? $offeringLetter->position : $employee?->current_position) }}"
                                required autocomplete="off" placeholder="Contoh: Senior Backend Developer"
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('position') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] outline-hidden transition">
                            @error('position')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Cabang / Unit Kerja -->
                        <div>
                            <label for="branch" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Cabang / Lokasi Penempatan <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="branch" id="branch"
                                value="{{ old('branch', $offeringLetter ? $offeringLetter->branch : $employee?->current_branch) }}"
                                required autocomplete="off" placeholder="Contoh: Kantor Pusat Jakarta"
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('branch') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] outline-hidden transition">
                            @error('branch')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal Mulai -->
                        <div>
                            <label for="start_date" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Tanggal Mulai Kontrak <span class="text-rose-500">*</span>
                            </label>
                            <input type="date" name="start_date" id="start_date"
                                value="{{ old('start_date', $offeringLetter ? $offeringLetter->proposed_start_date->format('Y-m-d') : ($employee?->first_join_date ? $employee->first_join_date->format('Y-m-d') : date('Y-m-d'))) }}"
                                required autocomplete="off"
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('start_date') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] outline-hidden transition">
                            @error('start_date')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal Selesai -->
                        <div>
                            <label for="end_date" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Tanggal Berakhir Kontrak <span class="text-rose-500">*</span>
                            </label>
                            <input type="date" name="end_date" id="end_date"
                                value="{{ old('end_date', $offeringLetter ? $offeringLetter->proposed_end_date->format('Y-m-d') : date('Y-m-d', strtotime('+1 year -1 day'))) }}"
                                required autocomplete="off"
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('end_date') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] outline-hidden transition">
                            @error('end_date')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nama Atasan -->
                        <div>
                            <label for="supervisor_name" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Nama Atasan <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="supervisor_name" id="supervisor_name"
                                value="{{ old('supervisor_name', $offeringLetter?->supervisor_name) }}" required
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
                                value="{{ old('supervisor_position', $offeringLetter?->supervisor_position) }}" required
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
                                value="{{ old('office_address', $offeringLetter?->office_address) }}" required
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
                    <a href="{{ route('contracts.index') }}"
                        class="px-4 py-2.5 rounded-xl border border-[#E2E8F0] text-slate-600 hover:bg-slate-50 text-xs font-semibold transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-5 py-2.5 rounded-xl bg-[#3C50E0] text-white hover:bg-[#2F40BD] text-xs font-bold shadow-lg shadow-[#3C50E0]/25 transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        <span>Terbitkan & Simpan Kontrak</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script for Dynamic Number Suggestion based on type & Combobox -->
    <script>
        const suggestedNumbers = @json($suggestedNumbers);

        function updateFullNumberPreview() {
            const contractNumberInput = document.getElementById('contract_number');
            const kodeInput = document.getElementById('kode');
            const previewEl = document.getElementById('full_number_preview');
            if (!contractNumberInput || !previewEl) return;
            const rawNumber = contractNumberInput.value.trim();
            const rawKode = kodeInput ? kodeInput.value.trim().toUpperCase() : '';

            if (rawNumber && rawKode && !rawNumber.endsWith('/' + rawKode)) {
                previewEl.textContent = rawNumber + '/' + rawKode;
            } else {
                previewEl.textContent = rawNumber || '-';
            }
        }

        function updateContractNumberSuggestion() {
            const type = document.getElementById('contract_type').value;
            if (suggestedNumbers[type]) {
                document.getElementById('contract_number').value = suggestedNumbers[type];
                updateFullNumberPreview();
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const contractNumberInput = document.getElementById('contract_number');
            const kodeInput = document.getElementById('kode');

            if (kodeInput) {
                kodeInput.addEventListener('input', function() {
                    this.value = this.value.toUpperCase();
                    updateFullNumberPreview();
                });
            }

            if (contractNumberInput) {
                contractNumberInput.addEventListener('input', updateFullNumberPreview);
            }

            updateFullNumberPreview();
        });

        @if (!$offeringLetter)
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
                        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
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
                if (gender) gender.textContent = emp.gender === 'female' ? 'Perempuan' : (emp.gender === 'male' ?
                    'Laki-laki' : '-');
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

            document.addEventListener('DOMContentLoaded', function() {
                const wrapper = document.getElementById('employee-combobox-wrapper');
                if (!wrapper) return;

                const hiddenInput = document.getElementById('employee_id');
                const searchInput = document.getElementById('employee_search_input');
                const clearBtn = document.getElementById('employee_clear_btn');
                const toggleBtn = document.getElementById('employee_dropdown_toggle');
                const chevronIcon = document.getElementById('employee_chevron_icon');
                const dropdownMenu = document.getElementById('employee_dropdown_menu');
                const noResults = document.getElementById('employee_no_results');
                const items = dropdownMenu.querySelectorAll('.employee-item');

                let currentSelectedId = hiddenInput.value ? String(hiddenInput.value) : '';
                let selectedLabel = '';

                function openDropdown() {
                    dropdownMenu.classList.remove('hidden');
                    if (chevronIcon) chevronIcon.classList.add('rotate-180');
                }

                function closeDropdown() {
                    dropdownMenu.classList.add('hidden');
                    if (chevronIcon) chevronIcon.classList.remove('rotate-180');
                }

                function isDropdownOpen() {
                    return !dropdownMenu.classList.contains('hidden');
                }

                function selectEmployee(id, triggerFill) {
                    const emp = employeeMap[id];
                    if (!emp) {
                        clearSelection();
                        return;
                    }

                    currentSelectedId = String(id);
                    hiddenInput.value = currentSelectedId;
                    selectedLabel = `${emp.name} (NIK: ${emp.ktp_number})`;
                    searchInput.value = selectedLabel;
                    searchInput.setCustomValidity('');

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
                    hiddenInput.value = '';
                    selectedLabel = '';
                    searchInput.value = '';
                    if (clearBtn) clearBtn.classList.add('hidden');

                    items.forEach(item => {
                        item.classList.remove('bg-indigo-50/70', 'font-semibold', 'hidden');
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
                            hiddenInput.value = '';
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

                if (clearBtn) {
                    clearBtn.addEventListener('click', function(e) {
                        e.stopPropagation();
                        clearSelection();
                        searchInput.focus();
                    });
                }

                if (toggleBtn) {
                    toggleBtn.addEventListener('click', function(e) {
                        e.stopPropagation();
                        if (isDropdownOpen()) {
                            closeDropdown();
                        } else {
                            searchInput.focus();
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
                    if (!wrapper.contains(e.target)) {
                        closeDropdown();
                    }
                });

                const form = wrapper.closest('form');
                if (form) {
                    form.addEventListener('submit', function(e) {
                        if (!hiddenInput.value) {
                            searchInput.setCustomValidity(
                                'Silakan pilih karyawan dari daftar yang tersedia.');
                            searchInput.reportValidity();
                            e.preventDefault();
                        } else {
                            searchInput.setCustomValidity('');
                        }
                    });
                }

                if (currentSelectedId && employeeMap[currentSelectedId]) {
                    selectEmployee(currentSelectedId, false);
                }
            });
        @endif
    </script>
@endsection
