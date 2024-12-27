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
                                    <td>{{ $dispute->dispute_reason }}</td>
                                    <td>
                                        @if($dispute->deleted_at)
                                            <span class="badge badge-danger">Deleted</span>
                                        @else
                                            <span class="badge badge-success">Active</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-primary btn-sm view-btn" data-id="{{ $dispute->id }}">View</button>
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

<!-- Modal for Viewing Dispute Details -->
<div class="modal fade" id="viewDisputeModal" tabindex="-1" aria-labelledby="viewDisputeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewDisputeModalLabel">Dispute Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="disputeDetails">
                    <!-- Dynamic content will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

                <!-- Pagination Links -->
                <div class="d-flex justify-content-center">
                    {{ $disputes->links('vendor.pagination.custom') }}  <!-- This generates the pagination links -->
                </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // View Button Logic - Show Modal with Dispute Details
        document.querySelectorAll('.view-btn').forEach(button => {
            button.addEventListener('click', async () => {
                const disputeId = button.getAttribute('data-id');

                try {
                    const response = await fetch(`/admin/disputes/${disputeId}`);
                    if (response.ok) {
                        const dispute = await response.json();
                        let disputeDetails = `
                            <p><strong>ID:</strong> ${dispute.id}</p>
                            <p><strong>Job Title:</strong> ${dispute.job ? dispute.job.title : 'N/A'}</p>
                            <p><strong>Initiator:</strong> ${dispute.initiator ? dispute.initiator.name : 'N/A'}</p>
                            <p><strong>Technician:</strong> ${dispute.technician ? dispute.technician.name : 'N/A'}</p>
                            <p><strong>Client:</strong> ${dispute.client ? dispute.client.name : 'N/A'}</p>
                            <p><strong>Reason:</strong> ${dispute.dispute_reason}</p>
                            <p><strong>Status:</strong> ${dispute.status}</p>
                            <p><strong>Created At:</strong> ${dispute.created_at}</p>
                            <p><strong>Resolved At:</strong> ${dispute.resolved_at || 'N/A'}</p>
                        `;

                        document.getElementById('disputeDetails').innerHTML = disputeDetails;

                        // Show the modal using Bootstrap
                        var modal = new bootstrap.Modal(document.getElementById('viewDisputeModal'));
                        modal.show();
                    } else {
                        Swal.fire('Error', 'Failed to fetch dispute details.', 'error');
                    }
                } catch (error) {
                    Swal.fire('Error', 'Network error. Failed to fetch dispute details.', 'error');
                }
            });
        });

        // Soft Delete Button Logic
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

        // Restore Button Logic
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
