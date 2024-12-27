<?php

namespace App\Http\Controllers\Technician;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
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
        // Fetch contacts related to the current authenticated technician
        $technician = auth()->user(); // Get the authenticated technician (User)
        
        if ($technician->isTechnician()) {
            // Paginate the contacts related to this technician (including soft-deleted ones)
            $contacts = Contact::where('technician_id', $technician->id)
                                ->withTrashed()
                                ->paginate(10); // Change 10 to any number of items per page you want
            
            return view('technician.contacts.index', compact('contacts'));
        }
    
        return redirect()->back()->with('error', 'You do not have permission to view contacts.');
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
