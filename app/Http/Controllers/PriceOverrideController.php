<?php

namespace App\Http\Controllers;

use App\Models\PriceList;
use App\Models\PriceOverride;
use Illuminate\Http\Request;

class PriceOverrideController extends Controller
{
    public function index(PriceList $pricelist)
    {
        $overrides = $pricelist->overrides()->orderBy('for_date')->orderBy('starts_at')->get();
        return view('priceoverrides.index', compact('pricelist','overrides'));
    }

    public function create(PriceList $pricelist)
    {
        return view('priceoverrides.create', [
            'pricelist' => $pricelist,
            'override' => new PriceOverride()
        ]);
    }

    public function store(Request $request, PriceList $pricelist)
    {
        $validated = $request->validate([
            'for_date'   => ['required','date'],
            'starts_at'  => ['required','date_format:H:i'],
            'ends_at'    => ['required','date_format:H:i','after:starts_at'],
            'slot_price' => ['required','numeric','min:0'],
            'capacity'   => ['nullable','integer','min:0'],
            'is_active'  => ['sometimes','boolean'],
        ]);

        $pricelist->overrides()->create($validated);

        return redirect()->route('priceoverrides.index', $pricelist)->with('success', __('messages.priceoverrides.messages.created'));
    }

    public function edit(PriceList $pricelist, PriceOverride $override)
    {
        return view('priceoverrides.edit', compact('pricelist','override'));
    }

    public function update(Request $request, PriceList $pricelist, PriceOverride $override)
    {
        \Log::info('REQUEST', $request->all());
        \Log::info('VALIDATED BEFORE', []);

        $validated = $request->validate([
            'for_date'   => ['required','date'],
            'starts_at'  => ['required','date_format:H:i'],
            'ends_at'    => ['required','date_format:H:i','after:starts_at'],
            'slot_price' => ['required','numeric','min:0'],
            'capacity'   => ['nullable','integer','min:0'],
            'is_active'  => ['sometimes','boolean'],
        ]);

        \Log::info('VALIDATED', $validated);

        $override->update($validated);

        \Log::info('UPDATE CALLED', [
            'pricelist_id' => $pricelist->id,
            'override_id' => $override->id,
            'data' => $request->all()
        ]);

        return redirect()->route('priceoverrides.index', $pricelist)->with('success', __('messages.priceoverrides.messages.created'));
    }

    public function destroy(PriceList $pricelist, PriceOverride $override)
    {
        $pricelist = $override->priceList;
        $override->delete();

        return redirect()->route('priceoverrides.index', $pricelist)->with('success', __('messages.priceoverrides.messages.deleted'));
    }
}
