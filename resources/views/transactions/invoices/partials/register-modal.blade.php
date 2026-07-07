<div class="modal fade" id="registerInvoiceModal" tabindex="-1" role="dialog" aria-labelledby="registerInvoiceModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="registerInvoiceModalLabel">Register New Service</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="invoiceForm">
                <div class="modal-body">
                    <p class="fw-medium text-muted small mb-2">Customer Data</p>
                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <label for="customer_name" class="form-label">Customer Name</label>
                            <input type="text" class="form-control" id="customer_name" name="customer_name" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-sm-6">
                            <label for="customer_phone" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="customer_phone" name="customer_phone"
                                required>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                    <p class="fw-medium text-muted small mb-2">Device Data</p>
                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <label for="device_type_id" class="form-label">Device Type</label>
                            <select class="form-select" id="device_type_id" name="device_type_id" required>
                                <option value="">-- Select Device Type --</option>
                                @foreach ($deviceTypes as $deviceType)
                                    <option value="{{ $deviceType->id }}" data-name="{{ $deviceType->name }}">
                                        {{ $deviceType->name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-sm-6">
                            <label for="device_name" class="form-label">Device Name</label>
                            <input type="text" class="form-control" id="device_name" name="device_name" required>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="imei_serial_number" class="form-label">IMEI / Serial Number</label>
                        <input type="text" class="form-control" id="imei_serial_number" name="imei_serial_number">
                        <div class="invalid-feedback"></div>
                    </div>

                    <p class="fw-medium text-muted small mb-2">Condition & Complaint</p>
                    <div class="mb-3">
                        <label for="physical_condition" class="form-label">Physical Condition <span
                                class="text-muted">(one point per line)</span></label>
                        <textarea class="form-control" id="physical_condition" name="physical_condition" rows="2"></textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="complaint" class="form-label">Complaint</label>
                        <textarea class="form-control" id="complaint" name="complaint" rows="2" required></textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3" style="max-width: 220px;">
                        <label for="estimated_finish_day" class="form-label">Estimated Finish (days)</label>
                        <input type="number" min="1" class="form-control" id="estimated_finish_day"
                            name="estimated_finish_day">
                        <div class="invalid-feedback"></div>
                    </div>

                    <p class="fw-medium text-muted small mb-2">Estimated Services</p>
                    <div id="itemsAlert" class="alert alert-danger py-2 d-none"></div>
                    <div class="d-flex gap-2 mb-2">
                        <select class="form-select" id="serviceSelect" disabled>
                            <option value="">-- Select device type first --</option>
                        </select>
                        <button type="button" class="btn btn-outline-primary text-nowrap" id="addItemBtn" disabled>
                            <i class="fa fa-plus"></i> Add
                        </button>
                    </div>
                    <table class="table table-sm table-bordered mb-0" id="itemsTable">
                        <thead class="table-light">
                            <tr>
                                <th>Service</th>
                                <th style="width: 70px;">Qty</th>
                                <th style="width: 130px;" class="text-end">Price</th>
                                <th style="width: 30px;"></th>
                            </tr>
                        </thead>
                        <tbody id="itemsTableBody">
                            <tr id="itemsEmptyRow">
                                <td colspan="4" class="text-center text-muted py-2">No service added yet</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2"></td>
                                <td class="text-end fw-medium">Total</td>
                                <td class="text-end fw-medium" id="itemsGrandTotal">Rp 0</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-outline-secondary"
                        data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-sm btn-primary" id="submitInvoiceBtn">Save &
                        Register</button>
                </div>
            </form>
        </div>
    </div>
</div>
