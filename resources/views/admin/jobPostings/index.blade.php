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
    document.querySelectorAll('.soft-delete-btn').forEach(button => {
        button.addEventListener('click', async () => {
            const jobPostingId = button.getAttribute('data-id');
            
            try {
                // إرسال طلب لحذف الـ Job Posting باستخدام الـ Soft Delete
                const response = await fetch(`/admin/jobPostings/${jobPostingId}/soft-delete`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();
                
                if (response.ok && data.success) {
                    // عرض رسالة نجاح باستخدام SweetAlert
                    Swal.fire('Success', 'Job posting soft deleted successfully.', 'success');

                    // تحديث الواجهة بدون عمل ريفرش (إخفاء الوظيفة المحذوفة أو تعديل الستايل)
                    const row = document.getElementById(`jobPosting-row-${jobPostingId}`);
                    row.classList.add('text-muted');  // إضافة "text-muted" لجعل الوظيفة تبدو محذوفة
                    row.querySelector('.soft-delete-btn').setAttribute('disabled', 'true'); // تعطيل زر الحذف
                    row.querySelector('.soft-delete-btn').innerText = 'Deleted'; // تغيير نص الزر
                } else {
                    Swal.fire('Error', 'Failed to soft delete the job posting.', 'error');
                }
            } catch (error) {
                Swal.fire('Error', 'Network error. Failed to soft delete the job posting.', 'error');
            }
        });
    });
});


</script>
@endpush
