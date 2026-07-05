<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: 'Courier New', monospace;
            font-size: 11px;
            line-height: 1.4;
            margin: 0;
            padding: 8px;
        }

        .center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        .divider {
            border-top: 1px dashed #000;
            margin: 6px 0;
        }

        table.info td {
            padding: 1px 0;
            vertical-align: top;
        }

        table.info td.label {
            width: 85px;
        }

        ul.condition {
            margin: 2px 0 2px 14px;
            padding: 0;
        }

        ul.condition li {
            margin: 0;
        }

        table.signature {
            width: 100%;
            margin-top: 30px;
        }

        table.signature td {
            width: 50%;
            text-align: center;
        }

        .signature-space {
            margin-top: 40px;
        }
    </style>
</head>

<body>
    <div class="center bold">{{ config('company.name') }}</div>
    <div class="center">{{ config('company.address') }}</div>
    <div class="center">Telp. {{ config('company.phone') }}</div>

    <div class="divider"></div>
    <div class="center bold">BUKTI PENERIMAAN SERVICE</div>
    <div class="divider"></div>

    <table class="info">
        <tr>
            <td class="label">No Service</td>
            <td>: {{ $invoice->service_code }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Masuk</td>
            <td>: {{ $invoice->received_date->translatedFormat('d F Y') }}</td>
        </tr>
    </table>

    <div class="bold" style="margin-top: 8px;">DATA PELANGGAN</div>
    <table class="info">
        <tr>
            <td class="label">Nama</td>
            <td>: {{ $invoice->customer_name }}</td>
        </tr>
        <tr>
            <td class="label">No HP</td>
            <td>: {{ $invoice->customer_phone }}</td>
        </tr>
    </table>

    <div class="bold" style="margin-top: 8px;">DATA PERANGKAT</div>
    <table class="info">
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
        <div class="bold" style="margin-top: 8px;">KONDISI FISIK</div>
        <ul class="condition">
            @foreach (explode("\n", trim($invoice->physical_condition)) as $line)
                @if (trim($line))
                    <li>{{ trim($line) }}</li>
                @endif
            @endforeach
        </ul>
    @endif

    <table class="info" style="margin-top: 6px;">
        <tr>
            <td class="label">Keluhan</td>
            <td>: {{ $invoice->complaint }}</td>
        </tr>
        <tr>
            <td class="label">Estimasi Biaya</td>
            <td>: Rp{{ number_format($invoice->grand_total, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="label">Estimasi Selesai</td>
            <td>: {{ $invoice->estimated_finish_day ?? '-' }} Hari</td>
        </tr>
    </table>

    <div class="divider" style="margin-top: 10px;"></div>
    <div class="bold">Catatan:</div>
    <div>1. Barang yang tidak diambil lebih dari 30 hari menjadi tanggung jawab pemilik.</div>
    <div>2. Kerusakan akibat cacat komponen tidak menjadi tanggung jawab toko.</div>
    <div>3. Estimasi biaya dapat berubah setelah pengecekan teknisi.</div>
    <div class="divider"></div>

    <table class="signature">
        <tr>
            <td>Pelanggan,</td>
            <td>Penerima,</td>
        </tr>
        <tr>
            <td class="signature-space">(.............)</td>
            <td class="signature-space">(.............)</td>
        </tr>
    </table>
</body>

</html>
