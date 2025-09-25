<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FavoritesController extends Controller
{

    public function index(Request $request)
    {
        $user = $request->user();

        $favorites = $user->favoriteProperties()
            ->with(['area', 'images'])
            ->orderByDesc('favorites.created_at')
            ->paginate(12)
            ->through(function($property) {
                $primaryImage = $property->images->first();
                return [
                    'id' => $property->id,
                    'title' => $property->title,
                    'title_en' => $property->title_en,
                    'price' => $property->price_usd,
                    'currency' => $property->currency ?? 'USD',
                    'status' => $property->status ?? 'available',
                    'area' => $property->area ? ['name' => $property->area->name] : null,
                    'featured_image_url' => $property->featured_image ? 
                        Storage::url($property->featured_image) : 
                        ($primaryImage ? $primaryImage->url : null),
                    'primaryImage' => [
                        'url' => $primaryImage ? $primaryImage->url : null
                    ],
                ];
            })
            ->withQueryString();

        return inertia('User/Favorites', [
            'favorites' => $favorites
        ]);
    }

    public function destroy(\Illuminate\Http\Request $request, \App\Models\Property $property)
    {
        $user = $request->user();
        // リレーション名に合わせて detach
        if (method_exists($user, "favoriteProperties")) {
            $user->favoriteProperties()->detach($property->id);
        } else {
            // 例: favorites() の場合
            if (method_exists($user, "favorites")) $user->favorites()->detach($property->id);
        }
        return back()->with("success","お気に入りを解除しました");
    }
}