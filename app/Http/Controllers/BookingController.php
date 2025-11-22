<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking; 
use App\Models\Platform;
use App\Models\Client;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bookings = Booking::all();
        return view('bookings.index', compact('bookings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('bookings.create', [
            'booking' => new \App\Models\Booking(),
            'clients' => \App\Models\Client::all(),
            'platforms' => \App\Models\Platform::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'platform_id'=>'required|exists:platforms,id',
            'client_id'=>'required|exists:clients,id',
            'slot_id'=>'nullable|exists:slots,id',
            'starts_at'=>'nullable|date',
            'ends_at'=>'nullable|date|after:starts_at',
            'promo_code'=>'nullable|string',
            'notes'       => 'nullable|string',
        ]);

        $platform = Platform::findOrFail($request->platform_id);
        $client = Client::findOrFail($request->client_id);

        // determine time window: either slot times or provided times
        if ($request->slot_id) {
            $slot = Slot::lockForUpdate()->findOrFail($request->slot_id); // pessimistic lock
            if (!$slot->isAvailable()) {
                return back()->withErrors(['slot'=>'Slot no longer available']);
            }
            $start = $slot->starts_at;
            $end   = $slot->ends_at;
        } else {
            $start = Carbon::parse($request->starts_at, $platform->priceLists()->first()->timezone);
            $end   = Carbon::parse($request->ends_at, $platform->priceLists()->first()->timezone);
        }

        $pricing = app(\App\Services\PricingService::class)
            ->quote($platform, $start, $end, $request->promo_code, $client->id);

        DB::transaction(function() use($slot, $request, $client, $platform, $pricing) {
            $booking = Booking::create([
                'platform_id' => $platform->id,
                'client_id'   => $client->id,
                'slot_id'     => $slot->id ?? null,
                'starts_at'   => $start,
                'ends_at'     => $end,
                'price'       => $pricing['final_price'],
                'list_price'  => $pricing['list_price'],
                'discount_amount' => $pricing['discount'],
                'currency'    => $pricing['currency'],
                'promo_code_id'=> $pricing['promo_code_id'],
                'status'      => 'pending',
            ]);

            if (isset($slot)) {
                // update slot used_capacity atomically
                $slot->used_capacity++;
                if ($slot->used_capacity >= $slot->capacity) {
                    $slot->status = 'booked';
                }
                $slot->save();
            }
        });

        return redirect()->route('bookings.index')->with('success','Booking created');
    }


    /**
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Booking $booking)
    {
        return view('bookings.edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'platform_id' => 'required|exists:platforms,id',
            'client_id'   => 'required|exists:clients,id',
            'slot_id'     => 'nullable|exists:slots,id',
            'starts_at'   => 'nullable|date',
            'ends_at'     => 'nullable|date|after:starts_at',
            'promo_code'  => 'nullable|string',
            'status'      => 'required|in:pending,confirmed,cancelled,completed',
            'notes'       => 'nullable|string',
        ]);

        // Запрещаем менять подтвержденные или завершённые брони (по бизнес-правилу)
        if (in_array($booking->status, ['confirmed', 'completed'])) {
            return back()->withErrors(['status' => 'Confirmed/completed bookings cannot be edited.']);
        }

        $platform = Platform::findOrFail($request->platform_id);
        $client = Client::findOrFail($request->client_id);
        $oldSlot = $booking->slot; // текущий слот до изменений
        $slot = null;

        // определяем новые даты
        if ($request->slot_id) {
            $slot = Slot::lockForUpdate()->findOrFail($request->slot_id);
            if (!$slot->isAvailable() && $slot->id !== optional($oldSlot)->id) {
                return back()->withErrors(['slot' => 'New slot is not available']);
            }
            $start = $slot->starts_at;
            $end   = $slot->ends_at;
        } else {
            $start = Carbon::parse($request->starts_at, $platform->priceLists()->first()->timezone);
            $end   = Carbon::parse($request->ends_at, $platform->priceLists()->first()->timezone);
        }

        // пересчитать цену
        $pricing = app(\App\Services\PricingService::class)
            ->quote($platform, $start, $end, $request->promo_code, $client->id);

        DB::transaction(function() use ($booking, $slot, $oldSlot, $platform, $client, $pricing, $start, $end, $request) {
            // 1️⃣ Освободить старый слот (если был и если изменился)
            if ($oldSlot && (!$slot || $oldSlot->id !== $slot->id)) {
                $oldSlot->used_capacity = max(0, $oldSlot->used_capacity - 1);
                if ($oldSlot->used_capacity < $oldSlot->capacity) {
                    $oldSlot->status = 'available';
                }
                $oldSlot->save();
            }

            // 2️⃣ Обновить данные брони
            $booking->update([
                'platform_id'     => $platform->id,
                'client_id'       => $client->id,
                'slot_id'         => $slot->id ?? null,
                'starts_at'       => $start,
                'ends_at'         => $end,
                'list_price'      => $pricing['list_price'],
                'discount_amount' => $pricing['discount'],
                'price'           => $pricing['final_price'],
                'currency'        => $pricing['currency'],
                'promo_code_id'   => $pricing['promo_code_id'],
                'status'          => $request->input('status', $booking->status),
                'notes'           => $request->input('notes', $booking->notes),
            ]);

            // 3️⃣ Занять новый слот, если изменился
            if ($slot && (!$oldSlot || $slot->id !== $oldSlot->id)) {
                $slot->used_capacity++;
                if ($slot->used_capacity >= $slot->capacity) {
                    $slot->status = 'booked';
                }
                $slot->save();
            }
        });

        return redirect()->route('bookings.show', $booking)->with('success', 'Booking updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        $booking->delete();

        return redirect()
            ->route('bookings.index')
            ->with('success', 'Client deleted successfully.');
    }
}
