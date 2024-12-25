<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use App\Models\JobBid;
use App\Models\JobPosting;
use Illuminate\Http\Request;

class TechnicianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('page.technicians.profile');
    }

    public function createProfile(){
        return view('page.technicians.profile');
        
    }
 
    public function manageContracts(Request $request)
{
    // Retrieve filter values from the request
    $location = $request->get('location');
    $duration = $request->get('duration');
    $min_budget = $request->get('min_budget');
    $max_budget = $request->get('max_budget');

    // Fetch job bids with the related job and technician data, applying the filters
    $bids = JobBid::whereIn('status', ['pending', 'accepted'])
        ->with(['job', 'technician'])  // Eager load related job and technician
        ->when($location, function($query, $location) {
            return $query->whereHas('job', function($q) use ($location) {
                $q->where('location', 'like', "%$location%");
            });
        })
        ->when($duration, function($query, $duration) {
            return $query->whereHas('job', function($q) use ($duration) {
                $q->where('duration', 'like', "%$duration%");
            });
        })
        ->when($min_budget, function($query, $min_budget) {
            return $query->where('bid_amount', '>=', $min_budget);
        })
        ->when($max_budget, function($query, $max_budget) {
            return $query->where('bid_amount', '<=', $max_budget);
        })
        ->paginate(10);  // Pagination for results

    return view('page.technicians.contract', compact('bids'));
}

    


    
    public function bidOnJob(Request $request)
{
    // Retrieve filter values from the request
    $location = $request->get('location');
    $duration = $request->get('duration');
    $min_budget = $request->get('min_budget');
    $max_budget = $request->get('max_budget');

    // Build the query with dynamic filters
    $jobPostings = JobPosting::with('client');

    if ($location) {
        $jobPostings->where('location', 'like', "%$location%");
    }

    if ($duration) {
        $jobPostings->where('duration', 'like', "%$duration%");
    }

    if ($min_budget) {
        $jobPostings->where('budget_min', '>=', $min_budget);
    }

    if ($max_budget) {
        $jobPostings->where('budget_max', '<=', $max_budget);
    }

    // Paginate the job postings
    $jobPostings = $jobPostings->paginate(10);  // Adjust the number of items per page as needed

    // Return the view with the job postings and the current filters
    return view('page.technicians.bid', compact('jobPostings'));
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
