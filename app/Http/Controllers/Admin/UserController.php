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
        
        $users = User::withTrashed()->get(); // Get all users, including soft deleted
        return view('admin.users.index', compact('users'));
    }

    public function store(Request $request)
    {
        // Validate the input data
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:users,name',
            'email' => 'required|email|max:255|unique:users,email',
            'user_role' => 'required|in:admin,client,technician',
        ]);
    
        // Create a new user with validated data
        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->user_role = $request->input('user_role');
        $user->password = bcrypt($request->input('password')); // or use a default password logic
    
        // Save the new user to the database
        $user->save();
    
        // Redirect to the users list page or wherever you want
        return redirect()->route('admin.users.index')->with('success', 'User created successfully');
    }
    

    public function edit(string $id)
    {
        $user = User::find($id);
        if (!$user) {
            dd('User not found'); // Debugging line to check if the user is found
        }
        return view('admin.users.edit', compact('user'));
    }
    
    
  // Update method
  public function update(Request $request, $id)
  {
      $validated = $request->validate([
          'name' => 'required|string|max:255',
          'email' => 'required|email|max:255|unique:users,email,' . $id,
          'user_role' => 'required|in:admin,client,technician',
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
}
