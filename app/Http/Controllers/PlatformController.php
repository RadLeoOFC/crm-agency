<?php

namespace App\Http\Controllers;

use App\Models\Platform;
use App\Models\User;
use App\Notifications\PlatformCreationNotification;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PlatformController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $platforms = Platform::all();
        return view('platforms.index', compact('platforms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('platforms.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:telegram,youtube,facebook,website',
            'description' => 'required|string|max:255',
            'base_price' => 'required|numeric|min:0|max:999999.99',
        ]);

        $platform = Platform::create($validated);

        $creator = auth()->user(); // текущий залогиненный
        foreach (User::role('admin')->get() as $admin) {
            $admin->notify(new PlatformCreationNotification($creator));
        }
        foreach (User::role('manager')->get() as $manager) {
            $manager->notify(new PlatformCreationNotification($creator));
        }

        return redirect()
            ->route('platforms.index')
            ->with('success', 'Platform registered successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Platform $platform)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Platform $platform)
    {
        return view('platforms.edit', compact('platform'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Platform $platform)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:telegram,youtube,facebook,website',
            'description' => 'required|string|max:255',
            'base_price' => 'required|numeric|min:0|max:999999.99',
        ]);

        $platform->update($validated);

        return redirect()
            ->route('platforms.index')
            ->with('success', 'Platform updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Platform $platform)
    {
        $platform->delete();

        return redirect()
            ->route('platforms.index')
            ->with('success', 'Platform deleted successfully.');
    }
}
