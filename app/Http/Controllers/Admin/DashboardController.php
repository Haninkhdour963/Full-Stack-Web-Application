<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EscrowPayment;
use App\Models\JobBid;
use App\Models\JobPosting;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index()
    {
        // Retrieve counts for total users, job postings, and job bids
        $totalUsers = User::count();
        $totalJobPostings = JobPosting::count();
        $totalJobBids = JobBid::count();
        
        // Sum of escrow payments - handling nullable values
        $totalEscrowPayments = EscrowPayment::whereNotNull('amount_min')->sum('amount_min'); // Ensure no null values are counted

        // Retrieve the recent payments with related client and job postings
        $recentPayments = EscrowPayment::with(['client', 'job'])->latest()->limit(10)->get();

        // Retrieve the number of users created in the last 6 months
        $usersData = User::selectRaw('MONTH(created_at) as month, COUNT(*) as user_count')
                         ->where('created_at', '>=', Carbon::now()->subMonths(6))
                         ->groupBy('month')
                         ->orderBy('month')
                         ->get();

        // Retrieve the number of pending job bids in the last 6 months
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

        return view('admin.dashboard', compact('totalUsers', 'totalJobPostings', 'totalJobBids', 'totalEscrowPayments', 'recentPayments', 'usersChartData', 'bidsChartData', 'months'));
    }
}
