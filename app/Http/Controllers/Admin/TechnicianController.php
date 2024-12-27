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
        // جلب التفاصيل الخاصة بالفني بناءً على الـ ID
        $technician = Technician::findOrFail($id);
        
        // إعادة البيانات بتنسيق JSON
        return response()->json([
            'name' => $technician->name,  // افترض أن هناك حقل للاسم
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