@extends('layouts.master')

@section('title', 'User List')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">User List</h4>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Profile Image</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="userTableBody">
                            @foreach($users as $user)
                                <tr id="user-row-{{ $user->id }}" class="{{ $user->deleted_at ? 'text-muted' : '' }}">
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ ucfirst($user->user_role) }}</td>
                                    <td>
                                        @if($user->profile_image)
                                            <img src="{{ asset('storage/' . $user->profile_image) }}" alt="Profile Image" style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                            <img src="{{ asset('images/default-profile-image.jpg') }}" alt="Default Profile Image" style="width: 50px; height: 50px; object-fit: cover;">
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->deleted_at)
                                            <button class="btn btn-success btn-sm restore-btn" data-id="{{ $user->id }}">Restore</button>
                                        @else
                                            <button class="btn btn-info btn-sm view-btn" data-id="{{ $user->id }}">View</button>
                                            <button class="btn btn-danger btn-sm soft-delete-btn" data-id="{{ $user->id }}">Delete</button>
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

<!-- Modal for viewing user details -->
<div class="modal fade" id="viewUserModal" tabindex="-1" aria-labelledby="viewUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewUserModalLabel">User Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>ID:</strong> <span id="user-id"></span></p>
                <p><strong>Name:</strong> <span id="user-name"></span></p>
                <p><strong>Email:</strong> <span id="user-email"></span></p>
                <p><strong>Role:</strong> <span id="user-role"></span></p>
                <p><strong>Location:</strong> <span id="user-location"></span></p>
                <p><strong>Phone Number:</strong> <span id="user-phone-number"></span></p>
                <p><strong>Mobile Phone:</strong> <span id="user-mobile-phone"></span></p>
                <p><strong>Profile Image:</strong> 
                    <img id="user-profile-image" src="" alt="Profile Image" class="img-fluid" />
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Pagination Links -->
<div class="d-flex justify-content-center">
    {{ $users->links('vendor.pagination.custom') }}
</div>
@endsection

@push('scripts')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Handle soft delete button click
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
                            const response = await fetch(`/admin/users/${userId}/softDelete`, {
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

                                    // Replace the "Delete" button with a "Restore" button
                                    const restoreButton = document.createElement('button');
                                    restoreButton.className = 'btn btn-success btn-sm restore-btn';
                                    restoreButton.setAttribute('data-id', userId);
                                    restoreButton.innerText = 'Restore';
                                    restoreButton.addEventListener('click', () => handleRestore(userId));
                                    button.replaceWith(restoreButton);
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

        // Handle restore button click
        document.querySelectorAll('.restore-btn').forEach(button => {
            button.addEventListener('click', () => handleRestore(button.getAttribute('data-id')));
        });

        // Function to handle restore
        const handleRestore = async (userId) => {
            Swal.fire({
                title: 'Are you sure?',
                text: 'This action will restore the user!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, restore it!',
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        const response = await fetch(`/admin/users/${userId}/restore`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Content-Type': 'application/json',
                            }
                        });

                        if (response.ok) {
                            const data = await response.json();
                            if (data.success) {
                                Swal.fire('Restored!', 'User has been restored.', 'success');
                                const row = document.querySelector(`#user-row-${userId}`);
                                row.classList.remove('text-muted');

                                // Replace the "Restore" button with a "Delete" button
                                const deleteButton = document.createElement('button');
                                deleteButton.className = 'btn btn-danger btn-sm soft-delete-btn';
                                deleteButton.setAttribute('data-id', userId);
                                deleteButton.innerText = 'Delete';
                                deleteButton.addEventListener('click', () => handleSoftDelete(userId));
                                row.querySelector('.restore-btn').replaceWith(deleteButton);
                            } else {
                                Swal.fire('Error', 'Failed to restore user.', 'error');
                            }
                        } else {
                            Swal.fire('Error', 'Failed to communicate with the server.', 'error');
                        }
                    } catch (error) {
                        Swal.fire('Error', 'Network error. Failed to communicate with the server.', 'error');
                    }
                }
            });
        };

        // Handle view button click
        document.querySelectorAll('.view-btn').forEach(button => {
            button.addEventListener('click', async () => {
                const userId = button.getAttribute('data-id');

                try {
                    // Fetch user details via AJAX request
                    const response = await fetch(`/admin/users/${userId}`);
                    if (response.ok) {
                        const user = await response.json();  // Assuming you return user data as JSON

                        // Populate the modal with user details
                        document.getElementById('user-id').textContent = user.id;
                        document.getElementById('user-name').textContent = user.name;
                        document.getElementById('user-email').textContent = user.email;
                        document.getElementById('user-role').textContent = user.user_role;
                        document.getElementById('user-location').textContent = user.location;
                        document.getElementById('user-phone-number').textContent = user.phone_number;
                        document.getElementById('user-mobile-phone').textContent = user.mobile_phone;

                        // Check if profile image exists and display it
                        const profileImageUrl = user.profile_image || 'default-profile-image.jpg'; // Default if no profile image
                        document.getElementById('user-profile-image').src = profileImageUrl;

                        // Show the modal
                        const viewModal = new bootstrap.Modal(document.getElementById('viewUserModal'));
                        viewModal.show();
                    } else {
                        Swal.fire('Error', 'Failed to fetch user details.', 'error');
                    }
                } catch (error) {
                    Swal.fire('Error', 'Network error. Failed to fetch user details.', 'error');
                }
            });
        });
    });
</script>
@endpush