<?php

namespace Database\Seeders;

use App\Models\DeviceType;
use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $servicesByDeviceType = [
            'Iphone 11' => [
                'Ganti LCD' => 650000,
                'Ganti Baterai' => 250000,
                'Ganti Charger Port' => 200000,
                'Servis Kamera Belakang' => 300000,
                'Ganti Back Glass' => 275000,
                'Service Software / Flash Ulang' => 150000,
            ],
            'Iphone 12' => [
                'Ganti LCD' => 850000,
                'Ganti Baterai' => 300000,
                'Ganti Charger Port' => 225000,
                'Servis Kamera Belakang' => 350000,
                'Ganti Back Glass' => 300000,
                'Service Software / Flash Ulang' => 150000,
            ],
            'Iphone 13' => [
                'Ganti LCD' => 1200000,
                'Ganti Baterai' => 350000,
                'Ganti Charger Port' => 250000,
                'Servis Kamera Belakang' => 400000,
                'Ganti Back Glass' => 350000,
                'Service Software / Flash Ulang' => 175000,
            ],
            'Iphone 14' => [
                'Ganti LCD' => 1650000,
                'Ganti Baterai' => 400000,
                'Ganti Charger Port' => 275000,
                'Servis Kamera Belakang' => 450000,
                'Ganti Back Glass' => 400000,
                'Service Software / Flash Ulang' => 175000,
            ],
            'Samsung Galaxy A54' => [
                'Ganti LCD' => 900000,
                'Ganti Baterai' => 275000,
                'Ganti Charger Port' => 175000,
                'Servis Kamera Belakang' => 325000,
                'Ganti Layar Sentuh' => 400000,
                'Service Software / Flash Ulang' => 125000,
            ],
            'Samsung Galaxy A34' => [
                'Ganti LCD' => 750000,
                'Ganti Baterai' => 250000,
                'Ganti Charger Port' => 150000,
                'Servis Kamera Belakang' => 300000,
                'Ganti Layar Sentuh' => 350000,
                'Service Software / Flash Ulang' => 125000,
            ],
            'Samsung Galaxy S21' => [
                'Ganti LCD' => 1350000,
                'Ganti Baterai' => 325000,
                'Ganti Charger Port' => 200000,
                'Servis Kamera Belakang' => 400000,
                'Ganti Layar Sentuh' => 450000,
                'Service Software / Flash Ulang' => 150000,
            ],
            'Xiaomi Redmi Note 12' => [
                'Ganti LCD' => 550000,
                'Ganti Baterai' => 200000,
                'Ganti Charger Port' => 125000,
                'Servis Kamera Belakang' => 250000,
                'Ganti Layar Sentuh' => 300000,
                'Service Software / Flash Ulang' => 100000,
            ],
            'Xiaomi Redmi 10' => [
                'Ganti LCD' => 450000,
                'Ganti Baterai' => 175000,
                'Ganti Charger Port' => 100000,
                'Servis Kamera Belakang' => 225000,
                'Ganti Layar Sentuh' => 275000,
                'Service Software / Flash Ulang' => 100000,
            ],
            'Oppo A57' => [
                'Ganti LCD' => 500000,
                'Ganti Baterai' => 200000,
                'Ganti Charger Port' => 125000,
                'Servis Kamera Belakang' => 250000,
                'Ganti Layar Sentuh' => 300000,
                'Service Software / Flash Ulang' => 100000,
            ],
            'Oppo Reno 8' => [
                'Ganti LCD' => 850000,
                'Ganti Baterai' => 275000,
                'Ganti Charger Port' => 175000,
                'Servis Kamera Belakang' => 325000,
                'Ganti Layar Sentuh' => 400000,
                'Service Software / Flash Ulang' => 125000,
            ],
            'Vivo Y21' => [
                'Ganti LCD' => 475000,
                'Ganti Baterai' => 200000,
                'Ganti Charger Port' => 125000,
                'Servis Kamera Belakang' => 250000,
                'Ganti Layar Sentuh' => 300000,
                'Service Software / Flash Ulang' => 100000,
            ],
            'Vivo V27' => [
                'Ganti LCD' => 800000,
                'Ganti Baterai' => 250000,
                'Ganti Charger Port' => 175000,
                'Servis Kamera Belakang' => 300000,
                'Ganti Layar Sentuh' => 375000,
                'Service Software / Flash Ulang' => 125000,
            ],
            'Laptop Asus X441' => [
                'Ganti LCD/Layar' => 900000,
                'Ganti Baterai' => 450000,
                'Ganti Keyboard' => 250000,
                'Install Ulang OS + Driver' => 150000,
                'Ganti Fan/Heatsink' => 175000,
                'Upgrade SSD' => 500000,
            ],
            'Laptop Acer Aspire 5' => [
                'Ganti LCD/Layar' => 850000,
                'Ganti Baterai' => 425000,
                'Ganti Keyboard' => 225000,
                'Install Ulang OS + Driver' => 150000,
                'Ganti Fan/Heatsink' => 175000,
                'Upgrade SSD' => 500000,
            ],
            'Laptop Lenovo Ideapad Slim 3' => [
                'Ganti LCD/Layar' => 800000,
                'Ganti Baterai' => 400000,
                'Ganti Keyboard' => 225000,
                'Install Ulang OS + Driver' => 150000,
                'Ganti Fan/Heatsink' => 175000,
                'Upgrade SSD' => 500000,
            ],
        ];

        foreach ($servicesByDeviceType as $deviceTypeName => $services) {
            $deviceType = DeviceType::where('name', $deviceTypeName)->first();

            if (!$deviceType) {
                continue;
            }

            foreach ($services as $serviceName => $price) {
                Service::create([
                    'device_type_id' => $deviceType->id,
                    'name' => $serviceName,
                    'price' => $price,
                ]);
            }
        }
    }
}
