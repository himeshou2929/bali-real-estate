<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Property;

class PropertyFactory extends Factory
{
    protected $model = Property::class;

    public function definition(): array
    {
        return [
            'title'        => $this->faker->streetName(),
            'name'         => null,
            'description'  => $this->faker->paragraph(),
            'area_id'      => \App\Models\Area::factory(),
            'price_usd'    => $this->faker->numberBetween(80000, 500000),
            'bedrooms'     => $this->faker->numberBetween(0, 5),
            'bathrooms'    => $this->faker->numberBetween(0, 4),
            'land_sqm'     => $this->faker->numberBetween(100, 1000),
            'building_sqm' => $this->faker->numberBetween(50, 500),
            'is_published' => true,
        ];
    }

    public function unpublished(): self
    {
        return $this->state(fn() => ['is_published' => false]);
    }
}