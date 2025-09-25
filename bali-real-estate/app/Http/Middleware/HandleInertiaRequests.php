<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'flash' => [
                'success' => fn() => session('success'),
                'error'   => fn() => session('error'),
                'info'    => fn() => session('info'),
            ],
            'i18n' => [
                'locale' => app()->getLocale(),
                'messages' => \Illuminate\Support\Facades\Lang::get('ui'),
            ],
            'userCurrency' => fn()=> optional($request->user())->currency ?? 'JPY',
            'csrf_token' => csrf_token(),
        ];
    }
}
