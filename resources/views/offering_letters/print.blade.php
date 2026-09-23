<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offering Letter - {{ $offeringLetter->letter_number }} - {{ $offeringLetter->employee->name }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #111827;
            margin: 0;
            padding: 2.5cm 2.5cm;
            background: #fff;
        }

        .no-print-bar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: #1C2434;
            color: white;
            padding: 12px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-family: system-ui, sans-serif;
            font-size: 13px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .btn-print {
            background: #3C50E0;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 12px;
        }

        .btn-print:hover {
            background: #2F40BD;
        }

        .header-logo {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 12px;
            margin-bottom: 24px;
        }

        .company-name {
            font-size: 16pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0;
        }

        .company-sub {
            font-size: 10pt;
            color: #4B5563;
            margin: 4px 0 0 0;
        }

        .doc-title {
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            text-decoration: underline;
            margin-top: 16px;
            margin-bottom: 4px;
        }

        .doc-number {
            text-align: center;
            font-size: 11pt;
            margin-bottom: 24px;
        }

        table.info-table {
            width: 100%;
            margin: 16px 0;
            border-collapse: collapse;
        }

        table.info-table td {
            padding: 5px 0;
            vertical-align: top;
        }

        .signature-block {
            margin-top: 48px;
            display: flex;
            justify-content: space-between;
        }

        .sig-col {
            width: 45%;
            text-align: center;
        }

        .sig-space {
            height: 70px;
        }

        @media print {
            .no-print-bar {
                display: none !important;
            }

            body {
                padding: 1.5cm 1.5cm;
            }
        }
    </style>
</head>

<body>

    <div class="no-print-bar">
        <span>Pratinjau Cetak: <strong>Surat Penawaran Kerja (Offering Letter)</strong></span>
        <div>
            <button onclick="window.print()" class="btn-print">Cetak / Simpan PDF</button>
            <button onclick="window.close()" class="btn-print"
                style="background: #475569; margin-left: 8px;">Tutup</button>
        </div>
    </div>

    <!-- Letterhead -->
    <div class="header-logo">
        <h1 class="company-name">PT. CIPTA KARYA UTAMA</h1>
        <p class="company-sub">
            {{ $offeringLetter->office_address ?: 'Gedung Perkantoran Sudirman Central, Lantai 12, Jakarta Pusat' }}
        </p>
    </div>

    <!-- Title & Reference -->
    <div class="doc-title">SURAT PENAWARAN KERJA (OFFERING LETTER)</div>
    <div class="doc-number">Nomor: {{ $offeringLetter->letter_number }}</div>

    <p>
        {{ $offeringLetter->branch ? $offeringLetter->branch . ', ' : '' }}{{ $offeringLetter->offer_date->translatedFormat('d F Y') }}<br>
        Kepada Yth.<br>
        <strong>Sdr/i. {{ $offeringLetter->employee->name }}</strong><br>
        Di Tempat
    </p>

    <p>
        Dengan hormat,<br>
        Sehubungan dengan proses seleksi dan wawancara yang telah dilaksanakan, dengan ini Manajemen Perusahaan
        menyampaikan penawaran kerja untuk bergabung bersama PT. Cipta Karya Utama dengan rincian penempatan dan
        ketentuan rencana kontrak sebagai berikut:
    </p>

    <table class="info-table">
        <tr>
            <td style="width: 30%;">Nama Karyawan</td>
            <td style="width: 3%;">:</td>
            <td style="width: 67%;"><strong>{{ $offeringLetter->employee->name }}</strong></td>
        </tr>
        <tr>
            <td>Nomor Induk Kependudukan (NIK)</td>
            <td>:</td>
            <td>{{ $offeringLetter->employee->ktp_number }}</td>
        </tr>
        <tr>
            <td>Posisi / Jabatan Ditawarkan</td>
            <td>:</td>
            <td><strong>{{ $offeringLetter->position }}</strong></td>
        </tr>
        @if ($offeringLetter->bidang)
            <tr>
                <td>Bidang</td>
                <td>:</td>
                <td><strong>{{ $offeringLetter->bidang }}</strong></td>
            </tr>
        @endif
        @if ($offeringLetter->kode)
            <tr>
                <td>Kode Surat</td>
                <td>:</td>
                <td>{{ $offeringLetter->kode }}</td>
            </tr>
        @endif
        <tr>
            <td>Cabang / Lokasi Penempatan</td>
            <td>:</td>
            <td>{{ $offeringLetter->branch }}</td>
        </tr>
        <tr>
            <td>Tanggal Awal Kontrak</td>
            <td>:</td>
            <td>{{ $offeringLetter->proposed_start_date->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td>Tanggal Akhir Kontrak</td>
            <td>:</td>
            <td>
                {{ $offeringLetter->proposed_end_date->translatedFormat('d F Y') }}
                @php
                    $duration = $offeringLetter->proposed_start_date->diffInMonths($offeringLetter->proposed_end_date);
                @endphp
                @if ($duration > 0)
                    ({{ $duration }} Bulan)
                @endif
            </td>
        </tr>
    </table>

    <p>
        Demikian surat penawaran kerja ini kami sampaikan. Atas perhatian dan kesediaan Saudara/i, kami ucapkan terima
        kasih.
    </p>

    <!-- Signatures -->
    <div class="signature-block">
        <div class="sig-col">
            <p>Penerima Penawaran,</p>
            <div class="sig-space"></div>
            <p style="text-decoration: underline; font-weight: bold; margin-bottom: 2px;">
                {{ $offeringLetter->employee->name }}</p>
            <p style="font-size: 10pt; color: #4B5563; margin-top: 0;">Kandidat / Karyawan</p>
        </div>

        <div class="sig-col">
            <p>Hormat kami,<br><strong>PT. CIPTA KARYA UTAMA</strong></p>
            <div class="sig-space"></div>
            <p style="text-decoration: underline; font-weight: bold; margin-bottom: 2px;">
                {{ $offeringLetter->supervisor_name ?: 'Hendra Wijaya, S.Psi.' }}</p>
            <p style="font-size: 10pt; color: #4B5563; margin-top: 0;">
                {{ $offeringLetter->supervisor_position ?: 'Human Resources Manager' }}</p>
        </div>
    </div>

</body>

</html>
