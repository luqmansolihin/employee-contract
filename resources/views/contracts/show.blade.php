@extends('layouts.app')

@section('title', 'Detail Kontrak - ' . ($contract->contract_number ?: 'Tanpa Nomor'))

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Top Breadcrumb & Status -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs text-[#8A99AD] mb-1">
                    <a href="{{ route('employees.index') }}" class="hover:text-[#3C50E0] transition">Karyawan</a>
                    <span>/</span>
                    <a href="{{ route('employees.show', $contract->employee) }}"
                        class="hover:text-[#3C50E0] transition">{{ $contract->employee->name }}</a>
                    <span>/</span>
                    <a href="{{ route('contracts.index') }}" class="hover:text-[#3C50E0] transition">Kontrak Kerja</a>
                    <span>/</span>
                    <span
                        class="text-slate-800 font-medium font-mono">{{ $contract->contract_number ?: '#' . $contract->id }}</span>
                </div>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-extrabold tracking-tight text-[#1C2434]">
                        Detail Kontrak Kerja
                    </h1>
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $contract->contract_type_badge_class }}">
                        {{ $contract->contract_type }}
                    </span>
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $contract->status_badge_class }}">
                        {{ $contract->status_label }}
                    </span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('contracts.print', $contract) }}" target="_blank"
                    class="px-3.5 py-2 rounded-xl bg-white border border-[#E2E8F0] hover:border-[#3C50E0] text-slate-700 hover:text-[#3C50E0] text-xs font-semibold transition flex items-center gap-2 shadow-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                        </path>
                    </svg>
                    <span>Cetak Surat Perjanjian</span>
                </a>

                <a href="{{ route('addendums.create', $contract) }}"
                    class="px-4 py-2 rounded-xl bg-[#3C50E0] text-white hover:bg-[#2F40BD] text-xs font-bold shadow-lg shadow-[#3C50E0]/25 transition flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>
                    <span>Buat Adendum Kontrak</span>
                </a>
            </div>
        </div>

        <!-- Contract Progress Bar Card -->
        <div class="p-6 rounded-2xl bg-white border border-[#E2E8F0] shadow-xs space-y-3">
            <div class="flex items-center justify-between text-xs">
                <span class="font-bold text-[#1C2434]">{{ $contract->remaining_days_text }}</span>
                <span class="text-slate-500 font-semibold">{{ $contract->progress_percentage }}% Masa Berlaku
                    Berjalan</span>
            </div>
            <div class="w-full h-2.5 rounded-full bg-slate-100 overflow-hidden">
                <div class="h-full rounded-full transition-all duration-500 {{ $contract->calculated_status === 'expired' ? 'bg-rose-500' : ($contract->calculated_status === 'expiring_soon' ? 'bg-amber-400' : 'bg-[#3C50E0]') }}"
                    style="width: {{ $contract->progress_percentage }}%"></div>
            </div>
            <div class="flex items-center justify-between text-[11px] text-slate-400">
                <span>Mulai: {{ $contract->start_date->format('d M Y') }}</span>
                <span>Berakhir: {{ $contract->end_date->format('d M Y') }}</span>
            </div>
        </div>

        <!-- Detail Card -->
        <div class="p-6 sm:p-8 rounded-2xl bg-white border border-[#E2E8F0] shadow-xs space-y-6">
            <!-- Header Section -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pb-6 border-b border-[#E2E8F0]">
                <div>
                    <p class="text-xs text-slate-400 font-medium">Informasi Karyawan</p>
                    <h3 class="text-lg font-bold text-[#1C2434] mt-0.5">{{ $contract->employee->name }}</h3>
                    <p class="text-xs text-slate-500 mt-1 font-mono">NIK: {{ $contract->employee->ktp_number }}</p>
                    <p class="text-xs text-slate-500 mt-0.5">{{ $contract->employee->address }}</p>
                    <a href="{{ route('employees.show', $contract->employee) }}"
                        class="text-xs text-[#3C50E0] hover:underline mt-2 inline-block font-semibold">
                        Lihat Profil Karyawan &rarr;
                    </a>
                </div>

                <div class="sm:text-right">
                    <p class="text-xs text-slate-400 font-medium">Nomor Kontrak Resmi</p>
                    <p class="text-base font-bold font-mono text-[#1C2434] mt-0.5">{{ $contract->contract_number ?: '-' }}
                    </p>
                    <p class="text-xs text-slate-500 mt-1">Urutan: <strong>{{ $contract->sequence_label }}</strong></p>
                    @if ($contract->offeringLetter)
                        <p class="text-xs text-indigo-600 mt-0.5">
                            Dari OL: <a href="{{ route('offering-letters.show', $contract->offeringLetter) }}"
                                class="hover:underline font-mono">#{{ $contract->offeringLetter->letter_number }}</a>
                        </p>
                    @endif
                </div>
            </div>

            <!-- Position, Duration, Salary -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 pb-6 border-b border-[#E2E8F0]">
                <div>
                    <span class="text-xs text-slate-400 block font-medium">Jabatan & Penempatan</span>
                    <span class="text-sm font-bold text-[#1C2434] block mt-0.5">{{ $contract->position }}</span>
                    <span class="text-xs text-slate-500 block">{{ $contract->branch }}</span>
                </div>

                <div>
                    <span class="text-xs text-slate-400 block font-medium">Durasi Hubungan Kerja</span>
                    <span class="text-sm font-bold text-[#1C2434] block mt-0.5">
                        {{ $contract->duration_in_months }} Bulan
                    </span>
                    <span class="text-xs text-slate-500 block">Total: {{ $contract->duration_in_days }} Hari</span>
                </div>

                <div>
                    <span class="text-xs text-slate-400 block font-medium">Kompensasi Kontrak</span>
                    <span class="text-base font-extrabold text-[#3C50E0] block mt-0.5">
                        {{ $contract->formatted_salary ?: 'Belum Diatur' }}
                    </span>
                    @if ($contract->allowance)
                        <span class="text-[11px] text-slate-400 block">Tunjangan:
                            {{ $contract->formatted_allowance }}</span>
                    @endif
                </div>
            </div>

            @if ($contract->notes)
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Catatan Tambahan</h4>
                    <p class="text-xs text-slate-700 bg-slate-50 p-3 rounded-xl border border-slate-200">
                        {{ $contract->notes }}</p>
                </div>
            @endif
        </div>

        <!-- Section: Adendum Kontrak Terkait -->
        <div class="p-6 sm:p-8 rounded-2xl bg-white border border-[#E2E8F0] shadow-xs space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-[#1C2434]">Riwayat Adendum Kontrak</h3>
                    <p class="text-xs text-slate-400">Daftar amandemen atau perpanjangan masa berlaku untuk kontrak ini.</p>
                </div>

                <a href="{{ route('addendums.create', $contract) }}"
                    class="px-3 py-1.5 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-semibold border border-indigo-200 transition">
                    + Buat Adendum
                </a>
            </div>

            @if ($contract->addendums->isNotEmpty())
                <div class="divide-y divide-[#E2E8F0] border border-[#E2E8F0] rounded-xl overflow-hidden text-xs">
                    @foreach ($contract->addendums as $ad)
                        <div
                            class="p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 hover:bg-slate-50/50 transition">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-[#1C2434] font-mono">{{ $ad->addendum_number }}</span>
                                    <span
                                        class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-purple-50 text-purple-700 border border-purple-200">
                                        {{ $ad->sequence_label }}
                                    </span>
                                </div>
                                <p class="text-slate-600">
                                    Alasan: <strong>{{ $ad->amendment_reason }}</strong>
                                </p>
                                <p class="text-[11px] text-slate-400">
                                    Masa Berlaku Baru: <strong
                                        class="text-slate-700">{{ $ad->effective_date->format('d/m/Y') }} s/d
                                        {{ $ad->new_end_date->format('d/m/Y') }}</strong> (Diperpanjang dari
                                    {{ $ad->previous_end_date->format('d/m/Y') }})
                                </p>
                            </div>

                            <div class="flex items-center gap-2 shrink-0">
                                <a href="{{ route('addendums.print', $ad) }}" target="_blank"
                                    class="px-2.5 py-1.5 rounded-lg border border-[#E2E8F0] hover:border-emerald-500 text-slate-600 hover:text-emerald-600 text-xs font-semibold transition flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                        </path>
                                    </svg>
                                    <span>Cetak</span>
                                </a>

                                <a href="{{ route('addendums.show', $ad) }}"
                                    class="px-2.5 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition">
                                    Detail
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div
                    class="p-6 text-center text-slate-400 bg-[#F8FAFC] rounded-xl border border-dashed border-slate-200 text-xs">
                    Kontrak ini belum memiliki adendum atau perubahan.
                </div>
            @endif
        </div>
    </div>
@endsection
