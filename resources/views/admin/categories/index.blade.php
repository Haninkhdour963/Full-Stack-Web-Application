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
                                        <button class="btn btn-primary btn-sm view-details-btn" data-id="{{ $category->id }}">View</button>
                                        <button class="btn btn-warning btn-sm edit-btn" data-id="{{ $category->id }}" data-name="{{ $category->category_name }}" data-description="{{ $category->description }}" data-image="{{ $category->category_icon }}">Edit</button>
                                        @if($category->deleted_at)
                                            <button class="btn btn-danger btn-sm" disabled>Deleted</button>
                                        @else
                                            <button class="btn btn-danger btn-sm soft-delete-btn" data-id="{{ $category->id }}">Delete</button>
                                        @endif
                                    </td>
                                </tr>
                                <tr id="details-row-{{ $category->id }}" class="details-row" style="display: none;">
                                    <td colspan="5">
                                        <ul>
                                            <li><strong>Category Name:</strong> {{ $category->category_name }}</li>
                                            <li><strong>Description:</strong> {{ $category->description }}</li>
                                            <li><strong>Image:</strong> <img src="{{ asset('storage/' . $category->category_icon) }}" alt="category_icon" width="100"></li>
                                            <li><strong>Created At:</strong> {{ $category->created_at }}</li>
                                            <li><strong>Updated At:</strong> {{ $category->updated_at }}</li>
                                        </ul>
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
            <label for="editCategoryImage" class="form-label">Category Icon</label>
            <input type="file" class="form-control" id="editCategoryImage" name="category_icon">
          </div>
          <button type="submit" class="btn btn-warning">Update Category</button>
        </form>
      </div>
    </div>
  </div>
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

        // Open edit category modal
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', () => {
                const categoryId = button.getAttribute('data-id');
                const categoryName = button.getAttribute('data-name');
                const categoryDescription = button.getAttribute('data-description');
                const categoryImage = button.getAttribute('data-image');

                document.getElementById('editCategoryId').value = categoryId;
                document.getElementById('editCategoryName').value = categoryName;
                document.getElementById('editCategoryDescription').value = categoryDescription;
                document.getElementById('editCategoryImage').value = categoryImage;

                new bootstrap.Modal(document.getElementById('editCategoryModal')).show();
            });
        });

        // View category details
        document.querySelectorAll('.view-details-btn').forEach(button => {
            button.addEventListener('click', () => {
                const categoryId = button.getAttribute('data-id');
                const detailsRow = document.getElementById(`details-row-${categoryId}`);
                
                // Toggle visibility of the details row
                if (detailsRow.style.display === 'none' || detailsRow.style.display === '') {
                    detailsRow.style.display = 'table-row';
                } else {
                    detailsRow.style.display = 'none';
                }
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
            try {
                const response = await fetch(`/admin/categories/${document.getElementById('editCategoryId').value}`, {
                    method: 'PUT',
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

        // Soft delete category
        document.querySelectorAll('.soft-delete-btn').forEach(button => {
            button.addEventListener('click', async () => {
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
                                    const row = document.querySelector(`#category-row-${categoryId}`);
                                    row.classList.add('text-muted');
                                    row.querySelector('.soft-delete-btn').setAttribute('disabled', 'true');
                                    row.querySelector('.soft-delete-btn').innerText = 'Deleted';
                                }
                            }
                        } catch (error) {
                            Swal.fire('Error', 'An error occurred while deleting the category.', 'error');
                        }
                    }
                });
            });
        });
    });
</script>
@endpush
