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
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z]/'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users','regex:/^[A-Za-z]/'],
            'mobile_phone' => ['required', 'string', 'digits:10'],
            'phone_number' => ['nullable', 'string', 'digits:10'],
            'user_role' => ['required', 'in:admin,client,technician'],
            'profile_image' => ['required', 'image', 'max:2048'],
            'password' => [
                'required', 
                'confirmed', 
                Rules\Password::defaults(),
                'regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'
            ],
            'identity_number' => ['required_if:user_role,technician', 'string', 'max:255','regex:/^[A-Za-z]/'],
            'skills' => ['required_if:user_role,technician', 'string'],
            'hourly_rate' => ['required_if:user_role,technician', 'numeric', 'min:20'],
            'bio' => ['required_if:user_role,technician', 'string'],
            'location' => ['required_if:user_role,technician', 'string'],
            'available_from' => ['required_if:user_role,technician', 'date'],
        ], [
            'name.regex' => 'The name must start with a letter.',
            'mobile_phone.digits' => 'The mobile phone must be exactly 10 digits.',
            'phone_number.digits' => 'The phone number must be exactly 10 digits.',
            'password.regex' => 'The password must contain at least one uppercase letter, one number, and one special character.',
        ]);

        $profileImagePath = null;
        if ($request->hasFile('profile_image')) {
            $profileImagePath = $request->file('profile_image')->store('profile_images', 'public');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'mobile_phone' => $request->mobile_phone,
            'phone_number' => $request->phone_number,
            'user_role' => $request->user_role,
            'profile_image' => $profileImagePath,
            'password' => Hash::make($request->password),
        ]);

        if ($request->user_role === 'technician') {
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

        event(new Registered($user));
        Auth::login($user);

        return redirect()->route('index');
    }
}
