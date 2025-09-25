<?php

namespace Database\Seeders;

use App\Models\Property;
use Illuminate\Database\Seeder;

class RentalTenurePricesSeeder extends Seeder
{
    public function run(): void
    {
        $properties = Property::all();

        foreach ($properties as $property) {
            // Generate realistic rental and tenure prices based on property value
            $basePrice = $property->price_usd;
            
            // Monthly rent: approximately 0.5-1.5% of property value per month
            $monthlyRentUSD = rand(intval($basePrice * 0.005), intval($basePrice * 0.015));
            
            // Convert to JPY (approximately 150 JPY per USD)
            $monthlyRentJPY = $monthlyRentUSD * 150;
            
            // Yearly rent: 12 months with slight discount
            $yearlyRentJPY = $monthlyRentJPY * 11.5; // 11.5x monthly for annual discount
            
            // Leasehold price: 60-80% of freehold price
            $leaseholdMultiplier = rand(60, 80) / 100;
            $leaseholdPriceJPY = $basePrice * 150 * $leaseholdMultiplier;
            
            // Freehold price: current USD price converted to JPY
            $freeholdPriceJPY = $basePrice * 150;
            
            // Leasehold years: typically 25-30 years for Bali
            $leaseholdYears = rand(25, 30);
            
            $property->update([
                'monthly_rent' => round($monthlyRentJPY),
                'yearly_rent' => round($yearlyRentJPY),
                'leasehold_price' => round($leaseholdPriceJPY),
                'freehold_price' => round($freeholdPriceJPY),
                'leasehold_years' => $leaseholdYears,
            ]);
        }

        // Add some specific examples matching your requested values
        $firstProperty = Property::first();
        if ($firstProperty) {
            $firstProperty->update([
                'monthly_rent' => 319132,      // 月額レンタル：319,132 円
                'yearly_rent' => 2205249,     // 年間レンタル：2,205,249 円  
                'freehold_price' => 433484409, // FREEHOLD：433,484,409 円
                'leasehold_price' => 300000000, // レaseホールド：約3億円
                'leasehold_years' => 27,
            ]);
        }

        $this->command->info('Rental and tenure prices seeded successfully!');
    }
}