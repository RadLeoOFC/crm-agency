<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\{Booking, Platform, Client, Slot};
use App\Services\PricingService;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['client','platform','slot'])->latest('id')->paginate(20);
        return view('bookings.index', compact('bookings'));
    }

    public function create()
    {
        return view('bookings.create', [
            'booking'   => new Booking(),
            'clients'   => Client::orderBy('name')->get(),
            'platforms' => Platform::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request, PricingService $pricingService)
    {
        $request->validate([
            'platform_id' => 'required|exists:platforms,id',
            'client_id'   => 'required|exists:clients,id',
            'slot_id'     => 'nullable|exists:slots,id',
            'starts_at'   => 'nullable|date',
            'ends_at'     => 'nullable|date|after:starts_at',
            'promo_code'  => 'nullable|string|max:64',
            'notes'       => 'nullable|string',
        ]);

        $platform = Platform::findOrFail($request->platform_id);
        $client   = Client::findOrFail($request->client_id);

        // определить интервал
        $slot  = null;
        $start = null;
        $end   = null;

        if ($request->slot_id) {
            // блокировка слота внутри транзакции
            $slot = Slot::findOrFail($request->slot_id);
            $start = $slot->starts_at;
            $end   = $slot->ends_at;
        } else {
            $pl = $platform->priceLists()->where('is_active', true)->first();
            if (!$pl) {
                return back()->withErrors(['platform_id'=>'Для площадки нет активного прайс-листа'])->withInput();
            }
            $tz    = $pl->timezone;
            $start = Carbon::parse($request->starts_at, $tz);
            $end   = Carbon::parse($request->ends_at, $tz);
        }

        $quote = $pricingService->quote($platform, $start, $end, $request->promo_code, $client->id);

        DB::transaction(function() use ($slot, $request, $client, $platform, $quote, $start, $end) {
            if ($slot) {
                // повторно загрузим слот с блокировкой
                $slot = Slot::whereKey($slot->id)->lockForUpdate()->first();
                if (!$slot->isAvailable()) {
                    throw new \RuntimeException('Slot no longer available');
                }
            }

            $booking = Booking::create([
                'platform_id'     => $platform->id,
                'client_id'       => $client->id,
                'slot_id'         => $slot->id ?? null,
                'starts_at'       => $start,
                'ends_at'         => $end,
                'status'          => 'pending',
                'notes'           => $request->input('notes'),
                // цены
                'price'           => $quote['final_price'],
                'list_price'      => $quote['list_price'],
                'discount_amount' => $quote['discount'],
                'currency'        => $quote['currency'],
                'promo_code_id'   => $quote['promo_code_id'],
            ]);

            if ($slot) {
                $slot->used_capacity++;
                if ($slot->used_capacity >= $slot->capacity) {
                    $slot->status = 'booked';
                }
                $slot->save();
            }
        });

        return redirect()->route('bookings.index')->with('success','Booking created');
    }

    public function edit(Booking $booking)
    {
        return view('bookings.edit', [
            'booking'   => $booking->load(['client','platform','slot']),
            'clients'   => Client::orderBy('name')->get(),
            'platforms' => Platform::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Booking $booking, PricingService $pricingService)
    {
        $request->validate([
            'platform_id' => 'required|exists:platforms,id',
            'client_id'   => 'required|exists:clients,id',
            'slot_id'     => 'nullable|exists:slots,id',
            'starts_at'   => 'nullable|date',
            'ends_at'     => 'nullable|date|after:starts_at',
            'promo_code'  => 'nullable|string|max:64',
            'status'      => 'required|in:pending,confirmed,cancelled,completed',
            'notes'       => 'nullable|string',
        ]);

        if (in_array($booking->status, ['confirmed','completed'])) {
            return back()->withErrors(['status'=>'Confirmed/completed bookings cannot be edited.']);
        }

        $platform = Platform::findOrFail($request->platform_id);
        $client   = Client::findOrFail($request->client_id);
        $oldSlot  = $booking->slot;
        $slot     = null;
        $start    = null;
        $end      = null;

        if ($request->slot_id) {
            $slot  = Slot::findOrFail($request->slot_id);
            $start = $slot->starts_at;
            $end   = $slot->ends_at;
        } else {
            $pl = $platform->priceLists()->where('is_active', true)->first();
            if (!$pl) {
                return back()->withErrors(['platform_id'=>'Для площадки нет активного прайс-листа'])->withInput();
            }
            $tz    = $pl->timezone;
            $start = Carbon::parse($request->starts_at, $tz);
            $end   = Carbon::parse($request->ends_at, $tz);
        }

        $quote = $pricingService->quote($platform, $start, $end, $request->promo_code, $client->id);

        DB::transaction(function() use ($booking, $slot, $oldSlot, $platform, $client, $quote, $start, $end, $request) {

            if ($oldSlot && (!$slot || $oldSlot->id !== optional($slot)->id)) {
                $oldSlot = Slot::whereKey($oldSlot->id)->lockForUpdate()->first();
                $oldSlot->used_capacity = max(0, $oldSlot->used_capacity - 1);
                if ($oldSlot->used_capacity < $oldSlot->capacity) {
                    $oldSlot->status = 'available';
                }
                $oldSlot->save();
            }

            if ($slot) {
                $slot = Slot::whereKey($slot->id)->lockForUpdate()->first();
                if (!$slot->isAvailable() && $slot->id !== optional($oldSlot)->id) {
                    throw new \RuntimeException('New slot is not available');
                }
            }

            $booking->update([
                'platform_id'     => $platform->id,
                'client_id'       => $client->id,
                'slot_id'         => $slot->id ?? null,
                'starts_at'       => $start,
                'ends_at'         => $end,
                'status'          => $request->input('status', $booking->status),
                'notes'           => $request->input('notes', $booking->notes),
                'price'           => $quote['final_price'],
                'list_price'      => $quote['list_price'],
                'discount_amount' => $quote['discount'],
                'currency'        => $quote['currency'],
                'promo_code_id'   => $quote['promo_code_id'],
            ]);

            if ($slot && (!$oldSlot || $slot->id !== $oldSlot->id)) {
                $slot->used_capacity++;
                if ($slot->used_capacity >= $slot->capacity) {
                    $slot->status = 'booked';
                }
                $slot->save();
            }
        });

        return redirect()->route('bookings.index')->with('success','Booking updated successfully.');
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();
        return redirect()->route('bookings.index')->with('success','Booking deleted successfully.');
    }
}
