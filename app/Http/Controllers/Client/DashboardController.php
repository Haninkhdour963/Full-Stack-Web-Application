<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\JobBid;
use App\Models\JobPosting;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Auth;  // Import the Auth facade to get the authenticated user
use Illuminate\Support\Facades\Auth as FacadesAuth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:client');
    }

    public function index()
    {
        // Get the authenticated client
        $client = Auth::user(); // Fetches the logged-in user (client)
        

        // Calculate total users with the role 'technician'
        $totalUsers = User::where('user_role', 'technician')->count();

        // Calculate total job postings for this specific client
        $totalJobPostings = JobPosting::where('client_id', $client->id)->count(); // Job postings specific to this client

        // Calculate total job bids for this client
        $totalJobBids = JobBid::count(); 

        // Calculate total payments for this client (sum of payment amounts)
        $totalPayments = $client->Payments()->sum('amount'); // Sum the payment amounts for this client

        // Get the last 6 months for the charts
        $months = [];
        $usersChartData = [];
        $bidsChartData = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i)->format('F Y');
            $months[] = $month;

            // Get the number of users registered in each of the last 6 months
            $usersChartData[] = User::where('user_role', 'technician')
                ->whereBetween('created_at', [
                    Carbon::now()->subMonths($i)->startOfMonth(),
                    Carbon::now()->subMonths($i)->endOfMonth()
                ])->count();

            // Get the number of job bids in each of the last 6 months
            $bidsChartData[] = JobBid::whereBetween('created_at', [
                Carbon::now()->subMonths($i)->startOfMonth(),
                Carbon::now()->subMonths($i)->endOfMonth()
            ])->count();
        }

        // Get recent payments for the table (specific to this client)
        $recentPayments = Payment::where('client_id', $client->id)->latest()->take(5)->get();

        // Pass all data to the view
        return view('client.dashboard', compact(
            'totalUsers', 'totalJobPostings', 'totalJobBids', 
            'months', 'usersChartData', 'bidsChartData', 'recentPayments', 'totalPayments'
        ));
    }
}
