<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','string','lowercase','email','max:255','unique:'.User::class],
            'country_code' => ['required','string','in:+380,+359,+7,+49,+1'],
            'phone' => ['required','string','max:32', 'unique:users,phone'],
            'password' => ['required','confirmed', Rules\Password::defaults()],
        ]);

        $digits = preg_replace('/\D+/', '', (string)$request->phone); // только цифры
        $code = (string)$request->country_code; // "+380" etc.
        $e164 = $code . $digits;                // "+380672811633"

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $e164,
            'password' => Hash::make($request->password),
            'locale' => session('locale', config('app.locale')),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
