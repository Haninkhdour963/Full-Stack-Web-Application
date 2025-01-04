@extends('layouts.master')

@section('title', 'Reviews List')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Reviews List</h4>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Job Title</th>
                                <th>Reviewer</th>
                                <th>Reviewee</th>
                                <th>Rating</th>
                                <th>Comment</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reviews as $review)
                                <tr id="review-row-{{ $review->id }}" class="{{ $review->deleted_at ? 'text-muted' : '' }}">
                                    <td>{{ $review->id }}</td>
                                    <td>{{ $review->job->title ?? 'N/A' }}</td>
                                    <td>{{ $review->reviewer->name ?? 'N/A' }}</td>
                                    <td>{{ $review->reviewee->name ?? 'N/A' }}</td>
                                    <td>{{ $review->rating }}</td>
                                    <td>{{ $review->comment }}</td>
                                    <td>
                                        @if($review->deleted_at)
                                            <span class="badge badge-danger">Deleted</span>
                                        @else
                                            <span class="badge badge-success">Active</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn {{ $review->deleted_at ? 'btn-success' : 'btn-danger' }} btn-sm toggle-delete-btn"
                                                data-id="{{ $review->id }}"
                                                data-status="{{ $review->deleted_at ? 'deleted' : 'active' }}">
                                            {{ $review->deleted_at ? 'Restore' : 'Soft Delete' }}
                                        </button>
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
    {{ $reviews->links('vendor.pagination.custom') }}
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.toggle-delete-btn').forEach(button => {
            button.addEventListener('click', async () => {
                const reviewId = button.getAttribute('data-id');
                const status = button.getAttribute('data-status');
                const action = status === 'deleted' ? 'restore' : 'softDelete';
                const url = `/admin/reviews/${reviewId}/${action}`;

                Swal.fire({
                    title: 'Are you sure?',
                    text: `This action will ${action === 'softDelete' ? 'soft delete' : 'restore'} the review!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: `Yes, ${action === 'softDelete' ? 'soft delete' : 'restore'} it!`,
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        try {
                            const response = await fetch(url, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Content-Type': 'application/json',
                                }
                            });

                            if (response.ok) {
                                const data = await response.json();
                                if (data.success) {
                                    Swal.fire('Success!', `Review has been ${action === 'softDelete' ? 'soft deleted' : 'restored'}.`, 'success');
                                    
                                    // Toggle button text and status
                                    const newStatus = status === 'deleted' ? 'active' : 'deleted';
                                    button.setAttribute('data-status', newStatus);
                                    button.innerText = newStatus === 'deleted' ? 'Restore' : 'Soft Delete';
                                    button.classList.toggle('btn-danger');
                                    button.classList.toggle('btn-success');

                                    // Update row styling
                                    const row = document.querySelector(`#review-row-${reviewId}`);
                                    row.classList.toggle('text-muted');

                                    // Update status badge
                                    const statusBadge = row.querySelector('td:nth-child(7) span');
                                    statusBadge.innerText = newStatus === 'deleted' ? 'Deleted' : 'Active';
                                    statusBadge.classList.toggle('badge-danger');
                                    statusBadge.classList.toggle('badge-success');
                                } else {
                                    Swal.fire('Error', 'Failed to update review status.', 'error');
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