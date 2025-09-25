<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    // Middleware applied via routes instead of constructor in Laravel 11

    public function index(Request $request)
    {
        $user = $request->user();
        // 関連カウント（必要に応じて relation 名を合わせてください）
        $favoritesCount = $user->favoriteProperties()->count() ?? 0;
        $inquiriesCount = $user->inquiries()->count() ?? 0;

        return Inertia::render('User/Dashboard', [
            'me' => $user->only(['id','name','email','phone','avatar','language','currency']),
            'stats' => [
                'favorites' => $favoritesCount,
                'inquiries' => $inquiriesCount,
            ]
        ]);
    }

    public function profile(Request $request)
    {
        $u = $request->user();
        return Inertia::render('User/Profile', [
            'user' => [
                'id'=>$u->id,
                'name'=>$u->name,
                'email'=>$u->email,
                'phone'=>$u->phone,
                'language'=>$u->language ?? 'en',
                'currency'=>$u->currency ?? 'USD',
            ]
        ]);
    }

    public function updateProfile(Request $request)
    {
        $u = $request->user();

        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'phone' => ['nullable','string','max:50'],
            'language' => ['nullable','in:ja,en,id'],
            'currency' => ['nullable','in:JPY,USD,IDR'],
        ]);

        $u->forceFill($data)->save();

        return back()->with('status', 'updated');
    }
}