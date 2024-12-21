<?php

namespace App\Http\Controllers\Technician;

use App\Http\Controllers\Controller;
use App\Models\EscrowPayment;
use Illuminate\Http\Request;

class EscrowPaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:technician');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Paginate EscrowPayments with related job and client data
        $escrowPayments = EscrowPayment::with(['job', 'client', 'technician'])->withTrashed()->paginate(15);
    
        return view('technician.escrowPayments.index', compact('escrowPayments'));
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
     * Display the specified resource.
     */
    public function show(string $id)
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
