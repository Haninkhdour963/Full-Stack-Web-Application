<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
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
        // Fetch all reviews, including soft-deleted ones
        $reviews = Review::withTrashed()->get();
        return view('admin.reviews.index', compact('reviews'));
    }

    /**
     * Soft delete the review.
     */
    public function softDelete($id)
    {
        $review = Review::findOrFail($id);

        // Soft delete the review
        $review->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Restore the soft-deleted review.
     */
    public function restore($id)
    {
        $review = Review::withTrashed()->findOrFail($id);

        // Restore the soft-deleted review
        $review->restore();

        return response()->json(['success' => true]);
    }

    // Other methods like create, store, show, edit, update, destroy would go here.
}
