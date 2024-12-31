<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use App\Models\EscrowPayment;
use App\Models\JobBid;
use App\Models\JobPosting;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; 
use PayPal\Api\Payer;
use PayPal\Api\Amount;
use PayPal\Api\Transaction;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Payment;

class TechnicianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('page.technicians.profile');
       
    }
   
    // This function shows the form with the correct step.
    public function showForm($jobId = null)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to place a bid.');
        }

        $jobId = $jobId ?? session('job_id');
        $job = null;
        
        if ($jobId) {
            $job = JobPosting::with('client')->findOrFail($jobId);
            
            // Check if user is a technician
            if (auth()->user()->user_role !== 'technician') {
                return redirect()->back()->with('error', 'Only technicians can place bids.');
            }

            // Check if user is not the job owner
            if (auth()->id() === $job->client_id) {
                return redirect()->back()->with('error', 'You cannot bid on your own job posting.');
            }

            session(['job_id' => $jobId]);
        }

        $currentStep = session('current_step', 1);
        $existingBid = null;
        
        if ($jobId && auth()->check()) {
            $existingBid = JobBid::where('job_id', $jobId)
                                ->where('technician_id', auth()->id())
                                ->first();
        }

        return view('page.technicians.form', compact('job', 'currentStep', 'existingBid'));
    }

    public function submitBid(Request $request)
    {
        if (!Auth::check() || Auth::user()->user_role !== 'technician') {
            return redirect()->route('login')->with('error', 'Only technicians can submit bids.');
        }

        $validated = $request->validate([
            'job_id' => 'required|exists:job_postings,id',
            'bid_amount' => 'required|numeric|min:1',
            'bid_message' => 'nullable|string',
        ]);

        $job = JobPosting::findOrFail($validated['job_id']);
        
        // Create or update the bid
        $bid = JobBid::updateOrCreate(
            [
                'job_id' => $validated['job_id'],
                'technician_id' => Auth::id(),
            ],
            [
                'bid_amount' => $validated['bid_amount'],
                'bid_message' => $validated['bid_message'],
                'status' => 'pending'
            ]
        );

        // Update job posting status
        $job->update(['status' => 'in_progress']);

        session(['current_step' => 2, 'job_title' => $job->title, 'bid_id' => $bid->id]);
        return redirect()->route('page.technicians.form');
    }
    


    // Accept contract functionality
   // Accept contract functionality
public function acceptContract(Request $request)
{
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    $bidId = session('bid_id');
    $bid = JobBid::findOrFail($bidId);

    // Ensure only the client who owns the job can accept the bid
    if (Auth::user()->user_role === 'client' && Auth::id() === $bid->job->client_id) {
        $bid->update(['status' => 'accepted']);
        session(['current_step' => 3]); // Proceed to payment step
        return redirect()->route('page.technicians.form');
    }

    return redirect()->back()->with('error', 'Only the client can accept the bid.');
}


    
    public function paymentSuccess(Request $request)
    {
        $bid = JobBid::findOrFail(session('bid_id'));
        
        // Update job status
        $bid->job->update(['status' => 'completed']);
        
        // Update escrow payment status
        EscrowPayment::where('job_id', $bid->job_id)
                     ->where('status', 'hold')
                     ->update(['status' => 'released']);

        session(['current_step' => 4]);
        return redirect()->route('page.technicians.form')->with('success', 'Payment processed successfully!');
    }



    // Reset the flow if the user wants to start over
    public function resetBidFlow()
    {
        session()->forget([
            'current_step',
            'bid_id',
            'bid_amount',
            'job_id',
            'job_title',
            'job_location',
            'job_duration'
        ]);
        return redirect()->route('page.technicians.bid');
    }
    



    
    public function createProfile(){
        // return view('page.technicians.profile');
        return redirect()->route('login')->with('error', 'You need to register as a technician to create a profile.');
        
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