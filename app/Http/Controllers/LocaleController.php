<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;

class LocaleController extends Controller
{
    public function update(Request $request, string $locale)
    {
        if (!in_array($locale, ['en', 'ur'])) {
            abort(400);
        }

        Session::put('locale', $locale);

        if (auth()->check()) {
            auth()->user()->update(['locale' => $locale]);
        }

        App::setLocale($locale);

        return redirect()->back();
    }
}