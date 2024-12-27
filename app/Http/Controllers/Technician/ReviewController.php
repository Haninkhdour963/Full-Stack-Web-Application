<?php

namespace App\Http\Controllers\Technician;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:technician');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();  // Get the authenticated user (Technician)
        
        // Get reviews where the technician is either the reviewer or the reviewee, with pagination
        $reviews = Review::with(['job', 'reviewer', 'reviewee'])
                        ->where(function($query) use ($user) {
                            $query->where('reviewer_id', $user->id)
                                  ->orWhere('reviewee_id', $user->id);
                        })
                        ->paginate(8); // Adjust the number 10 to the number of reviews per page you want
    
        return view('technician.reviews.index', compact('reviews'));
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
