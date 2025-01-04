<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EscrowPayment;
use Illuminate\Http\Request;

class EscrowPaymentController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Paginate EscrowPayments with related job, client, and technician data
        $escrowPayments = EscrowPayment::with(['job', 'client', 'technician'])->withTrashed()->paginate(8);
    
        return view('admin.escrowPayments.index', compact('escrowPayments'));
    }

    /**
     * Show the details of a specific EscrowPayment.
     */
    public function view($id)
    {
        $escrowPayment = EscrowPayment::with(['job', 'client', 'technician'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $escrowPayment->id,
                'job' => $escrowPayment->job ? $escrowPayment->job->title : 'N/A',
                'technician' => $escrowPayment->technician ? $escrowPayment->technician->name : 'N/A',
                'client' => $escrowPayment->client ? $escrowPayment->client->username : 'N/A',
                'status' => $escrowPayment->status,
                'created_at' => $escrowPayment->created_at,
                'updated_at' => $escrowPayment->updated_at,
            ]
        ]);
    }

    /**
     * Soft delete the EscrowPayment.
     */
    public function softDelete($id)
    {
        $escrowPayment = EscrowPayment::findOrFail($id);
        $escrowPayment->delete();  // Soft delete the escrow payment
    
        return response()->json(['success' => true]);
    }

    /**
     * Restore the soft-deleted EscrowPayment.
     */
    public function restore($id)
    {
        $escrowPayment = EscrowPayment::withTrashed()->findOrFail($id);
        $escrowPayment->restore();  // Restore the soft-deleted escrow payment
    
        return response()->json(['success' => true]);
    }
}