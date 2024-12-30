<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\JobBid;
use App\Models\JobPosting;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:client'); // Ensure only clients access this page
    }

    public function index()
    {
        // Get the authenticated client
        $client = Auth::user(); 
        
        // Calculate total users (technicians)
        $totalUsers = User::where('user_role', 'technician')->count();

        // Get the total job postings for this client
        $totalJobPostings = JobPosting::where('client_id', $client->id)->count();

        // Get the total job bids in the system (for this client, if needed, can filter by job_posting_id)
        $totalJobBids = JobBid::count();

        // Get the total payments made by the client (sum of payment amounts)
        $totalPayments = $client->Payments()->sum('amount');

        // Prepare data for the charts (Last 6 months)
        $months = [];
        $usersChartData = [];
        $bidsChartData = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i)->format('F Y');
            $months[] = $month;

            // Count new technicians in each of the last 6 months
            $usersChartData[] = User::where('user_role', 'technician')
                ->whereBetween('created_at', [
                    Carbon::now()->subMonths($i)->startOfMonth(),
                    Carbon::now()->subMonths($i)->endOfMonth()
                ])->count();

            // Count job bids for each of the last 6 months
            $bidsChartData[] = JobBid::whereBetween('created_at', [
                Carbon::now()->subMonths($i)->startOfMonth(),
                Carbon::now()->subMonths($i)->endOfMonth()
            ])->count();
        }

        // Get the most recent payments made by the client (optional: limit to last 5)
        $recentPayments = Payment::where('client_id', $client->id)->latest()->take(5)->get();

        // Pass all data to the view
        return view('client.dashboard', compact(
            'totalUsers', 'totalJobPostings', 'totalJobBids',
            'months', 'usersChartData', 'bidsChartData', 'recentPayments', 'totalPayments'
        ));
    }
}
