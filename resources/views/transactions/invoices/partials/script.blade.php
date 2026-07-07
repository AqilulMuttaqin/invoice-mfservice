@push('script')
    <script>
        $(document).ready(function() {
            var table = $('#dataInvoices').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    url: "{{ route('invoices.index') }}",
                    data: function(d) {
                        d.status = $('#statusFilter').val();
                    }
                },
                order: [
                    [4, 'desc']
                ], // tanggal masuk terbaru duluan
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'code',
                        name: 'service_code',
                        orderable: false,
                        searchable: true
                    },
                    {
                        data: 'customer_name',
                        name: 'customer_name',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'device_type',
                        name: 'device_types.name',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'received_date',
                        name: 'received_date',
                        orderable: true,
                        searchable: false
                    },
                    {
                        data: 'status_badge',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'grand_total',
                        name: 'grand_total',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                columnDefs: [{
                        targets: 0,
                        width: '30px',
                        className: 'text-center'
                    },
                    {
                        targets: [5, 7],
                        className: 'text-center'
                    },
                    {
                        targets: 6,
                        className: 'text-end'
                    }
                ],
            });

            $('#statusFilter').on('change', function() {
                table.ajax.reload();
            });

            // ================= REGISTER INVOICE MODAL =================
            var items = []; // {service_id, name, price, qty}

            function formatCurrency(value) {
                return 'Rp ' + Number(value).toLocaleString('id-ID');
            }

            function clearValidationErrors() {
                $('#invoiceForm').find('.is-invalid').removeClass('is-invalid');
                $('#invoiceForm').find('.invalid-feedback').text('');
                $('#itemsAlert').addClass('d-none').text('');
            }

            function showValidationErrors(errors) {
                $.each(errors, function(field, messages) {
                    if (field === 'items' || field.startsWith('items.')) {
                        $('#itemsAlert').removeClass('d-none').text(messages[0]);
                        return;
                    }
                    var input = $('#' + field);
                    input.addClass('is-invalid');
                    input.siblings('.invalid-feedback').text(messages[0]);
                });
            }

            function resetInvoiceForm() {
                $('#invoiceForm')[0].reset();
                clearValidationErrors();
                items = [];
                renderItemsTable();
                $('#serviceSelect').html('<option value="">-- Select device type first --</option>').prop(
                    'disabled', true);
                $('#addItemBtn').prop('disabled', true);
            }

            function renderItemsTable() {
                var tbody = $('#itemsTableBody');
                tbody.empty();

                if (items.length === 0) {
                    tbody.append(
                        '<tr id="itemsEmptyRow"><td colspan="4" class="text-center text-muted py-2">No service added yet</td></tr>'
                    );
                } else {
                    $.each(items, function(index, item) {
                        tbody.append(`
                            <tr>
                                <td>${item.name}</td>
                                <td>
                                    <input type="number" min="1" class="form-control form-control-sm item-qty" data-index="${index}" value="${item.qty}">
                                </td>
                                <td class="text-end">${formatCurrency(item.price * item.qty)}</td>
                                <td class="text-center">
                                    <i class="fa fa-trash text-danger item-remove" data-index="${index}" role="button"></i>
                                </td>
                            </tr>
                        `);
                    });
                }

                var grandTotal = items.reduce((sum, item) => sum + (item.price * item.qty), 0);
                $('#itemsGrandTotal').text(formatCurrency(grandTotal));
            }

            // Register button opens modal, reset form
            $('#registerInvoiceBtn').click(function() {
                resetInvoiceForm();
                $('#registerInvoiceModal').modal('show');
            });

            // Load services when device type changes
            $('#device_type_id').on('change', function() {
                var deviceTypeId = $(this).val();
                var selectedText = $(this).find(':selected').data('name') || '';

                $('#device_name').val(selectedText);
                items = [];
                renderItemsTable();

                if (!deviceTypeId) {
                    $('#serviceSelect').html('<option value="">-- Select device type first --</option>')
                        .prop('disabled', true);
                    $('#addItemBtn').prop('disabled', true);
                    return;
                }

                $.ajax({
                    url: "{{ url('device-types') }}/" + deviceTypeId + "/services",
                    type: 'GET',
                    success: function(services) {
                        var options = '<option value="">-- Select service --</option>';
                        $.each(services, function(_, service) {
                            options +=
                                `<option value="${service.id}" data-name="${service.name}" data-price="${service.price}">${service.name} — ${formatCurrency(service.price)}</option>`;
                        });
                        $('#serviceSelect').html(options).prop('disabled', false);
                        $('#addItemBtn').prop('disabled', false);
                    },
                    error: function() {
                        Swal.fire({
                            title: "Error",
                            text: "Failed to load services for this device type.",
                            icon: "error"
                        });
                    }
                });
            });

            // Add item to table
            $('#addItemBtn').on('click', function() {
                var selected = $('#serviceSelect').find(':selected');
                var serviceId = selected.val();

                if (!serviceId) return;

                var existing = items.find(item => item.service_id == serviceId);
                if (existing) {
                    existing.qty += 1;
                } else {
                    items.push({
                        service_id: serviceId,
                        name: selected.data('name'),
                        price: parseFloat(selected.data('price')),
                        qty: 1
                    });
                }

                renderItemsTable();
                $('#serviceSelect').val('');
            });

            // Update qty
            $('#itemsTableBody').on('change', '.item-qty', function() {
                var index = $(this).data('index');
                var qty = parseInt($(this).val()) || 1;
                items[index].qty = qty;
                renderItemsTable();
            });

            // Remove item
            $('#itemsTableBody').on('click', '.item-remove', function() {
                var index = $(this).data('index');
                items.splice(index, 1);
                renderItemsTable();
            });

            // Submit form
            $('#invoiceForm').on('submit', function(e) {
                e.preventDefault();
                clearValidationErrors();

                var payload = {
                    device_type_id: $('#device_type_id').val(),
                    device_name: $('#device_name').val(),
                    imei_serial_number: $('#imei_serial_number').val(),
                    customer_name: $('#customer_name').val(),
                    customer_phone: $('#customer_phone').val(),
                    complaint: $('#complaint').val(),
                    physical_condition: $('#physical_condition').val(),
                    estimated_finish_day: $('#estimated_finish_day').val(),
                    items: items.map(item => ({
                        service_id: item.service_id,
                        qty: item.qty
                    }))
                };

                $.ajax({
                    url: "{{ route('invoices.store') }}",
                    type: 'POST',
                    data: payload,
                    success: function(response) {
                        table.ajax.reload();
                        $('#registerInvoiceModal').modal('hide');
                        Swal.fire({
                            title: "Success",
                            text: response.message,
                            icon: "success"
                        });
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            showValidationErrors(xhr.responseJSON.errors);
                        } else {
                            Swal.fire({
                                title: "Error",
                                text: xhr.responseJSON?.message ??
                                    'Something went wrong.',
                                icon: "error"
                            });
                        }
                    }
                });
            });

            // ================= LOAD DETAIL MODAL =================
            function loadInvoiceDetail(id) {
                $('#invoiceDetailModal').data('invoice-id', id);
                $('#invoiceDetailModalBody').html('<div class="text-center py-4 text-muted">Loading...</div>');
                $('#invoiceDetailModal').modal('show');

                $.ajax({
                    url: "{{ url('invoices') }}/" + id,
                    type: 'GET',
                    success: function(response) {
                        $('#invoiceDetailModalBody').html(response.html);
                    },
                    error: function() {
                        $('#invoiceDetailModalBody').html(
                            '<div class="text-center py-4 text-danger">Failed to load invoice detail.</div>'
                            );
                    }
                });
            }

            $('#dataInvoices').on('click', '.btn-view-detail', function() {
                loadInvoiceDetail($(this).data('id'));
            });

            function reloadInvoiceDetail() {
                var id = $('#invoiceDetailModal').data('invoice-id');
                loadInvoiceDetail(id);
                table.ajax.reload(null, false);
            }

            // ================= ADD ITEM (delegated, karena konten modal dinamis) =================
            $('#invoiceDetailModal').on('click', '#addItemBtn', function() {
                var id = $('#invoiceDetailModal').data('invoice-id');
                var serviceId = $('#serviceSelect').val();
                var qty = parseInt($('#itemQty').val()) || 1;

                $('#itemsAlert').addClass('d-none').text('');

                if (!serviceId) {
                    $('#itemsAlert').removeClass('d-none').text('Please select a service.');
                    return;
                }

                $.ajax({
                    url: "{{ url('invoices') }}/" + id + "/items",
                    type: 'POST',
                    data: {
                        service_id: serviceId,
                        qty: qty
                    },
                    success: function() {
                        reloadInvoiceDetail();
                    },
                    error: function(xhr) {
                        $('#itemsAlert').removeClass('d-none').text(xhr.responseJSON?.message ??
                            'Failed to add item.');
                    }
                });
            });

            // ================= REMOVE ITEM =================
            $('#invoiceDetailModal').on('click', '.item-remove', function() {
                var id = $('#invoiceDetailModal').data('invoice-id');
                var itemId = $(this).closest('tr').data('item-id');

                Swal.fire({
                    title: "Remove this item?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes, remove it",
                    cancelButtonText: "Cancel"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ url('invoices') }}/" + id + "/items/" + itemId,
                            type: 'DELETE',
                            success: function() {
                                reloadInvoiceDetail();
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    title: "Error",
                                    text: xhr.responseJSON?.message ??
                                        'Failed to remove item.',
                                    icon: "error"
                                });
                            }
                        });
                    }
                });
            });

            // ================= SAVE TECHNICIAN NOTES =================
            $('#invoiceDetailModal').on('click', '#saveNotesBtn', function() {
                var id = $('#invoiceDetailModal').data('invoice-id');

                $.ajax({
                    url: "{{ url('invoices') }}/" + id + "/technician-notes",
                    type: 'PATCH',
                    data: {
                        technician_notes: $('#technicianNotes').val()
                    },
                    success: function(response) {
                        Swal.fire({
                            title: "Saved",
                            text: response.message,
                            icon: "success",
                            timer: 1500,
                            showConfirmButton: false
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: "Error",
                            text: xhr.responseJSON?.message ?? 'Failed to save notes.',
                            icon: "error"
                        });
                    }
                });
            });

            // ================= UPDATE STATUS =================
            $('#invoiceDetailModal').on('click', '.status-btn, [data-status="cancelled"]', function() {
                var id = $('#invoiceDetailModal').data('invoice-id');
                var targetStatus = $(this).data('status');
                var isCancel = targetStatus === 'cancelled';

                Swal.fire({
                    title: isCancel ? "Cancel this transaction?" : "Update status?",
                    text: isCancel ? "This action cannot be undone." :
                        "Confirm to move this transaction to the next stage.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes, confirm",
                    cancelButtonText: "Back"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ url('invoices') }}/" + id + "/status",
                            type: 'PATCH',
                            data: {
                                status: targetStatus
                            },
                            success: function() {
                                reloadInvoiceDetail();
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    title: "Error",
                                    text: xhr.responseJSON?.message ??
                                        'Failed to update status.',
                                    icon: "error"
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
