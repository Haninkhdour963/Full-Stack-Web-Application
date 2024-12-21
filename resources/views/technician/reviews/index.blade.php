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
                const reviewId = button.getAttribute('data-id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This action will soft delete the review!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, soft delete it!',
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        try {
                            const response = await fetch(`/admin/reviews/${reviewId}/softDelete`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Content-Type': 'application/json',
                                }
                            });

                            if (response.ok) {
                                const data = await response.json();
                                if (data.success) {
                                    Swal.fire('Deleted!', 'Review has been soft deleted.', 'success');
                                    const row = document.querySelector(`#review-row-${reviewId}`);
                                    row.classList.add('text-muted');
                                    button.disabled = true;
                                    button.innerText = 'Deleted';
                                } else {
                                    Swal.fire('Error', 'Failed to delete review.', 'error');
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
                const reviewId = button.getAttribute('data-id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This action will restore the soft deleted review!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, restore it!',
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        try {
                            const response = await fetch(`/admin/reviews/${reviewId}/restore`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Content-Type': 'application/json',
                                }
                            });

                            if (response.ok) {
                                const data = await response.json();
                                if (data.success) {
                                    Swal.fire('Restored!', 'Review has been restored.', 'success');
                                    const row = document.querySelector(`#review-row-${reviewId}`);
                                    row.classList.remove('text-muted');
                                    button.disabled = true;
                                    button.innerText = 'Restored';
                                } else {
                                    Swal.fire('Error', 'Failed to restore review.', 'error');
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
