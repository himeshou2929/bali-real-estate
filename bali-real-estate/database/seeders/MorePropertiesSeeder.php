<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Area;
use App\Models\Property;
use App\Models\User;

class MorePropertiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $areaIds = Area::pluck('id')->toArray();
        $agent = User::where('role', 'agent')->first();
        
        $properties = [
            [
                'title' => 'Uluwatu Cliff Villa',
                'area_id' => $areaIds[array_rand($areaIds)],
                'price_usd' => 850000,
                'bedrooms' => 4,
                'bathrooms' => 3,
                'land_sqm' => 500,
                'building_sqm' => 280,
                'description' => 'Stunning cliff-top villa with breathtaking ocean views. Modern design with infinity pool and luxury amenities.',
                'featured_image' => 'https://picsum.photos/800/600?random=2',
                'is_published' => true,
            ],
            [
                'title' => 'Seminyak Cozy House',
                'area_id' => $areaIds[array_rand($areaIds)],
                'price_usd' => 320000,
                'bedrooms' => 2,
                'bathrooms' => 2,
                'land_sqm' => 200,
                'building_sqm' => 120,
                'description' => 'Charming 2-bedroom house in the heart of Seminyak. Walking distance to beaches and trendy restaurants.',
                'featured_image' => 'https://picsum.photos/800/600?random=3',
                'is_published' => true,
            ],
            [
                'title' => 'Ubud Rice Field Retreat',
                'area_id' => $areaIds[array_rand($areaIds)],
                'price_usd' => 450000,
                'bedrooms' => 3,
                'bathrooms' => 2,
                'land_sqm' => 800,
                'building_sqm' => 180,
                'description' => 'Peaceful retreat surrounded by lush rice fields. Traditional Balinese architecture with modern comforts.',
                'featured_image' => 'https://picsum.photos/800/600?random=4',
                'is_published' => true,
            ],
            [
                'title' => 'Canggu Surf Lodge',
                'area_id' => $areaIds[array_rand($areaIds)],
                'price_usd' => 280000,
                'bedrooms' => 2,
                'bathrooms' => 1,
                'land_sqm' => 150,
                'building_sqm' => 90,
                'description' => 'Perfect for surf enthusiasts. Just 5 minutes walk to the beach with laid-back Canggu vibes.',
                'featured_image' => 'https://picsum.photos/800/600?random=5',
                'is_published' => true,
            ],
            [
                'title' => 'Sanur Beachfront Apartment',
                'area_id' => $areaIds[array_rand($areaIds)],
                'price_usd' => 180000,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'land_sqm' => null,
                'building_sqm' => 65,
                'description' => 'Modern beachfront apartment with direct beach access. Perfect for investment or holiday home.',
                'featured_image' => 'https://picsum.photos/800/600?random=6',
                'is_published' => true,
            ],
            [
                'title' => 'Jimbaran Bay Luxury Estate',
                'area_id' => $areaIds[array_rand($areaIds)],
                'price_usd' => 1200000,
                'bedrooms' => 5,
                'bathrooms' => 4,
                'land_sqm' => 1000,
                'building_sqm' => 450,
                'description' => 'Magnificent luxury estate with private beach access. Multiple pavilions, tropical gardens, and staff quarters.',
                'featured_image' => 'https://picsum.photos/800/600?random=7',
                'is_published' => true,
            ],
        ];

        foreach ($properties as $property) {
            $property['agent_id'] = $agent->id;
            Property::create($property);
        }
    }
}
