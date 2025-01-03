<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use Illuminate\Http\Request;

class JobPostingController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       // Fetch paginated JobPostings with their related Category and Client, including soft-deleted ones
    $jobPostings = JobPosting::with(['category', 'client'])->withTrashed()->paginate(10); // 10 items per page

        return view('admin.jobPostings.index', compact('jobPostings'));
    }

    public function softDelete($id)
    {
        $jobPosting = JobPosting::findOrFail($id);
    
        // Soft delete the job posting
        $jobPosting->delete();
    
        return response()->json(['success' => true]);
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
    public function show($id)
    {
        // Fetch the job posting with related data
        $jobPosting = JobPosting::with(['category', 'client'])->findOrFail($id);
        
        // Return the job posting data as JSON
        return response()->json($jobPosting);
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