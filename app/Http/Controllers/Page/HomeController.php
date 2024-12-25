<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\JobPosting;
use App\Models\Review;
use App\Models\Technician;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Fetch categories
        $categories = Category::paginate(8);

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
        

        // Filter by hourly rate if provided
        if ($request->has('hourly_rate') && $request->get('hourly_rate') != '') {
            $hourlyRate = $request->get('hourly_rate');
            $query->where('hourly_rate', '<=', $hourlyRate);
        }

       // Apply technician category filter if provided
if ($request->has('technician_category') && $request->get('technician_category') != '') {
    $technicianCategory = $request->get('technician_category');
    
    // Get technicians filtered by category
    $filteredTechnicians = Technician::byHourlyRate($technicianCategory)->pluck('id');
    
    // Filter job postings that have bids from these technicians
    $query->whereHas('jobBids', function($q) use ($filteredTechnicians) {
        $q->whereIn('technician_id', $filteredTechnicians);
    });
}


        // Fetch the job postings with their related category data and paginate
        $jobPostings = $query->with(['category', 'jobBids.technician'])->paginate(10);

        // Return the view with the necessary data
        return view('index', compact('jobPostings', 'categories', 'reviews'));
    }
}