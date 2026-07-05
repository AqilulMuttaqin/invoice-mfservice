<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard', [
            'title' => 'Dashboard',
            'metrics' => $this->getMetrics(),
            'overdueCount' => $this->getOverdueCount(),
            'weeklyRevenue' => $this->getWeeklyRevenue(),
            'monthlyRevenue' => $this->getMonthlyRevenue(),
            'popularServices' => $this->getPopularServices(),
            'recentInvoices' => $this->getRecentInvoices(),
        ]);
    }

    private function getMetrics(): array
    {
        return [
            'monthly_revenue' => Invoice::where('status', 'picked_up')
                ->whereMonth('picked_up_date', now()->month)
                ->whereYear('picked_up_date', now()->year)
                ->sum('grand_total'),

            'today_new' => Invoice::whereDate('received_date', today())->count(),

            'in_progress' => Invoice::where('status', 'repairing')->count(),

            'awaiting_pickup' => Invoice::where('status', 'completed')->count(),
        ];
    }

    private function getOverdueCount(): int
    {
        return Invoice::where('status', 'completed')
            ->where('completed_date', '<=', now()->subDays(30))
            ->count();
    }

    private function getWeeklyRevenue(): array
    {
        $days = collect(range(6, 0))->map(fn($i) => now()->subDays($i)->startOfDay());

        $raw = Invoice::where('status', 'picked_up')
            ->where('picked_up_date', '>=', now()->subDays(6)->startOfDay())
            ->selectRaw('DATE(picked_up_date) as date, SUM(grand_total) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        return [
            'labels' => $days->map(fn($d) => $d->translatedFormat('D')),
            'values' => $days->map(fn($d) => (float) ($raw[$d->toDateString()] ?? 0)),
        ];
    }

    private function getMonthlyRevenue(): array
    {
        $months = collect(range(5, 0))->map(fn($i) => now()->subMonths($i)->startOfMonth());

        $raw = Invoice::where('status', 'picked_up')
            ->where('picked_up_date', '>=', now()->subMonths(5)->startOfMonth())
            ->selectRaw('DATE_FORMAT(picked_up_date, "%Y-%m") as month, SUM(grand_total) as total')
            ->groupBy('month')
            ->pluck('total', 'month');

        return [
            'labels' => $months->map(fn($m) => $m->translatedFormat('M')),
            'values' => $months->map(fn($m) => (float) ($raw[$m->format('Y-m')] ?? 0)),
        ];
    }

    private function getPopularServices()
    {
        return InvoiceItem::query()
            ->join('services', 'invoice_items.service_id', '=', 'services.id')
            ->join('device_types', 'services.device_type_id', '=', 'device_types.id')
            ->selectRaw('services.name as service_name, device_types.name as device_type_name, SUM(invoice_items.qty) as total_qty')
            ->groupBy('services.id', 'services.name', 'device_types.name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get()
            ->map(function ($row) {
                return [
                    'label' => "{$row->service_name} ({$row->device_type_name})",
                    'total' => (int) $row->total_qty,
                ];
            });
    }

    private function getRecentInvoices()
    {
        return Invoice::query()
            ->join('device_types', 'invoices.device_type_id', '=', 'device_types.id')
            ->select('invoices.*')
            ->orderByDesc('invoices.created_at')
            ->limit(5)
            ->get();
    }
}
