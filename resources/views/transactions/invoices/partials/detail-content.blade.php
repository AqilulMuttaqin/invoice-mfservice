<div class="row align-items-center mb-3">
    <div class="col-sm-8">
        <p class="fw-medium mb-1" style="font-size: 16px;">{{ $invoice->service_code }}</p>
        <p class="text-muted small mb-0">{{ $invoice->customer_name }} &middot; {{ $invoice->deviceType->name }}</p>
    </div>
    <div class="col-sm-4 d-flex justify-content-end">
        @include('transactions.invoices.partials.status-badge', ['status' => $invoice->status])
    </div>
</div>

<div class="row g-3 mb-3 border-bottom pb-3">
    <div class="col-sm-3">
        <p class="text-muted small mb-1">Received Date</p>
        <p class="mb-0">{{ $invoice->received_date?->translatedFormat('d M Y') }}</p>
    </div>
    <div class="col-sm-3">
        <p class="text-muted small mb-1">Estimated Finish</p>
        <p class="mb-0">{{ $invoice->estimated_finish_day ? $invoice->estimated_finish_day . ' days' : '-' }}</p>
    </div>
    <div class="col-sm-3">
        <p class="text-muted small mb-1">Customer Phone</p>
        <p class="mb-0">{{ $invoice->customer_phone }}</p>
    </div>
    <div class="col-sm-3">
        <p class="text-muted small mb-1">IMEI / Serial</p>
        <p class="mb-0">{{ $invoice->imei_serial_number ?? '-' }}</p>
    </div>
    <div class="col-sm-6">
        <p class="text-muted small mb-1">Physical Condition</p>
        <p class="mb-0" style="white-space: pre-line;">{{ $invoice->physical_condition ?? '-' }}</p>
    </div>
    <div class="col-sm-6">
        <p class="text-muted small mb-1">Complaint</p>
        <p class="mb-0" style="white-space: pre-line;">{{ $invoice->complaint }}</p>
    </div>
</div>

<p class="fw-medium mb-2">Service Items</p>

@if (in_array($invoice->status, ['received', 'repairing']))
    <div id="itemsAlert" class="alert alert-danger py-2 d-none"></div>
    <div class="d-flex gap-2 mb-3">
        <select class="form-select" id="serviceSelect">
            <option value="">-- Select service --</option>
            @foreach ($services as $service)
                <option value="{{ $service->id }}">
                    {{ $service->name }} — Rp {{ number_format($service->price, 0, ',', '.') }}
                </option>
            @endforeach
        </select>
        <input type="number" id="itemQty" min="1" value="1" class="form-control" style="width: 80px;">
        <button type="button" class="btn btn-outline-primary text-nowrap" id="addItemBtn">
            <i class="fa fa-plus"></i> Add
        </button>
    </div>
@endif

<table class="table table-sm table-bordered mb-3">
    <thead class="table-light">
        <tr>
            <th>Service</th>
            <th style="width: 70px;">Qty</th>
            <th style="width: 130px;" class="text-end">Price</th>
            <th style="width: 130px;" class="text-end">Subtotal</th>
            @if (in_array($invoice->status, ['received', 'repairing']))
                <th style="width: 30px;"></th>
            @endif
        </tr>
    </thead>
    <tbody>
        @forelse ($invoice->items as $item)
            <tr data-item-id="{{ $item->id }}">
                <td>{{ $item->service->name ?? '-' }}</td>
                <td>{{ $item->qty }}</td>
                <td class="text-end">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                <td class="text-end">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                @if (in_array($invoice->status, ['received', 'repairing']))
                    <td class="text-center">
                        <i class="fa fa-trash text-danger item-remove" role="button"></i>
                    </td>
                @endif
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center text-muted py-2">No service item added yet</td>
            </tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr>
            <td colspan="{{ in_array($invoice->status, ['received', 'repairing']) ? 3 : 2 }}"></td>
            <td class="text-end fw-medium">Total</td>
            <td class="text-end fw-medium">Rp {{ number_format($invoice->grand_total, 0, ',', '.') }}</td>
        </tr>
    </tfoot>
</table>

<p class="fw-medium mb-2">Technician Notes</p>
<textarea class="form-control mb-2" id="technicianNotes" rows="2">{{ $invoice->technician_notes }}</textarea>
<div class="d-flex justify-content-end mb-4">
    <button type="button" class="btn btn-sm btn-outline-primary" id="saveNotesBtn">Save Notes</button>
</div>

<div class="d-flex flex-wrap gap-2 border-top pt-3">
    <a href="{{ route('invoices.print-receipt', $invoice->id) }}" target="_blank" class="btn btn-secondary">
        <i class="fa fa-file-alt"></i> Print Receipt
    </a>

    @if (in_array($invoice->status, ['picked_up']))
        <a href="{{ route('invoices.print-invoice', $invoice->id) }}" target="_blank" class="btn btn-primary">
            <i class="fa fa-file-invoice"></i> Print Invoice
        </a>
    @else
        <button type="button" class="btn btn-outline-secondary" disabled>
            <i class="fa fa-file-invoice"></i> Print Invoice
        </button>
    @endif

    <div class="ms-auto d-flex gap-2">
        @if ($invoice->status === 'received')
            <button type="button" class="btn btn-outline-danger" data-status="cancelled">Cancel</button>
            <button type="button" class="btn btn-warning status-btn" data-status="repairing">Start Repairing</button>
        @elseif ($invoice->status === 'repairing')
            <button type="button" class="btn btn-outline-danger" data-status="cancelled">Cancel</button>
            <button type="button" class="btn btn-success status-btn" data-status="completed">Mark as Completed</button>
        @elseif ($invoice->status === 'completed')
            <button type="button" class="btn btn-primary status-btn" data-status="picked_up">Mark as Picked Up</button>
        @endif
    </div>
</div>
