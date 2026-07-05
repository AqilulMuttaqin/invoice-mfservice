<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #222;
            margin: 0;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #222;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }

        .header .company-name {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .header .company-detail {
            font-size: 11px;
            color: #555;
        }

        .doc-title {
            text-align: center;
            font-size: 15px;
            font-weight: bold;
            letter-spacing: 1px;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        table.meta {
            width: 100%;
            margin-bottom: 16px;
        }

        table.meta td {
            padding: 2px 0;
            vertical-align: top;
        }

        table.meta td.label {
            width: 130px;
            color: #555;
        }

        .section-title {
            font-size: 12px;
            font-weight: bold;
            background: #f2f2f2;
            padding: 5px 8px;
            margin-bottom: 8px;
            margin-top: 14px;
        }

        table.detail {
            width: 100%;
            margin-bottom: 4px;
        }

        table.detail td {
            padding: 3px 0;
            vertical-align: top;
        }

        table.detail td.label {
            width: 140px;
            color: #555;
        }

        ul.condition {
            margin: 4px 0 4px 18px;
            padding: 0;
        }

        ul.condition li {
            margin-bottom: 2px;
        }

        .notes-box {
            border: 1px solid #ccc;
            background: #fafafa;
            padding: 10px 14px;
            margin-top: 18px;
            font-size: 11px;
        }

        .notes-box .notes-title {
            font-weight: bold;
            margin-bottom: 6px;
        }

        .notes-box ol {
            margin: 0;
            padding-left: 16px;
        }

        .notes-box li {
            margin-bottom: 3px;
        }

        table.signature {
            width: 100%;
            margin-top: 50px;
        }

        table.signature td {
            width: 50%;
            text-align: center;
            font-size: 12px;
        }

        table.signature .signature-line {
            padding-top: 60px;
        }

        table.header-table {
            width: 100%;
            border-bottom: 2px solid #222;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }

        table.header-table td {
            vertical-align: middle;
        }

        .logo-cell {
            width: 80px;
        }

        .logo-cell img {
            max-width: 70px;
            max-height: 70px;
        }

        .company-cell {
            text-align: center;
        }

        .company-cell .company-name {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .company-cell .company-detail {
            font-size: 11px;
            color: #555;
        }

        .spacer-cell {
            width: 80px;
        }
    </style>
</head>

<body>
    <table class="header-table">
        <tr>
            <td class="logo-cell">
                @if ($companyLogo)
                    <img src="{{ $companyLogo }}" alt="Logo">
                @endif
            </td>
            <td class="company-cell">
                <div class="company-name">{{ config('company.name') }}</div>
                <div class="company-detail">{{ config('company.address') }}</div>
                <div class="company-detail">Telp. {{ config('company.phone') }}</div>
            </td>
            <td class="spacer-cell"></td>
        </tr>
    </table>

    <div class="doc-title">Bukti Penerimaan Service</div>

    <table class="meta">
        <tr>
            <td class="label">No Service</td>
            <td>: {{ $invoice->service_code }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Masuk</td>
            <td>: {{ $invoice->received_date->translatedFormat('d F Y') }}</td>
        </tr>
    </table>

    <div class="section-title">Data Pelanggan</div>
    <table class="detail">
        <tr>
            <td class="label">Nama</td>
            <td>: {{ $invoice->customer_name }}</td>
        </tr>
        <tr>
            <td class="label">No HP</td>
            <td>: {{ $invoice->customer_phone }}</td>
        </tr>
    </table>

    <div class="section-title">Data Perangkat</div>
    <table class="detail">
        <tr>
            <td class="label">Merk/Type</td>
            <td>: {{ $invoice->deviceType->name }}</td>
        </tr>
        <tr>
            <td class="label">IMEI</td>
            <td>: {{ $invoice->imei_serial_number ?? '-' }}</td>
        </tr>
    </table>

    @if ($invoice->physical_condition)
        <div class="section-title">Kondisi Fisik</div>
        <ul class="condition">
            @foreach (explode("\n", trim($invoice->physical_condition)) as $line)
                @if (trim($line))
                    <li>{{ trim($line) }}</li>
                @endif
            @endforeach
        </ul>
    @endif

    <div class="section-title">Keluhan &amp; Estimasi</div>
    <table class="detail">
        <tr>
            <td class="label">Keluhan</td>
            <td>: {{ $invoice->complaint }}</td>
        </tr>
        <tr>
            <td class="label">Estimasi Biaya</td>
            <td>: Rp {{ number_format($invoice->grand_total, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="label">Estimasi Selesai</td>
            <td>: {{ $invoice->estimated_finish_day ?? '-' }} Hari</td>
        </tr>
    </table>

    <div class="notes-box">
        <div class="notes-title">Catatan:</div>
        <ol>
            <li>Barang yang tidak diambil lebih dari 30 hari menjadi tanggung jawab pemilik.</li>
            <li>Kerusakan akibat cacat komponen tidak menjadi tanggung jawab toko.</li>
            <li>Estimasi biaya dapat berubah setelah pengecekan teknisi.</li>
        </ol>
    </div>

    <table class="signature">
        <tr>
            <td>Pelanggan,</td>
            <td>Penerima,</td>
        </tr>
        <tr>
            <td>{{ ($invoice->customer_name) }}</td>
            <td>{{ (auth()->user()->name) }}</td>
        </tr>
    </table>
</body>

</html>
