<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PriceList;
use App\Models\Platform;

class PriceListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pricelists = PriceList::all();
        return view('pricelists.index', compact('pricelists'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Получаем список всех активных площадок
        $platforms = Platform::where('is_active', true)->get();

        // Доступные валюты (при необходимости — можно вынести в конфиг)
        $currencies = ['USD', 'EUR', 'BGN', 'GBP', 'RUB'];

        // Список популярных таймзон (можно заменить на CarbonTimeZone::listIdentifiers())
        $timezones = [
            'Europe/Sofia', 'Europe/Moscow', 'Europe/Berlin', 
            'Asia/Dubai', 'UTC'
        ];

        return view('pricelists.create', [
            'pricelist' => new PriceList(),
            'platforms' => $platforms,
            'currencies' => $currencies,
            'timezones' => $timezones,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1️⃣ Валидация данных формы
        $validated = $request->validate([
            'platform_id' => 'required|exists:platforms,id',
            'name'        => 'required|string|max:255',
            'base_price'  => 'required|numeric|min:0',
            'currency'    => 'required|string|size:3',
            'timezone'    => 'required|string|max:64',
            'starts_at'   => 'nullable|date',
            'ends_at'     => 'nullable|date|after:starts_at',
            'is_active'   => 'boolean',
        ]);

        // 2️⃣ Создаём прайс-лист
        $priceList = \App\Models\PriceList::create($validated);

        // 3️⃣ Перенаправление с сообщением
        return redirect()
            ->route('pricelists.show', $priceList)
            ->with('success', 'Прайс-лист успешно создан.');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PriceList $pricelist)
    {
        // Аналогично create(), но с уже существующим объектом
        $platforms = Platform::where('is_active', true)->get();
        $currencies = ['USD', 'EUR', 'BGN', 'GBP', 'RUB'];
        $timezones = [
            'Europe/Sofia', 'Europe/Moscow', 'Europe/Berlin', 
            'Asia/Dubai', 'UTC'
        ];

        return view('pricelists.edit', [
            'pricelist' => $pricelist,
            'platforms' => $platforms,
            'currencies' => $currencies,
            'timezones' => $timezones,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, \App\Models\PriceList $priceList)
    {
        // 1️⃣ Валидация данных формы
        $validated = $request->validate([
            'platform_id' => 'required|exists:platforms,id',
            'name'        => 'required|string|max:255',
            'base_price'  => 'required|numeric|min:0',
            'currency'    => 'required|string|size:3',
            'timezone'    => 'required|string|max:64',
            'starts_at'   => 'nullable|date',
            'ends_at'     => 'nullable|date|after:starts_at',
            'is_active'   => 'boolean',
        ]);

        // 2️⃣ Обновляем данные
        $priceList->update($validated);

        // 3️⃣ Перенаправление на страницу просмотра
        return redirect()
            ->route('pricelists.show', $priceList)
            ->with('success', 'Прайс-лист успешно обновлён.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
