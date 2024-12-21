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
                                <th>Name</th>
                                <th>Email</th>
                                <th>Image Profile</th>
                                <th>Mobile Phone</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="technicianTableBody">
                          
                        <tr id="technician-row-{{ $technician->id }}" class="{{ $technician->deleted_at ? 'text-muted' : '' }}">
        <td>{{ $technician->name }}</td>
        <td>{{ $technician->email }}</td>
        <td>
            @if($technician->profile_image)
                <img src="{{ asset('storage/' . $technician->profile_image) }}" alt="User Image" style="width: 50px; height: 50px; object-fit: cover;">
            @else
                <span>No Image</span>
            @endif
        </td>
        <td>{{ $technician->mobile_phone }}</td>
        <td>
            <button class="btn btn-info btn-sm view-details-btn" data-id="{{ $technician->id }}">View</button>
            @if($technician->deleted_at)
                <button class="btn btn-danger btn-sm" disabled>Deleted</button>
            @else
                <button class="btn btn-danger btn-sm soft-delete-btn" data-id="{{ $technician->id }}">Delete</button>
            @endif
        </td>
    </tr>
    <tr id="technician-details-{{ $technician->id }}" class="technician-details-row" style="display:none;">
        <td colspan="5">
            <strong>Identity Number:</strong> {{ $technician->identity_number }} <br>
            <strong>Skills:</strong> {{ $technician->skills }} <br>
            <strong>Hourly Rate:</strong> ${{ number_format($technician->hourly_rate, 2) }} <br>
            <strong>Rating:</strong> {{ $technician->rating }} <br>
            <strong>Location:</strong> {{ $technician->location }}
        </td>
    </tr>
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
        document.querySelectorAll('.view-details-btn').forEach(button => {
            button.addEventListener('click', () => {
                const technicianId = button.getAttribute('data-id');
                const detailsRow = document.querySelector(`#technician-details-${technicianId}`);

                // Toggle visibility of the details row
                if (detailsRow.style.display === 'none') {
                    detailsRow.style.display = 'table-row';
                } else {
                    detailsRow.style.display = 'none';
                }
            });
        });

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
                            const response = await fetch(`/technician/technicians/${technicianId}/soft-delete`, {

    method: 'POST',
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'Content-Type': 'application/json',
    }
});


                            const data = await response.json();
                            if (data.success) {
                                Swal.fire('Deleted!', 'Technician has been soft deleted.', 'success');
                                const row = document.querySelector(`#technician-row-${technicianId}`);
                                row.classList.add('text-muted');
                                button.disabled = true;
                                button.innerText = 'Deleted';
                            } else {
                                Swal.fire('Error', data.message || 'Failed to delete technician.', 'error');
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
