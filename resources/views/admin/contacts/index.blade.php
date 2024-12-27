@extends('layouts.master')

@section('title', 'Contacts List')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Contacts List</h4>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Job Title</th>
                                <th>Technician</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Subject</th>
                                <th>Message</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($contacts as $contact)
                                <tr id="contact-row-{{ $contact->id }}" class="{{ $contact->deleted_at ? 'text-muted' : '' }}">
                                    <td>{{ $contact->id }}</td>
                                    <td>{{ $contact->job->title ?? 'N/A' }}</td>
                                    <td>{{ $contact->technician->name ?? 'N/A' }}</td>
                                    <td>{{ $contact->name }}</td>
                                    <td>{{ $contact->email }}</td>
                                    <td>{{ $contact->subject }}</td>
                                    <td>{{ $contact->message }}</td>
                                    <td>
                                        @if($contact->deleted_at)
                                            <span class="badge badge-danger">Deleted</span>
                                        @else
                                            <span class="badge badge-success">Active</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($contact->deleted_at)
                                            <button class="btn btn-success btn-sm restore-btn" data-id="{{ $contact->id }}">Restore</button>
                                        @else
                                            <button class="btn btn-danger btn-sm soft-delete-btn" data-id="{{ $contact->id }}">Soft Delete</button>
                                        @endif
                                        <!-- Add View Button -->
                                        <button class="btn btn-info btn-sm view-btn" data-id="{{ $contact->id }}">View</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
  <!-- Add Pagination Links -->
  <div class="d-flex justify-content-center">
                        {{ $contacts->links('vendor.pagination.custom') }}
                    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.view-btn').forEach(button => {
            button.addEventListener('click', async () => {
                const contactId = button.getAttribute('data-id');
                
                // Fetch contact details from the server
                try {
                    const response = await fetch(`/admin/contacts/${contactId}`);
                    const contact = await response.json();

                    if (contact) {
                        // Show the contact details in the SweetAlert popup
                        Swal.fire({
                            title: 'Contact Details',
                            html: `
                                <p><strong>ID:</strong> ${contact.id}</p>
                                <p><strong>Job Title:</strong> ${contact.job_title || 'N/A'}</p>
                                <p><strong>Technician:</strong> ${contact.technician_name || 'N/A'}</p>
                                <p><strong>Name:</strong> ${contact.name}</p>
                                <p><strong>Email:</strong> ${contact.email}</p>
                                <p><strong>Subject:</strong> ${contact.subject}</p>
                                <p><strong>Message:</strong> ${contact.message}</p>
                                <p><strong>Status:</strong> ${contact.status}</p>
                            `,
                            icon: 'info',
                            confirmButtonText: 'Close'
                        });
                    } else {
                        Swal.fire('Error', 'Failed to load contact details.', 'error');
                    }
                } catch (error) {
                    Swal.fire('Error', 'Failed to fetch contact details from the server.', 'error');
                }
            });
        });
    });
</script>
@endpush
