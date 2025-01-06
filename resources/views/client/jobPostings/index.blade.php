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
                                <th>Location</th>
                                <th>Budget</th>
                                <th>Status</th>
                                <!-- <th>Posted At</th> -->
                                <!-- <th>Actions</th> -->
                            </tr>
                        </thead>
                        <tbody id="jobPostingsTableBody">
                            @foreach($jobPostings as $jobPosting)
                                <tr id="jobPosting-row-{{ $jobPosting->id }}" class="{{ $jobPosting->deleted_at ? 'text-muted' : '' }}">
                                    <td>{{ $jobPosting->title }}</td>
                                    <td>{{ $jobPosting->location }}</td>
                                    <td>JOD {{ number_format($jobPosting->budget_min, 2) }} - JOD {{ number_format($jobPosting->budget_max, 2) }}</td>
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
                                    <!-- <td>{{ $jobPosting->posted_at ? $jobPosting->posted_at->format('Y-m-d H:i:s') : 'N/A' }}</td> -->
                                    <td>
                                        <button class="btn btn-info btn-sm view-btn" data-id="{{ $jobPosting->id }}">View</button>
                                       
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

<!-- Modal to show job posting details -->
<div class="modal fade" id="viewJobPostingModal" tabindex="-1" aria-labelledby="viewJobPostingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewJobPostingModalLabel">Job Posting Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="jobPostingDetails">
                    <p><strong>Title:</strong> <span id="modalJobTitle"></span></p>
                    <p><strong>Description:</strong> <span id="modalJobDescription"></span></p>
                    <p><strong>Category:</strong> <span id="modalJobCategory"></span></p>
                    <p><strong>Client:</strong> <span id="modalJobClient"></span></p>
                    <p><strong>Location:</strong> <span id="modalJobLocation"></span></p>
                    <p><strong>Budget:</strong> <span id="modalJobBudget"></span></p>
                    <p><strong>Status:</strong> <span id="modalJobStatus"></span></p>
                    <p><strong>Posted At:</strong> <span id="modalJobPostedAt"></span></p>
                </div>
            </div>
            <div class="modal-footer">
                <!-- Adding Close button in footer for closing the modal -->
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
        // Soft Delete button logic (same as you have)
        document.querySelectorAll('.soft-delete-btn').forEach(button => {
            button.addEventListener('click', async () => {
                const jobPostingId = button.getAttribute('data-id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This action will soft delete the job posting!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, soft delete it!',
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        try {
                            const response = await fetch(`/client/jobPostings/${jobPostingId}/soft-delete`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Content-Type': 'application/json',
                                }
                            });

                            if (response.ok) {
                                const data = await response.json();
                                if (data.success) {
                                    Swal.fire('Deleted!', 'Job posting has been soft deleted.', 'success');
                                    const row = document.querySelector(`#jobPosting-row-${jobPostingId}`);
                                    row.classList.add('text-muted');
                                    button.disabled = true;
                                    button.innerText = 'Deleted';
                                } else {
                                    Swal.fire('Error', 'Failed to delete job posting.', 'error');
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

        // View button logic
        document.querySelectorAll('.view-btn').forEach(button => {
            button.addEventListener('click', async () => {
                const jobPostingId = button.getAttribute('data-id');

                // Fetch the details of the job posting
                try {
                    const response = await fetch(`/client/jobPostings/${jobPostingId}`);
                    if (response.ok) {
                        const jobPosting = await response.json();

                        // Populate the modal with job posting data
                        document.getElementById('modalJobTitle').innerText = jobPosting.title;
                        document.getElementById('modalJobDescription').innerText = jobPosting.description;
                        document.getElementById('modalJobCategory').innerText = jobPosting.category_name || 'N/A';
                        document.getElementById('modalJobClient').innerText = jobPosting.client_name || 'N/A';
                        document.getElementById('modalJobLocation').innerText = jobPosting.location;
                        document.getElementById('modalJobBudget').innerText = `$${jobPosting.budget_min} - $${jobPosting.budget_max}`;
                        document.getElementById('modalJobStatus').innerText = jobPosting.status;
                        document.getElementById('modalJobPostedAt').innerText = jobPosting.posted_at;

                        // Show the modal
                        var myModal = new bootstrap.Modal(document.getElementById('viewJobPostingModal'));
                        myModal.show();
                    } else {
                        Swal.fire('Error', 'Failed to fetch job posting details.', 'error');
                    }
                } catch (error) {
                    Swal.fire('Error', 'Failed to fetch job posting details.', 'error');
                }
            });
        });
    });
</script>
@endpush
