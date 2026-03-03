<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    protected array $supported = ['en', 'bn'];

    public function handle(Request $request, Closure $next): Response
    {
        $locale = session('locale')
            ?? Setting::get('default_locale', config('app.locale', 'en'));

        if (! in_array($locale, $this->supported)) {
            $locale = 'en';
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
