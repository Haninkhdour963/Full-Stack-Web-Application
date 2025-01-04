@extends('layouts.master')

@section('title', 'Job Bids')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Job Bids</h4>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Technician</th>
                                <th>Job Posting</th>
                                <th>Bid Amount</th>
                                <th>Status</th>
                                <th>Bid Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="jobBidsTableBody">
                            @foreach($jobBids as $jobBid)
                                <tr id="jobBid-row-{{ $jobBid->id }}" class="{{ $jobBid->deleted_at ? 'text-muted' : '' }}">
                                    <td>{{ optional($jobBid->technician)->name ?? 'N/A' }}</td>
                                    <td>{{ optional($jobBid->job)->title ?? 'N/A' }}</td>
                                    <td>${{ number_format($jobBid->bid_amount, 2) }}</td>
                                    <td>
                                        @if($jobBid->status == 'pending')
                                            <span class="badge badge-warning">Pending</span>
                                        @elseif($jobBid->status == 'accepted')
                                            <span class="badge badge-success">Accepted</span>
                                        @elseif($jobBid->status == 'declined')
                                            <span class="badge badge-danger">Declined</span>
                                        @elseif($jobBid->status == 'withdrawn')
                                            <span class="badge badge-secondary">Withdrawn</span>
                                        @endif
                                    </td>
                                    <td>{{ $jobBid->bid_date ? $jobBid->bid_date->format('Y-m-d H:i:s') : 'N/A' }}</td>
                                    <td>
                                        <button class="btn btn-info btn-sm view-btn" data-id="{{ $jobBid->id }}">View</button>
                                        @if($jobBid->deleted_at)
                                            <button class="btn btn-success btn-sm restore-btn" data-id="{{ $jobBid->id }}">Restore</button>
                                        @else
                                            <button class="btn btn-danger btn-sm soft-delete-btn" data-id="{{ $jobBid->id }}">Soft Delete</button>
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

<!-- Pagination Links -->
<div class="d-flex justify-content-center">
    {{ $jobBids->links('vendor.pagination.custom') }}
</div>

<!-- Modal for Viewing Job Bid Details -->
<div class="modal fade" id="viewJobBidModal" tabindex="-1" aria-labelledby="viewJobBidModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewJobBidModalLabel">Job Bid Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="jobBidDetails">
                <!-- Job Bid Details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Function to handle Soft Delete
    const softDeleteHandler = async (event) => {
        const button = event.target;
        const jobBidId = button.getAttribute('data-id');

        Swal.fire({
            title: 'Are you sure?',
            text: 'This action will soft delete the job bid!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, soft delete it!',
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    const response = await fetch(`/admin/jobBids/${jobBidId}/softDelete`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ id: jobBidId }),
                    });

                    if (response.ok) {
                        const data = await response.json();
                        if (data.success) {
                            Swal.fire('Deleted!', 'Job bid has been soft deleted.', 'success');
                            const row = document.querySelector(`#jobBid-row-${jobBidId}`);
                            row.classList.add('text-muted'); // Add muted class
                            
                            // Replace Soft Delete button with Restore button
                            const restoreButton = document.createElement('button');
                            restoreButton.className = 'btn btn-success btn-sm restore-btn';
                            restoreButton.setAttribute('data-id', jobBidId);
                            restoreButton.innerText = 'Restore';
                            button.replaceWith(restoreButton);

                            // Add event listener to the new Restore button
                            restoreButton.addEventListener('click', restoreHandler);
                        } else {
                            Swal.fire('Error', 'Failed to delete job bid.', 'error');
                        }
                    } else {
                        Swal.fire('Error', 'Failed to communicate with the server.', 'error');
                    }
                } catch (error) {
                    Swal.fire('Error', 'Network error. Failed to communicate with the server.', 'error');
                }
            }
        });
    };

    // Function to handle Restore
    const restoreHandler = async (event) => {
        const button = event.target;
        const jobBidId = button.getAttribute('data-id');

        Swal.fire({
            title: 'Are you sure?',
            text: 'This action will restore the job bid!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, restore it!',
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    const response = await fetch(`/admin/jobBids/${jobBidId}/restore`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ id: jobBidId }),
                    });

                    if (response.ok) {
                        const data = await response.json();
                        if (data.success) {
                            Swal.fire('Restored!', 'Job bid has been restored.', 'success');
                            const row = document.querySelector(`#jobBid-row-${jobBidId}`);
                            row.classList.remove('text-muted'); // Remove muted class
                            
                            // Replace Restore button with Soft Delete button
                            const softDeleteButton = document.createElement('button');
                            softDeleteButton.className = 'btn btn-danger btn-sm soft-delete-btn';
                            softDeleteButton.setAttribute('data-id', jobBidId);
                            softDeleteButton.innerText = 'Soft Delete';
                            button.replaceWith(softDeleteButton);

                            // Add event listener to the new Soft Delete button
                            softDeleteButton.addEventListener('click', softDeleteHandler);
                        } else {
                            Swal.fire('Error', 'Failed to restore job bid.', 'error');
                        }
                    } else {
                        Swal.fire('Error', 'Failed to communicate with the server.', 'error');
                    }
                } catch (error) {
                    Swal.fire('Error', 'Network error. Failed to communicate with the server.', 'error');
                }
            }
        });
    };

    // Add event listeners to existing Soft Delete buttons
    document.querySelectorAll('.soft-delete-btn').forEach(button => {
        button.addEventListener('click', softDeleteHandler);
    });

    // Add event listeners to existing Restore buttons
    document.querySelectorAll('.restore-btn').forEach(button => {
        button.addEventListener('click', restoreHandler);
    });

    // View Job Bid Details
    document.querySelectorAll('.view-btn').forEach(button => {
        button.addEventListener('click', async () => {
            const jobBidId = button.getAttribute('data-id');
            try {
                const response = await fetch(`/admin/jobBids/${jobBidId}`);
                if (response.ok) {
                    const data = await response.json();
                    const jobBid = data.jobBid;
                    let jobBidDetails = `
                        <p><strong>Technician:</strong> ${jobBid.technician?.name ?? 'N/A'}</p>
                        <p><strong>Job Title:</strong> ${jobBid.job?.title ?? 'N/A'}</p>
                        <p><strong>Bid Amount:</strong> $${jobBid.bid_amount}</p>
                        <p><strong>Status:</strong> ${jobBid.status}</p>
                        <p><strong>Bid Date:</strong> ${jobBid.bid_date}</p>
                    `;
                    document.getElementById('jobBidDetails').innerHTML = jobBidDetails;

                    // Show modal with job bid details
                    var modal = new bootstrap.Modal(document.getElementById('viewJobBidModal'));
                    modal.show();
                } else {
                    Swal.fire('Error', 'Failed to fetch job bid details.', 'error');
                }
            } catch (error) {
                Swal.fire('Error', 'Network error. Failed to fetch job bid details.', 'error');
            }
        });
    });
  });
</script>
@endpush