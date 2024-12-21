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
        // Fetch all contacts, including soft-deleted ones
        $contacts = Contact::withTrashed()->get();
        return view('admin.contacts.index', compact('contacts'));
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

    // Other methods like show, store, update, destroy would go here.
}
