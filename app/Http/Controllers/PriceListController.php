<?php

namespace App\Http\Controllers;

use App\Models\PriceList;
use App\Models\Platform;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PriceListController extends Controller
{
    public function index(Request $request)
    {
        $pricelists = PriceList::with('platform')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('pricelists.index', compact('pricelists'));
    }

    public function create()
    {
        $platforms  = Platform::where('is_active', true)->orderBy('name')->get();
        $currencies = ['USD','EUR','BGN','GBP','RUB']; // валюта прайс-листа — своя
        $timezones  = ['Europe/Sofia','Europe/Moscow','Europe/Berlin','Asia/Dubai','UTC'];

        return view('pricelists.create', [
            'pricelist'  => new PriceList(),
            'platforms'  => $platforms,
            'currencies' => $currencies,
            'timezones'  => $timezones,
        ]);
    }

    public function store(Request $request)
    {
        // Set is_active to false if not present in request (unchecked checkbox)
        $request->merge(['is_active' => $request->has('is_active')]);

        $validated = $request->validate([
            'platform_id'            => ['required','exists:platforms,id'],
            'name'                   => ['required','string','max:255'],
            'currency'               => ['required','string','size:3'],
            'is_active'              => ['sometimes','boolean'],
            'valid_from'             => ['nullable','date','after_or_equal:today'],
            'valid_to'               => ['nullable','date','after_or_equal:valid_from'],
            'timezone'               => ['required','string','max:64'],
            'default_slot_duration'  => ['required','integer','min:5','max:480'],
        ]);

        $priceList = PriceList::create($validated);

        return redirect()
            ->route('pricelists.index', $priceList)
            ->with('success', __('messages.pricelists.messages.created'));
    }

    public function show(PriceList $pricelist)
    {
        $pricelist->load(['platform','rules','overrides']);
        $pricelists = PriceList::with('platform')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();
        return view('pricelists.show', compact('pricelist', 'pricelists'));
    }

    public function edit(PriceList $pricelist)
    {
        $platforms  = Platform::where('is_active', true)->orderBy('name')->get();
        $currencies = ['USD','EUR','BGN','GBP','RUB'];
        $timezones  = ['Europe/Sofia','Europe/Moscow','Europe/Berlin','Asia/Dubai','UTC'];

        return view('pricelists.edit', compact('pricelist','platforms','currencies','timezones'));
    }

    public function update(Request $request, PriceList $pricelist)
    {
        // Set is_active to false if not present in request (unchecked checkbox)
        $request->merge(['is_active' => $request->has('is_active')]);

        $validated = $request->validate([
            'platform_id'            => ['required','exists:platforms,id'],
            'name'                   => ['required','string','max:255'],
            'currency'               => ['required','string','size:3'],
            'is_active'              => ['sometimes','boolean'],
            'valid_from'             => ['nullable','date','after_or_equal:today'],
            'valid_to'               => ['nullable','date','after_or_equal:valid_from'],
            'timezone'               => ['required','string','max:64'],
            'default_slot_duration'  => ['required','integer','min:5','max:480'],
        ]);

        $pricelist->update($validated);

        return redirect()
            ->route('pricelists.index', $pricelist)
            ->with('success', __('messages.pricelists.messages.updated'));
    }

    public function destroy(PriceList $pricelist)
    {
        $pricelist->delete();

        return redirect()
            ->route('pricelists.index')
            ->with('success','Price list deleted successfully.');
    }

    public function generateSlots(PriceList $pricelist)
    {
        $service = app(\App\Services\SlotGeneratorService::class);
        $created = $service->generateForPriceList($pricelist);

        return redirect()
            ->route('pricelists.show', $pricelist)
            ->with('success', __('messages.pricelists.messages.generated_slots', ['count' => $created]));
    }

}
