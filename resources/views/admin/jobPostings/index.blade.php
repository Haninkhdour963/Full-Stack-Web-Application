@extends('layouts.master')

@section('title', 'Job Postings')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Job Postings</h4>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Client</th>
                                <th>Location</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="jobPostingsTableBody">
                            @foreach($jobPostings as $jobPosting)
                                <tr id="jobPosting-row-{{ $jobPosting->id }}" class="{{ $jobPosting->deleted_at ? 'text-muted' : '' }}">
                                    <td>{{ $jobPosting->title }}</td>
                                    <td>{{ $jobPosting->category->category_name ?? 'N/A' }}</td>
                                    <td>{{ $jobPosting->client->name ?? 'N/A' }}</td>
                                    <td>{{ $jobPosting->location }}</td>
                                    <td>
                                        @if($jobPosting->status == 'open')
                                            <span class="badge badge-primary">Open</span>
                                        @elseif($jobPosting->status == 'in_progress')
                                            <span class="badge badge-warning">In Progress</span>
                                        @elseif($jobPosting->status == 'completed')
                                            <span class="badge badge-success">Completed</span>
                                        @elseif($jobPosting->status == 'cancelled')
                                            <span class="badge badge-danger">Cancelled</span>
                                        @elseif($jobPosting->status == 'closed')
                                            <span class="badge badge-secondary">Closed</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-info btn-sm view-btn" data-id="{{ $jobPosting->id }}">View</button>
                                        @if($jobPosting->deleted_at)
                                            <button class="btn btn-success btn-sm restore-btn" data-id="{{ $jobPosting->id }}">Restore</button>
                                        @else
                                            <button class="btn btn-danger btn-sm soft-delete-btn" data-id="{{ $jobPosting->id }}">Soft Delete</button>
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

<!-- Modal -->
<div class="modal fade" id="jobPostingModal" tabindex="-1" aria-labelledby="jobPostingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="jobPostingModalLabel">Job Posting Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Title:</strong> <span id="modal-title"></span></p>
                <p><strong>Description:</strong> <span id="modal-description"></span></p>
                <p><strong>Category:</strong> <span id="modal-category"></span></p>
                <p><strong>Client:</strong> <span id="modal-client"></span></p>
                <p><strong>Location:</strong> <span id="modal-location"></span></p>
                <p><strong>Budget:</strong> <span id="modal-budget"></span></p>
                <p><strong>Status:</strong> <span id="modal-status"></span></p>
                <p><strong>Posted At:</strong> <span id="modal-posted_at"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Pagination Links -->
<div class="d-flex justify-content-center">
    {{ $jobPostings->links('vendor.pagination.custom') }}
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Function to attach event listeners to buttons
    const attachEventListeners = (row) => {
        const viewButton = row.querySelector('.view-btn');
        const softDeleteButton = row.querySelector('.soft-delete-btn');
        const restoreButton = row.querySelector('.restore-btn');

        if (viewButton) {
            viewButton.addEventListener('click', viewHandler);
        }
        if (softDeleteButton) {
            softDeleteButton.addEventListener('click', softDeleteHandler);
        }
        if (restoreButton) {
            restoreButton.addEventListener('click', restoreHandler);
        }
    };

    // View Button Handler
    const viewHandler = async (event) => {
        const jobPostingId = event.target.getAttribute('data-id');
        try {
            const response = await fetch(`/admin/jobPostings/${jobPostingId}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });
            const data = await response.json();
            if (response.ok) {
                // Populate Modal
                document.getElementById('modal-title').textContent = data.title;
                document.getElementById('modal-description').textContent = data.description;
                document.getElementById('modal-category').textContent = data.category?.category_name || 'N/A';
                document.getElementById('modal-client').textContent = data.client?.name || 'N/A';
                document.getElementById('modal-location').textContent = data.location;
                document.getElementById('modal-budget').textContent = data.budget;
                document.getElementById('modal-status').textContent = data.status;
                document.getElementById('modal-posted_at').textContent = new Date(data.created_at).toLocaleString();
                // Show Modal
                new bootstrap.Modal(document.getElementById('jobPostingModal')).show();
            } else {
                Swal.fire('Error', 'Failed to fetch job posting details.', 'error');
            }
        } catch (error) {
            Swal.fire('Error', 'Network error. Failed to fetch job posting details.', 'error');
        }
    };

    // Soft Delete Button Handler
    const softDeleteHandler = async (event) => {
        const jobPostingId = event.target.getAttribute('data-id');
        try {
            const response = await fetch(`/admin/jobPostings/${jobPostingId}/softDelete`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });
            const data = await response.json();
            if (response.ok && data.success) {
                Swal.fire('Success', 'Job posting soft deleted successfully.', 'success');
                // Update UI
                const row = document.getElementById(`jobPosting-row-${jobPostingId}`);
                row.classList.add('text-muted');
                event.target.outerHTML = '<button class="btn btn-success btn-sm restore-btn" data-id="' + jobPostingId + '">Restore</button>';
                // Reattach event listeners to the new "Restore" button
                attachEventListeners(row);
            } else {
                Swal.fire('Error', 'Failed to soft delete the job posting.', 'error');
            }
        } catch (error) {
            Swal.fire('Error', 'Network error. Failed to soft delete the job posting.', 'error');
        }
    };

    // Restore Button Handler
    const restoreHandler = async (event) => {
        const jobPostingId = event.target.getAttribute('data-id');
        try {
            const response = await fetch(`/admin/jobPostings/${jobPostingId}/restore`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });
            const data = await response.json();
            if (response.ok && data.success) {
                Swal.fire('Success', 'Job posting restored successfully.', 'success');
                // Update UI
                const row = document.getElementById(`jobPosting-row-${jobPostingId}`);
                row.classList.remove('text-muted');
                event.target.outerHTML = '<button class="btn btn-danger btn-sm soft-delete-btn" data-id="' + jobPostingId + '">Soft Delete</button>';
                // Reattach event listeners to the new "Soft Delete" button
                attachEventListeners(row);
            } else {
                Swal.fire('Error', 'Failed to restore the job posting.', 'error');
            }
        } catch (error) {
            Swal.fire('Error', 'Network error. Failed to restore the job posting.', 'error');
        }
    };

    // Attach event listeners to all buttons initially
    document.querySelectorAll('#jobPostingsTableBody tr').forEach(row => {
        attachEventListeners(row);
    });
});
</script>
@endpush
