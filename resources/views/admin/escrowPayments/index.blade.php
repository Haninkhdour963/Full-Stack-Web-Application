@extends('layouts.master')

@section('title', 'Escrow Payments List')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Escrow Payments List</h4>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Job Posting</th>
                                
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="escrowPaymentTableBody">
                            @foreach($escrowPayments as $escrowPayment)
                                <tr id="escrowPayment-row-{{ $escrowPayment->id }}" class="{{ $escrowPayment->deleted_at ? 'text-muted' : '' }}">
                                    <td>{{ $escrowPayment->id }}</td>
                                    <td>{{ $escrowPayment->job ? $escrowPayment->job->title : 'N/A' }}</td>
                                    
                                    <td>{{ ucfirst($escrowPayment->status) }}</td>
                                    <td>{{ $escrowPayment->created_at ? $escrowPayment->created_at->format('Y-m-d H:i:s') : 'N/A' }}</td>
                                    <td>{{ $escrowPayment->updated_at ? $escrowPayment->updated_at->format('Y-m-d H:i:s') : 'N/A' }}</td>
                                    <td>
                                        <button class="btn btn-info btn-sm view-btn" data-id="{{ $escrowPayment->id }}">View</button>
                                        @if($escrowPayment->deleted_at)
                                            <button class="btn btn-danger btn-sm" disabled>Deleted</button>
                                        @else
                                            <button class="btn btn-danger btn-sm soft-delete-btn" data-id="{{ $escrowPayment->id }}">Soft Delete</button>
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

<!-- Modal for Viewing Escrow Payment Details -->
<div class="modal fade" id="escrowPaymentModal" tabindex="-1" aria-labelledby="escrowPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="escrowPaymentModalLabel">Escrow Payment Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="escrowPaymentDetails">
                    <!-- Details will be populated here -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Pagination Links -->
<div class="d-flex justify-content-center">
                    {{ $escrowPayments->links('vendor.pagination.custom') }}  <!-- This generates the pagination links -->
                </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Handle soft delete button click (unchanged)
    document.querySelectorAll('.soft-delete-btn').forEach(button => {
        button.addEventListener('click', async () => {
            // Soft delete logic
        });
    });

    // Handle view button click
    document.querySelectorAll('.view-btn').forEach(button => {
        button.addEventListener('click', async () => {
            const escrowPaymentId = button.getAttribute('data-id');

            try {
                const response = await fetch(`/admin/escrowPayments/${escrowPaymentId}/view`);
                const data = await response.json();

                if (data.success) {
                    const escrowPayment = data.data;

                    // Populate modal with escrow payment details
                    let detailsHtml = `
                        <p><strong>Escrow Payment ID:</strong> ${escrowPayment.id}</p>
                        <p><strong>Job Posting:</strong> ${escrowPayment.job ? escrowPayment.job.title : 'N/A'}</p>
                        <p><strong>Client:</strong> ${escrowPayment.client ? escrowPayment.client.username : 'N/A'}</p>
                        <p><strong>Technician:</strong> ${escrowPayment.technician ? escrowPayment.technician.username : 'N/A'}</p>
                        <p><strong>Amount Min:</strong> $${escrowPayment.amount_min}</p>
                        <p><strong>Amount Max:</strong> $${escrowPayment.amount_max}</p>
                        <p><strong>Status:</strong> ${escrowPayment.status}</p>
                        <p><strong>Created At:</strong> ${escrowPayment.created_at}</p>
                        <p><strong>Updated At:</strong> ${escrowPayment.updated_at}</p>
                    `;

                    document.getElementById('escrowPaymentDetails').innerHTML = detailsHtml;

                    // Show modal
                    $('#escrowPaymentModal').modal('show');
                } else {
                    Swal.fire('Error', 'Failed to fetch escrow payment details.', 'error');
                }
            } catch (error) {
                Swal.fire('Error', 'Network error. Failed to fetch details.', 'error');
            }
        });
    });
});
</script>
@endpush
