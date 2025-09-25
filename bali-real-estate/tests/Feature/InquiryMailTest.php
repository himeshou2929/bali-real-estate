<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use App\Mail\InquirySubmitted;
use App\Models\Property;
use PHPUnit\Framework\Attributes\Test;

class InquiryMailTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function 問い合わせ送信で管理者メールがキューされる()
    {
        // Arrange
        Mail::fake();
        // Area データを事前に作成（PropertyFactory の area_id => 1 に対応）
        $this->seed(\Database\Seeders\AreaSeeder::class);
        // Factoryで物件を1件用意
        $property = Property::factory()->create();

        // ルート名の解決を試み、なければフォールバックURLにPOST
        $payload = [
            'property_id' => $property->id,
            'name'        => 'テスト太郎',
            'email'       => 'test@example.com',
            'phone'       => '080-0000-0000',
            'message'     => '問い合わせテスト本文',
        ];

        // 管理者メールをテスト用に固定
        config(['mail.admin_address' => 'admin@example.com']);

        // Act
        $response = null;
        try {
            // まずは一般的なルート名を試す
            $response = $this->post(route('inquiries.store'), $payload);
        } catch (\Throwable $e) {
            // フォールバック（必要に応じて変更）
            $response = $this->post('/inquiries', $payload);
        }

        // Assert
        $response->assertStatus(302); // バリデーション成功→リダイレクト想定
        $this->assertDatabaseHas('inquiries', [
            'property_id' => $property->id,
            'email'       => 'test@example.com',
        ]);

        Mail::assertQueued(InquirySubmitted::class, function ($mailable) {
            return $mailable->hasTo('admin@example.com');
        });
    }
}