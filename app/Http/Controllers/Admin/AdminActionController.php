<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAction;
use App\Models\User; // Add this line to import the User model
use Illuminate\Http\Request;

class AdminActionController extends Controller
{
    // Index route to show list of actions
    public function index()
    {
        // Get all admin actions, including soft-deleted ones
        $adminActions = AdminAction::withTrashed()->get();

        // Get all users to pass to the view
        $users = User::all();  // Fetch all users

        // Return the view with admin actions and users
        return view('admin.adminActions.index', compact('adminActions', 'users'));
    }

    // Store route for creating/updating an admin action
    public function store(Request $request, $id = null)
    {
        // Validate the incoming request data
        $request->validate([
            'action_type' => 'required|in:ban_user,approve_profile,resolve_dispute',
            'description' => 'nullable|string|max:1000',
            'target_user_id' => 'required|exists:users,id',
        ]);

        // If ID is provided, update the existing action, otherwise create a new one
        $adminAction = $id ? AdminAction::find($id) : new AdminAction;

        if (!$adminAction && $id) {
            // Return a 404 error if the admin action does not exist
            return response()->json(['error' => 'Admin action not found'], 404);
        }

        // Update or create the action
        $adminAction->action_type = $request->action_type;
        $adminAction->description = $request->description;
        $adminAction->target_user_id = $request->target_user_id;
        $adminAction->save();

        return response()->json(['success' => true, 'adminAction' => $adminAction]);
    }

    // Soft delete route
    public function softDelete($id)
    {
        // Fetch the admin action using its ID
        $adminAction = AdminAction::findOrFail($id);
        
        // Soft delete the admin action
        $adminAction->delete();

        return response()->json(['success' => true]);
    }
}
