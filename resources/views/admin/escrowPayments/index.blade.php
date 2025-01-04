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
                        <tbody>
                            @foreach($escrowPayments as $escrowPayment)
                                <tr id="escrowPayment-row-{{ $escrowPayment->id }}" class="{{ $escrowPayment->deleted_at ? 'text-muted' : '' }}">
                                    <td>{{ $escrowPayment->id }}</td>
                                    <td>{{ $escrowPayment->job ? $escrowPayment->job->title : 'N/A' }}</td>
                                    <td>{{ ucfirst($escrowPayment->status) }}</td>
                                    <td>{{ $escrowPayment->created_at ? $escrowPayment->created_at->format('Y-m-d H:i:s') : 'N/A' }}</td>
                                    <td>{{ $escrowPayment->updated_at ? $escrowPayment->updated_at->format('Y-m-d H:i:s') : 'N/A' }}</td>
                                    <td>
                                        <button class="btn btn-primary btn-sm view-btn" data-id="{{ $escrowPayment->id }}">View</button>
                                        <button class="btn {{ $escrowPayment->deleted_at ? 'btn-success' : 'btn-danger' }} btn-sm toggle-delete-btn" data-id="{{ $escrowPayment->id }}">
                                            {{ $escrowPayment->deleted_at ? 'Restore' : 'Soft Delete' }}
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
                    <!-- Dynamic content will be populated here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // View Button Logic - Show Modal with Escrow Payment Details
        document.querySelectorAll('.view-btn').forEach(button => {
            button.addEventListener('click', async () => {
                const escrowPaymentId = button.getAttribute('data-id');

                try {
                    const response = await fetch(`/admin/escrowPayments/${escrowPaymentId}/view`);
                    if (response.ok) {
                        const escrowPayment = await response.json();
                        let escrowPaymentDetails = `
                            <p><strong>ID:</strong> ${escrowPayment.data.id}</p>
                            <p><strong>Job Posting:</strong> ${escrowPayment.data.job}</p>
                            <p><strong>Technician:</strong> ${escrowPayment.data.technician}</p>
                            <p><strong>Client:</strong> ${escrowPayment.data.client}</p>
                            <p><strong>Status:</strong> ${escrowPayment.data.status}</p>
                            <p><strong>Created At:</strong> ${escrowPayment.data.created_at}</p>
                            <p><strong>Updated At:</strong> ${escrowPayment.data.updated_at}</p>
                        `;

                        document.getElementById('escrowPaymentDetails').innerHTML = escrowPaymentDetails;

                        // Show the modal using Bootstrap
                        var modal = new bootstrap.Modal(document.getElementById('escrowPaymentModal'));
                        modal.show();
                    } else {
                        Swal.fire('Error', 'Failed to fetch escrow payment details.', 'error');
                    }
                } catch (error) {
                    Swal.fire('Error', 'Network error. Failed to fetch details.', 'error');
                }
            });
        });

        // Toggle Delete/Restore Button Logic
        document.querySelectorAll('.toggle-delete-btn').forEach(button => {
            button.addEventListener('click', async () => {
                const escrowPaymentId = button.getAttribute('data-id');
                const isDeleted = button.classList.contains('btn-success'); // Check if the button is in "Restore" mode

                const action = isDeleted ? 'restore' : 'softDelete';
                const actionText = isDeleted ? 'restore' : 'soft delete';

                Swal.fire({
                    title: 'Are you sure?',
                    text: `This action will ${actionText} the escrow payment!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: `Yes, ${actionText} it!`,
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        try {
                            const response = await fetch(`/admin/escrowPayments/${escrowPaymentId}/${action}`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Content-Type': 'application/json',
                                }
                            });

                            if (response.ok) {
                                const data = await response.json();
                                if (data.success) {
                                    Swal.fire('Success!', `Escrow Payment has been ${actionText}d.`, 'success');

                                    // Update the button text and class
                                    if (isDeleted) {
                                        button.classList.remove('btn-success');
                                        button.classList.add('btn-danger');
                                        button.innerText = 'Soft Delete';
                                    } else {
                                        button.classList.remove('btn-danger');
                                        button.classList.add('btn-success');
                                        button.innerText = 'Restore';
                                    }

                                    // Update the row styling
                                    const row = document.querySelector(`#escrowPayment-row-${escrowPaymentId}`);
                                    if (isDeleted) {
                                        row.classList.remove('text-muted');
                                    } else {
                                        row.classList.add('text-muted');
                                    }
                                } else {
                                    Swal.fire('Error', `Failed to ${actionText} escrow payment.`, 'error');
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