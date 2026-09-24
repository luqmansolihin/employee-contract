@extends('layouts.app')

@section('title', 'Detail Offering Letter - ' . $offeringLetter->letter_number)

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Top Breadcrumb & Status -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs text-[#8A99AD] mb-1">
                    <a href="{{ route('employees.index') }}" class="hover:text-[#3C50E0] transition">Karyawan</a>
                    <span>/</span>
                    <a href="{{ route('employees.show', $offeringLetter->employee) }}"
                        class="hover:text-[#3C50E0] transition">{{ $offeringLetter->employee->name }}</a>
                    <span>/</span>
                    <a href="{{ route('offering-letters.index') }}" class="hover:text-[#3C50E0] transition">Offering
                        Letter</a>
                    <span>/</span>
                    <span class="text-slate-800 font-medium font-mono">{{ $offeringLetter->letter_number }}</span>
                </div>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-extrabold tracking-tight text-[#1C2434]">
                        Surat Penawaran Kerja
                    </h1>
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $offeringLetter->status_badge_class }}">
                        {{ $offeringLetter->status_label }}
                    </span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('offering-letters.edit', $offeringLetter) }}"
                    class="px-3.5 py-2 rounded-xl bg-white border border-[#E2E8F0] hover:border-[#3C50E0] text-slate-700 hover:text-[#3C50E0] text-xs font-semibold transition flex items-center gap-1.5 shadow-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>
                    <span>Edit</span>
                </a>

                <a href="{{ route('offering-letters.print', $offeringLetter) }}" target="_blank"
                    class="px-3.5 py-2 rounded-xl bg-white border border-[#E2E8F0] hover:border-[#3C50E0] text-slate-700 hover:text-[#3C50E0] text-xs font-semibold transition flex items-center gap-2 shadow-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                        </path>
                    </svg>
                    <span>Cetak Surat</span>
                </a>

                @if (!$offeringLetter->contract)
                    <a href="{{ route('contracts.create', ['offering_letter_id' => $offeringLetter->id]) }}"
                        class="px-4 py-2 rounded-xl bg-[#3C50E0] text-white hover:bg-[#2F40BD] text-xs font-bold shadow-lg shadow-[#3C50E0]/25 transition flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <span>Terbitkan Kontrak Kerja</span>
                    </a>
                @else
                    <a href="{{ route('contracts.show', $offeringLetter->contract) }}"
                        class="px-3.5 py-2 rounded-xl bg-emerald-600 text-white hover:bg-emerald-700 text-xs font-bold transition flex items-center gap-1.5 shadow-xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Lihat Kontrak Kerja Terbit</span>
                    </a>
                @endif
            </div>
        </div>

        <!-- Status Change Quick Bar -->
        <div
            class="p-4 rounded-2xl bg-white border border-[#E2E8F0] shadow-xs flex flex-wrap items-center justify-between gap-3">
            <span class="text-xs text-slate-500 font-medium">Ubah Status Surat Penawaran:</span>
            <div class="flex items-center gap-2">
                @if ($offeringLetter->status === 'draft')
                    <form action="{{ route('offering-letters.status', $offeringLetter) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="sent">
                        <button type="submit"
                            class="px-3 py-1 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-semibold border border-blue-200 transition">
                            Tandai Terkirim
                        </button>
                    </form>
                @else
                    @if ($offeringLetter->status !== 'accepted')
                        <form action="{{ route('offering-letters.status', $offeringLetter) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="accepted">
                            <button type="submit"
                                class="px-3 py-1 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-semibold border border-emerald-200 transition">
                                Tandai Diterima (Accepted)
                            </button>
                        </form>
                    @endif

                    @if ($offeringLetter->status !== 'rejected')
                        <form action="{{ route('offering-letters.status', $offeringLetter) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="rejected">
                            <button type="submit"
                                class="px-3 py-1 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-semibold border border-rose-200 transition">
                                Tandai Ditolak
                            </button>
                        </form>
                    @endif
                @endif
            </div>
        </div>

        <!-- Detail Information Card -->
        <div class="p-6 sm:p-8 rounded-2xl bg-white border border-[#E2E8F0] shadow-xs space-y-6">
            <!-- 1. Karyawan Penerima Penawaran & Nomor Surat Header -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pb-6 border-b border-[#E2E8F0]">
                <div>
                    <p class="text-xs text-slate-400 font-medium uppercase tracking-wider">1. Karyawan Penerima Penawaran
                    </p>
                    <h3 class="text-lg font-bold text-[#1C2434] mt-1">{{ $offeringLetter->employee->name }}</h3>
                    <div class="mt-2 space-y-1 text-xs text-slate-600">
                        <p><span class="text-slate-400">NIK:</span> <span
                                class="font-mono font-medium">{{ $offeringLetter->employee->ktp_number }}</span></p>
                        <p><span class="text-slate-400">Jenis Kelamin:</span> <span
                                class="font-medium">{{ $offeringLetter->employee->gender ?: '-' }}</span></p>
                        <p><span class="text-slate-400">Alamat:</span> <span
                                class="font-medium">{{ $offeringLetter->employee->address ?: '-' }}</span></p>
                    </div>
                    <a href="{{ route('employees.show', $offeringLetter->employee) }}"
                        class="text-xs text-[#3C50E0] hover:underline mt-3 inline-block font-semibold">
                        Lihat Profil Karyawan &rarr;
                    </a>
                </div>

                <div class="sm:text-right">
                    <p class="text-xs text-slate-400 font-medium uppercase tracking-wider">2. Informasi Surat</p>
                    <p class="text-base font-bold font-mono text-[#1C2434] mt-1">{{ $offeringLetter->letter_number }}</p>
                    <div class="mt-2 space-y-1 text-xs text-slate-600 sm:text-right">
                        @if ($offeringLetter->kode)
                            <p><span class="text-slate-400">Kode Surat:</span> <span
                                    class="font-bold font-mono text-[#3C50E0]">{{ $offeringLetter->kode }}</span></p>
                        @endif
                        <p><span class="text-slate-400">Tanggal Surat:</span> <strong
                                class="text-slate-800">{{ $offeringLetter->offer_date->translatedFormat('d F Y') }}</strong>
                        </p>
                    </div>
                </div>
            </div>

            <!-- 2. Informasi Rencana Kontrak & Penempatan -->
            <div class="pb-6 border-b border-[#E2E8F0]">
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4">Informasi Pekerjaan & Penempatan
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-[10px] uppercase font-semibold text-slate-400 block">Posisi / Jabatan</span>
                        <span class="text-xs font-bold text-slate-800 block mt-1">{{ $offeringLetter->position }}</span>
                    </div>

                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-[10px] uppercase font-semibold text-slate-400 block">Bidang</span>
                        <span
                            class="text-xs font-bold text-slate-800 block mt-1">{{ $offeringLetter->bidang ?: '-' }}</span>
                    </div>

                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-[10px] uppercase font-semibold text-slate-400 block">Cabang / Penempatan</span>
                        <span class="text-xs font-bold text-slate-800 block mt-1">{{ $offeringLetter->branch }}</span>
                    </div>

                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-[10px] uppercase font-semibold text-slate-400 block">Rencana Periode
                            Kontrak</span>
                        <span class="text-xs font-bold text-slate-800 block mt-1">
                            {{ $offeringLetter->proposed_start_date->translatedFormat('d M Y') }} s/d
                            {{ $offeringLetter->proposed_end_date->translatedFormat('d M Y') }}
                        </span>
                        @php
                            $months = $offeringLetter->proposed_start_date->diffInMonths(
                                $offeringLetter->proposed_end_date,
                            );
                        @endphp
                        @if ($months > 0)
                            <span class="text-[11px] text-slate-500 block mt-0.5">({{ $months }} Bulan)</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- 3. Atasan & Alamat Kantor -->
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4">Penandatangan & Alamat Kantor
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-[10px] uppercase font-semibold text-slate-400 block">Nama & Jabatan Atasan</span>
                        <span
                            class="text-sm font-bold text-slate-800 block mt-1">{{ $offeringLetter->supervisor_name ?: '-' }}</span>
                        <span
                            class="text-xs text-slate-500 block mt-0.5">{{ $offeringLetter->supervisor_position ?: '-' }}</span>
                    </div>

                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-[10px] uppercase font-semibold text-slate-400 block">Alamat Kantor</span>
                        <span
                            class="text-xs font-medium text-slate-700 block mt-1 leading-relaxed">{{ $offeringLetter->office_address ?: '-' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
