@extends('layouts.master')

@section('title', 'Category List')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Category List</h4>
                <button class="btn btn-success mb-3" id="addCategoryBtn">Add New Category</button>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Category Name</th>
                                <th>Description</th>
                                <th>Category Icon</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="categoryTableBody">
                            @foreach($categories as $category)
                                <tr id="category-row-{{ $category->id }}">
                                    <td>{{ $category->id }}</td>
                                    <td>{{ $category->category_name }}</td>
                                    <td>{{ $category->description }}</td>
                                    <td><img src="{{ asset('storage/' . $category->category_icon) }}" alt="Category Image" width="50"></td>
                                    <td>
                                        <button class="btn btn-primary btn-sm view-details-btn" data-id="{{ $category->id }}" data-name="{{ $category->category_name }}" data-description="{{ $category->description }}" data-image="{{ $category->category_icon }}" data-created-at="{{ $category->created_at }}" data-updated-at="{{ $category->updated_at }}">View</button>
                                        <button class="btn btn-warning btn-sm edit-btn" data-id="{{ $category->id }}" data-name="{{ $category->category_name }}" data-description="{{ $category->description }}" data-image="{{ $category->category_icon }}">Edit</button>
                                        @if($category->deleted_at)
                                            <button class="btn btn-info btn-sm restore-btn" data-id="{{ $category->id }}">Restore</button>
                                        @else
                                            <button class="btn btn-danger btn-sm soft-delete-btn" data-id="{{ $category->id }}">Delete</button>
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

<!-- View Category Modal -->
<div class="modal fade" id="viewCategoryModal" tabindex="-1" aria-labelledby="viewCategoryModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewCategoryModalLabel">Category Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p><strong>Category Name:</strong> <span id="viewCategoryName"></span></p>
        <p><strong>Description:</strong> <span id="viewCategoryDescription"></span></p>
        <p><strong>Created At:</strong> <span id="viewCategoryCreatedAt"></span></p>
        <p><strong>Updated At:</strong> <span id="viewCategoryUpdatedAt"></span></p>
        <p><strong>Category Icon:</strong></p>
        <img id="viewCategoryImage" src="" alt="Category Image" class="img-fluid">
      </div>
    </div>
  </div>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addCategoryModalLabel">Add New Category</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="addCategoryForm" enctype="multipart/form-data">
          @csrf
          <div class="mb-3">
            <label for="categoryName" class="form-label">Category Name</label>
            <input type="text" class="form-control" id="categoryName" name="category_name" required>
          </div>
          <div class="mb-3">
            <label for="categoryDescription" class="form-label">Description</label>
            <textarea class="form-control" id="categoryDescription" name="description"></textarea>
          </div>
          <div class="mb-3">
            <label for="categoryIcon" class="form-label">Category Icon</label>
            <input type="file" class="form-control" id="categoryIcon" name="category_icon" required>
          </div>
          <button type="submit" class="btn btn-primary">Add Category</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editCategoryModalLabel">Edit Category</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editCategoryForm" enctype="multipart/form-data">
          @csrf
          @method('PUT')
          <input type="hidden" id="editCategoryId" name="id">
          <div class="mb-3">
            <label for="editCategoryName" class="form-label">Category Name</label>
            <input type="text" class="form-control" id="editCategoryName" name="category_name" required>
          </div>
          <div class="mb-3">
            <label for="editCategoryDescription" class="form-label">Description</label>
            <textarea class="form-control" id="editCategoryDescription" name="description"></textarea>
          </div>
          <div class="mb-3">
            <label for="editCategoryIcon" class="form-label">Category Icon</label>
            <input type="file" class="form-control" id="editCategoryIcon" name="category_icon">
          </div>
          <button type="submit" class="btn btn-primary">Update Category</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Add Pagination Links -->
<div class="d-flex justify-content-center">
    {{ $categories->links('vendor.pagination.custom') }}
</div>
@endsection

@push('scripts')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Open add category modal
        document.getElementById('addCategoryBtn').addEventListener('click', () => {
            new bootstrap.Modal(document.getElementById('addCategoryModal')).show();
        });

        // View category details
        document.querySelectorAll('.view-details-btn').forEach(button => {
            button.addEventListener('click', () => {
                const categoryId = button.getAttribute('data-id');
                const categoryName = button.getAttribute('data-name');
                const categoryDescription = button.getAttribute('data-description');
                const categoryImage = button.getAttribute('data-image');
                const categoryCreatedAt = button.getAttribute('data-created-at');
                const categoryUpdatedAt = button.getAttribute('data-updated-at');

                // Update modal content
                document.getElementById('viewCategoryName').textContent = categoryName;
                document.getElementById('viewCategoryDescription').textContent = categoryDescription;
                document.getElementById('viewCategoryCreatedAt').textContent = categoryCreatedAt;
                document.getElementById('viewCategoryUpdatedAt').textContent = categoryUpdatedAt;
                document.getElementById('viewCategoryImage').src = `{{ asset('storage/') }}/${categoryImage}`;

                // Show modal
                new bootstrap.Modal(document.getElementById('viewCategoryModal')).show();
            });
        });

        // Add category form submission
        document.getElementById('addCategoryForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const form = new FormData(e.target);
            try {
                const response = await fetch('/admin/categories', {
                    method: 'POST',
                    body: form,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                if (response.ok) {
                    const data = await response.json();
                    if (data.success) {
                        Swal.fire('Success', 'Category added successfully.', 'success');
                        location.reload();
                    }
                }
            } catch (error) {
                Swal.fire('Error', 'An error occurred while adding the category.', 'error');
            }
        });

        // Edit category form submission
        document.getElementById('editCategoryForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const form = new FormData(e.target);
            const categoryId = document.getElementById('editCategoryId').value;
            try {
                const response = await fetch(`/admin/categories/${categoryId}`, {
                    method: 'POST',
                    body: form,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                if (response.ok) {
                    const data = await response.json();
                    if (data.success) {
                        Swal.fire('Success', 'Category updated successfully.', 'success');
                        location.reload();
                    }
                }
            } catch (error) {
                Swal.fire('Error', 'An error occurred while updating the category.', 'error');
            }
        });

        // Open edit category modal
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', () => {
                const categoryId = button.getAttribute('data-id');
                const categoryName = button.getAttribute('data-name');
                const categoryDescription = button.getAttribute('data-description');
                const categoryImage = button.getAttribute('data-image');

                // Update modal content
                document.getElementById('editCategoryId').value = categoryId;
                document.getElementById('editCategoryName').value = categoryName;
                document.getElementById('editCategoryDescription').value = categoryDescription;

                // Show modal
                new bootstrap.Modal(document.getElementById('editCategoryModal')).show();
            });
        });

        // Function to handle soft delete
        const softDeleteCategory = async (event) => {
            const button = event.target;
            const categoryId = button.getAttribute('data-id');
            Swal.fire({
                title: 'Are you sure?',
                text: 'This action will soft delete the category!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, soft delete it!'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        const response = await fetch(`/admin/categories/${categoryId}/soft-delete`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Content-Type': 'application/json'
                            }
                        });
                        if (response.ok) {
                            const data = await response.json();
                            if (data.success) {
                                Swal.fire('Deleted!', 'Category has been soft deleted.', 'success');
                                
                                // Update the button to "Restore"
                                const row = document.querySelector(`#category-row-${categoryId}`);
                                const deleteButton = row.querySelector('.soft-delete-btn');
                                deleteButton.classList.remove('btn-danger', 'soft-delete-btn');
                                deleteButton.classList.add('btn-info', 'restore-btn');
                                deleteButton.innerText = 'Restore';
                                
                                // Update the event listener for the new "Restore" button
                                deleteButton.removeEventListener('click', softDeleteCategory);
                                deleteButton.addEventListener('click', restoreCategory);
                            }
                        }
                    } catch (error) {
                        Swal.fire('Error', 'An error occurred while deleting the category.', 'error');
                    }
                }
            });
        };

        // Function to handle restore
        const restoreCategory = async (event) => {
            const button = event.target;
            const categoryId = button.getAttribute('data-id');
            Swal.fire({
                title: 'Are you sure?',
                text: 'This action will restore the category!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, restore it!'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        const response = await fetch(`/admin/categories/${categoryId}/restore`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Content-Type': 'application/json'
                            }
                        });
                        if (response.ok) {
                            const data = await response.json();
                            if (data.success) {
                                Swal.fire('Restored!', 'Category has been restored.', 'success');
                                
                                // Update the button to "Delete"
                                const row = document.querySelector(`#category-row-${categoryId}`);
                                const restoreButton = row.querySelector('.restore-btn');
                                restoreButton.classList.remove('btn-info', 'restore-btn');
                                restoreButton.classList.add('btn-danger', 'soft-delete-btn');
                                restoreButton.innerText = 'Delete';
                                
                                // Update the event listener for the new "Delete" button
                                restoreButton.removeEventListener('click', restoreCategory);
                                restoreButton.addEventListener('click', softDeleteCategory);
                            }
                        }
                    } catch (error) {
                        Swal.fire('Error', 'An error occurred while restoring the category.', 'error');
                    }
                }
            });
        };

        // Attach event listeners to existing buttons
        document.querySelectorAll('.soft-delete-btn').forEach(button => {
            button.addEventListener('click', softDeleteCategory);
        });

        document.querySelectorAll('.restore-btn').forEach(button => {
            button.addEventListener('click', restoreCategory);
        });
    });
</script>
@endpush