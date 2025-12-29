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
        // Проверяем язык в БД
        $language = Language::where('code', $code)->firstOrFail();

        // Сохраняем в сессию
        Session::put('locale', $language->code);

        // Устанавливаем язык сразу
        App::setLocale($language->code);

        return redirect()->back();
    }
}

