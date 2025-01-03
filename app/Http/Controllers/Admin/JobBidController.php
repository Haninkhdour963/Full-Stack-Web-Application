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

    public function softDelete($id)
    {
        $jobBid = JobBid::findOrFail($id);
    
        // Soft delete the JobBid
        $jobBid->delete();
    
        return response()->json(['success' => true]);
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
