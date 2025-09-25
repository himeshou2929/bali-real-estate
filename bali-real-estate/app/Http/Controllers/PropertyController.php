<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Area;
use App\Services\PropertySearchService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PropertyController extends Controller
{
    private function mapImageVariants(array $img): array
    {
        // 既存の画像URLをそのまま返す（最適化はフロントエンドでフォールバック処理）
        $base = $img["url"] ?? ($img["path"] ?? null);
        if (!$base) {
            // 基本情報がない場合はプレースホルダーを設定
            $img["url"] = 'https://via.placeholder.com/1280x800?text=No+Image';
        }
        
        // 将来的に画像最適化が必要な場合のための準備
        // 現在はPicsumを使用しているため、元のURLをそのまま使用
        $img["variants"] = [
            "large"  => [
                "jpg" => $img["url"] ?? 'https://via.placeholder.com/1280x800?text=No+Image',
                "webp" => null,
                "avif" => null
            ],
            "medium" => [
                "jpg" => $img["url"] ?? 'https://via.placeholder.com/960x600?text=No+Image',
                "webp" => null,
                "avif" => null
            ],
            "thumb"  => [
                "jpg" => $img["url"] ?? 'https://via.placeholder.com/480x300?text=No+Image',
                "webp" => null,
                "avif" => null
            ],
        ];
        
        return $img;
    }

    public function index(Request $request, PropertySearchService $searchService)
    {
        $query = $searchService->build($request);
        $properties = $query->paginate(12)->withQueryString();

        return Inertia::render('Properties/Index', [
            'properties' => $properties,
            'areas' => Area::orderBy('name')->get(),
            'filters' => [
                'bedrooms' => [1,2,3,4,5],
            ],
            'query' => $request->only(['q','area_id','min_price','max_price','beds','baths','yield_min','sort',
                'has_monthly_rent','monthly_rent_max','has_yearly_rent','yearly_rent_max',
                'has_leasehold','leasehold_price_max','has_freehold','freehold_price_max']),
        ]);
    }

    public function show(Property $property)
    {
        if ($property->is_published) {
            $property->increment('views_count');
        }
        $property->load(['area','images' => fn($q) => $q->orderBy('sort_order')]);
        
        // Inertia用に画像データを準備
        $propertyData = $property->toArray();
        
        // featured_imageを画像配列に含める
        $images = [];
        if ($property->featured_image) {
            $images[] = [
                'url' => $property->featured_image,
                'path' => $property->featured_image,
                'alt' => $property->title ?? 'Property Image'
            ];
        }
        
        // 追加の画像（PropertyImageテーブル）がある場合は追加
        if ($property->images && $property->images->count() > 0) {
            foreach ($property->images as $img) {
                $images[] = [
                    'url' => $img->url,
                    'path' => $img->url,
                    'alt' => $img->alt ?? ($property->title . ' - 追加画像'),
                    'sort_order' => $img->sort_order
                ];
            }
        }
        
        $propertyData['images'] = $images;
        
        return Inertia::render('Properties/Show', ['property' => $propertyData]);
    }
}
