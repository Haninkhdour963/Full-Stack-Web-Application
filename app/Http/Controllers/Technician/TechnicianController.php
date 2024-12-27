<?php

namespace App\Http\Controllers\Technician;

use App\Http\Controllers\Controller;
use App\Models\Technician;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TechnicianController extends Controller
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
        // Get the currently authenticated technician
        $technician = Auth::user();
        
        // Pass only the current technician's data to the view
        return view('technician.technicians.index', compact('technician'));
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        try {
            $technician = Technician::findOrFail($id);
            return response()->json($technician);
        } catch (\Exception $e) {
            Log::error('Error fetching technician data: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch technician data'], 500);
        }
    }

   

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // This would render a view for creating a new technician
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Logic to store a new technician in the database
    }

    /**
     * Display the specified resource.
     */
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Logic for showing the edit form for a technician
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Logic to update a technician's details
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Logic to permanently delete a technician
    }
}
