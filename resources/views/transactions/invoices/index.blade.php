@extends('layouts.app')

@section('content')
    <div class="row align-items-center mb-4">
        <div class="col-sm-6">
            <h1 class="h3 mb-1">Invoices</h1>
            <p class="text-muted mb-0">Manage service transaction data</p>
        </div>
        <div class="col-sm-6">
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-primary" id="registerInvoiceBtn">
                    Register New Service
                </button>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <label for="statusFilter" class="me-2 mb-0">
                            Filter Status:
                        </label>
                        <div style="width: 180px;">
                            <select class="form-select form-select-sm" id="statusFilter">
                                <option value="">All Status</option>
                                <option value="received">Received</option>
                                <option value="repairing">Repairing</option>
                                <option value="completed">Completed</option>
                                <option value="picked_up">Picked Up</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped text-nowrap w-100" id="dataInvoices">
                            <thead>
                                <tr>
                                    <th style="width: 30px;">No</th>
                                    <th>Code</th>
                                    <th>Customer</th>
                                    <th>Device</th>
                                    <th>Received Date</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                    <th style="width: 100px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('transactions.invoices.partials.register-modal')
    @include('transactions.invoices.partials.detail-modal')
    @include('transactions.invoices.partials.script')
@endsection
