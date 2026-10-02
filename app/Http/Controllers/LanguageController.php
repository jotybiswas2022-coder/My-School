<?php

namespace App\Http\Controllers;

use App\Http\Middleware\SetLocale;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    /**
     * Store the chosen locale in the session and send the visitor back.
     */
    public function switch(Request $request, string $locale): RedirectResponse
    {
        if (array_key_exists($locale, SetLocale::SUPPORTED)) {
            $request->session()->put('locale', $locale);
        }

        return redirect()->back(fallback: route('home'));
    }
}
