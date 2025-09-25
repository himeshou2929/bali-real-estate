<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    public function run(): void
    {
        $cangguArea = Area::where('name', 'Canggu')->first();
        $agent = User::where('role', 'agent')->first();

        Property::create([
            'area_id' => $cangguArea->id,
            'agent_id' => $agent->id,
            'title' => 'Modern Villa with Pool',
            'price_usd' => 320000,
            'bedrooms' => 3,
            'bathrooms' => 2,
            'land_sqm' => 300,
            'building_sqm' => 180,
            'description' => 'Close to beach. Leasehold 25 years.',
            'featured_image' => 'https://picsum.photos/800/600?random=1',
            'is_published' => true
        ]);
    }
}