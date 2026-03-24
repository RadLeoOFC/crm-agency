<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = Service::latest('id')->paginate(15)->withQueryString();
        return view('services.index', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('services.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required','string','max:64'],
            'name' => ['required','in:SEO,SMM,PPC,Dev'],
            'description' => ['required','string','max:1000'],
            'base_price' => ['required','numeric','min:0'],
            'currency'    => ['required','string','in:' . implode(',', array_keys(Service::$currencies))],
            'is_active' => 'boolean',
        ]);

        $service = Service::create($validated);

        return redirect()->route('services.index')->with('success', 'Service created successfull');
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
    public function edit(Service $service)
    {
        return view('services.edit', compact('service'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'code' => ['required','string','max:64'],
            'name' => ['required','string','max:64'],
            'description' => ['required','string','max:1000'],
            'base_price' => ['required','numeric','min:0'],
            'currency'    => 'required|string|in:' . implode(',', array_keys(Service::$currencies)),
            'is_active' => 'boolean',
        ]);

        $service->update($validated);

        return redirect()->route('services.index')->with('success', 'Service updated successfull');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        $service->delete();
        return redirect()->route('services.index')->with('success', 'Service deleted successfull');
    }
}
