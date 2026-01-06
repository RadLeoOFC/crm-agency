<?php

namespace App\Http\Controllers;

use App\Models\Language;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageSwitchController extends Controller
{
    public function switch(string $code): RedirectResponse
    {
        $language = Language::where('code', $code)->firstOrFail();

        Session::put('locale', $language->code);
        App::setLocale($language->code);

        if (auth()->check()) {
            auth()->user()->update(['locale' => $language->code]);
        }

        return redirect()->back();
    }

}

