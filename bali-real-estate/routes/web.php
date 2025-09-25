<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\Api\FavoriteController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// 不動産サイトのメインページを物件一覧に設定
Route::get('/', [PropertyController::class, 'index'])->name('home');

// 物件関連ルート
Route::get('/properties', [PropertyController::class, 'index'])->name('properties.index');
Route::get('/properties/{property}', [PropertyController::class, 'show'])->name('properties.show');

// 問い合わせ関連ルート
Route::get('/inquiries/create', [InquiryController::class, 'create'])->name('inquiries.create');
Route::post('/inquiries', [InquiryController::class, 'store'])->name('inquiries.store');
Route::get('/inquiries/success', [InquiryController::class, 'success'])->name('inquiries.success');

// 管理画面（認証必要）
Route::middleware(['auth', 'verified'])->group(function () {
    // Original dashboard route replaced with user dashboard
    // Route::get('/dashboard', function () {
    //     return Inertia::render('Dashboard');
    // })->name('dashboard');
    
    Route::get('/admin/inquiries', [InquiryController::class, 'index'])->name('admin.inquiries');
    Route::get('/admin/inquiries/{inquiry}', [InquiryController::class, 'show'])->name('admin.inquiries.show');
    Route::patch('/admin/inquiries/{inquiry}', [InquiryController::class, 'update'])->name('admin.inquiries.update');
    Route::post('/admin/inquiries/{inquiry}/reply-template', [InquiryController::class, 'replyWithTemplate'])->name('admin.inquiries.reply-template');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // お気に入りページ
    Route::get('/favorites', [FavoriteController::class, 'page'])->name('favorites.page');
    
    // お気に入りAPI
    Route::get('/api/favorites', [FavoriteController::class, 'index']);
    Route::post('/api/favorites', [FavoriteController::class, 'store']);
    Route::delete('/api/favorites/{property}', [FavoriteController::class, 'destroy']);
    
    // メールプレビュー（開発用）
    Route::get('/mail/preview/inquiry-reply/{inquiry}', function (\App\Models\Inquiry $inquiry) {
        return new \App\Mail\InquiryReplyMailable(
            $inquiry,
            'Sample Reply Subject',
            'This is a sample reply body for testing the email template.',
            'Best regards,\nYour Agent Name'
        );
    })->name('mail.preview.inquiry-reply');
});

require __DIR__.'/auth.php';

if (app()->environment(['local','development'])) {
    Route::get('/_debug/notify/{inquiry}/{type}', function (\App\Models\Inquiry $inquiry, string $type) {
        switch ($type) {
            case 'created': event(new \App\Events\InquiryCreated($inquiry)); break;
            case 'assigned': event(new \App\Events\InquiryAssigned($inquiry, 'Agent Taro')); break;
            case 'replied': event(new \App\Events\InquiryReplied($inquiry)); break;
            case 'status': event(new \App\Events\InquiryStatusChanged($inquiry, 'open', 'replied')); break;
            default: abort(404);
        }
        return 'OK: '.$type;
    })->middleware('auth');
}

// --- Sample restricted routes ---
Route::middleware(['auth','role:admin'])->group(function () {
    Route::get('/admin/only', fn()=>'Admin only page');
});

Route::middleware(['auth','role:agent'])->group(function () {
    Route::get('/agent/only', fn()=>'Agent only page');
});

use App\Http\Controllers\User\DashboardController;

Route::middleware(['auth'])->group(function () {
    // Breeze の /dashboard を会員マイページに差し替え
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // プロフィール表示・更新
    Route::get('/profile', [DashboardController::class, 'profile'])->name('user.profile');
    Route::post('/profile', [DashboardController::class, 'updateProfile'])->name('user.profile.update');
});

use App\Http\Controllers\Agent\PropertyController as AgentPropertyController;
use App\Http\Controllers\Agent\DashboardController as AgentDashboardController;
use App\Http\Controllers\User\ProfileController as UserProfileController;

Route::middleware(['auth','role:agent,admin'])->prefix('agent')->name('agent.')->group(function () {
    Route::get('/dashboard', [AgentDashboardController::class, 'index'])->name('dashboard');
    Route::resource('properties', AgentPropertyController::class)->except(['show']);
    Route::get('/reports', [App\Http\Controllers\Agent\ReportsController::class, 'index'])->name('reports');
    Route::get('/profile', [UserProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [UserProfileController::class, 'update'])->name('profile.update');
});

// ===== User MyPage =====
use App\Http\Controllers\User\FavoritesController;
use App\Http\Controllers\User\InquiriesController;

Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [DashboardController::class,'index'])->name('dashboard');
    Route::get('/profile',   [UserProfileController::class,'show'])->name('profile.show');
    Route::put('/profile',   [UserProfileController::class,'update'])->name('profile.update');

    Route::get('/favorites', [FavoritesController::class,'index'])->name('favorites');
    Route::delete('/favorites/{property}', [FavoritesController::class,'destroy'])->name('favorites.destroy');
    Route::get('/inquiries', [InquiriesController::class,'index'])->name('inquiries');
});

Route::get('/locale/{lang}', function ($lang) {
    if (!in_array($lang, ['ja','en','id'])) abort(404);
    if (auth()->check()) {
        auth()->user()->update(['language'=>$lang]);
    } else {
        session(['locale'=>$lang]);
        app()->setLocale($lang);
    }
    return back();
})->name('locale.switch');

use App\Http\Controllers\User\InquiryDetailController;
Route::middleware(['auth'])->get('/user/inquiries/{inquiry}', [InquiryDetailController::class,'show'])->name('user.inquiries.show');

use App\Http\Controllers\Api\GeocodeController;
Route::get('/api/geocode', GeocodeController::class); // 認証が必要なら ->middleware('auth')

// Test route to debug
Route::get('/test-register', function() {
    return '
    <html>
    <head><title>Test Registration</title></head>
    <body>
        <h1>Test Registration Form</h1>
        <form method="POST" action="/test-post">
            <input type="hidden" name="role" value="user">
            <p><label>Name: <input type="text" name="name" required></label></p>
            <p><label>Email: <input type="email" name="email" required></label></p>
            <p><label>Password: <input type="password" name="password" required></label></p>
            <p><label>Confirm Password: <input type="password" name="password_confirmation" required></label></p>
            <p><button type="submit">Test Submit</button></p>
        </form>
    </body>
    </html>';
});

Route::post('/test-post', function(Request $request) {
    \Log::info('Test POST route reached!!!', $request->all());
    return response('SUCCESS! POST request received. Data: ' . json_encode($request->all()));
});

Route::post('/test-login', function(Request $request) {
    \Log::info('Test login route reached', $request->all());
    return response('Test route reached! Data: ' . json_encode($request->all()));
});

Route::middleware('guest')->group(function () {
    Route::get('/register/buyer', function () {
        return Inertia::render('Auth/Register', ['defaultRole' => 'user']);
    })->name('register.buyer');

    Route::get('/register/agent', function () {
        return Inertia::render('Auth/Register', ['defaultRole' => 'agent']);
    })->name('register.agent');

    Route::get('/login/buyer', function () {
        return Inertia::render('Auth/Login', ['userType' => 'buyer', 'title' => '購入希望者ログイン']);
    })->name('login.buyer');

    Route::get('/login/agent', function () {
        return Inertia::render('Auth/Login', ['userType' => 'agent', 'title' => 'エージェントログイン']);
    })->name('login.agent');
});
