<div class="d-flex justify-content-center gap-1">
    <button type="button" class="btn btn-sm btn-outline-info btn-view-detail" data-id="{{ $invoice->id }}" title="View Detail">
        Detail
    </button>

    {{-- @if ($invoice->status !== 'cancelled')
        <a href="{{ route('invoices.print-receipt', $invoice->id) }}" target="_blank" class="btn btn-sm btn-secondary"
            title="Print Receipt">
            <i class="fa fa-file-alt"></i>
        </a>

        @if ($invoice->status === 'picked_up')
            <a href="{{ route('invoices.print-invoice', $invoice->id) }}" target="_blank" class="btn btn-sm btn-primary"
                title="Print Invoice">
                <i class="fa fa-file-invoice"></i>
            </a>
        @else
            <button type="button" class="btn btn-sm btn-outline-secondary" disabled
                title="Available after item is picked up">
                <i class="fa fa-file-invoice"></i>
            </button>
        @endif
    @endif --}}
</div>
