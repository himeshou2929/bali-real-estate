<?php

namespace Tests\Feature;

use App\Models\Property;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PropertySearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_shows_published_only(): void
    {
        $p1 = Property::factory()->create(['is_published' => true]);
        $p2 = Property::factory()->create(['is_published' => false]);

        $res = $this->get('/properties');
        $res->assertStatus(200);
        $html = $res->getContent();

        $this->assertStringContainsString((string)$p1->id, $html);
        $this->assertStringNotContainsString((string)$p2->id, $html);
    }

    public function test_filter_by_bedrooms_or_more(): void
    {
        $p0 = Property::factory()->create(['bedrooms' => 0]);
        $p2 = Property::factory()->create(['bedrooms' => 2]);

        $res = $this->get('/properties?bedrooms=1');
        $res->assertOk();
        $html = $res->getContent();

        $this->assertStringContainsString((string)$p2->id, $html);
        $this->assertStringNotContainsString((string)$p0->id, $html);
    }

    public function test_filter_by_price_range(): void
    {
        $cheap = Property::factory()->create(['price_usd' => 50000]);
        $mid   = Property::factory()->create(['price_usd' => 150000]);
        $high  = Property::factory()->create(['price_usd' => 400000]);

        $res = $this->get('/properties?min_price=100000&max_price=200000');
        $res->assertOk();
        $html = $res->getContent();

        $this->assertStringContainsString((string)$mid->id, $html);
        $this->assertStringNotContainsString((string)$cheap->id, $html);
        $this->assertStringNotContainsString((string)$high->id, $html);
    }
}
