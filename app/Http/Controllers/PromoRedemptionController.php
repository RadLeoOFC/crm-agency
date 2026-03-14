<?php

namespace App\Http\Controllers;

use App\Models\PromoRedemption;
use App\Models\PromoCode;
use App\Models\Client;
use App\Models\Booking;
use Illuminate\Http\Request;

class PromoRedemptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $promoredemptions = PromoRedemption::with('promocode','client','booking')->latest('id')->paginate(20);
        return view('promoredemptions.index', compact('promoredemptions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('promoredemptions.create', [
            'promoredemption' => new PromoRedemption(),
            'promocodes' => PromoCode::orderBy('code')->get(),
            'clients' => Client::orderBy('name')->get(),
            'bookings' => Booking::orderBy('id')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = request()->validate([
            'promo_code_id' => ['required','exists:promo_codes,id'],
            'client_id' => ['required','exists:clients,id'],
            'booking_id' => ['nullable','exists:bookings,id'],
            'discount_amount' => ['required','numeric','min:0'],
            'used_at' => ['required', 'date'],
        ]);

        PromoRedemption::create($validated);

        return redirect()->route('promoredemptions.index')->with('success', __('messages.promoredemptions.messages.created'));
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
    public function edit(PromoRedemption $promoredemption)
    {
        return view('promoredemptions.edit', [
            'promoredemption' => $promoredemption, 
            'promocodes' => PromoCode::orderBy('code')->get(),
            'clients' => Client::orderBy('name')->get(),
            'bookings' => Booking::orderBy('id')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PromoRedemption $promoredemption)
    {
        $validated = request()->validate([
            'promo_code_id' => ['required','exists:promo_codes,id'],
            'client_id' => ['required','exists:clients,id'],
            'booking_id' => ['nullable','exists:bookings,id'],
            'discount_amount' => ['required','numeric','min:0'],
            'used_at' => ['required', 'date'],
        ]);

        $promoredemption->update($validated);

        return redirect()->route('promoredemptions.index')->with('success', __('messages.promoredemptions.messages.updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PromoRedemption $promoredemption)
    {
        $promoredemption->delete();
        return redirect()->route('promoredemptions.index')->with('success', __('messages.promoredemptions.messages.deleted'));
    }
}
