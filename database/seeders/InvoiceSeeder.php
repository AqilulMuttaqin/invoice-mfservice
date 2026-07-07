<?php

namespace Database\Seeders;

use App\Models\DeviceType;
use App\Models\Invoice;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class InvoiceSeeder extends Seeder
{
    private array $codeCounters = [];

    public function run(): void
    {
        $deviceTypes = DeviceType::with('services')->get()->filter(fn($dt) => $dt->services->isNotEmpty());

        if ($deviceTypes->isEmpty()) {
            $this->command->warn('No device types with services found. Run DeviceTypeSeeder and ServiceSeeder first.');
            return;
        }

        $now = Carbon::now();

        // Target jumlah transaksi per bulan, dari 5 bulan lalu sampai sekarang (tren naik)
        $monthlyTargets = [18, 22, 20, 27, 25, 30];

        foreach (range(5, 0) as $index => $monthsAgo) {
            $monthStart = $now->copy()->subMonths($monthsAgo)->startOfMonth();
            $isCurrentMonth = $monthsAgo === 0;

            if ($isCurrentMonth) {
                // Batasi biar full siklus (received -> completed -> picked up) tetap muat sebelum "hari ini"
                $monthEnd = $now->copy()->subDays(3);
                $daysAvailable = max(1, $monthStart->diffInDays($monthEnd));
                $target = (int) round($monthlyTargets[$index] * ($daysAvailable / 30));
            } else {
                $monthEnd = $monthStart->copy()->endOfMonth();
                $target = $monthlyTargets[$index];
            }

            for ($i = 0; $i < $target; $i++) {
                $this->createInvoice($deviceTypes, $monthStart, $monthEnd);
            }
        }

        $this->command->info('Invoice seeding completed.');
    }

    private function createInvoice($deviceTypes, Carbon $rangeStart, Carbon $rangeEnd): void
    {
        $deviceType = $deviceTypes->random();
        $services = $deviceType->services;

        $receivedDate = Carbon::createFromTimestamp(
            fake()->numberBetween($rangeStart->timestamp, $rangeEnd->timestamp)
        )->startOfDay();

        $completedDate = $receivedDate->copy()->addDays(fake()->numberBetween(1, 4));
        $pickedUpDate = $completedDate->copy()->addDays(fake()->numberBetween(0, 3));

        // Pastikan tidak melewati hari ini
        $now = Carbon::now();
        if ($pickedUpDate->greaterThan($now)) {
            $pickedUpDate = $now->copy();
        }
        if ($completedDate->greaterThan($pickedUpDate)) {
            $completedDate = $pickedUpDate->copy();
        }
        if ($receivedDate->greaterThan($completedDate)) {
            $receivedDate = $completedDate->copy();
        }

        $isLaptop = str_contains(strtolower($deviceType->name), 'laptop');

        $invoice = Invoice::create([
            'service_code' => $this->generateCode('SRV', $receivedDate),
            'invoice_code' => $this->generateCode('INV', $pickedUpDate),
            'device_type_id' => $deviceType->id,
            'device_name' => $deviceType->name,
            'imei_serial_number' => $isLaptop
                ? strtoupper(fake()->bothify('SN-########'))
                : fake()->numerify('###############'),
            'customer_name' => fake('id_ID')->name(),
            'customer_phone' => '08' . fake()->numerify('##########'),
            'complaint' => $this->randomComplaint($isLaptop),
            'physical_condition' => $this->randomPhysicalCondition($isLaptop),
            'technician_notes' => fake()->boolean(60)
                ? $this->randomTechnicianNotes()
                : null,
            'received_date' => $receivedDate,
            'estimated_finish_day' => fake()->numberBetween(1, 4),
            'completed_date' => $completedDate,
            'picked_up_date' => $pickedUpDate,
            'status' => 'picked_up',
            'remaining_warranty_claim' => fake()->randomElement([3, 3, 3, 2, 1]),
            'warranty_days' => 30,
            'grand_total' => 0,
        ]);

        $selectedServices = $services->random(min(fake()->numberBetween(1, 2), $services->count()));
        if (!$selectedServices instanceof \Illuminate\Support\Collection) {
            $selectedServices = collect([$selectedServices]);
        }

        $grandTotal = 0;

        foreach ($selectedServices as $service) {
            $qty = fake()->boolean(85) ? 1 : 2;
            $subtotal = $service->price * $qty;

            $invoice->items()->create([
                'service_id' => $service->id,
                'qty' => $qty,
                'price' => $service->price,
                'subtotal' => $subtotal,
            ]);

            $grandTotal += $subtotal;
        }

        $invoice->update(['grand_total' => $grandTotal]);
    }

    private function generateCode(string $prefix, Carbon $date): string
    {
        $key = $prefix . '-' . $date->format('Ymd');

        if (!isset($this->codeCounters[$key])) {
            $this->codeCounters[$key] = 0;
        }

        $this->codeCounters[$key]++;

        return $key . '-' . str_pad($this->codeCounters[$key], 3, '0', STR_PAD_LEFT);
    }

    private function randomComplaint(bool $isLaptop): string
    {
        $phoneComplaints = [
            '-',
        ];

        $laptopComplaints = [
            '-',
        ];

        return fake()->randomElement($isLaptop ? $laptopComplaints : $phoneComplaints);
    }

    private function randomPhysicalCondition(bool $isLaptop): string
    {
        $phoneConditions = [
            "Layar retak bagian kanan atas\nFrame terdapat goresan ringan",
            "Casing belakang tergores\nTidak ada retak pada layar",
            "Kondisi mulus, tidak ada goresan berarti",
            "Terdapat penyok kecil di sudut bawah\nLayar masih utuh",
        ];

        $laptopConditions = [
            "Casing atas tergores\nEngsel sedikit longgar",
            "Kondisi baik, hanya debu di ventilasi",
            "Terdapat stiker bekas di cover atas\nTidak ada retak",
        ];

        return fake()->randomElement($isLaptop ? $laptopConditions : $phoneConditions);
    }

    private function randomTechnicianNotes(): string
    {
        return fake()->randomElement([
            '-',
        ]);
    }
}
