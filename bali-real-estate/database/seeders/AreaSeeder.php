<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    public function run(): void
    {
        $areas = [
            'Canggu',
            'Seminyak',
            'Ubud',
            'Uluwatu'
        ];

        foreach ($areas as $areaName) {
            Area::create([
                'name' => $areaName
            ]);
        }
    }
}