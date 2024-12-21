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
                                <th>Description</th>
                                <th>Category</th>
                                <th>Client</th>
                                <th>Location</th>
                                <th>Budget</th>
                                <th>Status</th>
                                <th>Posted At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="jobPostingsTableBody">
                            @foreach($jobPostings as $jobPosting)
                                <tr id="jobPosting-row-{{ $jobPosting->id }}" class="{{ $jobPosting->deleted_at ? 'text-muted' : '' }}">
                                    <td>{{ $jobPosting->title }}</td>
                                    <td>{{ Str::limit($jobPosting->description, 50) }}</td>
                                    <td>{{ $jobPosting->category->category_name ?? 'N/A' }}</td>
                                    <td>{{ $jobPosting->client->name ?? 'N/A' }}</td>
                                    <td>{{ $jobPosting->location }}</td>
                                    <td>${{ number_format($jobPosting->budget_min, 2) }} - ${{ number_format($jobPosting->budget_max, 2) }}</td>
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
                                    <td>{{ $jobPosting->posted_at ? $jobPosting->posted_at->format('Y-m-d H:i:s') : 'N/A' }}</td>
                                    <td>
                                        @if($jobPosting->deleted_at)
                                            <button class="btn btn-danger btn-sm" disabled>Deleted</button>
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
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
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
    });
</script>
@endpush
