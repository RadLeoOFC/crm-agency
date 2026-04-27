<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Order::with('manager');
        // Only show own orders if not admin/manager
        if (!Auth::user()->hasRole(['admin', 'manager'])) {
            $query->where('user_id', Auth::id());
        }
        $orders = $query->latest()->paginate(10);
        return view('orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('orders.create', [
            'orders'   => new Order(),
            'clients'   => Client::orderBy('name')->get(),
            'users' => User::orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => ['required','exists:clients,id'],
            'manager_id' => ['required','exists:users,id'],
            'status' => ['required','in:new,in_progress,completed,cancelled'],
        ]);

        $order = Order::create($validated);

        return redirect()->route('orders.index')->with('success', 'Order created successfull');
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
    public function edit(Order $order)
    {
        return view('orders.edit',[
            'order'   => $order->load(['client','manager']),
            'clients'   => Client::orderBy('name')->get(),
            'users' => User::orderBy('name')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'client_id' => ['required','exists:clients,id'],
            'manager_id' => ['required','exists:users,id'],
            'status' => ['required','in:new,in_progress,completed,cancelled'],
        ]);

        $order->update($validated);

        return redirect()->route('orders.index')->with('success', 'Order updated successfull');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Order deleted successfull');
    }
}
