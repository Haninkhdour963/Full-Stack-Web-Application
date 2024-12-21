@extends('layouts.master')

@section('title', 'Disputes List')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Disputes List</h4>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Job Title</th>
                                <th>Initiator</th>
                                <th>Technician</th>
                                <th>Client</th>
                                <th>Reason</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($disputes as $dispute)
                                <tr id="dispute-row-{{ $dispute->id }}" class="{{ $dispute->deleted_at ? 'text-muted' : '' }}">
                                    <td>{{ $dispute->id }}</td>
                                    <td>{{ $dispute->job->title ?? 'N/A' }}</td>
                                    <td>{{ $dispute->initiator->name ?? 'N/A' }}</td>
                                    <td>{{ $dispute->technician->name ?? 'N/A' }}</td>
                                    <td>{{ $dispute->client->name ?? 'N/A' }}</td>
                                    <td>{{ $dispute->dispute_reason }}</td>
                                    <td>
                                        @if($dispute->deleted_at)
                                            <span class="badge badge-danger">Deleted</span>
                                        @else
                                            <span class="badge badge-success">Active</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($dispute->deleted_at)
                                            <button class="btn btn-success btn-sm restore-btn" data-id="{{ $dispute->id }}">Restore</button>
                                        @else
                                            <button class="btn btn-danger btn-sm soft-delete-btn" data-id="{{ $dispute->id }}">Soft Delete</button>
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
                const disputeId = button.getAttribute('data-id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This action will soft delete the dispute!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, soft delete it!',
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        try {
                            const response = await fetch(`/admin/disputes/${disputeId}/softDelete`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Content-Type': 'application/json',
                                }
                            });

                            if (response.ok) {
                                const data = await response.json();
                                if (data.success) {
                                    Swal.fire('Deleted!', 'Dispute has been soft deleted.', 'success');
                                    const row = document.querySelector(`#dispute-row-${disputeId}`);
                                    row.classList.add('text-muted');
                                    button.disabled = true;
                                    button.innerText = 'Deleted';
                                } else {
                                    Swal.fire('Error', 'Failed to delete dispute.', 'error');
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
                const disputeId = button.getAttribute('data-id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This action will restore the soft deleted dispute!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, restore it!',
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        try {
                            const response = await fetch(`/admin/disputes/${disputeId}/restore`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Content-Type': 'application/json',
                                }
                            });

                            if (response.ok) {
                                const data = await response.json();
                                if (data.success) {
                                    Swal.fire('Restored!', 'Dispute has been restored.', 'success');
                                    const row = document.querySelector(`#dispute-row-${disputeId}`);
                                    row.classList.remove('text-muted');
                                    button.disabled = true;
                                    button.innerText = 'Restored';
                                } else {
                                    Swal.fire('Error', 'Failed to restore dispute.', 'error');
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
