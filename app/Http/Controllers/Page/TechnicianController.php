<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use App\Models\EscrowPayment;
use App\Models\JobBid;
use App\Models\JobPosting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        // Get job ID from session if not provided
        $jobId = $jobId ?? session('job_id');
        
        // Get job details
        $job = null;
        if ($jobId) {
            $job = JobPosting::with('client')->findOrFail($jobId);
            session(['job_id' => $jobId]);
        }

        // Get current step
        $currentStep = session('current_step', 1);

        // Get existing bid data if technician has already placed a bid
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
        // Ensure the technician is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to submit a bid.');
        }
    
        // Validate incoming request
        $validated = $request->validate([
            'job_id' => 'required|exists:job_postings,id',
            'bid_amount' => 'required|numeric|min:1',
            'bid_message' => 'nullable|string',
        ]);
    
        $technicianId = auth()->id();
    
        // Create or update the bid
        $existingBid = JobBid::updateOrCreate(
            [
                'job_id' => $validated['job_id'],
                'technician_id' => $technicianId,
            ],
            [
                'bid_amount' => $validated['bid_amount'],
                'bid_message' => $validated['bid_message'],
            ]
        );
    
        // Update session and proceed to the next step (Bid Confirmation)
        session(['current_step' => 2, 'job_title' => $existingBid->job->title, 'bid_id' => $existingBid->id]);
    
        // Redirect to the form for confirmation
        return redirect()->route('page.technicians.form');
    }
    


    // Accept contract functionality
    public function acceptContract(Request $request)
    {
        // Your logic to accept the contract
        // For now, we just proceed to the next step
        session(['current_step' => 3]);

        return redirect()->route('page.technicians.form');
    }

    public function processPayment(Request $request)
    {
        // Example data, you may replace with actual logic
        $bid_id = $request->bid_id;

        // Set up the payment details (mock data for this example)
        $payer = new Payer();
        $payer->setPaymentMethod("peyapal");

        $amount = new Amount();
        $amount->setCurrency("JOD")
            ->setTotal(100.00);  // Replace with the actual amount

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setDescription("Bid payment for job");

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl(route('success'))  // Define success URL
            ->setCancelUrl(route('cancel'));  // Define cancel URL

        $payment = new Payment();
        $payment->setIntent("sale")
            ->setPayer($payer)
            ->setTransactions([$transaction])
            ->setRedirectUrls($redirectUrls);

        try {
            $payment->create($this->apiContext);

            // Return the approval URL
            return response()->json([
                'success' => true,
                'approval_url' => $payment->getApprovalLink()
            ]);
        } catch (\Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating PayPal payment: ' . $ex->getMessage()
            ]);
        }
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