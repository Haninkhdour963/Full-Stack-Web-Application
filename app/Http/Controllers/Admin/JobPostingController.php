<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use Illuminate\Http\Request;

class JobPostingController extends Controller
{
    public function __construct()
    {
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

    /**
     * Display the specified resource.
     */
    public function show($id)
{
    $jobPosting = JobPosting::with(['category', 'client'])->findOrFail($id);
    return response()->json($jobPosting);
}


    /**
     * Soft delete the specified resource.
     */
    public function softDelete($id)
    {
        $jobPosting = JobPosting::findOrFail($id);
        $jobPosting->delete(); // Soft delete
        return response()->json(['success' => true]);
    }

    /**
     * Restore the specified resource.
     */
    public function restore($id)
    {
        $jobPosting = JobPosting::withTrashed()->findOrFail($id);
        $jobPosting->restore(); // Restore
        return response()->json(['success' => true]);
    }
}
