@push('script')
    <script>
        $(document).ready(function() {
            var table = $('#dataServices').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: "{{ route('services.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'device_type',
                        name: 'deviceType.name',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'name',
                        name: 'name',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'price',
                        name: 'price',
                        orderable: true,
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
                        targets: 4,
                        width: '40px',
                        className: 'text-center'
                    }
                ],
            });

            function resetFormFields() {
                $('#device_type_id').val('');
                $('#name').val('');
                $('#price').val('');
                clearValidationErrors();
            }

            function clearValidationErrors() {
                $('#serviceForm').find('.is-invalid').removeClass('is-invalid');
                $('#serviceForm').find('.invalid-feedback').text('');
            }

            function showValidationErrors(errors) {
                $.each(errors, function(field, messages) {
                    var input = $('#' + field);
                    input.addClass('is-invalid');
                    input.siblings('.invalid-feedback').text(messages[0]);
                });
            }

            // Add
            $('#addServiceBtn').click(function() {
                resetFormFields();
                $('#submitBtn').text('Submit');
                $('#serviceModalLabel').text('Add Service');
                $('#serviceForm').attr('action', "{{ route('services.store') }}");
                $('#serviceForm').attr('method', 'POST');

                $('#serviceModal').modal('show');
            });

            // Edit
            $('#dataServices').on('click', '.btn-edit', function() {
                var id = $(this).data('id');
                resetFormFields();

                $.ajax({
                    url: "{{ url('services') }}/" + id + "/edit",
                    type: 'GET',
                    success: function(response) {
                        var data = response.data;
                        $('#device_type_id').val(data.device_type_id);
                        $('#name').val(data.name);
                        $('#price').val(data.price);
                        $('#submitBtn').text('Update');
                        $('#serviceModalLabel').text('Edit Service');
                        $('#serviceForm').attr('action', "{{ url('services') }}/" + id);
                        $('#serviceForm').attr('method', 'PUT');

                        $('#serviceModal').modal('show');
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
            $('#dataServices').on('click', '.btn-delete', function() {
                var id = $(this).data('id');

                Swal.fire({
                    title: "Are you sure?",
                    text: "This service will be deleted.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes, delete it",
                    cancelButtonText: "Cancel"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ url('services') }}/" + id,
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

            // Submit (Add & Edit)
            $('#serviceForm').on('submit', function(e) {
                e.preventDefault();
                clearValidationErrors();

                var formData = $(this).serialize();
                var url = $(this).attr('action');
                var method = $(this).attr('method');
                var currentPage = $('#dataServices').DataTable().page();

                $.ajax({
                    url: url,
                    type: method,
                    data: formData,
                    success: function(response) {
                        $('#dataServices').DataTable().ajax.reload();
                        $('#dataServices').DataTable().page(currentPage).draw('page');
                        $('#serviceModal').modal('hide');
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
