<?php

namespace App\Http\Controllers;

use App\Models\{Invoice, Booking, Order, User};
use App\Models\Client;
use App\Services\InvoiceService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class InvoicesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Invoice $invoice, Client $client)
    {
        $query = Invoice::with('booking', 'order', 'client');
        // Only show own invoices if not admin/manager
        if (!Auth::user()->hasRole(['admin', 'manager', 'accountant'])) {
            $query->whereHas('client', function ($q) {
                $q->where('user_id', Auth::id());
            });
            $query->whereIn('status', ['sent', 'partially_paid', 'paid', 'void']);
        }
        $invoices = $query->latest()->paginate(10);
        return view('invoices.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('invoices.create', [
            'invoice'   => new Invoice(),
            'clients'   => Client::orderBy('name')->get(),
            'orders' => Order::orderBy('id')->get(),
            'bookings' => Booking::orderBy('platform_id')->get(),
            'created_by' => User::orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, InvoiceService $invoiceService, Invoice $invoice)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'order_id' => 'required|exists:orders,id',
            'booking_id' => 'required|exists:bookings,id',
            'currency' => 'required|string|in:' . implode(',', array_keys(Invoice::$currencies)),
            'amount_due' => 'required|numeric|min:0',
            'due_at' => 'nullable|date',
            'purpose' => 'required|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        $service = app(\App\Services\InvoiceService::class);
        $token = $service->TokenCreation($invoice);

        $invoice->create([
            'client_id' => $request->client_id,
            'order_id' => $request->order_id,
            'booking_id' => $request->booking_id,
            'created_by' => Auth::id(),
            'currency' => $request->currency,
            'amount_due' => $request->amount_due,
            'status' => 'draft',
            'due_at' => $request->due_at,
            'public_token' => $token,
            'purpose' => $request->purpose,
            'notes' => $request->notes,
        ]);

        return redirect()
            ->route('invoices.index')
            ->with('success', __('messages.clients.messages.created'));
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
    public function edit(Invoice $invoice)
    {
        return view('invoices.edit', [
            'invoice'   => $invoice,
            'clients'   => Client::orderBy('name')->get(),
            'orders' => Order::orderBy('id')->get(),
            'bookings' => Booking::orderBy('platform_id')->get(),
            'created_by' => User::orderBy('name')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice, InvoiceService $invoiceService)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'order_id' => 'required|exists:orders,id',
            'booking_id' => 'required|exists:bookings,id',
            'currency' => 'required|string|in:' . implode(',', array_keys(Invoice::$currencies)),
            'amount_due' => 'required|numeric|min:0',
            'due_at' => 'nullable|date',
            'purpose' => 'required|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($invoice->status == 'draft') {
            $invoice->update($validated);
        } else if ($invoice->status == 'issued') {
            $invoice->update([
                'currency' => $request->currency,
                'amount_due' => $request->amount_due,
                'due_at' => $request->due_at,
                'purpose' => $request->purpose,
                'notes' => $request->notes,
            ]);
        } else {
            echo ('Error');
        }


        return redirect()->route('invoices.index')->with('success', 'Invoice updated successfull');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfull');
    }

    public function publish(Invoice $invoice)
    {
        $invoice->publish();
        return redirect()->route('invoices.index')->with('success', 'Invoice issued successfull');
    }

    public function send(Invoice $invoice) 
    {
        $invoice->send();
        return redirect()->route('invoices.index')->with('success', 'Invoice successfull sent to client');
    }

    public function paymentPage() 
    {
        return view('invoices.paymentPage', [
            'invoice'   => $invoice,
            'clients'   => Client::orderBy('name')->get(),
            'orders' => Order::orderBy('id')->get(),
            'booking' => Booking::orderBy('platform_id')->get(),
        ]);
    }
}
