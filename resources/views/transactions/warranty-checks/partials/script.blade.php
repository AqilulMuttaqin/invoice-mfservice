@push('script')
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script>
        $(document).ready(function() {
            var html5QrCode = null;

            function performSearch(code) {
                if (!code) return;

                $.ajax({
                    url: "{{ route('warranty-checks.search') }}",
                    type: 'POST',
                    data: {
                        code: code
                    },
                    success: function(response) {
                        $('#resultContainer').html(response.html);
                        $('#resultCard').show();
                    },
                    error: function() {
                        Swal.fire({
                            title: "Error",
                            text: "Failed to check invoice code.",
                            icon: "error"
                        });
                    }
                });
            }

            // ================= MANUAL CHECK =================
            $('#checkBtn').on('click', function() {
                performSearch($('#invoiceCodeInput').val().trim());
            });

            $('#invoiceCodeInput').on('keypress', function(e) {
                if (e.which === 13) {
                    performSearch($(this).val().trim());
                }
            });

            // ================= QR SCANNER =================
            $('#scanQrBtn').on('click', function() {
                $('#qrScannerModal').modal('show');
            });

            $('#qrScannerModal').on('shown.bs.modal', function() {
                html5QrCode = new Html5Qrcode("qrReader");

                html5QrCode.start({
                        facingMode: "environment"
                    }, {
                        fps: 10,
                        qrbox: {
                            width: 250,
                            height: 250
                        }
                    },
                    function(decodedText) {
                        $('#invoiceCodeInput').val(decodedText);
                        $('#qrScannerModal').modal('hide');
                        performSearch(decodedText);
                    },
                    function() {
                        // ignore scan failure per frame, keep scanning
                    }
                ).catch(function(err) {
                    Swal.fire({
                        title: "Camera Error",
                        text: "Unable to access camera. Please check permissions or use manual input.",
                        icon: "error"
                    });
                    $('#qrScannerModal').modal('hide');
                });
            });

            $('#qrScannerModal').on('hidden.bs.modal', function() {
                if (html5QrCode) {
                    html5QrCode.stop().then(function() {
                        html5QrCode.clear();
                    }).catch(function() {});
                }
            });

            // ================= SUBMIT CLAIM (delegated, karena konten dinamis) =================
            $('#resultContainer').on('click', '#submitClaimBtn', function() {
                var invoiceId = $(this).data('invoice-id');
                var description = $('#claimDescription').val().trim();

                $('#claimAlert').addClass('d-none').text('');

                if (!description) {
                    $('#claimAlert').removeClass('d-none').text('Please describe the issue being claimed.');
                    return;
                }

                Swal.fire({
                    title: "Submit this warranty claim?",
                    text: "This will reduce the remaining claim quota by one.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes, submit",
                    cancelButtonText: "Cancel"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ url('invoices') }}/" + invoiceId + "/warranty-claim",
                            type: 'POST',
                            data: {
                                description: description
                            },
                            success: function(response) {
                                Swal.fire({
                                    title: "Success",
                                    text: response.message,
                                    icon: "success"
                                });
                                performSearch($('#invoiceCodeInput').val().trim());
                            },
                            error: function(xhr) {
                                $('#claimAlert').removeClass('d-none').text(xhr
                                    .responseJSON?.message ??
                                    'Failed to submit claim.');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
