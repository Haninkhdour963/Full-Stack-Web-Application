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
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.soft-delete-btn').forEach(button => {
            button.addEventListener('click', async () => {
                const contactId = button.getAttribute('data-id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This action will soft delete the message!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, soft delete it!',
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        try {
                            const response = await fetch(`/admin/contacts/${contactId}/soft-delete`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Content-Type': 'application/json',
                                }
                            });

                            if (response.ok) {
                                const data = await response.json();
                                if (data.success) {
                                    Swal.fire('Deleted!', 'Message has been soft deleted.', 'success');
                                    const row = document.querySelector(`#contact-row-${contactId}`);
                                    row.classList.add('text-muted');
                                    button.disabled = true;
                                    button.innerText = 'Deleted';
                                } else {
                                    Swal.fire('Error', 'Failed to delete message.', 'error');
                                }
                            } else {
                                Swal.fire('Error', 'Failed to communicate with the server.', 'error');
                            }
                        } catch (error) {
                            Swal.fire('Error', 'Network error. Failed to communicate with the server.', 'error');
                        }
                    }
                });
            });
        });

        document.querySelectorAll('.restore-btn').forEach(button => {
            button.addEventListener('click', async () => {
                const contactId = button.getAttribute('data-id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This action will restore the soft deleted message!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, restore it!',
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        try {
                            const response = await fetch(`/admin/contacts/${contactId}/restore`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Content-Type': 'application/json',
                                }
                            });

                            if (response.ok) {
                                const data = await response.json();
                                if (data.success) {
                                    Swal.fire('Restored!', 'Message has been restored.', 'success');
                                    const row = document.querySelector(`#contact-row-${contactId}`);
                                    row.classList.remove('text-muted');
                                    button.disabled = true;
                                    button.innerText = 'Restored';
                                } else {
                                    Swal.fire('Error', 'Failed to restore message.', 'error');
                                }
                            } else {
                                Swal.fire('Error', 'Failed to communicate with the server.', 'error');
                            }
                        } catch (error) {
                            Swal.fire('Error', 'Network error. Failed to communicate with the server.', 'error');
                        }
                    }
                });
            });
        });
    });
</script>
@endpush
