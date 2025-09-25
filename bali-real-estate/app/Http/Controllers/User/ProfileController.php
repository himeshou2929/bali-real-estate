<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{

    public function show(Request $request)
    {
        return inertia('User/Profile', [
            'user' => $request->user()->only(['id','name','email','phone','language','currency','avatar']),
        ]);
    }

    public function update(ProfileUpdateRequest $request)
    {
        $user = $request->user();
        $data = $request->validated();

        // アバター
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $path;
        }

        // メールが変わる場合は再認証
        $emailChanged = isset($data['email']) && $data['email'] !== $user->email;
        if ($emailChanged) {
            $user->forceFill([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'language' => $data['language'],
                'currency' => $data['currency'],
                'avatar' => $data['avatar'] ?? $user->avatar,
                'email_verified_at' => null,
            ])->save();
            // 検証メール送信
            $user->sendEmailVerificationNotification();
            return back()->with('info','メールアドレスを変更しました。確認メールのリンクから検証を完了してください。');
        }

        // 通常更新
        $user->update($data);
        return back()->with('success','プロフィールを更新しました');
    }
}