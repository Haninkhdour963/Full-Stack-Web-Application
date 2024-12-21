@extends('layouts.master')

@section('title', 'User Profile')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">My Profile: {{ $user->name }}</h4>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                              
                                <th>Name</th>
                                <th>Email</th>
                                <th>Profile Image</th>
                                <th>Phone Number</th>
                                <th>Mobile Phone</th>
                                <th>Location</th>

                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="userTableBody">
                            <tr id="user-row-{{ $user->id }}" class="{{ $user->deleted_at ? 'text-muted' : '' }}">
                               
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                               
                                <td>
                                    @if($user->profile_image)
                                        <img src="{{ asset('storage/' . $user->profile_image) }}" alt="User Image" style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <span>No Image</span>
                                    @endif
                                </td>

                                <td>{{ $user->phone_number }}</td>
                                <td>{{ $user->mobile_phone }}</td>
                                <td>{{ $user->location }}</td>
                                <td>
                                    <button class="btn btn-primary btn-sm view-btn" data-id="{{ $user->id }}">Edit</button>
                                    @if($user->deleted_at)
                                        <button class="btn btn-danger btn-sm" disabled>Deleted</button>
                                    @else
                                        <button class="btn btn-danger btn-sm soft-delete-btn" data-id="{{ $user->id }}">Delete</button>
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

<!-- Modal to display user details -->
<div class="modal fade" id="userDetailsModal" tabindex="-1" role="dialog" aria-labelledby="userDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userDetailsModalLabel">User Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>Name:</strong> <span id="modalUserName"></span></p>
                <p><strong>Email:</strong> <span id="modalUserEmail"></span></p>
                <p><strong>Phone Number:</strong> <span id="modalUserPhoneNumber"></span></p>
                <p><strong>Location:</strong> <span id="modalUserLocation"></span></p>
                <p><strong>Gender:</strong> <span id="modalUserGender"></span></p>
                <p><strong>Age:</strong> <span id="modalUserAge"></span></p>
                <p><strong>Address:</strong> <span id="modalUserAddress"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        let isModalOpen = false; // Track if the modal is open

        // Handle "View" button click
        document.querySelectorAll('.view-btn').forEach(button => {
            button.addEventListener('click', async () => {
                const userId = button.getAttribute('data-id');

                // Check if modal is already open
                if (isModalOpen) {
                    // If modal is open, close it and reset the flag
                    $('#userDetailsModal').modal('hide');
                    isModalOpen = false;
                } else {
                    try {
                        // Check the URL being requested
                        console.log(`Fetching user details from: /client/users/${userId}/view-profile`);
                        const response = await fetch(`/client/users/${userId}/view-profile`);

                        // If the response is not ok, log it and throw an error
                        if (!response.ok) {
                            console.error('Failed to fetch user details: ', response.statusText);
                            throw new Error('Failed to fetch user data');
                        }

                        const user = await response.json();

                        // Check the returned user object
                        console.log('User data:', user);

                        // Update modal content with the user details
                        document.getElementById('modalUserName').textContent = user.name;
                        document.getElementById('modalUserEmail').textContent = user.email;
                        document.getElementById('modalUserPhoneNumber').textContent = user.phone_number || 'N/A';
                        document.getElementById('modalUserLocation').textContent = user.location || 'N/A';
                        document.getElementById('modalUserGender').textContent = user.gender || 'N/A';
                        document.getElementById('modalUserAge').textContent = user.age || 'N/A';
                        document.getElementById('modalUserAddress').textContent = user.address || 'N/A';

                        // Show the modal
                        $('#userDetailsModal').modal('show');
                        isModalOpen = true; // Set the flag to true as modal is open now
                    } catch (error) {
                        console.error('Error fetching user details:', error);
                        Swal.fire('Error', 'Failed to load user details. Please check the console for more details.', 'error');
                    }
                }
            });
        });

        // Handle soft delete for the current user (if applicable)
        document.querySelectorAll('.soft-delete-btn').forEach(button => {
            button.addEventListener('click', async () => {
                const userId = button.getAttribute('data-id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This action will soft delete the user!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, soft delete it!',
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        try {
                            const response = await fetch(`/client/users/${userId}/soft-delete`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Content-Type': 'application/json',
                                }
                            });

                            if (response.ok) {
                                const data = await response.json();
                                if (data.success) {
                                    Swal.fire('Deleted!', 'User has been soft deleted.', 'success');
                                    const row = document.querySelector(`#user-row-${userId}`);
                                    row.classList.add('text-muted');
                                    button.disabled = true;
                                    button.innerText = 'Deleted';
                                } else {
                                    Swal.fire('Error', 'Failed to delete user.', 'error');
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
