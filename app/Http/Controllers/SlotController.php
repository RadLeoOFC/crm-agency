<?php

namespace App\Http\Controllers;

use App\Models\Slot;
use App\Models\Platform;
use App\Models\PriceList;
use Illuminate\Http\Request;

class SlotController extends Controller
{
    public function index(Request $request)
    {
        $query = Slot::with(['platform','priceList'])->orderBy('starts_at','desc');

        if ($request->filled('platform_id')) {
            $query->where('platform_id', $request->platform_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $slots = $query->paginate(20)->withQueryString();
        $platforms = Platform::orderBy('name')->get();

        return view('slots.index', compact('slots','platforms'));
    }

    public function edit(Slot $slot)
    {
        $platforms = Platform::orderBy('name')->get();
        $pricelists = PriceList::orderBy('id','desc')->get();

        return view('slots.edit', compact('slot','platforms','pricelists'));
    }

    public function update(Request $request, Slot $slot)
    {
        $validated = $request->validate([
            'platform_id'   => ['required','exists:platforms,id'],
            'price_list_id' => ['nullable','exists:price_lists,id'],
            'starts_at'     => ['required','date'],
            'ends_at'       => ['required','date','after:starts_at'],
            'price'         => ['required','numeric','min:0'],
            'status'        => ['required','in:available,reserved,booked,cancelled'],
            'capacity'      => ['required','integer','min:0'],
            'used_capacity' => ['required','integer','min:0','lte:capacity'],
        ]);
        $slot->update($validated);

        return redirect()->route('slots.index')->with('success','Слот обновлён.');
    }

    public function destroy(Slot $slot)
    {
        $slot->delete();
        return redirect()->route('slots.index')->with('success','Слот удалён.');
    }
}
