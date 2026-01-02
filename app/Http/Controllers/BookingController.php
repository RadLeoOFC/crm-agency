<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\BookingCreationNotification;
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
        $validated = $request->validate([
            'platform_id' => ['required','exists:platforms,id'],
            'client_id'   => ['required','exists:clients,id'],
            'slot_id'     => ['nullable','exists:slots,id'],
            'starts_at'   => ['nullable','date'],
            'ends_at'     => ['nullable','date','after:starts_at'],
            'promo_code'  => ['nullable','string','max:64'],
            'notes'       => ['nullable','string'],
            'status'      => ['nullable','in:pending,confirmed,cancelled,completed'],
        ]);

        try {
            return DB::transaction(function () use ($validated, $pricingService) {

                $platform = Platform::findOrFail($validated['platform_id']);
                $client   = Client::findOrFail($validated['client_id']);

                // 🔹 активный прайс-лист
                $priceList = $platform->priceLists()
                    ->where('is_active', true)
                    ->first();

                if (!$priceList) {
                    throw new \RuntimeException(__('messages.bookings.messages.errors.no_active_pricelist'));
                }

                $slot  = null;
                $start = null;
                $end   = null;

                // 🔹 если выбран слот — блокируем его
                if (!empty($validated['slot_id'])) {

                    $slot = Slot::whereKey($validated['slot_id'])
                        ->lockForUpdate()
                        ->firstOrFail();

                    if (!$slot->isAvailable()) {
                        throw new \RuntimeException(__('messages.bookings.messages.errors.slot_unavailable'));
                    }

                    $start = $slot->starts_at;
                    $end   = $slot->ends_at;

                } else {
                    // 🔹 бронирование по времени
                    if (empty($validated['starts_at']) || empty($validated['ends_at'])) {
                        throw new \RuntimeException(__('messages.bookings.messages.errors.start_end_required'));
                    }

                    $tz    = $priceList->timezone;
                    $start = Carbon::parse($validated['starts_at'], $tz);
                    $end   = Carbon::parse($validated['ends_at'], $tz);
                }

                // 🔹 расчёт цены (ВСЕГДА через PriceList)
                $quote = $pricingService->quote(
                    $priceList,
                    $start,
                    $end,
                    $validated['promo_code'] ?? null,
                    $client->id
                );

                // 🔹 создаём бронирование
                $booking = Booking::create([
                    'platform_id'     => $platform->id,
                    'client_id'       => $client->id,
                    'slot_id'         => $slot?->id,
                    'starts_at'       => $start,
                    'ends_at'         => $end,
                    'status'          => $validated['status'] ?? 'pending',
                    'notes'           => $validated['notes'] ?? null,

                    'price'           => $quote['final_price'],
                    'list_price'      => $quote['list_price'],
                    'discount_amount' => $quote['discount'],
                    'currency'        => $quote['currency'],
                    'promo_code_id'   => $quote['promo_code_id'],
                ]);

                // 🔹 обновляем слот
                if ($slot) {
                    $slot->increment('used_capacity');

                    if ($slot->used_capacity >= $slot->capacity) {
                        $slot->status = 'booked';
                        $slot->save();
                    }
                }

                $creator = auth()->user(); // текущий залогиненный
                foreach (User::role('admin')->get() as $admin) {
                    $admin->notify(new BookingCreationNotification($creator));
                }
                foreach (User::role('manager')->get() as $manager) {
                    $manager->notify(new BookingCreationNotification($creator));
                }
                foreach (User::role('client')->get() as $client) {
                    $client->notify(new BookingCreationNotification($creator));
                }
                foreach (User::role('partner')->get() as $partner) {
                    $partner->notify(new BookingCreationNotification($creator));
                }

                return redirect()
                    ->route('bookings.index')
                    ->with('success', __('messages.bookings.messages.created'));
            });

        } catch (\Throwable $e) {

            report($e);

            return back()
                ->withInput()
                ->withErrors([
                    'booking' => $e->getMessage()
                ]);
        }
    }

    public function show(Booking $booking)
    {
        $booking->load(['client','platform','slot']);
        $bookings = Booking::with('platform')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();
        return view('bookings.show', compact('booking', 'bookings'));
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
            return back()->withErrors(['status'=> __('messages.bookings.messages.cannot_edit_confirmed')]);
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
            $platform  = Platform::findOrFail($request->platform_id);
            $client    = Client::findOrFail($request->client_id);
            $priceList = $platform->priceLists()->where('is_active', true)->first();

            if (!$priceList) {
                return back()
                    ->withErrors(['platform_id' => __('messages.bookings.messages.errors.no_active_pricelist')])
                    ->withInput();
            }
            $tz    = $priceList->timezone;
            $start = Carbon::parse($request->starts_at, $tz);
            $end   = Carbon::parse($request->ends_at, $tz);
        }

        $quote = $pricingService->quote($priceList, $start, $end, $request->promo_code, $client->id);

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
                    throw new \RuntimeException(__('messages.bookings.messages.errors.new_slot_unavailable'));
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

        return redirect()->route('bookings.index')->with('success', __('messages.bookings.messages.updated'));
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();
        return redirect()->route('bookings.index')->with('success', __('messages.bookings.messages.deleted'));
    }
}
