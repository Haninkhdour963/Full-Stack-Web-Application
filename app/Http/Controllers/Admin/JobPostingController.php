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
        // Fetch all JobPostings with their related Category and Client (User), including soft-deleted ones
        $jobPostings = JobPosting::with(['category', 'client'])->withTrashed()->get();
        return view('admin.jobPostings.index', compact('jobPostings'));
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
