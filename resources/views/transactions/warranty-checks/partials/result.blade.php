@if (!$invoice)
    <div style="border: 0.5px solid #f5c2c7; background: #f8d7da; border-radius: 8px; padding: 1rem;">
        <p class="mb-0 text-danger">{{ $validity['reason'] }}</p>
    </div>
@else
    <div class="d-flex justify-content-between align-items-center mb-3">
        @if ($validity['valid'])
            <span class="badge bg-success">Warranty is still valid</span>
            <span class="text-muted small">
                {{ $validity['days_remaining'] }} days remaining &middot; {{ $invoice->remaining_warranty_claim }} of
                claims left
            </span>
        @else
            <span class="badge bg-danger">{{ $validity['reason'] }}</span>
            @if (isset($validity['expiry_date']))
                <span class="text-muted small">Expired on
                    {{ $validity['expiry_date']->translatedFormat('d M Y') }}</span>
            @endif
        @endif
    </div>

    <div class="row g-3 border-top pt-3 mb-3">
        <div class="col-sm-4">
            <p class="text-muted small mb-1">No Invoice</p>
            <p class="mb-0">{{ $invoice->invoice_code }}</p>
        </div>
        <div class="col-sm-4">
            <p class="text-muted small mb-1">Customer</p>
            <p class="mb-0">{{ $invoice->customer_name }}</p>
        </div>
        <div class="col-sm-4">
            <p class="text-muted small mb-1">Device</p>
            <p class="mb-0">{{ $invoice->deviceType->name }}</p>
        </div>
        <div class="col-sm-4">
            <p class="text-muted small mb-1">Picked Up Date</p>
            <p class="mb-0">{{ $invoice->picked_up_date?->translatedFormat('d M Y') ?? '-' }}</p>
        </div>
        <div class="col-sm-4">
            <p class="text-muted small mb-1">Valid Until</p>
            <p class="mb-0">
                {{ $invoice->picked_up_date?->copy()->addDays((int) $invoice->warranty_days)->translatedFormat('d M Y') ?? '-' }}
            </p>
        </div>
        <div class="col-sm-4">
            <p class="text-muted small mb-1">Previous Services</p>
            <p class="mb-0">{{ $invoice->items->pluck('service.name')->filter()->implode(', ') ?: '-' }}</p>
        </div>
    </div>

    @if ($invoice->warrantyClaims->isNotEmpty())
        <div class="border-top pt-3 mb-3">
            <p class="fw-medium mb-2">Claim History</p>
            <table class="table table-sm table-bordered mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 130px;">Date</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invoice->warrantyClaims as $claim)
                        <tr>
                            <td>{{ $claim->claimed_at->translatedFormat('d M Y') }}</td>
                            <td>{{ $claim->description }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    @if ($validity['valid'])
        <div class="border-top pt-3">
            <div id="claimAlert" class="alert alert-danger py-2 d-none"></div>
            <label for="claimDescription" class="form-label">Describe the issue being claimed</label>
            <textarea class="form-control mb-2" id="claimDescription" rows="2" placeholder="e.g. LCD showing lines again"></textarea>
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-success" id="submitClaimBtn"
                    data-invoice-id="{{ $invoice->id }}">
                    Submit Warranty Claim
                </button>
            </div>
        </div>
    @endif
@endif
