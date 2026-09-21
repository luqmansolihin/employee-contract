<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Perjanjian Kerja {{ $contract->contract_type }} - {{ $contract->contract_number }} -
        {{ $contract->employee->name }}</title>
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
            font-size: 13pt;
            font-weight: bold;
            text-decoration: underline;
            margin-top: 16px;
            margin-bottom: 4px;
            text-transform: uppercase;
        }

        .doc-number {
            text-align: center;
            font-size: 11pt;
            margin-bottom: 24px;
        }

        table.party-table {
            width: 100%;
            margin-bottom: 16px;
            border-collapse: collapse;
        }

        table.party-table td {
            padding: 3px 0;
            vertical-align: top;
        }

        .article-title {
            font-weight: bold;
            text-align: center;
            margin-top: 20px;
            margin-bottom: 6px;
            text-transform: uppercase;
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
        <span>Pratinjau Cetak: <strong>Surat Perjanjian Kerja ({{ $contract->contract_type }})</strong></span>
        <div>
            <button onclick="window.print()" class="btn-print">Cetak / Simpan PDF</button>
            <button onclick="window.close()" class="btn-print"
                style="background: #475569; margin-left: 8px;">Tutup</button>
        </div>
    </div>

    <!-- Letterhead -->
    <div class="header-logo">
        <h1 class="company-name">PT. CIPTA KARYA UTAMA</h1>
        <p class="company-sub">Gedung Perkantoran Sudirman Central, Lantai 12, Jakarta Pusat • Telp: (021) 555-0199 •
            Email: hrd@ciptakarya.co.id</p>
    </div>

    @php
        $titleName = match ($contract->contract_type) {
            'PKWT' => 'PERJANJIAN KERJA WAKTU TERTENTU (PKWT)',
            'MT' => 'PERJANJIAN PROGRAM MANAGEMENT TRAINEE (MT)',
            'MAGANG' => 'PERJANJIAN PROGRAM PEMAGANGAN (MAGANG)',
            default => 'PERJANJIAN KERJA',
        };
    @endphp

    <div class="doc-title">SURAT {{ $titleName }}</div>
    <div class="doc-number">Nomor: {{ $contract->contract_number ?: 'PKWT/' . date('Y') . '/' . $contract->id }}</div>

    <p>
        Pada hari ini, <strong>{{ Carbon\Carbon::parse($contract->start_date)->translatedFormat('l, d F Y') }}</strong>,
        bertempat di Jakarta, telah dibuat dan ditandatangani perjanjian kerja oleh dan antara pihak-pihak:
    </p>

    <table class="party-table">
        <tr>
            <td style="width: 5%;"><strong>I.</strong></td>
            <td style="width: 25%;">Nama Perusahaan</td>
            <td style="width: 3%;">:</td>
            <td style="width: 67%;"><strong>PT. CIPTA KARYA UTAMA</strong></td>
        </tr>
        <tr>
            <td></td>
            <td>Diwakili Oleh</td>
            <td>:</td>
            <td>Hendra Wijaya, S.Psi. (Human Resources Manager)</td>
        </tr>
        <tr>
            <td></td>
            <td>Alamat Kantor</td>
            <td>:</td>
            <td>Gedung Sudirman Central Lt. 12, Jakarta Pusat</td>
        </tr>
        <tr>
            <td></td>
            <td colspan="3" style="padding-top: 4px;">Dalam hal ini bertindak untuk dan atas nama <strong>PT. CIPTA
                    KARYA UTAMA</strong>, selanjutnya disebut sebagai <strong>PIHAK PERTAMA</strong>.</td>
        </tr>
    </table>

    <table class="party-table">
        <tr>
            <td style="width: 5%;"><strong>II.</strong></td>
            <td style="width: 25%;">Nama Lengkap</td>
            <td style="width: 3%;">:</td>
            <td style="width: 67%;"><strong>{{ $contract->employee->name }}</strong></td>
        </tr>
        <tr>
            <td></td>
            <td>No. KTP (NIK)</td>
            <td>:</td>
            <td>{{ $contract->employee->ktp_number }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Tempat, Tgl Lahir</td>
            <td>:</td>
            <td>{{ $contract->employee->birth_place }},
                {{ $contract->employee->birth_date->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Alamat Domisili</td>
            <td>:</td>
            <td>{{ $contract->employee->address }}</td>
        </tr>
        <tr>
            <td></td>
            <td colspan="3" style="padding-top: 4px;">Dalam hal ini bertindak untuk dan atas nama diri sendiri,
                selanjutnya disebut sebagai <strong>PIHAK KEDUA</strong>.</td>
        </tr>
    </table>

    <p>
        PIHAK PERTAMA dan PIHAK KEDUA secara bersama-sama disebut <strong>PARA PIHAK</strong>, sepakat untuk mengikatkan
        diri dalam Perjanjian Kerja dengan ketentuan dan syarat-syarat sebagai berikut:
    </p>

    <!-- Pasal 1 -->
    <div class="article-title">Pasal 1<br>JANGKA WAKTU PERJANJIAN</div>
    <p>
        1. Hubungan kerja antara PIHAK PERTAMA dan PIHAK KEDUA ini berlaku untuk jangka waktu tertentu, terhitung mulai
        tanggal <strong>{{ $contract->start_date->translatedFormat('d F Y') }}</strong> sampai dengan tanggal
        <strong>{{ $contract->end_date->translatedFormat('d F Y') }}</strong>.<br>
        2. Perjanjian ini dapat diperpanjang atau diubah melalui kesepakatan tertulis PARA PIHAK yang dituangkan dalam
        bentuk <strong>Adendum Perjanjian Kerja</strong>.
    </p>

    <!-- Pasal 2 -->
    <div class="article-title">Pasal 2<br>JABATAN DAN TEMPAT PENEMPATAN</div>
    <p>
        1. PIHAK PERTAMA mempekerjakan PIHAK KEDUA sebagai <strong>{{ $contract->position }}</strong> dengan lokasi
        penempatan di <strong>{{ $contract->branch }}</strong>.<br>
        2. PIHAK PERTAMA berhak melakukan mutasi, rotasi, atau penyesuaian tugas sesuai dengan kebutuhan operasional
        perusahaan.
    </p>

    <!-- Pasal 3 -->
    <div class="article-title">Pasal 3<br>UPAH DAN KOMPENSASI</div>
    <p>
        1. PIHAK PERTAMA memberikan upah kepada PIHAK KEDUA sebesar
        <strong>{{ $contract->formatted_salary ?: 'Rp 0' }}</strong> per bulan.<br>
        @if ((float) $contract->allowance > 0)
            2. PIHAK KEDUA berhak atas tunjangan sebesar <strong>{{ $contract->formatted_allowance }}</strong> per
            bulan.<br>
        @endif
        3. Pembayaran upah dibayarkan setiap akhir bulan kalender melalui transfer rekening bank.
    </p>

    <!-- Pasal 4 -->
    <div class="article-title">Pasal 4<br>PENUTUP</div>
    <p>
        Demikian Perjanjian Kerja ini dibuat dalam rangkap 2 (dua) bermaterai cukup dan memiliki kekuatan hukum yang
        sama bagi PARA PIHAK.
    </p>

    <!-- Signatures -->
    <div class="signature-block">
        <div class="sig-col">
            <p>PIHAK KEDUA,<br>Karyawan</p>
            <div class="sig-space"></div>
            <p style="text-decoration: underline; font-weight: bold; margin-bottom: 2px;">
                {{ $contract->employee->name }}</p>
            <p style="font-size: 10pt; color: #4B5563; margin-top: 0;">NIK: {{ $contract->employee->ktp_number }}</p>
        </div>

        <div class="sig-col">
            <p>PIHAK PERTAMA,<br><strong>PT. CIPTA KARYA UTAMA</strong></p>
            <div class="sig-space"></div>
            <p style="text-decoration: underline; font-weight: bold; margin-bottom: 2px;">Hendra Wijaya, S.Psi.</p>
            <p style="font-size: 10pt; color: #4B5563; margin-top: 0;">HR Manager</p>
        </div>
    </div>

</body>

</html>
