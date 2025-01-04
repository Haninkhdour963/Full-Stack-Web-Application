<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Technician;
use Illuminate\Http\Request;

class TechnicianController extends Controller
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
        // Fetch technicians with pagination (10 technicians per page)
        $technicians = Technician::withTrashed()->paginate(10);
        return view('admin.technicians.index', compact('technicians'));
    }

    /**
     * Soft delete the technician.
     */
    public function softDelete($id)
    {
        $technician = Technician::findOrFail($id);

        // Soft delete the technician
        $technician->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Restore the technician.
     */
    public function restore($id)
    {
        $technician = Technician::withTrashed()->findOrFail($id);

        // Restore the technician
        $technician->restore();

        return response()->json(['success' => true]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Fetch technician details based on ID
        $technician = Technician::findOrFail($id);

        // Return data in JSON format
        return response()->json([
            'name' => $technician->name,
            'identity_number' => $technician->identity_number,
            'skills' => $technician->skills,
            'hourly_rate' => number_format($technician->hourly_rate, 2),
            'rating' => $technician->rating,
            'location' => $technician->location,
            'bio' => $technician->bio,
            'certifications' => $technician->certifications,
            'available_from' => $technician->available_from,
        ]);
    }
}