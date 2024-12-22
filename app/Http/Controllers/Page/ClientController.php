<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use App\Models\JobPosting;
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

    // Store the job post details in the database
    public function storeJobPost(Request $request)
    {
         // Check if the user is authenticated
    if (!auth()->check()) {
        return redirect()->route('login')->with('error', 'You need to log in first!');
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

    public function signContract()
    {
        $jobPosts = JobPosting::whereIn('status', ['open', 'completed'])
                              ->with('category')  // Eager load the category relationship
                              ->get();
        // Return the view for signing a contract

        return view('page.clients.contract',compact('jobPosts'));  // Ensure the view exists at resources/views/page/clients/signContract.blade.php
    }

    public function hireTechnician()
    {
        // Return the view for hiring a technician
        return view('page.clients.hire');  // Ensure the view exists at resources/views/page/clients/hireTechnician.blade.php
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
