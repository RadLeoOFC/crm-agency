<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Service;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = OrderItem::with(['order','service']);
        // Only show own documents if not admin/manager
        if (!Auth::user()->hasRole(['admin', 'manager'])) {
            $query->where('user_id', Auth::id());
        }
        $orderitems = $query->latest()->paginate(10);
        return view('orderitems.index', compact('orderitems'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('orderitems.create', [
            'orderitems'   => new OrderItem(),
            'orders'   => Order::orderBy('id')->get(),
            'services' => Service::orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => ['required','exists:orders,id'],
            'service_id' => ['required','exists:services,id'],
            'qty' => ['required','integer','min:1'],
            'price' => ['required','numeric','min:0'],
            'subtotal' => ['required','numeric','min:0'],
        ]);

        $orderitem = OrderItem::create($validated);

        return redirect()->route('orderitems.index')->with('success', 'Order item created successfull');
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
    public function edit(OrderItem $orderitem)
    {
        return view('orderitems.edit', [
            'orderitem'   => $orderitem->load(['order','service']),
            'orders'   => Order::orderBy('id')->get(),
            'services' => Service::orderBy('name')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OrderItem $orderitem)
    {
        $validated = $request->validate([
            'order_id' => ['required','exists:orders,id'],
            'service_id' => ['required','exists:services,id'],
            'qty' => ['required','integer','min:1'],
            'price' => ['required','numeric','min:0'],
            'subtotal' => ['required','numeric','min:0'],
        ]);

        $orderitem->update($validated);

        return redirect()->route('orderitems.index')->with('success', 'Order item updated successfull');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OrderItem $orderitem)
    {
        $orderitem->delete();
        return redirect()->route('orderitems.index')->with('success', 'Order item deleted successfull');
    }
}
