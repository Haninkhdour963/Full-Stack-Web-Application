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
                                <th>Client</th>
                                <th>Technician</th>
                                <th>Amount Min</th>
                                <th>Amount Max</th>
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
                                    <td>{{ $escrowPayment->client ? $escrowPayment->client->username : 'N/A' }}</td>
                                    <td>{{ $escrowPayment->technician ? $escrowPayment->technician->username : 'N/A' }}</td>
                                    <td>${{ number_format($escrowPayment->amount_min, 2) }}</td>
                                    <td>${{ number_format($escrowPayment->amount_max, 2) }}</td>
                                    <td>{{ ucfirst($escrowPayment->status) }}</td>
                                    <td>{{ $escrowPayment->created_at ? $escrowPayment->created_at->format('Y-m-d H:i:s') : 'N/A' }}</td>
                                    <td>{{ $escrowPayment->updated_at ? $escrowPayment->updated_at->format('Y-m-d H:i:s') : 'N/A' }}</td>
                                    <td>
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
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.soft-delete-btn').forEach(button => {
            button.addEventListener('click', async () => {
                const escrowPaymentId = button.getAttribute('data-id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This action will soft delete the escrow payment!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, soft delete it!',
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        try {
                            const response = await fetch(`/admin/escrowPayments/${escrowPaymentId}/soft-delete`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Content-Type': 'application/json',
                                }
                            });

                            if (response.ok) {
                                const data = await response.json();
                                if (data.success) {
                                    Swal.fire('Deleted!', 'Escrow payment has been soft deleted.', 'success');
                                    const row = document.querySelector(`#escrowPayment-row-${escrowPaymentId}`);
                                    row.classList.add('text-muted');
                                    button.disabled = true;
                                    button.innerText = 'Deleted';
                                } else {
                                    Swal.fire('Error', 'Failed to delete escrow payment.', 'error');
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
