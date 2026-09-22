@extends('layouts.app')

@section('title', 'Daftar Offering Letter')

@section('content')
    <div class="space-y-6">
        <!-- Header & Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl font-extrabold tracking-tight text-[#1C2434]">
                    <span class="text-slate-400">SI-KONTRAK /</span> OFFERING LETTER
                </h1>
            </div>

            <div class="flex items-center gap-3 shrink-0">
                <a href="{{ route('employees.create') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#3C50E0] text-white text-xs font-semibold hover:bg-[#2F40BD] shadow-lg shadow-[#3C50E0]/25 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Input Karyawan & Buat OL</span>
                </a>
            </div>
        </div>

        <!-- Filter & Search Bar -->
        <div
            class="p-4 rounded-2xl bg-white border border-[#E2E8F0] shadow-xs flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <form action="{{ route('offering-letters.index') }}" method="GET" class="w-full lg:w-80 relative"
                autocomplete="off">
                @if (request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" autocomplete="off"
                    placeholder="Cari nomor surat, nama, posisi..."
                    class="w-full pl-9 pr-4 py-2 text-xs rounded-xl bg-[#F8FAFC] border border-[#E2E8F0] focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] outline-hidden transition">
            </form>

            <div class="flex flex-wrap items-center gap-2">
                <span class="text-xs text-slate-400 mr-1">Filter Status:</span>
                <a href="{{ route('offering-letters.index', array_merge(request()->query(), ['status' => null])) }}"
                    class="px-2.5 py-1 text-xs rounded-lg transition {{ !request('status') ? 'bg-[#1C2434] text-white font-semibold' : 'text-slate-600 hover:bg-slate-100' }}">
                    Semua
                </a>
                <a href="{{ route('offering-letters.index', array_merge(request()->query(), ['status' => 'draft'])) }}"
                    class="px-2.5 py-1 text-xs rounded-lg transition {{ request('status') === 'draft' ? 'bg-slate-600 text-white font-semibold' : 'text-slate-600 hover:bg-slate-100' }}">
                    Draft
                </a>
                <a href="{{ route('offering-letters.index', array_merge(request()->query(), ['status' => 'sent'])) }}"
                    class="px-2.5 py-1 text-xs rounded-lg transition {{ request('status') === 'sent' ? 'bg-[#3C50E0] text-white font-semibold' : 'text-slate-600 hover:bg-slate-100' }}">
                    Terkirim
                </a>
                <a href="{{ route('offering-letters.index', array_merge(request()->query(), ['status' => 'accepted'])) }}"
                    class="px-2.5 py-1 text-xs rounded-lg transition {{ request('status') === 'accepted' ? 'bg-emerald-600 text-white font-semibold' : 'text-slate-600 hover:bg-slate-100' }}">
                    Diterima
                </a>
                <a href="{{ route('offering-letters.index', array_merge(request()->query(), ['status' => 'rejected'])) }}"
                    class="px-2.5 py-1 text-xs rounded-lg transition {{ request('status') === 'rejected' ? 'bg-rose-600 text-white font-semibold' : 'text-slate-600 hover:bg-slate-100' }}">
                    Ditolak
                </a>
            </div>
        </div>

        <!-- Table List -->
        <div class="bg-white rounded-2xl border border-[#E2E8F0] shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="border-b border-[#E2E8F0] bg-[#F8FAFC] text-[11px] font-bold text-[#8A99AD] uppercase tracking-wider">
                            <th class="py-3.5 px-4">No. Surat & Tanggal</th>
                            <th class="py-3.5 px-4">Karyawan / Kandidat</th>
                            <th class="py-3.5 px-4">Posisi & Cabang</th>
                            <th class="py-3.5 px-4">Rencana Tipe</th>
                            <th class="py-3.5 px-4">Gaji / Saku</th>
                            <th class="py-3.5 px-4">Status</th>
                            <th class="py-3.5 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E2E8F0] text-xs">
                        @forelse($offeringLetters as $ol)
                            <tr class="hover:bg-slate-50/75 transition">
                                <td class="py-3.5 px-4 font-semibold text-[#1C2434]">
                                    <span class="font-mono text-xs block text-[#3C50E0]">{{ $ol->letter_number }}</span>
                                    <span
                                        class="text-[11px] font-normal text-slate-400">{{ $ol->offer_date->format('d M Y') }}</span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <a href="{{ route('employees.show', $ol->employee) }}"
                                        class="font-bold text-[#1C2434] hover:text-[#3C50E0] transition block truncate max-w-[160px]">
                                        {{ $ol->employee->name }}
                                    </a>
                                    <span class="text-[10px] text-slate-400 font-mono">NIK:
                                        {{ $ol->employee->ktp_number }}</span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="font-medium text-slate-800 block">{{ $ol->position }}</span>
                                    <span class="text-[11px] text-slate-400 block">{{ $ol->branch }}</span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold border {{ $ol->contract_type_badge_class }}">
                                        {{ $ol->contract_type }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 font-semibold text-slate-800">
                                    {{ $ol->formatted_total_compensation }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold border {{ $ol->status_badge_class }}">
                                        {{ $ol->status_label }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <a href="{{ route('offering-letters.show', $ol) }}"
                                            class="p-1.5 rounded-lg text-[#8A99AD] hover:text-[#3C50E0] hover:bg-[#3C50E0]/10 transition"
                                            title="Lihat Detail">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                        </a>

                                        <a href="{{ route('offering-letters.print', $ol) }}" target="_blank"
                                            class="p-1.5 rounded-lg text-[#8A99AD] hover:text-emerald-600 hover:bg-emerald-50 transition"
                                            title="Cetak Surat Penawaran">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                                </path>
                                            </svg>
                                        </a>

                                        @if (!$ol->contract)
                                            <a href="{{ route('contracts.create', ['offering_letter_id' => $ol->id]) }}"
                                                class="px-2.5 py-1 rounded-lg bg-[#3C50E0] text-white hover:bg-[#2F40BD] text-[10px] font-semibold transition flex items-center gap-1 shadow-xs"
                                                title="Terbitkan Kontrak Kerja Resmi">
                                                <span>Buat Kontrak</span>
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </a>
                                        @else
                                            <span
                                                class="text-[10px] font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200">
                                                Kontrak Terbit
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-slate-400">
                                    Belum ada data Offering Letter yang diterbitkan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($offeringLetters->hasPages())
                <div class="p-4 border-t border-[#E2E8F0]">
                    {{ $offeringLetters->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
