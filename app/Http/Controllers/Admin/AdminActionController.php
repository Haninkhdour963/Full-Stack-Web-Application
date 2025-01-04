<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAction;
use App\Models\User;
use Illuminate\Http\Request;

class AdminActionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    // Index - List all admin actions
    public function index()
    {
        $adminActions = AdminAction::with(['admin', 'targetUser'])->paginate(10);
        $users = User::all();
        return view('admin.adminActions.index', compact('adminActions', 'users'));
    }

    // Show - Display a specific admin action
    public function show($id)
    {
        $adminAction = AdminAction::with(['admin', 'targetUser'])->findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $adminAction
        ]);
    }

    // Store - Save a new admin action
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'admin_id' => 'required|exists:admins,id',
            'action_type' => 'required|in:ban_user,approve_profile,resolve_dispute',
            'description' => 'required|string',
            'target_user_id' => 'required|exists:users,id',
        ]);

        $adminAction = AdminAction::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Admin action created successfully.',
            'data' => $adminAction
        ]);
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'action_type' => 'required|string',
            'description' => 'required|string',
            'target_user_id' => 'required|exists:users,id',
        ]);
    
        $adminAction = AdminAction::findOrFail($id);
        $adminAction->update([
            'action_type' => $request->input('action_type'),
            'description' => $request->input('description'),
            'target_user_id' => $request->input('target_user_id'),
        ]);
    
        return response()->json([
            'success' => true,
            'message' => 'Admin action updated successfully.',
        ]);
    }
    // Soft Delete - Soft delete an admin action
    public function softDelete($id)
    {
        $adminAction = AdminAction::findOrFail($id);
        $adminAction->delete();

        return response()->json([
            'success' => true,
            'message' => 'Admin action soft deleted successfully.'
        ]);
    }

    // Restore - Restore a soft-deleted admin action
    public function restore($id)
    {
        $adminAction = AdminAction::withTrashed()->findOrFail($id);
        $adminAction->restore();

        return response()->json([
            'success' => true,
            'message' => 'Admin action restored successfully.'
        ]);
    }
}