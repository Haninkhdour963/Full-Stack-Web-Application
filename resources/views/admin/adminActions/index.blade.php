@extends('layouts.master')

@section('title', 'Admin Actions List')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Admin Actions List</h4>
                <button class="btn btn-success mb-3" id="addAdminActionBtn">Add New Action</button>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Action Type</th>
                                <th>Description</th>
                                <th>Target User</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="adminActionTableBody">
                            @foreach($adminActions as $adminAction)
                                <tr id="admin-action-row-{{ $adminAction->id }}">
                                    <td>{{ $adminAction->id }}</td>
                                    <td>{{ $adminAction->action_type ?? 'No Action' }}</td>
                                    <td>{{ $adminAction->description ?? 'No Description' }}</td>
                                    <td>{{ optional($adminAction->targetUser)->name ?? 'No User' }}</td>
                                    <td>
                                        <button class="btn btn-primary btn-sm view-details-btn" data-id="{{ $adminAction->id }}">View</button>
                                        <button class="btn btn-warning btn-sm edit-btn" data-id="{{ $adminAction->id }}" data-action-type="{{ $adminAction->action_type }}" data-description="{{ $adminAction->description }}" data-target-user-id="{{ $adminAction->target_user_id }}">Edit</button>
                                        <button class="btn btn-danger btn-sm toggle-delete-btn" data-id="{{ $adminAction->id }}" data-deleted="{{ $adminAction->deleted_at ? '1' : '0' }}">
                                            {{ $adminAction->deleted_at ? 'Restore' : 'Delete' }}
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Links -->
                <div class="d-flex justify-content-center">
                    {{ $adminActions->links('vendor.pagination.custom') }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Admin Action Modal -->
<div class="modal fade" id="addAdminActionModal" tabindex="-1" aria-labelledby="addAdminActionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAdminActionModalLabel">Add New Admin Action</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addAdminActionForm">
                    <div class="mb-3">
                        <label for="action_type" class="form-label">Action Type</label>
                        <select class="form-control" id="action_type" name="action_type" required>
                            <option value="ban_user">Ban User</option>
                            <option value="approve_profile">Approve Profile</option>
                            <option value="resolve_dispute">Resolve Dispute</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="target_user_id" class="form-label">Target User</label>
                        <select class="form-control" id="target_user_id" name="target_user_id" required>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveAdminActionBtn">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Admin Action Modal -->
<div class="modal fade" id="editAdminActionModal" tabindex="-1" aria-labelledby="editAdminActionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAdminActionModalLabel">Edit Admin Action</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editAdminActionForm">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="mb-3">
                        <label for="edit_action_type" class="form-label">Action Type</label>
                        <select class="form-control" id="edit_action_type" name="action_type" required>
                            <option value="ban_user">Ban User</option>
                            <option value="approve_profile">Approve Profile</option>
                            <option value="resolve_dispute">Resolve Dispute</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Description</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit_target_user_id" class="form-label">Target User</label>
                        <select class="form-control" id="edit_target_user_id" name="target_user_id" required>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="updateAdminActionBtn">Update</button>
            </div>
        </div>
    </div>
</div>

<!-- View Admin Action Modal -->
<div class="modal fade" id="viewAdminActionModal" tabindex="-1" aria-labelledby="viewAdminActionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewAdminActionModalLabel">Admin Action Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul>
                    <li><strong>Action Type:</strong> <span id="view_action_type"></span></li>
                    <li><strong>Description:</strong> <span id="view_description"></span></li>
                    <li><strong>Target User:</strong> <span id="view_target_user"></span></li>
                    <li><strong>Created At:</strong> <span id="view_created_at"></span></li>
                    <li><strong>Updated At:</strong> <span id="view_updated_at"></span></li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // Add Admin Action
        $('#addAdminActionBtn').click(function() {
            $('#addAdminActionModal').modal('show');
        });

        $('#saveAdminActionBtn').click(function() {
            $.ajax({
                url: "{{ route('admin.adminActions.store') }}",
                method: 'POST',
                data: $('#addAdminActionForm').serialize(),
                success: function(response) {
                    Swal.fire('Success', 'Admin action added successfully!', 'success');
                    $('#addAdminActionModal').modal('hide');

                    // Append the new row to the table
                    var newRow = `
                        <tr id="admin-action-row-${response.data.id}">
                            <td>${response.data.id}</td>
                            <td>${response.data.action_type}</td>
                            <td>${response.data.description}</td>
                            <td>${response.data.target_user.name}</td>
                            <td>
                                <button class="btn btn-primary btn-sm view-details-btn" data-id="${response.data.id}">View</button>
                                <button class="btn btn-warning btn-sm edit-btn" data-id="${response.data.id}" data-action-type="${response.data.action_type}" data-description="${response.data.description}" data-target-user-id="${response.data.target_user_id}">Edit</button>
                                <button class="btn btn-danger btn-sm toggle-delete-btn" data-id="${response.data.id}" data-deleted="0">Delete</button>
                            </td>
                        </tr>
                    `;
                    $('#adminActionTableBody').append(newRow);
                },
                error: function(response) {
                    Swal.fire('Error', 'An error occurred while adding the admin action.', 'error');
                }
            });
        });

        // Edit Admin Action
        $(document).on('click', '.edit-btn', function() {
            var id = $(this).data('id');
            var actionType = $(this).data('action-type');
            var description = $(this).data('description');
            var targetUserId = $(this).data('target-user-id');

            $('#edit_id').val(id);
            $('#edit_action_type').val(actionType);
            $('#edit_description').val(description);
            $('#edit_target_user_id').val(targetUserId);

            $('#editAdminActionModal').modal('show');
        });

        $('#updateAdminActionBtn').click(function() {
            $.ajax({
                url: "{{ route('admin.adminActions.update', '') }}/" + $('#edit_id').val(),
                method: 'PUT',
                data: $('#editAdminActionForm').serialize(),
                success: function(response) {
                    Swal.fire('Success', 'Admin action updated successfully!', 'success');
                    $('#editAdminActionModal').modal('hide');

                    // Update the row in the table
                    var row = $('#admin-action-row-' + response.data.id);
                    row.find('td:eq(1)').text(response.data.action_type);
                    row.find('td:eq(2)').text(response.data.description);
                    row.find('td:eq(3)').text(response.data.target_user.name);
                },
                error: function(response) {
                    Swal.fire('Error', 'An error occurred while updating the admin action.', 'error');
                }
            });
        });

        // View Admin Action
        $(document).on('click', '.view-details-btn', function() {
            var id = $(this).data('id');
            $.ajax({
                url: "{{ route('admin.adminActions.show', '') }}/" + id,
                method: 'GET',
                success: function(response) {
                    $('#view_action_type').text(response.data.action_type);
                    $('#view_description').text(response.data.description);
                    $('#view_target_user').text(response.data.target_user.name);
                    $('#view_created_at').text(response.data.created_at);
                    $('#view_updated_at').text(response.data.updated_at);
                    $('#viewAdminActionModal').modal('show');
                },
                error: function(response) {
                    Swal.fire('Error', 'An error occurred while fetching the admin action details.', 'error');
                }
            });
        });

        // Toggle Delete/Restore
        $(document).on('click', '.toggle-delete-btn', function() {
            var id = $(this).data('id');
            var isDeleted = $(this).data('deleted') === '1';
            var action = isDeleted ? 'restore' : 'delete';
            var actionText = isDeleted ? 'Restore' : 'Delete';
            var confirmText = isDeleted ? 'Are you sure you want to restore this action?' : 'Are you sure you want to delete this action?';

            Swal.fire({
                title: 'Are you sure?',
                text: confirmText,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, ' + actionText + ' it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('admin.adminActions.softDelete', '') }}/" + id,
                        method: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            action: action
                        },
                        success: function(response) {
                            Swal.fire(actionText + '!', 'Admin action has been ' + actionText + 'd.', 'success');

                            // Update the button text and data-deleted attribute
                            var button = $('.toggle-delete-btn[data-id="' + id + '"]');
                            if (action === 'delete') {
                                button.text('Restore').data('deleted', '1');
                            } else {
                                button.text('Delete').data('deleted', '0');
                            }
                        },
                        error: function(response) {
                            Swal.fire('Error', 'An error occurred while ' + actionText + 'ing the admin action.', 'error');
                        }
                    });
                }
            });
        });
    });
</script>
@endpush