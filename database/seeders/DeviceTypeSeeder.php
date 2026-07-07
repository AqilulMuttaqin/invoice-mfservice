<?php

namespace Database\Seeders;

use App\Models\DeviceType;
use Illuminate\Database\Seeder;

class DeviceTypeSeeder extends Seeder
{
    public function run(): void
    {
        $deviceTypes = [
            'Iphone 11',
            'Iphone 12',
            'Iphone 13',
            'Iphone 14',
            'Samsung Galaxy A54',
            'Samsung Galaxy A34',
            'Samsung Galaxy S21',
            'Xiaomi Redmi Note 12',
            'Xiaomi Redmi 10',
            'Oppo A57',
            'Oppo Reno 8',
            'Vivo Y21',
            'Vivo V27',
            'Laptop Asus X441',
            'Laptop Acer Aspire 5',
            'Laptop Lenovo Ideapad Slim 3',
        ];

        foreach ($deviceTypes as $name) {
            DeviceType::create(['name' => $name]);
        }
    }
}
