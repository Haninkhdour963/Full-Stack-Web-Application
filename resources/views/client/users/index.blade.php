@extends('layouts.master')

@section('title', 'User Profile')

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
                                    <button class="btn btn-primary btn-sm view-btn" data-id="{{ $user->id }}">View</button>
                                    @if($user->deleted_at)
                                        <button class="btn btn-danger btn-sm" disabled>Deleted</button>
                                    @else
                                        <button class="btn btn-danger btn-sm soft-delete-btn" data-id="{{ $user->id }}">Delete</button>
                                    @endif
                                    <button class="btn btn-warning btn-sm update-btn" data-id="{{ $user->id }}">Update</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- View Profile Modal -->
<div class="modal fade" id="userDetailsModal" tabindex="-1" role="dialog" aria-labelledby="userDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userDetailsModalLabel">User Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Soft Delete Confirmation Modal -->
<div class="modal fade" id="softDeleteModal" tabindex="-1" role="dialog" aria-labelledby="softDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="softDeleteModalLabel">Confirm Soft Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to soft delete this user? This action is irreversible.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmSoftDelete">Confirm Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- Update User Modal -->
<div class="modal fade" id="updateUserModal" tabindex="-1" role="dialog" aria-labelledby="updateUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateUserModalLabel">Update User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <form id="updateUserForm">
    <input type="hidden" id="updateUserId" name="id">
    <div class="mb-3">
        <label for="updateUserName" class="form-label">Name</label>
        <input type="text" class="form-control" id="updateUserName" name="name" required>
    </div>
    <div class="mb-3">
        <label for="updateUserEmail" class="form-label">Email</label>
        <input type="email" class="form-control" id="updateUserEmail" name="email" required>
    </div>
    <!-- <div class="mb-3">
        <label for="updateUserRole" class="form-label">Role</label>
        <select class="form-select" id="updateUserRole" name="user_role" required>
           
            <option value="client">Client</option>
        
        </select>
    </div> -->
    <div class="mb-3">
        <label for="updateUserPassword" class="form-label">Password</label>
        <input type="password" class="form-control" id="updateUserPassword" name="password">
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
</form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // View User Details
    document.querySelectorAll('.view-btn').forEach(button => {
        button.addEventListener('click', async () => {
            const userId = button.getAttribute('data-id');
            try {
                const response = await fetch(`/client/users/${userId}/view-profile`);
                if (!response.ok) throw new Error('Failed to fetch user data');
                
                const user = await response.json();
                document.getElementById('modalUserName').textContent = user.name;
                document.getElementById('modalUserEmail').textContent = user.email;
                document.getElementById('modalUserPhoneNumber').textContent = user.phone_number || 'N/A';
                document.getElementById('modalUserLocation').textContent = user.location || 'N/A';
                document.getElementById('modalUserGender').textContent = user.gender || 'N/A';
                document.getElementById('modalUserAge').textContent = user.age || 'N/A';
                document.getElementById('modalUserAddress').textContent = user.address || 'N/A';
                
                $('#userDetailsModal').modal('show');
            } catch (error) {
                Swal.fire('Error', 'Failed to load user details', 'error');
            }
        });
    });

    // Soft Delete User
    let userIdToDelete;
    document.querySelectorAll('.soft-delete-btn').forEach(button => {
        button.addEventListener('click', () => {
            userIdToDelete = button.getAttribute('data-id');
            $('#softDeleteModal').modal('show');
        });
    });

    document.getElementById('confirmSoftDelete').addEventListener('click', async () => {
        try {
            const response = await fetch(`/client/users/${userIdToDelete}/softDelete`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                }
            });

            if (response.ok) {
                Swal.fire('Deleted', 'User has been soft deleted.', 'success');
                document.getElementById(`user-row-${userIdToDelete}`).classList.add('text-muted');
                $('#softDeleteModal').modal('hide');
            } else {
                Swal.fire('Error', 'Failed to delete user', 'error');
            }
        } catch (error) {
            Swal.fire('Error', 'Failed to communicate with server', 'error');
        }
    });

    // Update User
    document.querySelectorAll('.update-btn').forEach(button => {
        button.addEventListener('click', async () => {
            const userId = button.getAttribute('data-id');
            const response = await fetch(`/client/users/${userId}/view-profile`);
            const user = await response.json();

            document.getElementById('updateUserId').value = user.id;
            document.getElementById('updateUserName').value = user.name;
            document.getElementById('updateUserEmail').value = user.email;
        //    //

            $('#updateUserModal').modal('show');
        });
    });

    // Handle Update Form Submission
   // Handle Update Form Submission
document.getElementById('updateUserForm').addEventListener('submit', async (event) => {
    event.preventDefault();

    const formData = new FormData(event.target);
    const userId = formData.get('id');

    try {
        const response = await fetch(`/client/users/${userId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json', // Ensure the server knows we expect JSON
            },
            body: formData, // Send FormData directly
        });

        const result = await response.json(); // Parse the JSON response

        if (response.ok) {
            Swal.fire('Success', 'User updated successfully', 'success');
            $('#updateUserModal').modal('hide');

            // Optionally, update the user row in the table without reloading the page
            const userRow = document.getElementById(`user-row-${userId}`);
            if (userRow) {
                userRow.querySelector('td:nth-child(1)').textContent = formData.get('name');
                userRow.querySelector('td:nth-child(2)').textContent = formData.get('email');
                // Update other fields as needed
            }
        } else {
            Swal.fire('Error', result.error || 'Failed to update user', 'error');
        }
    } catch (error) {
        Swal.fire('Error', 'Failed to communicate with the server', 'error');
    }
});
});
</script>
@endpush