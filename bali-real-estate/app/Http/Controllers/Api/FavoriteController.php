<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Property;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{

    // GET /api/favorites
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        $query = Property::query()
            ->select('properties.*')
            ->join('favorites', 'favorites.property_id', '=', 'properties.id')
            ->where('favorites.user_id', $userId)
            ->with(['area']); // 必要なら代表画像リレーションも

        return response()->json([
            'data' => $query->paginate(20)
        ]);
    }

    // POST /api/favorites  { property_id }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'property_id' => ['required','exists:properties,id'],
        ]);

        Favorite::firstOrCreate([
            'user_id'     => $request->user()->id,
            'property_id' => $validated['property_id'],
        ]);

        return response()->json(['status' => 'ok']);
    }

    // DELETE /api/favorites/{property}
    public function destroy(Request $request, Property $property)
    {
        Favorite::where('user_id', $request->user()->id)
            ->where('property_id', $property->id)
            ->delete();

        return response()->json(['status' => 'ok']);
    }

    // GET /favorites - お気に入り一覧ページ
    public function page(Request $request)
    {
        $userId = $request->user()->id;
        
        $favorites = Property::query()
            ->select('properties.*')
            ->join('favorites', 'favorites.property_id', '=', 'properties.id')
            ->where('favorites.user_id', $userId)
            ->with(['area', 'images'])
            ->orderBy('favorites.created_at', 'desc')
            ->paginate(12);

        return \Inertia\Inertia::render('Favorites/Index', [
            'favorites' => $favorites
        ]);
    }
}
