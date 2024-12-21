<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dispute;
use Illuminate\Http\Request;

class DisputeController extends Controller
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
        $disputes = Dispute::withTrashed()->get(); // Fetch all disputes, including soft-deleted ones
        return view('admin.disputes.index', compact('disputes'));
    }

    /**
     * Soft delete the dispute.
     */
    public function softDelete($id)
    {
        $dispute = Dispute::findOrFail($id);
        $dispute->delete(); // Soft delete the dispute
        return response()->json(['success' => true]);
    }

    /**
     * Restore the soft-deleted dispute.
     */
    public function restore($id)
    {
        $dispute = Dispute::withTrashed()->findOrFail($id);
        $dispute->restore(); // Restore the soft-deleted dispute
        return response()->json(['success' => true]);
    }
}
