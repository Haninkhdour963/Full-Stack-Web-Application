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
                                      <button class="btn btn-info btn-sm view-btn" data-id="{{ $contact->id }}">View</button>
                                        @if($contact->deleted_at)
                                            <button class="btn btn-success btn-sm restore-btn" data-id="{{ $contact->id }}">Restore</button>
                                        @else
                                            <button class="btn btn-danger btn-sm soft-delete-btn" data-id="{{ $contact->id }}">Soft Delete</button>
                                        @endif
                                        
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
        // Function to handle AJAX requests
        const handleRequest = async (url, method, data = {}) => {
            try {
                const response = await fetch(url, {
                    method,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    },
                    body: method === 'POST' ? JSON.stringify(data) : null,
                });
                return await response.json();
            } catch (error) {
                console.error('Error:', error);
                Swal.fire('Error', 'An error occurred while processing your request.', 'error');
                return null;
            }
        };

        // Function to toggle between Soft Delete and Restore buttons
        const toggleButtons = (row, contactId, isDeleted) => {
            const actionCell = row.querySelector('td:last-child');
            if (isDeleted) {
                actionCell.innerHTML = `
                    <button class="btn btn-success btn-sm restore-btn" data-id="${contactId}">Restore</button>
                    <button class="btn btn-info btn-sm view-btn" data-id="${contactId}">View</button>
                `;
            } else {
                actionCell.innerHTML = `
                    <button class="btn btn-danger btn-sm soft-delete-btn" data-id="${contactId}">Soft Delete</button>
                    <button class="btn btn-info btn-sm view-btn" data-id="${contactId}">View</button>
                `;
            }
            // Reattach event listeners to the new buttons
            attachEventListeners();
        };

        // Attach event listeners to buttons
        const attachEventListeners = () => {
            // Soft Delete Button
            document.querySelectorAll('.soft-delete-btn').forEach(button => {
                button.addEventListener('click', async () => {
                    const contactId = button.getAttribute('data-id');
                    const result = await handleRequest(`/admin/contacts/${contactId}/softDelete`, 'POST');

                    if (result && result.success) {
                        const row = document.getElementById(`contact-row-${contactId}`);
                        row.classList.add('text-muted');
                        row.querySelector('.badge').classList.replace('badge-success', 'badge-danger');
                        row.querySelector('.badge').textContent = 'Deleted';
                        toggleButtons(row, contactId, true); // Toggle buttons after soft delete
                    }
                });
            });

            // Restore Button
            document.querySelectorAll('.restore-btn').forEach(button => {
                button.addEventListener('click', async () => {
                    const contactId = button.getAttribute('data-id');
                    const result = await handleRequest(`/admin/contacts/${contactId}/restore`, 'POST');

                    if (result && result.success) {
                        const row = document.getElementById(`contact-row-${contactId}`);
                        row.classList.remove('text-muted');
                        row.querySelector('.badge').classList.replace('badge-danger', 'badge-success');
                        row.querySelector('.badge').textContent = 'Active';
                        toggleButtons(row, contactId, false); // Toggle buttons after restore
                    }
                });
            });

            // View Button
            document.querySelectorAll('.view-btn').forEach(button => {
                button.addEventListener('click', async () => {
                    const contactId = button.getAttribute('data-id');
                    const result = await handleRequest(`/admin/contacts/${contactId}`, 'GET');

                    if (result) {
                        Swal.fire({
                            title: 'Contact Details',
                            html: `
                                <p><strong>ID:</strong> ${result.id}</p>
                                <p><strong>Job Title:</strong> ${result.job_title || 'N/A'}</p>
                                <p><strong>Technician:</strong> ${result.technician_name || 'N/A'}</p>
                                <p><strong>Name:</strong> ${result.name}</p>
                                <p><strong>Email:</strong> ${result.email}</p>
                                <p><strong>Subject:</strong> ${result.subject}</p>
                                <p><strong>Message:</strong> ${result.message}</p>
                                <p><strong>Status:</strong> ${result.status}</p>
                            `,
                            icon: 'info',
                            confirmButtonText: 'Close',
                        });
                    }
                });
            });
        };

        // Initial attachment of event listeners
        attachEventListeners();
    });
</script>
@endpush