<?php

namespace App\Http\Controllers\Technician;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\JobPosting;
use App\Models\JobBid;
use App\Models\EscrowPayment;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:technician');
    }

    public function index()
    {
        // Get total counts for the dashboard
        $totalUsers = User::count();
        
        // Get counts for job postings and bids by clients (users with role 'client')
        $totalJobPostingsForClients = JobPosting::whereHas('client', function ($query) {
            $query->where('user_role', 'client');  // Filter users by role 'client'
        })->count();

        $totalJobBidsForClients = JobBid::whereHas('job.client', function ($query) {
            $query->where('user_role', 'client');  // Filter job postings by client role
        })->count();

        $totalPayments = Payment::sum('amount');
        
        // Get recent payments for the table
        $recentPayments = Payment::latest()->take(5)->get();

         // Retrieve the number of users created in the last 6 months
         $usersData = User::selectRaw('MONTH(created_at) as month, COUNT(*) as user_count')
         ->where('created_at', '>=', Carbon::now()->subMonths(6))
         ->groupBy('month')
         ->orderBy('month')
         ->get();

         $bidsData = JobBid::selectRaw('MONTH(created_at) as month, COUNT(*) as bid_count')
         ->where('created_at', '>=', Carbon::now()->subMonths(6))
         ->where('status', 'pending') // Only pending bids
         ->groupBy('month')
         ->orderBy('month')
         ->get();

// Prepare data for charts
$usersChartData = [];
$bidsChartData = [];

// Format users chart data
foreach ($usersData as $data) {
$usersChartData[] = $data->user_count;
}

// Format bids chart data
foreach ($bidsData as $data) {
$bidsChartData[] = $data->bid_count;
}

// Get months (6 months back)
$months = [];
for ($i = 5; $i >= 0; $i--) {
$months[] = Carbon::now()->subMonths($i)->format('M');
}


        return view('technician.dashboard', compact(
            'totalUsers', 
            'totalJobPostingsForClients', 
            'totalJobBidsForClients', 
            'totalPayments', 
            'recentPayments', 
            'months', 
            'usersChartData', 
            'bidsChartData'
        ));
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
