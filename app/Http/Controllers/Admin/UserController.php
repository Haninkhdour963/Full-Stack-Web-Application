<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index()
    {
        $users = User::withTrashed()->paginate(10); // Paginate with 10 users per page
        return view('admin.users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:users,name',
            'email' => 'required|email|max:255|unique:users,email',
            'user_role' => 'required|in:admin,client,technician',
        ]);
    
        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->user_role = $request->input('user_role');
        $user->password = bcrypt($request->input('password'));
    
        $user->save();
    
        return redirect()->route('admin.users.index')->with('success', 'User created successfully');
    }
    
    public function show($id)
    {
        $user = User::withTrashed()->findOrFail($id); // Get the user by ID, including soft deleted
    
        // Return user data as JSON, including the profile image URL
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'user_role' => ucfirst($user->user_role),
            'location' => $user->location,
            'phone_number' => $user->phone_number,
            'mobile_phone' => $user->mobile_phone,
            'profile_image' => asset('storage/' . $user->profile_image),  // Assuming images are stored in the 'storage' folder
            'deleted_at' => $user->deleted_at,
        ]);
    }
    
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'profile_image' => asset('storage/' . $user->profile_image),
        ]);

        $user = User::findOrFail($id);
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->user_role = $request->input('user_role');
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully');
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

    public function restore($id)
    {
        try {
            $user = User::withTrashed()->findOrFail($id);

            if (!$user->deleted_at) {
                return response()->json(['error' => 'User is not deleted.'], 400);
            }

            $user->restore();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to restore user. ' . $e->getMessage()], 500);
        }
    }
}