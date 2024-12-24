<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\JobPosting;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
          // Fetch the last 3 categories (or use paginate if you want to paginate categories)
          $categories = Category::paginate(10); // Use paginate instead of limit to enable pagination

        
       

   
    // Fetch 10 random reviews
    $reviews = Review::inRandomOrder()->limit(10)->get();
        
        // Start the query for job postings
        $query = JobPosting::query();
    
        // Filter by keyword if provided
        if ($request->has('keyword') && $request->get('keyword') != '') {
            $keyword = $request->get('keyword');
            $query->where(function($q) use ($keyword) {
                $q->where('title', 'like', '%' . $keyword . '%')
                  ->orWhere('description', 'like', '%' . $keyword . '%');
            });
        }
    
        // Filter by category if provided
        if ($request->has('category') && $request->get('category') != '') {
            $categoryId = $request->get('category');
            $query->where('category_id', $categoryId);
        }
    
        // Filter by location if provided
        if ($request->has('location') && $request->get('location') != '') {
            $location = $request->get('location');
            $query->where('location', 'like', '%' . $location . '%');
        }
    
        // Apply technician hourly rate category filter if provided
        if ($request->has('technician_category') && $request->get('technician_category') != '') {
            $technicianCategory = $request->get('technician_category');
            $query->whereHas('technicians', function ($q) use ($technicianCategory) {
                $q->byHourlyRate($technicianCategory); // Apply the scope for hourly_rate filtering
            });
        }
    
        // Fetch the job postings with their related category data
        $jobPostings = $query->with('category')->get();
    
        // Debugging: Log the SQL query and bindings to understand the query structure
        $sql = $query->toSql();
        $bindings = $query->getBindings();
        \Log::debug("SQL Query: " . $sql);
        \Log::debug("Bindings: " . implode(", ", $bindings));
    
        // Check if the query returned any results
        if ($jobPostings->isEmpty()) {
            \Log::debug("No job postings found.");
        }
    
        // Return the view with the necessary data
        return view('index', compact('jobPostings', 'categories', 'reviews'));
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