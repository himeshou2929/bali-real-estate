<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $lang = $request->get('lang')
            ?? ($request->user()->language ?? null)
            ?? config('app.locale');

        $lang = in_array($lang, ['ja','en','id']) ? $lang : config('app.locale');
        App::setLocale($lang);

        // フロント側でも参照できるようにヘッダで渡す（任意）
        app('router')->getRoutes(); // warmup
        return $next($request);
    }
}