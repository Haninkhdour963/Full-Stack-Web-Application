@extends('layouts.master')

@section('title', 'Technician List')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Technician List</h4>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Identity Number</th>
                                <th>Skills</th>
                                <th>Hourly Rate</th>
                                <th>Rating</th>
                                <th>Location</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="technicianTableBody">
                            @foreach($technicians as $technician)
                                <tr id="technician-row-{{ $technician->id }}" class="{{ $technician->deleted_at ? 'text-muted' : '' }}">
                                    <td>{{ $technician->id }}</td>
                                    <td>{{ $technician->identity_number }}</td>
                                    <td>{{ $technician->skills }}</td>
                                    <td>${{ number_format($technician->hourly_rate, 2) }}</td>
                                    <td>{{ $technician->rating }}</td>
                                    <td>{{ $technician->location }}</td>
                                    <td>
                                        <button class="btn btn-info btn-sm view-btn" data-id="{{ $technician->id }}">View</button>
                                        @if($technician->deleted_at)
                                            <button class="btn btn-danger btn-sm" disabled>Deleted</button>
                                        @else
                                            <button class="btn btn-danger btn-sm soft-delete-btn" data-id="{{ $technician->id }}">Delete</button>
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
    {{ $technicians->links('vendor.pagination.custom') }}
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Soft delete button
        document.querySelectorAll('.soft-delete-btn').forEach(button => {
            button.addEventListener('click', async () => {
                const technicianId = button.getAttribute('data-id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This action will soft delete the technician!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, soft delete it!',
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        try {
                            const response = await fetch(`/admin/technicians/${technicianId}/soft-delete`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Content-Type': 'application/json',
                                }
                            });

                            if (response.ok) {
                                const data = await response.json();
                                if (data.success) {
                                    Swal.fire('Deleted!', 'Technician has been soft deleted.', 'success');
                                    const row = document.querySelector(`#technician-row-${technicianId}`);
                                    row.classList.add('text-muted');
                                    button.disabled = true;
                                    button.innerText = 'Deleted';
                                } else {
                                    Swal.fire('Error', 'Failed to delete technician.', 'error');
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

        // View technician button
        document.querySelectorAll('.view-btn').forEach(button => {
            button.addEventListener('click', async () => {
                const technicianId = button.getAttribute('data-id');
                
                try {
                    const response = await fetch(`/admin/technicians/${technicianId}`);
                    
                    if (response.ok) {
                        const technician = await response.json();
                        Swal.fire({
                            title: `Technician Details - ${technician.name}`,
                            html: `
                                <strong>Identity Number:</strong> ${technician.identity_number}<br>
                                <strong>Skills:</strong> ${technician.skills}<br>
                                <strong>Hourly Rate:</strong> $${technician.hourly_rate}<br>
                                <strong>Rating:</strong> ${technician.rating}<br>
                                <strong>Location:</strong> ${technician.location}<br>
                                <strong>Bio:</strong> ${technician.bio}<br>
                                <strong>Certifications:</strong> ${technician.certifications}<br>
                                <strong>Available From:</strong> ${technician.available_from}<br>
                            `,
                            showCloseButton: true,
                        });
                    } else {
                        Swal.fire('Error', 'Failed to fetch technician details.', 'error');
                    }
                } catch (error) {
                    Swal.fire('Error', 'Network error. Failed to fetch technician details.', 'error');
                }
            });
        });
    });
</script>
@endpush
