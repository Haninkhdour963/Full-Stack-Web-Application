<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
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
       // Fetch all contacts, including soft-deleted ones, with pagination
    $contacts = Contact::withTrashed()->paginate(8);
        return view('admin.contacts.index', compact('contacts'));
    }

    /**
     * Get contact details by ID (for AJAX request).
     */
    public function show($id)
    {
        $contact = Contact::withTrashed()->findOrFail($id);

        // Prepare the response data for the popup
        return response()->json([
            'id' => $contact->id,
            'job_title' => $contact->job->title ?? 'N/A',
            'technician_name' => $contact->technician->name ?? 'N/A',
            'name' => $contact->name,
            'email' => $contact->email,
            'subject' => $contact->subject,
            'message' => $contact->message,
            'status' => $contact->deleted_at ? 'Deleted' : 'Active'
        ]);
    }

    /**
     * Soft delete the message.
     */
    public function softDelete($id)
    {
        $contact = Contact::findOrFail($id);

        // Soft delete the contact message
        $contact->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Restore the soft-deleted message.
     */
    public function restore($id)
    {
        $contact = Contact::withTrashed()->findOrFail($id);

        // Restore the soft-deleted contact message
        $contact->restore();

        return response()->json(['success' => true]);
    }
}