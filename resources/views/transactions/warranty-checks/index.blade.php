@extends('layouts.app')

@section('content')
    <div class="row align-items-center mb-4">
        <div class="col-sm-8">
            <h1 class="h3 mb-1">Warranty Checks</h1>
            <p class="text-muted mb-0">Verify warranty validity by scanning or entering an invoice code</p>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex gap-2 mb-2">
                        <input type="text" class="form-control" id="invoiceCodeInput" placeholder="INV-20260604-001">
                        <button type="button" class="btn btn-primary text-nowrap" id="checkBtn">Check</button>
                    </div>
                    <button type="button" class="btn btn-outline-secondary w-100" id="scanQrBtn">
                        <i class="fa fa-qrcode"></i> Scan QR Code
                    </button>
                </div>
            </div>

            <div class="card" id="resultCard" style="display: none;">
                <div class="card-body" id="resultContainer">
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="qrScannerModal" tabindex="-1" role="dialog" aria-labelledby="qrScannerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="qrScannerModalLabel">Scan QR Code</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="qrReader" style="width: 100%;"></div>
                </div>
            </div>
        </div>
    </div>

    @include('transactions.warranty-checks.partials.script')
@endsection
