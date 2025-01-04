<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch admins with pagination (8 admins per page)
        $admins = Admin::withTrashed()->paginate(8);
        return view('admin.admins.index', compact('admins'));
    }

    /**
     * Soft delete an admin.
     */
    public function softDelete($id)
    {
        try {
            $admin = Admin::findOrFail($id);

            if ($admin->deleted_at) {
                return response()->json(['error' => 'Admin already deleted.'], 400);
            }

            $admin->delete(); // Soft delete
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete admin. ' . $e->getMessage()], 500);
        }
    }

    /**
     * Restore a soft-deleted admin.
     */
    public function restore($id)
    {
        try {
            $admin = Admin::withTrashed()->findOrFail($id);

            if (!$admin->deleted_at) {
                return response()->json(['error' => 'Admin is not deleted.'], 400);
            }

            $admin->restore(); // Restore
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to restore admin. ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified admin.
     */
    public function show($id)
    {
        try {
            $admin = Admin::withTrashed()->findOrFail($id);
            return response()->json(['success' => true, 'admin' => $admin]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch admin details. ' . $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}