<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:client');
    }

    public function index()
    {
        $user = Auth::user();
        return view('client.users.index', compact('user'));
    }

    public function viewProfile($userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        return response()->json($user);
    }

    public function softDelete($id)
    {
        try {
            $user = User::findOrFail($id);

            if ($user->deleted_at) {
                return response()->json(['error' => 'User already deleted.'], 400);
            }

            $user->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete user. ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'profile_image' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'mobile_phone' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:255',
        ]);

        $user = User::findOrFail($id);

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->profile_image = $request->input('profile_image');
        $user->phone_number = $request->input('phone_number');
        $user->mobile_phone = $request->input('mobile_phone');
        $user->location = $request->input('location');

        $user->save();

        return response()->json(['success' => true, 'message' => 'User updated successfully']);
    }
}