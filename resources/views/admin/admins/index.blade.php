@extends('layouts.master')

@section('title', 'Admin List')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Admin List</h4>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User ID</th>
                                <th>Additional Info</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="adminTableBody">
                            @foreach($admins as $admin)
                                <tr id="admin-row-{{ $admin->id }}" class="{{ $admin->deleted_at ? 'text-muted' : '' }}">
                                    <td>{{ $admin->id }}</td>
                                    <td>{{ $admin->user_id }}</td>
                                    <td>{{ $admin->additional_info }}</td>
                                    <td>{{ $admin->created_at ? $admin->created_at->format('Y-m-d H:i:s') : 'N/A' }}</td>
                                    <td>{{ $admin->updated_at ? $admin->updated_at->format('Y-m-d H:i:s') : 'N/A' }}</td>
                                    <td>
                                        @if($admin->deleted_at)
                                            <span class="badge badge-danger">Deleted</span>
                                        @else
                                            <span class="badge badge-success">Active</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-info btn-sm view-btn" data-id="{{ $admin->id }}">View</button>
                                        @if($admin->deleted_at)
                                            <button class="btn btn-success btn-sm restore-btn" data-id="{{ $admin->id }}">Restore</button>
                                        @else
                                            <button class="btn btn-danger btn-sm soft-delete-btn" data-id="{{ $admin->id }}">Soft Delete</button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Pagination Links -->
                    <div class="d-flex justify-content-center">
                        {{ $admins->links('vendor.pagination.custom') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- View Admin Modal -->
<div class="modal fade" id="viewAdminModal" tabindex="-1" aria-labelledby="viewAdminModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewAdminModalLabel">Admin Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="adminDetails">
                <!-- Admin details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Function to update the row after soft delete
        const updateRowAfterSoftDelete = (adminId) => {
            const row = document.querySelector(`#admin-row-${adminId}`);
            if (row) {
                row.classList.add('text-muted');
                const statusBadge = row.querySelector('td:nth-child(6) span');
                if (statusBadge) {
                    statusBadge.classList.remove('badge-success');
                    statusBadge.classList.add('badge-danger');
                    statusBadge.innerText = 'Deleted';
                }
                const actionButton = row.querySelector('td:nth-child(7) button.soft-delete-btn');
                if (actionButton) {
                    actionButton.classList.remove('btn-danger', 'soft-delete-btn');
                    actionButton.classList.add('btn-success', 'restore-btn');
                    actionButton.innerText = 'Restore';
                    actionButton.setAttribute('onclick', `restoreAdmin(${adminId})`);
                }
            }
        };

        // Function to update the row after restore
        const updateRowAfterRestore = (adminId) => {
            const row = document.querySelector(`#admin-row-${adminId}`);
            if (row) {
                row.classList.remove('text-muted');
                const statusBadge = row.querySelector('td:nth-child(6) span');
                if (statusBadge) {
                    statusBadge.classList.remove('badge-danger');
                    statusBadge.classList.add('badge-success');
                    statusBadge.innerText = 'Active';
                }
                const actionButton = row.querySelector('td:nth-child(7) button.restore-btn');
                if (actionButton) {
                    actionButton.classList.remove('btn-success', 'restore-btn');
                    actionButton.classList.add('btn-danger', 'soft-delete-btn');
                    actionButton.innerText = 'Soft Delete';
                    actionButton.setAttribute('onclick', `softDeleteAdmin(${adminId})`);
                }
            }
        };

        // Soft Delete Function
        window.softDeleteAdmin = async (adminId) => {
            Swal.fire({
                title: 'Are you sure?',
                text: 'This action will soft delete the admin!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, soft delete it!',
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        const response = await fetch(`/admin/admins/${adminId}/softDelete`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Content-Type': 'application/json',
                            }
                        });

                        if (response.ok) {
                            const data = await response.json();
                            if (data.success) {
                                Swal.fire('Deleted!', 'Admin has been soft deleted.', 'success');
                                updateRowAfterSoftDelete(adminId);
                            } else {
                                Swal.fire('Error', 'Failed to delete admin.', 'error');
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

        // Restore Function
        window.restoreAdmin = async (adminId) => {
            Swal.fire({
                title: 'Are you sure?',
                text: 'This action will restore the admin!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, restore it!',
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        const response = await fetch(`/admin/admins/${adminId}/restore`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Content-Type': 'application/json',
                            }
                        });

                        if (response.ok) {
                            const data = await response.json();
                            if (data.success) {
                                Swal.fire('Restored!', 'Admin has been restored.', 'success');
                                updateRowAfterRestore(adminId);
                            } else {
                                Swal.fire('Error', 'Failed to restore admin.', 'error');
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

        // Function to handle the "View" button click
        const handleViewAdmin = async (adminId) => {
            try {
                const response = await fetch(`/admin/admins/${adminId}`, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    if (data.success) {
                        const admin = data.admin;
                        const formatDate = (dateString) => {
                            if (!dateString) return 'N/A';
                            const date = new Date(dateString);
                            return date.toLocaleString();
                        };

                        const adminDetails = `
                            <p><strong>ID:</strong> ${admin.id}</p>
                            <p><strong>User ID:</strong> ${admin.user_id}</p>
                            <p><strong>Additional Info:</strong> ${admin.additional_info}</p>
                            <p><strong>Created At:</strong> ${formatDate(admin.created_at)}</p>
                            <p><strong>Updated At:</strong> ${formatDate(admin.updated_at)}</p>
                            <p><strong>Status:</strong> ${admin.deleted_at ? 'Deleted' : 'Active'}</p>
                        `;
                        document.getElementById('adminDetails').innerHTML = adminDetails;
                        $('#viewAdminModal').modal('show');
                    } else {
                        Swal.fire('Error', 'Failed to fetch admin details.', 'error');
                    }
                } else {
                    Swal.fire('Error', 'Failed to communicate with the server.', 'error');
                }
            } catch (error) {
                Swal.fire('Error', 'Network error. Failed to communicate with the server.', 'error');
            }
        };

        // Attach event listeners to buttons
        document.querySelectorAll('.soft-delete-btn').forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const adminId = button.getAttribute('data-id');
                softDeleteAdmin(adminId);
            });
        });

        document.querySelectorAll('.restore-btn').forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const adminId = button.getAttribute('data-id');
                restoreAdmin(adminId);
            });
        });

        document.querySelectorAll('.view-btn').forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const adminId = button.getAttribute('data-id');
                handleViewAdmin(adminId);
            });
        });
    });
</script>
@endpush