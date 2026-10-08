<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Locales the site is available in.
     *
     * @var array<string, string>
     */
    public const SUPPORTED = [
        'en' => 'English',
        'bn' => 'বাংলা',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->session()->get('locale');

        if (! is_string($locale) || ! array_key_exists($locale, self::SUPPORTED)) {
            // Bengali-first public site; the admin panel reads better in English
            // because its interface text is English-only.
            $locale = ($request->is('admin') || $request->is('admin/*')) ? 'en' : 'bn';
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
