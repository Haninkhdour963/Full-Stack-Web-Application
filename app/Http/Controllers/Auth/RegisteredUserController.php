<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Technician;
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
     * Handle an incoming registration request for a technician.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
{
    
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
        'mobile_phone' => ['required', 'string', 'max:20'],
        'phone_number' => ['nullable', 'string', 'max:20'],
        'user_role' => ['required', 'in:admin,client,technician'],
        'profile_image' => ['nullable', 'image', 'max:2048'],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
        'identity_number' => ['required_if:user_role,technician', 'string', 'max:255'],
        'skills' => ['required_if:user_role,technician', 'string'],
        'hourly_rate' => ['required_if:user_role,technician', 'numeric', 'min:0'],
        'bio' => ['required_if:user_role,technician', 'string'],
        'location' => ['required_if:user_role,technician', 'string'],
        'available_from' => ['required_if:user_role,technician', 'date'],
    ]);

    // Handle profile image upload
    $profileImagePath = null;
    if ($request->hasFile('profile_image')) {
        $profileImagePath = $request->file('profile_image')->store('profile_images', 'public');
    }

    // Create the user
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'mobile_phone' => $request->mobile_phone,
        'phone_number' => $request->phone_number,
        'user_role' => $request->user_role,
        'profile_image' => $profileImagePath,
        'password' => Hash::make($request->password),
    ]);

   // If the user is a technician, create a technician record
if ($request->user_role === 'technician') {
    // Create the technician record with explicit user_id
    $technician = new Technician([
        'user_id' => $user->id,
        'identity_number' => $request->identity_number,
        'skills' => $request->skills,
        'hourly_rate' => $request->hourly_rate,
        'bio' => $request->bio,
        'location' => $request->location,
        'available_from' => $request->available_from,
    ]);
    
    $user->technician()->save($technician);
}

    

    // Trigger the Registered event and log in the user
    event(new Registered($user));
    Auth::login($user);

    // Redirect to the desired route
    return redirect()->route('index');
}
}