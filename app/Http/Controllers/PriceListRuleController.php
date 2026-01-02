<?php

namespace App\Http\Controllers;

use App\Models\PriceList;
use App\Models\PriceListRule;
use Illuminate\Http\Request;

class PriceListRuleController extends Controller
{
    public function index(PriceList $pricelist)
    {
        $rules = $pricelist->rules()->orderByRaw('weekday is null')->orderBy('weekday')->orderBy('starts_at')->get();
        return view('pricerules.index', compact('pricelist','rules'));
    }

    public function create(PriceList $pricelist)
    {
        return view('pricerules.create', [
            'pricelist' => $pricelist,
            'rule' => new PriceListRule()
        ]);
    }

    public function store(Request $request, PriceList $pricelist)
    {
        $validated = $request->validate([
            'weekday'    => ['nullable','integer','min:1','max:7'],
            'starts_at'  => ['required','date_format:H:i'],
            'ends_at'    => ['required','date_format:H:i','after:starts_at'],
            'slot_price' => ['required','numeric','min:0'],
            'capacity'   => ['required','integer','min:0'],
            'is_active'  => ['sometimes','boolean'],
        ]);

        $pricelist->rules()->create($validated);

        return redirect()->route('pricerules.index', $pricelist)->with('success', __('messages.pricerules.messages.created'));
    }

    public function edit(PriceList $pricelist, PriceListRule $rule)
    {
        return view('pricerules.edit', compact('pricelist','rule'));
    }

    public function update(Request $request, PriceList $pricelist, PriceListRule $rule)
    {
        $validated = $request->validate([
            'weekday'    => ['nullable','integer','min:1','max:7'],
            'starts_at'  => ['required','date_format:H:i'],
            'ends_at'    => ['required','date_format:H:i','after:starts_at'],
            'slot_price' => ['required','numeric','min:0'],
            'capacity'   => ['required','integer','min:0'],
            'is_active'  => ['sometimes','boolean'],
        ]);

        $rule->update($validated);

        return redirect()->route('pricerules.index', $pricelist)->with('success', __('messages.pricerules.messages.updated'));
    }

    public function destroy(PriceList $pricelist, PriceListRule $rule)
    {
        $pricelist = $rule->priceList;
        $rule->delete();
        return redirect()->route('pricerules.index', $pricelist)->with('success', __('messages.pricerules.messages.deleted'));
    }
}
