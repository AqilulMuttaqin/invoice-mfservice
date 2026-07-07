@push('script')
    <script>
        $(document).ready(function() {
            var table = $('#dataDeviceTypes').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: "{{ route('device-types.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name',
                        orderable: true,
                        searchable: true
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
                        targets: 2,
                        width: '40px',
                        className: 'text-center'
                    }
                ],
            });

            function resetFormFields() {
                $('#name').val('');
                clearValidationErrors();
            }

            function clearValidationErrors() {
                $('#deviceTypeForm').find('.is-invalid').removeClass('is-invalid');
                $('#deviceTypeForm').find('.invalid-feedback').text('');
            }

            function showValidationErrors(errors) {
                $.each(errors, function(field, messages) {
                    var input = $('#' + field);
                    input.addClass('is-invalid');
                    input.siblings('.invalid-feedback').text(messages[0]);
                });
            }

            // Add
            $('#addDeviceTypeBtn').click(function() {
                resetFormFields();
                $('#submitBtn').text('Submit');
                $('#deviceTypeModalLabel').text('Add Device Type');
                $('#deviceTypeForm').attr('action', "{{ route('device-types.store') }}");
                $('#deviceTypeForm').attr('method', 'POST');

                $('#deviceTypeModal').modal('show');
            });

            // Edit
            $('#dataDeviceTypes').on('click', '.btn-edit', function() {
                var id = $(this).data('id');
                resetFormFields();

                $.ajax({
                    url: "{{ url('device-types') }}/" + id + "/edit",
                    type: 'GET',
                    success: function(response) {
                        var data = response.data;
                        $('#name').val(data.name);
                        $('#submitBtn').text('Update');
                        $('#deviceTypeModalLabel').text('Edit Device Type');
                        $('#deviceTypeForm').attr('action', "{{ url('device-types') }}/" + id);
                        $('#deviceTypeForm').attr('method', 'PUT');

                        $('#deviceTypeModal').modal('show');
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: "Error",
                            text: xhr.responseJSON?.message ?? 'Failed to load data.',
                            icon: "error"
                        });
                    }
                });
            });

            // Delete
            $('#dataDeviceTypes').on('click', '.btn-delete', function() {
                var id = $(this).data('id');

                Swal.fire({
                    title: "Are you sure?",
                    text: "This device type will be deleted.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes, delete it",
                    cancelButtonText: "Cancel"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ url('device-types') }}/" + id,
                            type: 'DELETE',
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                table.ajax.reload(null, false);
                                Swal.fire({
                                    title: "Deleted",
                                    text: response.message,
                                    icon: "success"
                                });
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    title: "Error",
                                    text: xhr.responseJSON?.message ??
                                        'Failed to delete data.',
                                    icon: "error"
                                });
                            }
                        });
                    }
                });
            });

            // Submit (Add & Edit sekaligus, karena method & action-nya dinamis)
            $('#deviceTypeForm').on('submit', function(e) {
                e.preventDefault();
                clearValidationErrors();

                var formData = $(this).serialize();
                var url = $(this).attr('action');
                var method = $(this).attr('method');
                var currentPage = $('#dataDeviceTypes').DataTable().page();

                // Laravel butuh _method spoofing untuk PUT lewat form biasa
                if (method === 'PUT') {
                    formData += '&_method=PUT';
                    method = 'POST';
                }

                $.ajax({
                    url: url,
                    type: method,
                    data: formData,
                    success: function(response) {
                        $('#dataDeviceTypes').DataTable().ajax.reload();
                        $('#dataDeviceTypes').DataTable().page(currentPage).draw('page');
                        $('#deviceTypeModal').modal('hide');
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
        });
    </script>
@endpush
