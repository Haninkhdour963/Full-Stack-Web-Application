<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'mobile_phone' => ['required', 'max:10'],
            'phone_number' => ['nullable', 'max:15'], // Make phone_number optional
            'user_role' => ['required', 'in:admin,client,technician'], // Validating user_role field
            'profile_image' => ['nullable', 'image', 'max:2048'], // Validating profile_image (optional)
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $userData = $request->only(['name', 'email', 'mobile_phone', 'phone_number', 'user_role', 'profile_image']);

        // Handle profile image upload if provided
        if ($request->hasFile('profile_image')) {
            $userData['profile_image'] = $request->file('profile_image')->store('profile_images', 'public');
        }

        // Create new user
        $user = User::create([
            'name' => $userData['name'],
            'email' => $userData['email'],
            'mobile_phone' => $userData['mobile_phone'],
            'phone_number' => $userData['phone_number'] ?? null,
            'user_role' => $userData['user_role'],
            'profile_image' => $userData['profile_image'] ?? null,
            'password' => Hash::make($request->password),
        ]);

        // Fire Registered event
        event(new Registered($user));

        // Log in the newly created user
        Auth::login($user);

        // Redirect to home page
        return redirect(RouteServiceProvider::HOME);
    }
}
