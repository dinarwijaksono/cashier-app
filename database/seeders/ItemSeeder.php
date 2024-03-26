<?php

namespace Database\Seeders;

use App\Services\ItemService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    public $itemService;

    public function __construct(ItemService $itemService)
    {
        $this->itemService = $itemService;
    }

    public function run(): void
    {
        $units = ['kg', 'liter', 'm', 'kg'];

        $name = 'test-' . random_int(1, 9999);
        $unit = $units[random_int(0, 3)];
        $price = random_int(1, 9999) * 500;

        $this->itemService->create($name, $unit, $price);
    }
}
