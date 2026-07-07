@extends('layouts.app')

@section('content')
    <div class="row align-items-center mb-4">
        <div class="col-12">
            <h1 class="h3 mb-1">Dashboard</h1>
            <p class="text-muted mb-0">Overview of your service business</p>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-sm-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <p class="text-muted small mb-1">Revenue this month</p>
                    <p class="h4 mb-0">Rp {{ number_format($metrics['monthly_revenue'], 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <p class="text-muted small mb-1">New services today</p>
                    <p class="h4 mb-0">{{ $metrics['today_new'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <p class="text-muted small mb-1">Currently repairing</p>
                    <p class="h4 mb-0">{{ $metrics['in_progress'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <p class="text-muted small mb-1">Awaiting pickup</p>
                    <p class="h4 mb-0">{{ $metrics['awaiting_pickup'] }}</p>
                </div>
            </div>
        </div>
    </div>

    @if ($overdueCount > 0)
        <div class="alert alert-danger d-flex align-items-center gap-2 mb-3">
            <i class="fa fa-exclamation-triangle"></i>
            <span>{{ $overdueCount }} {{ Str::plural('item', $overdueCount) }} have been waiting for pickup for more than 30
                days.</span>
        </div>
    @endif

    <div class="row g-3 mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <p class="fw-medium mb-3">Revenue — last 7 days</p>
                    <div style="position: relative; height: 260px;">
                        <canvas id="weeklyRevenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <p class="fw-medium mb-0">Revenue — last 6 months</p>
                        <select class="form-select form-select-sm" id="monthlyRevenueFilter" style="width: 160px;">
                            @foreach ($monthOptions as $option)
                                <option value="{{ $option['value'] }}">{{ $option['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="position: relative; height: 220px;">
                        <canvas id="monthlyRevenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <p class="fw-medium mb-3">Most popular services</p>
                    <div style="position: relative; height: 220px;">
                        <canvas id="popularServicesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <p class="fw-medium mb-3">Recent transactions</p>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Customer</th>
                                    <th>Status</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentInvoices as $invoice)
                                    <tr>
                                        <td>{{ $invoice->status === 'picked_up' ? $invoice->invoice_code : $invoice->service_code }}
                                        </td>
                                        <td>{{ $invoice->customer_name }}</td>
                                        <td>@include('transactions.invoices.partials.status-badge', [
                                            'status' => $invoice->status,
                                        ])</td>
                                        <td class="text-end">Rp {{ number_format($invoice->grand_total, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3">No transactions yet</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('script')
        <script>
            $(document).ready(function() {
                var weeklyLabels = @json($weeklyRevenue['labels']);
                var weeklyValues = @json($weeklyRevenue['values']);

                new Chart(document.getElementById('weeklyRevenueChart'), {
                    type: 'line',
                    data: {
                        labels: weeklyLabels,
                        datasets: [{
                            data: weeklyValues,
                            borderColor: '#2a78d6',
                            backgroundColor: 'rgba(42,120,214,0.1)',
                            fill: true,
                            tension: 0.3,
                            pointRadius: 3,
                            pointBackgroundColor: '#2a78d6'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                ticks: {
                                    callback: function(v) {
                                        return 'Rp ' + (v / 1000000).toFixed(1) + 'jt';
                                    }
                                }
                            }
                        }
                    }
                });

                var monthlyLabels = @json($monthlyRevenue['labels']);
                var monthlyValues = @json($monthlyRevenue['values']);

                var monthlyChart = new Chart(document.getElementById('monthlyRevenueChart'), {
                    type: 'bar',
                    data: {
                        labels: monthlyLabels,
                        datasets: [{
                            data: monthlyValues,
                            backgroundColor: '#2a78d6',
                            borderRadius: 4,
                            maxBarThickness: 28
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                ticks: {
                                    callback: function(v) {
                                        return (v / 1000000).toFixed(1) + 'jt';
                                    }
                                }
                            }
                        }
                    }
                });

                $('#monthlyRevenueFilter').on('change', function() {
                    var endMonth = $(this).val();

                    $.ajax({
                        url: "{{ route('dashboard.monthly-revenue') }}",
                        type: 'GET',
                        data: {
                            end_month: endMonth
                        },
                        success: function(response) {
                            monthlyChart.data.labels = response.labels;
                            monthlyChart.data.datasets[0].data = response.values;
                            monthlyChart.update();
                        },
                        error: function() {
                            Swal.fire({
                                title: "Error",
                                text: "Failed to load revenue data for the selected month.",
                                icon: "error"
                            });
                        }
                    });
                });

                var serviceLabels = @json($popularServices->pluck('label'));
                var serviceValues = @json($popularServices->pluck('total'));

                new Chart(document.getElementById('popularServicesChart'), {
                    type: 'bar',
                    data: {
                        labels: serviceLabels,
                        datasets: [{
                            data: serviceValues,
                            backgroundColor: '#2a78d6',
                            borderRadius: 4,
                            maxBarThickness: 18
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            });
        </script>
    @endpush
@endsection
