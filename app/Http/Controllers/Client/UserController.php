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
        // Get the currently authenticated user
        $user = Auth::user();

        // Return the view with the current user data (if only one user view is needed)
        return view('client.users.index', compact('user'));
    }

    // Fetch user details by ID
    public function viewProfile($userId)
    {
        // Find the user by ID
        $user = User::find($userId);

        // If user not found, return a 404 error response
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Return user details as a JSON response
        return response()->json($user);
    }

    public function softDelete($id)
    {
        try {
            $user = User::findOrFail($id);  // Find the user by ID
    
            if ($user->deleted_at) {
                return response()->json(['error' => 'User already deleted.'], 400);
            }
    
            $user->delete();  // Perform the soft delete
    
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete user. ' . $e->getMessage()], 500);
        }
    }
    

    // Store a newly created user in storage
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:users,name',
            'email' => 'required|email|max:255|unique:users,email',
            'user_role' => 'required|in:admin,client,technician',
        ]);

        // Create and save the user
        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->user_role = $request->input('user_role');
        $user->password = bcrypt($request->input('password'));
        $user->save();

        return redirect()->route('users.index')->with('success', 'User created successfully');
    }

    // Edit the user information
    public function edit($id)
    {
        $user = User::find($id);
        if (!$user) {
            abort(404, 'User not found');
        }
        return view('client.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        // Validate the request data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'user_role' => 'required|in:admin,client,technician',
            'password' => 'nullable|string|min:6', // Password is optional
        ]);
    
        // Find the user
        $user = User::findOrFail($id);
    
        // Update user data
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->user_role = $request->input('user_role');
    
        // Update password only if provided
        if ($request->has('password') && $request->input('password')) {
            $user->password = bcrypt($request->input('password'));
        }
    
        $user->save();
    
        // Return a JSON response
        return response()->json(['success' => true, 'message' => 'User updated successfully']);
    }
    
}