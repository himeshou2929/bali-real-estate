<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Seeder;

class UpdateBaliAreasSeeder extends Seeder
{
    public function run(): void
    {
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

        // Update existing areas or create new ones
        foreach ($baliAreas as $index => $areaName) {
            $areaId = $index + 1;
            Area::updateOrCreate(['id' => $areaId], ['name' => $areaName]);
        }

        $this->command->info('Bali areas updated successfully!');
    }
}