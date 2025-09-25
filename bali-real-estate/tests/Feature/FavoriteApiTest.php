<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Property;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavoriteApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Sanctum 認証を使う設定の場合は、api middleware で auth:sanctum 前提
        // tests では actingAs で十分
    }

    public function test_user_can_add_and_remove_favorite(): void
    {
        $user = User::factory()->create();
        $property = Property::factory()->create();

        $this->actingAs($user, 'web');

        // 追加
        $res = $this->postJson('/api/favorites', ['property_id' => $property->id]);
        $res->assertOk();

        // 一覧に含まれる
        $res = $this->getJson('/api/favorites');
        $res->assertOk()->assertJsonPath('data.data.0.id', $property->id);

        // 解除
        $res = $this->deleteJson("/api/favorites/{$property->id}");
        $res->assertOk();

        // 空になる
        $res = $this->getJson('/api/favorites');
        $res->assertOk()->assertJsonCount(0, 'data.data');
    }

    public function test_guest_cannot_write_favorite(): void
    {
        $property = Property::factory()->create();

        $this->postJson('/api/favorites', ['property_id' => $property->id])
            ->assertStatus(401);
        $this->deleteJson("/api/favorites/{$property->id}")
            ->assertStatus(401);
    }
}
