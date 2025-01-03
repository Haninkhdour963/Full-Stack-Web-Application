@extends('layouts.master')

@section('title', 'My Profile')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">My Profile</h4>
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
                                <td>{{ $technician->user->name }}</td>
                                <td>{{ $technician->user->email }}</td>
                                <td>
                                    @if($technician->user->profile_image)
                                        <img src="{{ asset('storage/' . $technician->user->profile_image) }}" alt="User Image" style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <span>No Image</span>
                                    @endif
                                </td>
                                <td>{{ $technician->user->mobile_phone }}</td>
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
        // View Details Button
        document.querySelectorAll('.view-details-btn').forEach(button => {
            button.addEventListener('click', async () => {
                const technicianId = button.getAttribute('data-id');

                try {
                    // Fetch technician data from the server
                    const response = await fetch(`/technician/technicians/${technicianId}`);
                    const technicianData = await response.json();

                    // Display the technician data in a SweetAlert modal
                    Swal.fire({
                        title: `Technician Details: ${technicianData.user.name}`,
                        html: `
                            <strong>Email:</strong> ${technicianData.user.email} <br>
                            <strong>Mobile Phone:</strong> ${technicianData.user.mobile_phone} <br>
                            <strong>Identity Number:</strong> ${technicianData.identity_number} <br>
                            <strong>Skills:</strong> ${technicianData.skills} <br>
                            <strong>Hourly Rate:</strong> $${technicianData.hourly_rate} <br>
                            <strong>Rating:</strong> ${technicianData.rating} <br>
                            <strong>Location:</strong> ${technicianData.location} <br>
                            ${technicianData.user.profile_image ? `<img src="{{ asset('storage/') }}/${technicianData.user.profile_image}" alt="User Image" style="width: 100px; height: 100px; object-fit: cover;">` : '<span>No Image</span>'}
                        `,
                        confirmButtonText: 'Close'
                    });
                } catch (error) {
                    Swal.fire('Error', 'Failed to fetch technician data. Please try again later.', 'error');
                }
            });
        });

        // Update Button
        document.querySelectorAll('.update-btn').forEach(button => {
            button.addEventListener('click', async () => {
                const technicianId = button.getAttribute('data-id');

                try {
                    // Fetch technician data from the server
                    const response = await fetch(`/technician/technicians/${technicianId}`);
                    const technicianData = await response.json();

                    // Display the update form in a SweetAlert modal
                    Swal.fire({
                        title: `Update Technician: ${technicianData.user.name}`,
                        html: `
                            <form id="updateForm">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" id="name" class="form-control" value="${technicianData.user.name}" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" id="email" class="form-control" value="${technicianData.user.email}" required>
                                </div>
                                <div class="form-group">
                                    <label for="mobile_phone">Mobile Phone</label>
                                    <input type="text" id="mobile_phone" class="form-control" value="${technicianData.user.mobile_phone}" required>
                                </div>
                                <div class="form-group">
                                    <label for="identity_number">Identity Number</label>
                                    <input type="text" id="identity_number" class="form-control" value="${technicianData.identity_number}" required>
                                </div>
                                <div class="form-group">
                                    <label for="skills">Skills</label>
                                    <input type="text" id="skills" class="form-control" value="${technicianData.skills}" required>
                                </div>
                                <div class="form-group">
                                    <label for="hourly_rate">Hourly Rate</label>
                                    <input type="number" id="hourly_rate" class="form-control" value="${technicianData.hourly_rate}" required>
                                </div>
                                <div class="form-group">
                                    <label for="rating">Rating</label>
                                    <input type="number" id="rating" class="form-control" value="${technicianData.rating}" required>
                                </div>
                                <div class="form-group">
                                    <label for="location">Location</label>
                                    <input type="text" id="location" class="form-control" value="${technicianData.location}" required>
                                </div>
                            </form>
                        `,
                        showCancelButton: true,
                        confirmButtonText: 'Update',
                        preConfirm: async () => {
                            const name = Swal.getPopup().querySelector('#name').value;
                            const email = Swal.getPopup().querySelector('#email').value;
                            const mobile_phone = Swal.getPopup().querySelector('#mobile_phone').value;
                            const identity_number = Swal.getPopup().querySelector('#identity_number').value;
                            const skills = Swal.getPopup().querySelector('#skills').value;
                            const hourly_rate = Swal.getPopup().querySelector('#hourly_rate').value;
                            const rating = Swal.getPopup().querySelector('#rating').value;
                            const location = Swal.getPopup().querySelector('#location').value;

                            try {
                                const response = await fetch(`/technician/technicians/${technicianId}`, {
                                    method: 'PUT',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        name,
                                        email,
                                        mobile_phone,
                                        identity_number,
                                        skills,
                                        hourly_rate,
                                        rating,
                                        location
                                    })
                                });

                                const result = await response.json();

                                if (response.ok) {
                                    Swal.fire('Success', result.message, 'success');
                                    // Update the UI without reloading the page
                                    const row = document.getElementById(`technician-row-${technicianId}`);
                                    row.querySelector('td:nth-child(1)').textContent = name;
                                    row.querySelector('td:nth-child(2)').textContent = email;
                                    row.querySelector('td:nth-child(4)').textContent = mobile_phone;
                                } else {
                                    Swal.fire('Error', result.error, 'error');
                                }
                            } catch (error) {
                                Swal.fire('Error', 'Failed to update technician. Please try again later.', 'error');
                            }
                        }
                    });
                } catch (error) {
                    Swal.fire('Error', 'Failed to fetch technician data. Please try again later.', 'error');
                }
            });
        });
    });
</script>
@endpush