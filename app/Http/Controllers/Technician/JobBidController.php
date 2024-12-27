<?php

namespace App\Http\Controllers\Technician;

use App\Http\Controllers\Controller;
use App\Models\JobBid;
use Illuminate\Http\Request;

class JobBidController extends Controller
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
        // Get the current authenticated user
        $user = auth()->user();
        
        // Check if the user is a technician
        if ($user->isTechnician()) {
            // Paginate job bids for the authenticated technician (only those that belong to this technician)
            $jobBids = JobBid::where('technician_id', $user->id)
                             ->with(['job', 'technician'])
                             ->withTrashed()  // Include soft-deleted records if needed
                             ->paginate(8);  // Adjust the number per page as needed
    
            // Return the view with the job bids data
            return view('technician.jobBids.index', compact('jobBids'));
        }
    
        // If the user is not a technician, you could return an error or redirect
        return redirect()->route('home')->with('error', 'Unauthorized access.');
    }
    
    

    /**
     * Soft delete the JobBid.
     */
    public function softDelete($id)
    {
        $jobBid = JobBid::findOrFail($id);

        // Soft delete the JobBid
        $jobBid->delete();

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
