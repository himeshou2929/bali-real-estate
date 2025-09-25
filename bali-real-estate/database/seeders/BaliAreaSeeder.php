<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Seeder;

class BaliAreaSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing areas
        Area::truncate();

        $baliAreas = [
            'デンパサール (Denpasar)',
            'クタ (Kuta)',
            'レギャン (Legian)',
            'スミニャック (Seminyak)',
            'チャングー (Canggu)',
            'ジンバラン (Jimbaran)',
            'ヌサドゥア (Nusa Dua)',
            'サヌール (Sanur)',
            'ウブド (Ubud)',
        ];

        foreach ($baliAreas as $areaName) {
            Area::create(['name' => $areaName]);
        }

        $this->command->info('Bali areas seeded successfully!');
    }
}