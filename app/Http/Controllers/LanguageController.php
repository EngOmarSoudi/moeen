<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\App;

class LanguageController extends Controller
{
    public function switch(string $locale): RedirectResponse
    {
        if (! in_array($locale, ['en', 'ar'])) {
            abort(400);
        }

        App::setLocale($locale);
        session()->put('locale', $locale);

        return redirect()->back();
    }
}
