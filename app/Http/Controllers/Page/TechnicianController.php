<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use App\Models\EscrowPayment;
use App\Models\JobBid;
use App\Models\JobPosting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; 
use App\Notifications\NewBidNotification;

use Stripe\Stripe;
use Stripe\PaymentIntent;


class TechnicianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('page.technicians.profile');
       
    }
   
    // Show the bid form
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

    // Submit a bid
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

        // Send notification to the client
        $client = User::find($job->client_id);
        $client->notify(new NewBidNotification($bid));

        session(['current_step' => 2, 'job_title' => $job->title, 'bid_id' => $bid->id]);
        return redirect()->route('page.technicians.form');
    }


    public function acceptContract(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
    
        $bidId = session('bid_id');
        $bid = JobBid::findOrFail($bidId);
    
        // تأكد من أن المستخدم هو العميل صاحب الوظيفة
        if (Auth::user()->user_role === 'client' && Auth::id() === $bid->job->client_id) {
            DB::beginTransaction();
            try {
                // تحديث حالة العرض إلى "accepted"
                $bid->update(['status' => 'accepted']);
    
                // تحديث حالة الوظيفة إلى "in_progress"
                $bid->job->update(['status' => 'in_progress']);
    
                // إنشاء سجل دفع
                $payment = Payment::create([
                    'job_id' => $bid->job_id,
                    'client_id' => $bid->job->client_id,
                    'technician_id' => $bid->technician_id,
                    'amount' => $bid->bid_amount,
                    'payment_method' => 'stripe',
                    'payment_status' => 'pending',
                    'transaction_id' => null,
                    'payment_date' => now(),
                ]);
    
                // إرسال إشعار إلى الفني
                $technician = User::find($bid->technician_id);
                $technician->notify(new BidAcceptedNotification($bid));
    
                DB::commit();
    
                // توجيه المستخدم إلى صفحة الدفع
                return redirect()->route('page.technicians.processPayment', ['bid_id' => $bid->id]);
            } catch (\Exception $e) {
                DB::rollback();
                return redirect()->back()->with('error', 'An error occurred while processing your request.');
            }
        }
    
        return redirect()->back()->with('error', 'Only the client can accept the bid.');
    }

    
    public function paymentSuccess(Request $request)
    {
        $bidId = $request->get('bid_id');
        $bid = JobBid::findOrFail($bidId);
    
        DB::beginTransaction();
        try {
            // Update job status
            $bid->job->update(['status' => 'completed']);
    
            // Update bid status
            $bid->update(['status' => 'paid']);
    
            // Create payment record
            Payment::create([
                'job_id' => $bid->job_id,
                'client_id' => $bid->job->client_id,
                'technician_id' => $bid->technician_id,
                'amount' => $bid->bid_amount,
                'payment_method' => 'credit_card',
                'payment_status' => 'completed',
                'transaction_id' => $request->get('transaction_id'),
                'payment_date' => now()
            ]);
    
            DB::commit();
    
            return redirect()->route('page.technicians.contract')->with('success', 'Payment processed successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('page.technicians.contract')->with('error', 'Payment failed.');
        }
    }

   // Reset the bid flow
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

    public function paymentCancel(Request $request)
    {
        return redirect()->route('page.technicians.form')->with('error', 'Payment was cancelled.');
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
        ->with(['job', 'technician'])
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
        ->paginate(10);

    // If the request is AJAX, return JSON
    if ($request->ajax()) {
        return response()->json([
            'bids' => $bids->items(),
            'pagination' => $bids->links('vendor.pagination.custom')->render()
        ]);
    }

    // Return the full view for non-AJAX requests
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

    
   
 // Accept or reject a bid
 public function respondToBid(Request $request)
 {
     $validated = $request->validate([
         'bid_id' => 'required|exists:job_bids,id',
         'response' => 'required|in:accept,reject'
     ]);

     $bid = JobBid::with(['job', 'technician'])->findOrFail($validated['bid_id']);

     // Check if the logged-in client owns the job
     if (Auth::id() !== $bid->job->client_id) {
         return response()->json([
             'success' => false,
             'message' => 'Unauthorized action'
         ], 403);
     }

     DB::beginTransaction();
     try {
         if ($validated['response'] === 'accept') {
             // Update bid status to accepted
             $bid->update(['status' => 'accepted']);

             // Update job posting status
             $bid->job->update(['status' => 'in_progress']);

             // Create escrow payment record
             EscrowPayment::create([
                 'job_id' => $bid->job_id,
                 'client_id' => Auth::id(),
                 'technician_id' => $bid->technician_id,
                 'amount_min' => $bid->bid_amount,
                 'amount_max' => $bid->bid_amount,
                 'status' => 'hold'
             ]);

             $message = 'Bid accepted successfully. Please proceed with payment.';
         } else {
             // Update bid status to declined
             $bid->update(['status' => 'declined']);
             $message = 'Bid declined successfully.';
         }

         DB::commit();

         return response()->json([
             'success' => true,
             'message' => $message,
             'redirect_url' => $validated['response'] === 'accept'
                 ? route('page.technicians.processPayment', ['bid_id' => $bid->id])
                 : route('page.clients.contract')
         ]);
     } catch (\Exception $e) {
         DB::rollback();
         return response()->json([
             'success' => false,
             'message' => 'An error occurred while processing your request.'
         ], 500);
     }
 }

 public function processPayment(Request $request)
{
    $bidId = $request->get('bid_id');
    $bid = JobBid::findOrFail($bidId);

    Stripe::setApiKey(env('STRIPE_SECRET'));

    try {
        $paymentIntent = PaymentIntent::create([
            'amount' => $bid->bid_amount * 100, // Amount in cents
            'currency' => 'usd',
            'description' => 'Payment for job ' . $bid->job->title,
            'metadata' => [
                'bid_id' => $bid->id,
                'job_id' => $bid->job_id,
                'client_id' => $bid->job->client_id,
                'technician_id' => $bid->technician_id
            ]
        ]);

        return response()->json([
            'success' => true,
            'client_secret' => $paymentIntent->client_secret,
            'redirect_url' => route('page.technicians.paymentSuccess', ['bid_id' => $bid->id])
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Payment failed.'
        ], 500);
    }
}

public function showRespondToBidForm($bidId)
{
    $bid = JobBid::findOrFail($bidId);
    return view('page.technicians.respond_to_bid_form', compact('bid'));
}

public function handleBidResponse(Request $request)
{
    $validated = $request->validate([
        'bid_id' => 'required|exists:job_bids,id',
        'action' => 'required|in:accept,reject'
    ]);

    $bid = JobBid::findOrFail($validated['bid_id']);
    $job = $bid->job;

    DB::beginTransaction();
    try {
        if ($validated['action'] === 'accept') {
            // Update bid status to accepted
            $bid->update(['status' => 'accepted']);

            // Update job posting status
            $job->update(['status' => 'in_progress']);

            // Update notification status
            $notification = auth()->user()->notifications()->where('data->bid_id', $bid->id)->first();
            if ($notification) {
                $data = $notification->data;
                $data['status'] = 'accepted';
                $notification->update(['data' => $data]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'redirect_url' => route('stripe.payment', ['bid_id' => $bid->id])
            ]);
        } else {
            // Update bid status to declined
            $bid->update(['status' => 'declined']);

            // Update notification status
            $notification = auth()->user()->notifications()->where('data->bid_id', $bid->id)->first();
            if ($notification) {
                $data = $notification->data;
                $data['status'] = 'rejected';
                $notification->update(['data' => $data]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Bid rejected successfully.'
            ]);
        }
    } catch (\Exception $e) {
        DB::rollback();
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while processing your request.'
        ], 500);
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