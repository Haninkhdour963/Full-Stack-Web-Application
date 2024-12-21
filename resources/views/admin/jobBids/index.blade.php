{{-- resources/views/admin/jobBids/index.blade.php --}}

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
                                    <td>{{ optional($jobBid->jobPosting)->title ?? 'N/A' }}</td>
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
                                        @if($jobBid->deleted_at)
                                            <button class="btn btn-danger btn-sm" disabled>Deleted</button>
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
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.soft-delete-btn').forEach(button => {
            button.addEventListener('click', async () => {
                const jobBidId = button.getAttribute('data-id');

                // Display confirmation dialog
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This action will soft delete the job bid!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, soft delete it!',
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        try {
                            const response = await fetch(`/admin/jobBids/${jobBidId}/soft-delete`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Content-Type': 'application/json',
                                },
                                body: JSON.stringify({ id: jobBidId }),  // Make sure to pass the jobBidId in the request body
                            });

                            if (response.ok) {
                                const data = await response.json();
                                if (data.success) {
                                    Swal.fire('Deleted!', 'Job bid has been soft deleted.', 'success');
                                    const row = document.querySelector(`#jobBid-row-${jobBidId}`);
                                    row.classList.add('text-muted');
                                    button.disabled = true;
                                    button.innerText = 'Deleted';
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
            });
        });
    });
</script>
@endpush
