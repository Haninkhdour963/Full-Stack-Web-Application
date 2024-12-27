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
                                        <button class="btn btn-warning btn-sm update-btn" data-id="{{ $technician->id }}">Update</button>
                                    @endif
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
            button.addEventListener('click', async () => {
                const technicianId = button.getAttribute('data-id');

                try {
                    // Fetch technician data from the server
                    const response = await fetch(`/technician/technicians/${technicianId}`);
                    const technicianData = await response.json();

                    // Display the technician data in a SweetAlert modal
                    Swal.fire({
                        title: `Technician Details: ${technicianData.name}`,
                        html: `
                            <strong>Email:</strong> ${technicianData.email} <br>
                            <strong>Mobile Phone:</strong> ${technicianData.mobile_phone} <br>
                            <strong>Identity Number:</strong> ${technicianData.identity_number} <br>
                            <strong>Skills:</strong> ${technicianData.skills} <br>
                            <strong>Hourly Rate:</strong> $${technicianData.hourly_rate} <br>
                            <strong>Rating:</strong> ${technicianData.rating} <br>
                            <strong>Location:</strong> ${technicianData.location} <br>
                            ${technicianData.profile_image ? `<img src="${technicianData.profile_image}" alt="User Image" style="width: 100px; height: 100px; object-fit: cover;">` : '<span>No Image</span>'}
                        `,
                        confirmButtonText: 'Close'
                    });
                } catch (error) {
                    Swal.fire('Error', 'Failed to fetch technician data. Please try again later.', 'error');
                }
            });
        });
    });
</script>
@endpush
