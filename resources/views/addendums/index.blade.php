@extends('layouts.app')

@section('title', 'Daftar Adendum Kontrak')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl font-extrabold tracking-tight text-[#1C2434]">
                    <span class="text-slate-400">SI-KONTRAK /</span> ADENDUM
                </h1>
            </div>
        </div>

        <!-- Search Bar -->
        <div
            class="p-4 rounded-2xl bg-white border border-[#E2E8F0] shadow-xs flex flex-col sm:flex-row items-center justify-between gap-4">
            <form action="{{ route('addendums.index') }}" method="GET" class="w-full sm:w-80 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari nomor adendum, karyawan, alasan..."
                    class="w-full pl-9 pr-4 py-2 text-xs rounded-xl bg-[#F8FAFC] border border-[#E2E8F0] focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] outline-hidden transition">
            </form>

            <div class="text-xs text-slate-400">
                Menampilkan {{ $addendums->total() }} adendum kontrak
            </div>
        </div>

        <!-- Table Addendums -->
        <div class="bg-white rounded-2xl border border-[#E2E8F0] shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="border-b border-[#E2E8F0] bg-[#F8FAFC] text-[11px] font-bold text-[#8A99AD] uppercase tracking-wider">
                            <th class="py-3.5 px-4">No. Adendum & Urutan</th>
                            <th class="py-3.5 px-4">Karyawan</th>
                            <th class="py-3.5 px-4">Kontrak Induk</th>
                            <th class="py-3.5 px-4">Tanggal Terbit</th>
                            <th class="py-3.5 px-4">Perpanjangan Masa Berlaku</th>
                            <th class="py-3.5 px-4">Alasan Perubahan</th>
                            <th class="py-3.5 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E2E8F0] text-xs">
                        @forelse($addendums as $ad)
                            <tr class="hover:bg-slate-50/75 transition">
                                <td class="py-3.5 px-4">
                                    <span
                                        class="font-mono font-bold text-xs text-[#3C50E0] block">{{ $ad->addendum_number }}</span>
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-purple-50 text-purple-700 border border-purple-200 mt-0.5">
                                        {{ $ad->sequence_label }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <a href="{{ route('employees.show', $ad->employee) }}"
                                        class="font-bold text-[#1C2434] hover:text-[#3C50E0] transition block truncate max-w-[150px]">
                                        {{ $ad->employee->name }}
                                    </a>
                                    <span class="text-[10px] text-slate-400 font-mono">NIK:
                                        {{ $ad->employee->ktp_number }}</span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <a href="{{ route('contracts.show', $ad->contract) }}"
                                        class="font-mono text-slate-700 hover:text-[#3C50E0] hover:underline block font-semibold">
                                        {{ $ad->contract->contract_number ?: '#' . $ad->contract->id }}
                                    </a>
                                    <span class="text-[10px] text-slate-400">Tipe:
                                        {{ $ad->contract->contract_type }}</span>
                                </td>
                                <td class="py-3.5 px-4 text-slate-600 font-medium">
                                    {{ $ad->issue_date->format('d/m/Y') }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="font-bold text-emerald-700 block">
                                        Hingga {{ $ad->new_end_date->format('d/m/Y') }}
                                    </span>
                                    <span class="text-[10px] text-slate-400 block">
                                        Sebelumnya: {{ $ad->previous_end_date->format('d/m/Y') }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="text-slate-700 font-medium block truncate max-w-[180px]"
                                        title="{{ $ad->amendment_reason }}">
                                        {{ $ad->amendment_reason }}
                                    </span>
                                    @if ($ad->new_salary && $ad->new_salary != $ad->previous_salary)
                                        <span class="text-[10px] text-emerald-600 block">
                                            Penyesuaian Gaji: {{ $ad->formatted_new_salary }}
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <a href="{{ route('addendums.show', $ad) }}"
                                            class="p-1.5 rounded-lg text-[#8A99AD] hover:text-[#3C50E0] hover:bg-[#3C50E0]/10 transition"
                                            title="Lihat Detail Adendum">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                        </a>

                                        <a href="{{ route('addendums.print', $ad) }}" target="_blank"
                                            class="p-1.5 rounded-lg text-[#8A99AD] hover:text-emerald-600 hover:bg-emerald-50 transition"
                                            title="Cetak Surat Adendum">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                                </path>
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-slate-400">
                                    Belum ada data Adendum Kontrak.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($addendums->hasPages())
                <div class="p-4 border-t border-[#E2E8F0]">
                    {{ $addendums->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
