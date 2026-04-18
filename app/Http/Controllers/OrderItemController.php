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
    public function index(Order $order)
    {
        $orderitems = $order->order_item()->orderBy('service_id')->orderBy('qty')->get();
        return view('orderitems.index', compact('order', 'orderitems'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Order $order)
    {
        return view('orderitems.create', [
            'order'   => $order,
            'orderitem' => new OrderItem(),
            'services' => Service::orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Order $order)
    {
        $validated = $request->validate([
            'service_id' => ['required','exists:services,id'],
            'qty' => ['required','integer','min:1'],
            'price' => ['required','numeric','min:0'],
        ]);

        $subtotal = $this->calculateSubtotalPrice($request->qty, $request->price);

        $order->order_item()->create([
            'service_id' => $request->service_id,
            'qty' => $request->qty,
            'price' => $request->price,
            'subtotal' => $subtotal,
        ]);

        return redirect()->route('orderitems.index', $order)->with('success', 'Order item created successfull');
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
    public function edit(Order $order, OrderItem $orderitem)
    {
        return view('orderitems.edit', [
            'order'   => $order,
            'orderitem'   => $orderitem->load(['order','service']),
            'services' => Service::orderBy('name')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order, OrderItem $orderitem)
    {
        $validated = $request->validate([
            'service_id' => ['required','exists:services,id'],
            'qty' => ['required','integer','min:1'],
            'price' => ['required','numeric','min:0'],
        ]);

        $subtotal = $this->calculateSubtotalPrice($request->qty, $request->price);

        $orderitem->update([
            'service_id' => $request->service_id,
            'qty' => $request->qty,
            'price' => $request->price,
            'subtotal' => $subtotal,
        ]);

        return redirect()->route('orderitems.index', $order)->with('success', 'Order item updated successfull');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order, OrderItem $orderitem)
    {
        $orderitem->delete();
        return redirect()->route('orderitems.index', $order)->with('success', 'Order item deleted successfull');
    }

    private function calculateSubtotalPrice($qty, $price)
    {
        return round($qty * $price);
    }
}
