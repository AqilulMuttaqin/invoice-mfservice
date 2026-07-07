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

        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-top: 14px;
        }

        table.items th,
        table.items td {
            border: 1px solid #ccc;
            padding: 6px 8px;
        }

        table.items th {
            background: #f2f2f2;
            font-size: 11px;
            text-align: left;
        }

        table.items td.num {
            width: 30px;
            text-align: center;
        }

        table.items td.price {
            width: 130px;
            text-align: right;
        }

        table.total {
            width: 100%;
            margin-top: 6px;
        }

        table.total td {
            padding: 6px 8px;
        }

        table.total .total-label {
            text-align: right;
            font-weight: bold;
            width: 85%;
        }

        table.total .total-value {
            text-align: right;
            font-weight: bold;
            border-top: 2px solid #222;
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

        .thanks {
            text-align: center;
            margin-top: 20px;
            font-style: italic;
            color: #444;
        }

        table.signature {
            width: 100%;
            margin-top: 40px;
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

        .qr-cell {
            width: 80px;
            text-align: right;
        }

        .qr-cell img {
            width: 65px;
            height: 65px;
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
            <td class="qr-cell">
                <img src="{{ $qrCode }}" alt="QR Code">
            </td>
        </tr>
    </table>

    <div class="doc-title">Invoice Service</div>

    <table class="meta">
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
            <td class="label">Merk/Tipe</td>
            <td>: {{ $invoice->deviceType->name }}</td>
        </tr>
        <tr>
            <td class="label">Keluhan</td>
            <td>: {{ $invoice->complaint }}</td>
        </tr>
    </table>

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
                            &times; {{ $item->qty }}
                        @endif
                    </td>
                    <td class="price">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="total">
        <tr>
            <td class="total-label">Total</td>
            <td class="total-value" style="width: 130px;">Rp {{ number_format($invoice->grand_total, 0, ',', '.') }}
            </td>
        </tr>
    </table>

    <div class="notes-box">
        <div class="notes-title">Catatan:</div>
        <div>
            {{ str_replace([':warranty_days', ':warranty_claim'], [(int) $invoice->warranty_days, (int) $invoice->remaining_warranty_claim], config('company.warranty_note')) }}
        </div>
    </div>

    <div class="notes-box">
        <div class="notes-title">Catatan Teknisi:</div>
        <div>{{ $invoice->technician_notes ?: '-' }}</div>
    </div>

    <div class="thanks">Terima kasih telah menggunakan jasa kami.</div>

    <table class="signature">
        <tr>
            <td>Pelanggan,</td>
            <td>MF Service,</td>
        </tr>
        <tr>
            <td>{{ ($invoice->customer_name) }}</td>
            <td>{{ (auth()->user()->name) }}</td>
        </tr>
    </table>
</body>

</html>
