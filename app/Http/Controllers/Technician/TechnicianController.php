<?php

namespace App\Http\Controllers\Technician;

use App\Http\Controllers\Controller;
use App\Models\Technician;
use App\Models\User;
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
        $technician = Auth::user()->technician;
        return view('technician.technicians.index', compact('technician'));
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        try {
            $technician = Technician::with('user')->findOrFail($id);
            return response()->json([
                'user' => [
                    'name' => $technician->user->name,
                    'email' => $technician->user->email,
                    'mobile_phone' => $technician->user->mobile_phone,
                    'profile_image' => $technician->user->profile_image,
                ],
                'identity_number' => $technician->identity_number,
                'skills' => $technician->skills,
                'hourly_rate' => $technician->hourly_rate,
                'rating' => $technician->rating,
                'location' => $technician->location,
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching technician data: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch technician data'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $technician = Technician::findOrFail($id);
            $user = $technician->user;

            // Update user data
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'mobile_phone' => $request->mobile_phone,
            ]);

            // Update technician data
            $technician->update([
                'identity_number' => $request->identity_number,
                'skills' => $request->skills,
                'hourly_rate' => $request->hourly_rate,
                'rating' => $request->rating,
                'location' => $request->location,
            ]);

            return response()->json(['message' => 'Technician updated successfully', 'data' => $technician]);
        } catch (\Exception $e) {
            Log::error('Error updating technician: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update technician'], 500);
        }
    }
}