<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobBid;
use Illuminate\Http\Request;

class JobBidController extends Controller
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
        // Paginate JobBids, 10 per page (you can adjust the number as needed)
        $jobBids = JobBid::with(['job', 'technician'])->withTrashed()->paginate(10);
        return view('admin.jobBids.index', compact('jobBids'));
    }

    /**
     * Show the details of a specific JobBid.
     */
    public function show($id)
    {
        // Fetch the JobBid with its related Job and Technician
        $jobBid = JobBid::with(['job', 'technician'])->findOrFail($id);

        // Return the jobBid data as JSON
        return response()->json([
            'jobBid' => $jobBid
        ]);
    }

    /**
     * Soft delete the job bid.
     */
    public function softDelete($id)
    {
        $jobBid = JobBid::findOrFail($id);
        $jobBid->delete(); // Soft delete the JobBid
        return response()->json(['success' => true]);
    }

    /**
     * Restore the soft-deleted JobBid.
     */
    public function restore($id)
    {
        $jobBid = JobBid::withTrashed()->findOrFail($id);
        $jobBid->restore(); // Restore the soft-deleted JobBid
        return response()->json(['success' => true]);
    }
}