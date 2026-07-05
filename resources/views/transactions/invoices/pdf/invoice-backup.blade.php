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
            width: 90px;
        }

        table.items {
            width: 100%;
            margin-top: 6px;
            border-collapse: collapse;
        }

        table.items th,
        table.items td {
            padding: 2px 0;
        }

        table.items th.num,
        table.items td.num {
            width: 20px;
        }

        table.items th.price,
        table.items td.price {
            width: 90px;
            text-align: right;
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
    <div class="center">Telp: {{ config('company.phone') }}</div>

    <div class="divider"></div>
    <div class="center bold">INVOICE SERVICE</div>
    <div class="divider"></div>

    <table class="info">
        <tr>
            <td class="label">No Invoice</td>
            <td>: {{ $invoice->invoice_code }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Service</td>
            <td>: {{ $invoice->received_date->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Selesai</td>
            <td>: {{ $invoice->completed_date?->translatedFormat('d F Y') ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Diambil</td>
            <td>: {{ $invoice->picked_up_date?->translatedFormat('d F Y') ?? '-' }}</td>
        </tr>
    </table>

    <div class="bold" style="margin-top: 8px;">Data Pelanggan</div>
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

    <div class="bold" style="margin-top: 8px;">Data Perangkat</div>
    <table class="info">
        <tr>
            <td class="label">Merk/Tipe</td>
            <td>: {{ $invoice->deviceType->name }}</td>
        </tr>
        <tr>
            <td class="label">Keluhan</td>
            <td>: {{ $invoice->complaint }}</td>
        </tr>
    </table>

    <div class="divider"></div>
    <table class="items">
        <thead>
            <tr>
                <th class="num">No</th>
                <th>Layanan</th>
                <th class="price">Harga</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoice->items as $index => $item)
                <tr>
                    <td class="num">{{ $index + 1 }}</td>
                    <td>{{ $item->service->name ?? '-' }} @if ($item->qty > 1)
                            x{{ $item->qty }}
                        @endif
                    </td>
                    <td class="price">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="divider"></div>

    <table class="info">
        <tr>
            <td class="label bold">Total</td>
            <td class="bold" style="text-align: right; padding-right: 2px;">
                Rp{{ number_format($invoice->grand_total, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="bold" style="margin-top: 10px;">Catatan:</div>
    <div>
        {{ str_replace([':warranty_days', ':warranty_claim'], [(int) $invoice->warranty_days, (int) $invoice->remaining_warranty_claim], config('company.warranty_note')) }}
    </div>

    <div class="bold" style="margin-top: 8px;">Catatan Teknisi:</div>
    <div>{{ $invoice->technician_notes ?: '-' }}</div>

    <div class="divider" style="margin-top: 10px;"></div>
    <div class="center">Terima kasih telah menggunakan jasa kami.</div>

    <table class="signature">
        <tr>
            <td>Pelanggan,</td>
            <td>Teknisi,</td>
        </tr>
        <tr>
            <td class="signature-space">(.............)</td>
            <td class="signature-space">(.............)</td>
        </tr>
    </table>
</body>

</html>
