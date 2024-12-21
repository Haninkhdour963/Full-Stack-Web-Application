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
    $request->validate([
        'jobTitle' => 'required|string|max:255',
        'jobDescription' => 'required|string',
        'location' => 'required|string|max:255',
        'category' => 'required|integer',
        'budget_min' => 'required|numeric',
        'budget_max' => 'required|numeric',
        'skills' => 'required|string',
        'posted_at' => 'required|date',
    ]);

    // Save the job post into the database
    // Assuming you have a JobPost model
    JobPosting::create([
        'title' => $request->jobTitle,
        'description' => $request->jobDescription,
        'location' => $request->location,
        'category_id' => $request->category,
        'budget_min' => $request->budget,
        'budget_max' => $request->budget,
        'skills' => $request->skills,
        'posted_at' => $request->deadline,
    ]);

    // Redirect back with a success message
    return redirect()->route('page.clients.post')->with('success', 'Job post created successfully!');
}


    public function signContract()
    {
        // Return the view for signing a contract
        return view('page.clients.contract');  // Ensure the view exists at resources/views/page/clients/signContract.blade.php
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
