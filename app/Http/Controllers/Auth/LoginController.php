<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    // Show admin login form
    public function showAdminLoginForm()
    {
        return view('auth.admin.login');
    }

    // Handle admin login
    public function adminLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }else{
                Auth::logout();
            }
        }

        return back()->withErrors(['email' => 'Invalid credentials or unauthorized access.']);
    }

    // Handle authenticated user redirection
    protected function authenticated(Request $request, $user)
    {
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isClient()) {
            return redirect()->route('client.dashboard');
        } elseif ($user->isTechnician()) {
            return redirect()->route('technician.dashboard');
        }

        return redirect('/dashboard');
    }
}