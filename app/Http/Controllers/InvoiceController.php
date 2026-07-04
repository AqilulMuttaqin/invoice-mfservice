<?php

namespace App\Http\Controllers;

use App\Models\DeviceType;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Service;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class InvoiceController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $invoices = Invoice::query()
                ->join('device_types', 'invoices.device_type_id', '=', 'device_types.id')
                ->select('invoices.*', 'device_types.name as device_type_name')
                ->orderByDesc('invoices.created_at');

            if ($status = request('status')) {
                $invoices->where('invoices.status', $status);
            }

            return DataTables::of($invoices)
                ->addIndexColumn()
                ->addColumn('code', function ($row) {
                    return in_array($row->status, ['completed', 'picked_up'])
                        ? $row->invoice_code
                        : $row->service_code;
                })
                ->addColumn('device_type', function ($row) {
                    return $row->device_type_name ?? '-';
                })
                ->addColumn('received_date', function ($row) {
                    return $row->received_date?->translatedFormat('d M Y') ?? '-';
                })
                ->addColumn('status_badge', function ($row) {
                    return view('transactions.invoices.partials.status-badge', ['status' => $row->status])->render();
                })
                ->addColumn('grand_total', function ($row) {
                    return 'Rp ' . number_format($row->grand_total, 0, ',', '.');
                })
                ->addColumn('action', function ($row) {
                    return view('transactions.invoices.partials.action-button', ['invoice' => $row])->render();
                })
                ->filterColumn('code', function ($query, $keyword) {
                    $query->where(function ($q) use ($keyword) {
                        $q->where('invoices.service_code', 'like', "%{$keyword}%")
                            ->orWhere('invoices.invoice_code', 'like', "%{$keyword}%");
                    });
                })
                ->rawColumns(['status_badge', 'action'])
                ->make(true);
        }

        return view('transactions.invoices.index', [
            'title' => 'Invoices',
            'deviceTypes' => DeviceType::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'device_type_id' => 'required|exists:device_types,id',
            'device_name' => 'required|string|max:255',
            'imei_serial_number' => 'nullable|string|max:255',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'complaint' => 'required|string',
            'physical_condition' => 'nullable|string',
            'estimated_finish_day' => 'nullable|integer|min:1',
            'items' => 'required|array|min:1',
            'items.*.service_id' => 'required|exists:services,id',
            'items.*.qty' => 'required|integer|min:1',
        ], [
            'device_type_id.required' => 'Device type is required.',
            'device_name.required' => 'Device name is required.',
            'customer_name.required' => 'Customer name is required.',
            'customer_phone.required' => 'Customer phone is required.',
            'complaint.required' => 'Complaint is required.',
            'items.required' => 'Please add at least one service item.',
            'items.min' => 'Please add at least one service item.',
        ]);

        try {
            DB::beginTransaction();

            $invoice = Invoice::create([
                'service_code' => $this->generateServiceCode(),
                'device_type_id' => $request->device_type_id,
                'device_name' => $request->device_name,
                'imei_serial_number' => $request->imei_serial_number,
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'complaint' => $request->complaint,
                'physical_condition' => $request->physical_condition,
                'estimated_finish_day' => $request->estimated_finish_day,
                'received_date' => now(),
                'status' => 'received',
            ]);

            $grandTotal = 0;

            foreach ($request->items as $item) {
                $service = Service::findOrFail($item['service_id']);
                $subtotal = $service->price * $item['qty'];

                $invoice->items()->create([
                    'service_id' => $service->id,
                    'qty' => $item['qty'],
                    'price' => $service->price,
                    'subtotal' => $subtotal,
                ]);

                $grandTotal += $subtotal;
            }

            $invoice->update(['grand_total' => $grandTotal]);

            DB::commit();

            return response()->json([
                'message' => 'Service registered successfully.',
                'id' => $invoice->id,
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to register service.',
            ], 500);
        }
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['deviceType', 'items.service']);

        $services = $invoice->deviceType->services()->orderBy('name')->get(['id', 'name', 'price']);

        return response()->json([
            'html' => view('transactions.invoices.partials.detail-content', compact('invoice', 'services'))->render(),
        ]);
    }

    public function addItem(Request $request, Invoice $invoice)
    {
        if (!in_array($invoice->status, ['received', 'repairing'])) {
            return response()->json([
                'message' => 'Items can no longer be modified at this stage.'
            ], 422);
        }

        $request->validate([
            'service_id' => 'required|exists:services,id',
            'qty' => 'required|integer|min:1',
        ]);

        try {
            $service = Service::findOrFail($request->service_id);
            $existing = $invoice->items()->where('service_id', $service->id)->first();

            if ($existing) {
                $existing->qty += $request->qty;
                $existing->subtotal = $existing->qty * $existing->price;
                $existing->save();
            } else {
                $invoice->items()->create([
                    'service_id' => $service->id,
                    'qty' => $request->qty,
                    'price' => $service->price,
                    'subtotal' => $service->price * $request->qty,
                ]);
            }

            $invoice->update(['grand_total' => $invoice->items()->sum('subtotal')]);

            return response()->json(['message' => 'Service item added.']);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to add service item.'], 500);
        }
    }

    public function removeItem(Invoice $invoice, InvoiceItem $item)
    {
        if ($item->invoice_id !== $invoice->id) {
            abort(404);
        }

        if (!in_array($invoice->status, ['received', 'repairing'])) {
            return response()->json([
                'message' => 'Items can no longer be modified at this stage.'
            ], 422);
        }

        try {
            $item->delete();
            $invoice->update(['grand_total' => $invoice->items()->sum('subtotal')]);

            return response()->json(['message' => 'Service item removed.']);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to remove service item.'], 500);
        }
    }

    public function updateTechnicianNotes(Request $request, Invoice $invoice)
    {
        $request->validate([
            'technician_notes' => 'nullable|string',
        ]);

        try {
            $invoice->update(['technician_notes' => $request->technician_notes]);

            return response()->json(['message' => 'Technician notes saved.']);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to save technician notes.'], 500);
        }
    }

    public function updateStatus(Request $request, Invoice $invoice)
    {
        $request->validate([
            'status' => 'required|in:repairing,completed,picked_up,cancelled',
        ]);

        $target = $request->status;

        $validTransitions = [
            'received'  => ['repairing', 'cancelled'],
            'repairing' => ['completed', 'cancelled'],
            'completed' => ['picked_up'],
        ];

        if (!in_array($target, $validTransitions[$invoice->status] ?? [])) {
            return response()->json(['message' => 'Invalid status transition.'], 422);
        }

        if ($target === 'completed' && $invoice->items()->count() === 0) {
            return response()->json([
                'message' => 'Add at least one service item before marking as completed.'
            ], 422);
        }

        try {
            if ($target === 'completed') {
                $invoice->completed_date = now();
                $invoice->grand_total = $invoice->items()->sum('subtotal');
            }

            if ($target === 'picked_up') {
                $invoice->invoice_code = $this->generateInvoiceCode();
                $invoice->picked_up_date = now();
                $invoice->warranty_days = 7;
                $invoice->remaining_warranty_claim = 3;
            }

            $invoice->status = $target;
            $invoice->save();

            return response()->json(['message' => 'Status updated successfully.']);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to update status.'], 500);
        }
    }

    private function generateServiceCode(): string
    {
        $prefix = 'SRV-' . now()->format('Ymd') . '-';
        $last = Invoice::where('service_code', 'like', "{$prefix}%")->orderByDesc('service_code')->first();
        $number = $last ? ((int) substr($last->service_code, -3)) + 1 : 1;

        return $prefix . str_pad($number, 3, '0', STR_PAD_LEFT);
    }

    private function generateInvoiceCode(): string
    {
        $prefix = 'INV-' . now()->format('Ymd') . '-';
        $last = Invoice::where('invoice_code', 'like', "{$prefix}%")->orderByDesc('invoice_code')->first();
        $number = $last ? ((int) substr($last->invoice_code, -3)) + 1 : 1;

        return $prefix . str_pad($number, 3, '0', STR_PAD_LEFT);
    }
}
