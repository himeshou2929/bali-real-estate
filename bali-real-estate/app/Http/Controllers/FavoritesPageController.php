<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class FavoritesPageController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $props = auth()->user()
            ->favoriteProperties()               // User::favoriteProperties() は既に定義済みの想定
            ->with(['area','images'])            // 一覧に必要な関連
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return Inertia::render('Favorites/Index', [
            'properties' => $props,
        ]);
    }
}
