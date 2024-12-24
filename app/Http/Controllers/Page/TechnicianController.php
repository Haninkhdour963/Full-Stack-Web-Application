<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use App\Models\JobBid;
use App\Models\Technician;
use Illuminate\Http\Request;

class TechnicianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get paginated technicians
        $technicians = Technician::paginate(10);  // Adjust the number per page as needed
        
        // Return the view with the paginated technicians
        return view('page.technician.bid', compact('technicians'));
    }

    public function createProfile(){
        return view('page.technicians.profile');
        
    }
 
    public function manageContracts()
    {
      
        // Fetch job bids with the related job and technician data
        $bids = JobBid::whereIn('status', ['pending', 'accepted'])
            ->with(['job', 'technician'])  // Eager load related job and technician
            ->get();

        return view('page.technicians.contract', compact('bids'));  // Ensure the view exists at resources/views/page/technicians/contracts.blade.php
    }
    public function bidOnJob()
    {
        // Return the view for bidding on a job
        return view('page.technicians.bid');  // Ensure the view exists at resources/views/page/technicians/bid.blade.php
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
