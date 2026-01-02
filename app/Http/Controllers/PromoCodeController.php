<?php

namespace App\Http\Controllers;

use App\Models\PromoCode;
use App\Models\Platform;
use App\Models\PriceList;
use Illuminate\Http\Request;

class PromoCodeController extends Controller
{
    public function index()
    {
        $promocodes = PromoCode::latest('id')->paginate(20);
        return view('promocodes.index', compact('promocodes'));
    }

    public function create()
    {
        return view('promocodes.create', [
            'promo'      => new PromoCode(),
            'platforms'  => Platform::orderBy('name')->get(),
            'pricelists' => PriceList::with('platform')->orderBy('id','desc')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code'                 => ['required','string','max:64','unique:promo_codes,code'],
            'discount_type'        => ['required','in:percent,fixed'],
            'discount_value'       => ['required','numeric','min:0'],
            'currency'             => ['nullable','string','size:3'],
            'max_uses'             => ['nullable','integer','min:1'],
            'max_uses_per_client'  => ['nullable','integer','min:1'],
            'starts_at'            => ['nullable','date'],
            'ends_at'              => ['nullable','date','after_or_equal:starts_at'],
            'min_order_amount'     => ['nullable','numeric','min:0'],
            'applies_to'           => ['required','in:global,platform,price_list'],
            'platform_id'          => ['nullable','exists:platforms,id'],
            'price_list_id'        => ['nullable','exists:price_lists,id'],
            'is_active'            => ['sometimes','boolean'],
            'is_stackable'         => ['sometimes','boolean'],
        ]);

        PromoCode::create($validated);

        return redirect()->route('promocodes.index')->with('success', __('messages.promocodes.messages.created'));
    }

    public function edit(PromoCode $promocode)
    {
        return view('promocodes.edit', [
            'promo'      => $promocode,
            'platforms'  => Platform::orderBy('name')->get(),
            'pricelists' => PriceList::with('platform')->orderBy('id','desc')->get(),
        ]);
    }

    public function update(Request $request, PromoCode $promocode)
    {
        $validated = $request->validate([
            'code'                 => ['required','string','max:64',"unique:promo_codes,code,{$promocode->id}"],
            'discount_type'        => ['required','in:percent,fixed'],
            'discount_value'       => ['required','numeric','min:0'],
            'currency'             => ['nullable','string','size:3'],
            'max_uses'             => ['nullable','integer','min:1'],
            'max_uses_per_client'  => ['nullable','integer','min:1'],
            'starts_at'            => ['nullable','date'],
            'ends_at'              => ['nullable','date','after_or_equal:starts_at'],
            'min_order_amount'     => ['nullable','numeric','min:0'],
            'applies_to'           => ['required','in:global,platform,price_list'],
            'platform_id'          => ['nullable','exists:platforms,id'],
            'price_list_id'        => ['nullable','exists:price_lists,id'],
            'is_active'            => ['sometimes','boolean'],
            'is_stackable'         => ['sometimes','boolean'],
        ]);

        $promocode->update($validated);

        return redirect()->route('promocodes.index')->with('success', __('messages.promocodes.messages.updated'));
    }

    public function destroy(PromoCode $promocode)
    {
        $promocode->delete();
        return redirect()->route('promocodes.index')->with('success', __('messages.promocodes.messages.deleted'));
    }
}
