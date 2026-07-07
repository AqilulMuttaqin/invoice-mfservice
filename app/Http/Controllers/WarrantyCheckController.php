<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\WarrantyClaim;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WarrantyCheckController extends Controller
{
    public function index()
    {
        return view('transactions.warranty-checks.index', [
            'title' => 'Warranty Checks',
        ]);
    }

    public function search(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $invoice = Invoice::with(['deviceType', 'items.service', 'warrantyClaims.claimedBy'])
            ->where('invoice_code', trim($request->code))
            ->first();

        if (!$invoice) {
            return response()->json([
                'html' => view('transactions.warranty-checks.partials.result', [
                    'invoice' => null,
                    'validity' => ['valid' => false, 'reason' => 'Invoice code not found.'],
                ])->render(),
            ]);
        }

        $validity = $this->checkValidity($invoice);

        return response()->json([
            'html' => view('transactions.warranty-checks.partials.result', [
                'invoice' => $invoice,
                'validity' => $validity,
            ])->render(),
        ]);
    }

    public function claim(Request $request, Invoice $invoice)
    {
        $request->validate([
            'description' => 'required|string',
        ], [
            'description.required' => 'Please describe the issue being claimed.',
        ]);

        $validity = $this->checkValidity($invoice);

        if (!$validity['valid']) {
            return response()->json([
                'message' => $validity['reason'],
            ], 422);
        }

        try {
            DB::beginTransaction();

            WarrantyClaim::create([
                'invoice_id' => $invoice->id,
                'description' => $request->description,
                'claimed_by' => Auth::id(),
                'claimed_at' => now(),
            ]);

            $invoice->decrement('remaining_warranty_claim');

            DB::commit();

            return response()->json(['message' => 'Warranty claim recorded successfully.']);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['message' => 'Failed to process warranty claim.'], 500);
        }
    }

    private function checkValidity(Invoice $invoice): array
    {
        if ($invoice->status !== 'picked_up') {
            return [
                'valid' => false,
                'reason' => 'This item has not been picked up yet, warranty has not started.',
            ];
        }

        $expiryDate = $invoice->picked_up_date->copy()->addDays((int) $invoice->warranty_days);

        if (now()->greaterThan($expiryDate)) {
            return [
                'valid' => false,
                'reason' => 'Warranty period has expired.',
                'expiry_date' => $expiryDate,
            ];
        }

        if ($invoice->remaining_warranty_claim <= 0) {
            return [
                'valid' => false,
                'reason' => 'Warranty claim quota has been used up.',
                'expiry_date' => $expiryDate,
            ];
        }

        return [
            'valid' => true,
            'expiry_date' => $expiryDate,
            'days_remaining' => now()->startOfDay()->diffInDays($expiryDate->copy()->startOfDay(), false),
        ];
    }
}
