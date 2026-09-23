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
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $offeringLetter->contract_type_badge_class }}">
                        {{ $offeringLetter->contract_type }}
                    </span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-2 shrink-0">
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
                @if ($offeringLetter->status !== 'draft')
                    <form action="{{ route('offering-letters.status', $offeringLetter) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="draft">
                        <button type="submit"
                            class="px-3 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-medium transition">
                            Draft
                        </button>
                    </form>
                @endif

                @if ($offeringLetter->status !== 'sent')
                    <form action="{{ route('offering-letters.status', $offeringLetter) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="sent">
                        <button type="submit"
                            class="px-3 py-1 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-medium border border-blue-200 transition">
                            Tandai Terkirim
                        </button>
                    </form>
                @endif

                @if ($offeringLetter->status !== 'accepted')
                    <form action="{{ route('offering-letters.status', $offeringLetter) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="accepted">
                        <button type="submit"
                            class="px-3 py-1 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-medium border border-emerald-200 transition">
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
                            class="px-3 py-1 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-medium border border-rose-200 transition">
                            Tandai Ditolak
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Detail Information Card -->
        <div class="p-6 sm:p-8 rounded-2xl bg-white border border-[#E2E8F0] shadow-xs space-y-6">
            <!-- Candidate & Letter Header -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pb-6 border-b border-[#E2E8F0]">
                <div>
                    <p class="text-xs text-slate-400 font-medium">Informasi Kandidat / Karyawan</p>
                    <h3 class="text-lg font-bold text-[#1C2434] mt-0.5">{{ $offeringLetter->employee->name }}</h3>
                    <p class="text-xs text-slate-500 mt-1 font-mono">NIK: {{ $offeringLetter->employee->ktp_number }}</p>
                    <p class="text-xs text-slate-500 mt-0.5">{{ $offeringLetter->employee->address }}</p>
                    <a href="{{ route('employees.show', $offeringLetter->employee) }}"
                        class="text-xs text-[#3C50E0] hover:underline mt-2 inline-block font-semibold">
                        Lihat Profil Karyawan &rarr;
                    </a>
                </div>

                <div class="sm:text-right">
                    <p class="text-xs text-slate-400 font-medium">Nomor Surat Penawaran</p>
                    <p class="text-base font-bold font-mono text-[#1C2434] mt-0.5">{{ $offeringLetter->letter_number }}</p>
                    @if ($offeringLetter->kode)
                        <p class="text-[11px] text-slate-500 font-mono">Kode Surat: <span
                                class="font-bold text-[#3C50E0]">{{ $offeringLetter->kode }}</span></p>
                    @endif
                    <p class="text-xs text-slate-500 mt-1">Tanggal Surat:
                        <strong>{{ $offeringLetter->offer_date->format('d F Y') }}</strong>
                    </p>
                    @if ($offeringLetter->valid_until)
                        <p class="text-xs text-amber-600 mt-0.5">Batas Konfirmasi:
                            <strong>{{ $offeringLetter->valid_until->format('d F Y') }}</strong>
                        </p>
                    @endif
                </div>
            </div>

            <!-- Position & Terms -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 pb-6 border-b border-[#E2E8F0]">
                <div>
                    <span class="text-xs text-slate-400 block font-medium">Jabatan & Bidang</span>
                    <span class="text-sm font-bold text-[#1C2434] block mt-0.5">{{ $offeringLetter->position }}</span>
                    @if ($offeringLetter->bidang)
                        <span class="text-xs font-semibold text-[#3C50E0] block mt-0.5">Bidang:
                            {{ $offeringLetter->bidang }}</span>
                    @endif
                    <span class="text-xs text-slate-500 block mt-0.5">{{ $offeringLetter->branch }}</span>
                </div>

                <div>
                    <span class="text-xs text-slate-400 block font-medium">Rencana Masa Kerja</span>
                    <span class="text-sm font-bold text-[#1C2434] block mt-0.5">
                        {{ $offeringLetter->proposed_start_date->format('d M Y') }} s/d
                        {{ $offeringLetter->proposed_end_date->format('d M Y') }}
                    </span>
                    <span class="text-xs text-slate-500 block">Tipe: {{ $offeringLetter->contract_type }}</span>
                </div>

                <div>
                    <span class="text-xs text-slate-400 block font-medium">Total Kompensasi Ditawarkan</span>
                    <span class="text-base font-extrabold text-[#3C50E0] block mt-0.5">
                        {{ $offeringLetter->formatted_total_compensation }}
                    </span>
                    <span class="text-[11px] text-slate-400 block">
                        Gaji: {{ $offeringLetter->formatted_salary }} | Tunjangan:
                        {{ $offeringLetter->formatted_allowance }}
                    </span>
                </div>
            </div>

            <!-- Signer & Office Details -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pb-6 border-b border-[#E2E8F0]">
                <div>
                    <span class="text-xs text-slate-400 block font-medium">Penandatangan Surat</span>
                    <span
                        class="text-sm font-bold text-[#1C2434] block mt-0.5">{{ $offeringLetter->supervisor_name ?: 'Hendra Wijaya, S.Psi.' }}</span>
                    <span
                        class="text-xs text-slate-500 block">{{ $offeringLetter->supervisor_position ?: 'Human Resources Manager' }}</span>
                </div>
                <div>
                    <span class="text-xs text-slate-400 block font-medium">Alamat Kantor</span>
                    <span
                        class="text-xs text-slate-700 block mt-0.5">{{ $offeringLetter->office_address ?: 'Gedung Perkantoran Sudirman Central, Lantai 12, Jakarta Pusat' }}</span>
                </div>
            </div>

            <!-- Terms & Clauses -->
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Klausul Syarat & Ketentuan</h4>
                <div
                    class="p-4 rounded-xl bg-[#F8FAFC] border border-[#E2E8F0] text-xs text-slate-700 whitespace-pre-line leading-relaxed">
                    {{ $offeringLetter->terms ?: 'Tidak ada syarat & ketentuan khusus.' }}
                </div>
            </div>

            @if ($offeringLetter->notes)
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Catatan Internal</h4>
                    <p class="text-xs text-slate-500">{{ $offeringLetter->notes }}</p>
                </div>
            @endif
        </div>
    </div>
@endsection
