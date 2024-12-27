<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use Illuminate\Http\Request;

class JobPostingController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:client');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       // Fetch all JobPostings for the currently authenticated client, including related Category and Client, and including soft-deleted ones
    $jobPostings = JobPosting::where('client_id', auth()->id()) // Filter by the current client's ID
    ->with(['category', 'client'])
    ->withTrashed() // Include soft-deleted records
    ->paginate(8); // Paginate with 10 records per pag
        
        return view('client.jobPostings.index', compact('jobPostings'));
    }
    

    /**
     * Soft delete the JobPosting.
     */
    public function softDelete($id)
    {
        $jobPosting = JobPosting::findOrFail($id);

        // Soft delete the job posting
        $jobPosting->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Show the specified resource details.
     */
    public function show(string $id)
    {
        $jobPosting = JobPosting::with(['category', 'client'])->findOrFail($id);

        // Return the job posting as JSON for the AJAX request
        return response()->json([
            'title' => $jobPosting->title,
            'description' => $jobPosting->description,
            'category_name' => $jobPosting->category->category_name ?? 'N/A',
            'client_name' => $jobPosting->client->name ?? 'N/A',
            'location' => $jobPosting->location,
            'budget_min' => $jobPosting->budget_min,
            'budget_max' => $jobPosting->budget_max,
            'status' => $jobPosting->status,
            'posted_at' => $jobPosting->posted_at ? $jobPosting->posted_at->format('Y-m-d H:i:s') : 'N/A',
        ]);
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
