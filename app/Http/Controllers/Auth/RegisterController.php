<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    // Show client registration form
    public function showClientRegistrationForm()
    {
        return view('auth.client.register');
    }

    // Show technician registration form
    public function showTechnicianRegistrationForm()
    {
        return view('auth.technician.register');
    }

    // Handle registration
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'user_role' => $data['user_role'],
        ]);
    }
}