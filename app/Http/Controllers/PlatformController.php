<?php

namespace App\Http\Controllers;

use App\Models\Platform;
use App\Models\User;
use App\Notifications\PlatformCreationNotification;
use Illuminate\Http\Request;

class PlatformController extends Controller
{
    public function index(Request $request)
    {
        $platforms = Platform::latest('id')->paginate(15)->withQueryString();
        return view('platforms.index', compact('platforms'));
    }

    public function create()
    {
        $timezones = \DateTimeZone::listIdentifiers();
        return view('platforms.create', compact('timezones'));
    }

    public function store(Request $request)
    {
        // Set is_active to false if not present in request (unchecked checkbox)
        $request->merge(['is_active' => $request->has('is_active')]);

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'type'        => 'required|in:telegram,youtube,facebook,website',
            'description' => 'nullable|string|max:1000',
            'currency'    => 'required|string|in:' . implode(',', array_keys(Platform::$currencies)),
            'timezone'    => 'required|string|in:' . implode(',', \DateTimeZone::listIdentifiers()),
            'is_active'   => 'boolean',
        ]);

        $platform = Platform::create($validated);

        $creator = auth()->user(); // текущий залогиненный
        foreach (User::role('admin')->get() as $admin) {
            $admin->notify(new PlatformCreationNotification($creator));
        }
        foreach (User::role('manager')->get() as $manager) {
            \Log::info('Send telegram to', [
                'id' => $manager->id,
                'chat_id' => $manager->telegram_chat_id,
            ]);
            $manager->notify(new PlatformCreationNotification($creator));
        }

        return redirect()->route('platforms.index')->with('success', __('messages.platforms.messages.created'));
    }

    public function show(Platform $platform)
    {
        $platform->loadCount(['priceLists','slots','bookings']);
        return view('platforms.show', compact('platform'));
    }

    public function edit(Platform $platform)
    {
        $timezones = \DateTimeZone::listIdentifiers();
        return view('platforms.edit', compact('platform','timezones'));
    }

    public function update(Request $request, Platform $platform)
    {
        // Set is_active to false if not present in request (unchecked checkbox)
        $request->merge(['is_active' => $request->has('is_active')]);

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'type'        => 'required|in:telegram,youtube,facebook,website',
            'description' => 'nullable|string|max:1000',
            'currency'    => 'required|string|in:' . implode(',', array_keys(Platform::$currencies)),
            'timezone'    => 'required|string|in:' . implode(',', \DateTimeZone::listIdentifiers()),
            'is_active'   => 'boolean',
        ]);

        $platform->update($validated);

        return redirect()->route('platforms.index')->with('success', __('messages.platforms.messages.updated'));
    }

    public function destroy(Platform $platform)
    {
        $platform->delete();

        return redirect()->route('platforms.index')->with('success', __('messages.platforms.messages.deleted'));
    }
}
