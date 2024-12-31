<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Contact;
use App\Models\JobPosting;
use App\Models\Technician;
use GuzzleHttp\Middleware;
use Illuminate\Http\Request;

class ClientController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function createJobPost()
    {
        // Return the view for creating a job post
        return view('page.clients.post');  // Ensure the view exists at resources/views/page/clients/createJobPost.blade.php
    }

   public function storeJobPost(Request $request)
{
    // Check if the user is authenticated
    if (!auth()->check()) {
        return redirect()->route('login')->with('error', 'You need to log in first!');
    }

    // Check if the user has the 'client' role
    if (auth()->user()->user_role !== 'client') {
        return redirect()->route('register')->with('error', 'You need to register as a client to create a job post.');
    }

    $request->validate([
        'jobTitle' => 'required|string|max:255',
        'jobDescription' => 'required|string',
        'location' => 'required|string|max:255',
        'category' => 'required|integer',
        'budgetMin' => 'required|numeric',
        'budgetMax' => 'required|numeric',
        'skills' => 'required|string'
    ]);

    // Save the job post into the database
    JobPosting::create([
        'client_id' => auth()->user()->id, // Assuming the logged-in user is the client
        'title' => $request->jobTitle,
        'description' => $request->jobDescription,
        'location' => $request->location,
        'category_id' => $request->category,
        'budget_min' => $request->budgetMin,
        'budget_max' => $request->budgetMax,
        'skills' => $request->skills,
        'status' => 'open', // Default status (assuming the job is open)
        'posted_at' => now(),
    ]);

    // Redirect to contract page after job is created
    return redirect()->route('page.clients.contract')->with('success', 'Job post created successfully!');
}

public function signContract(Request $request)
{
    // Get filter options (status, category) from the request, or set defaults
    $statusFilter = $request->get('status', 'open'); // Default to 'open'
    $categoryFilter = $request->get('category', null); // Default to no category filter

    // Get job postings based on filters, also including pagination
    $jobPosts = JobPosting::query()
        ->when($statusFilter, function ($query, $statusFilter) {
            return $query->where('status', $statusFilter);
        })
        ->when($categoryFilter, function ($query, $categoryFilter) {
            return $query->where('category_id', $categoryFilter);
        })
        ->with('category')  // Eager load the category relationship
        ->paginate(10); // Add pagination with 10 items per page

    // Get all available categories for the filter dropdown
    $categories = Category::all();

    // Return the view for signing a contract, passing job postings, filters, and categories
    return view('page.clients.contract', compact('jobPosts', 'statusFilter', 'categoryFilter', 'categories'));
}



    public function hireTechnician(Request $request)
    {
        // Get the filter type from request, default to 'featured'
        $filterType = $request->get('filter', 'featured');
    
        // Get users who are technicians and their associated technician profiles
        $technicians = Technician::whereHas('user', function($query) {
                          $query->where('user_role', 'technician');
                      })
                      ->byHourlyRate($filterType) // Using the scope we defined in Technician model
                      ->with('user')  // Eager load the user relationship
                      ->paginate(10); // Add pagination with 10 items per page
    
        return view('page.clients.hire', compact('technicians', 'filterType'));
    }



    public function showContactForm($technician_id)
    {
        $technician = Technician::with('user')->findOrFail($technician_id);
        // Get available job postings for the logged-in client
        $jobPostings = JobPosting::where('client_id', auth()->id())
                                ->where('status', 'open')
                                ->get();
        
        return view('page.clients.contact-form', compact('technician', 'jobPostings'));
    }

    public function sendMessage(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'technician_id' => 'required|exists:technicians,id',
            'job_id' => 'required|exists:job_postings,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string'
        ]);

        // Create new contact record
        Contact::create([
            'technician_id' => $validated['technician_id'],
            'job_id' => $validated['job_id'], // Add job_id
            'name' => $validated['name'],
            'email' => $validated['email'],
            'subject' => $validated['subject'],
            'message' => $validated['message']
        ]);

        return redirect()->back()->with('success', 'Message sent successfully!');
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
