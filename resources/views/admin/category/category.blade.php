@extends('backend.layouts.app')
@section('content')
<style>
    .pagination-container{
        display: flex;
        justify-content: end;
        margin-top: 20px;
    }
    .pagination-container svg{
        width: 30px;
    }

    .pagination-container nav .justify-between{
        display: none;
    }
    .no-records-found {
        text-align: center;
        color: red;
        margin-top: 20px;
        font-size: 18px;
        display: none; /* Hidden by default */
    }

</style>
    <div class="content-wrapper page_content_section_hp">
        <div class="container-xxl">
            <div class="add_client_form_area_hp mb-4">
                <div class="card">
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        <h4>Add Category</h4>
                        <form action="{{ route('add.category') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row align-items-center justify-content-center">
                                <div class="col-xl-3 col-lg-5 col-md-6 col-12">
                                    <div class="mb-2">
                                        <label for="add_category_name" class="form-label">Category Name</label>
                                        <input type="text" name="categoryname" class="form-control" placeholder="Enter Category Name" id="add_category_name">
                                    </div>
                                    <span class="alert text-danger" id="add_category_name_error">
                                        @error('categoryname')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                                <div class="col-xl-3 col-lg-5 col-md-6 col-12">
                                    <div class="mb-2">
                                        <label for="add_category_short_name" class="form-label">Category Short Name</label>
                                        <input type="text" name="categoryshortname" class="form-control" id="add_category_short_name" placeholder="Enter Category Short Name">
                                    </div>
                                    <span class="alert text-danger" id="add_category_short_name_error">
                                        @error('categoryshortname')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                                <div class="col-xl-3 col-lg-5 col-md-6 col-12">
                                    <div class="mb-2">
                                        <label for="add_category_image" class="form-label">Category Image</label>
                                        <input type="file" name="categoryimage" class="form-control" id="add_category_image" placeholder="Enter Category Short Name">
                                    </div>
                                    <span class="alert text-danger" id="add_category_image_error">
                                        @error('categoryimage')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                                <div class="col">
                                    <div class="mb-4">
                                        <label for="exampleFormControlInput1" class="form-label"></label>
                                        <button type="submit" class="btn btn_1F446E_hp w-100"
                                            id="add_save_client">Save</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!--Edit client Modal--->
            <div class="modal fade" id="edit_category" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Edit Category</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="editcategoryform" enctype="multipart/form-data">
                            @csrf
                            @method('POST')
                            <input type="hidden" class="edit_category_id" name="id" value="" />
                            <div class="modal-body">
                                <div class="mb-2">
                                    <label for="edit_category_name" class="form-label fw-blod">Category Name</label>
                                    <input type="text" name="categoryname" class="form-control" placeholder="Enter Category Name" id="edit_category_name">
                                    <span id="edit_category_name_error" class="alert text-danger"></span>
                                </div>
                                <div class="mb-2">
                                    <label for="edit_category_short_name" class="form-label fw-blod">Category Short Name</label>
                                    <input type="text" name="categoryshortname" class="form-control" id="edit_category_short_name" placeholder="Enter Category Short Name">
                                    <span id="edit_category_short_name_error" class="alert text-danger"></span>
                                </div>
                                <div class="mb-2">
                                    <label for="edit_category_image" class="form-label fw-blod">Category Image</label>
                                    <input type="file" name="categoryimage" class="form-control" id="edit_category_image" placeholder="Enter Category Short Name">
                                    <span id="edit_category_image_error" class="alert text-danger"></span>
                                </div>
                                <div class="mb-2">
                                    <img src="" alt="" id="catimg" style="width: 21%;">
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn_1F446E_hp" id="edit_save_cateogry">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="delete_client" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Confirm Deletion</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete this Category?
                        </div>
                        <form id="deleteClientForm">
                            @csrf
                            @method('GET')
                            <input type="hidden" id="client_del_id" name="client_id" value=" ">
                        </form>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-danger" id="confirm_delete">Delete</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="client_list_area_hp">
                <div class="card">
                    <div class="card-body">
                        <div class="client_list_heading_area">
                            <h4>Category List</h4>
                            <div class="client_list_heading_search_area">
                                <i class="menu-icon tf-icons ti ti-search"></i>
                                <input type="search" id="categorySearch" class="form-control"
                                    placeholder="Searching ...">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped" id="categoriesTable">
                                        <thead class="table_head_1f446E">
                                            <tr>
                                                <th>S. No.</th>
                                                <th>Category Name</th>
                                                <th>Category Short Name</th>
                                                <th>Image</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $serialNumber = 1;
                                            @endphp
                                            @foreach ($categories as $category)
                                                <tr>
                                                    <td>{{ $serialNumber++ }}</td>
                                                    <td>{{ $category->name }}</td>
                                                    <td>{{ $category->short_name }}</td>
                                                    <td>
                                                        <img src="{{ asset('storage/categories/' . $category->image) }}" alt="{{ $category->name }}" width="50" height="50">
                                                    </td>
                                                    <td>
                                                        <div class="Client_table_action_area">
                                                            <button class="btn Client_table_action_icon px-2 edit_category_btn" data-id="{{ $category->id }}">
                                                                <i class="tf-icons ti ti-pencil"></i>
                                                            </button>

                                                            <button id="client_del_id"
                                                                class="btn Client_table_action_icon px-2 delete_client_btn"
                                                                data-id="{{ $category->id }}" data-bs-toggle="modal"
                                                                data-bs-target="#delete_client">
                                                                <i class="tf-icons ti ti-trash"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="no-records-found">No records found related to your search.</div>
                                        @if ($categories->count() > 0)
                                            <div class="pagination-container">
                                                {{ $categories->links() }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
       document.addEventListener("DOMContentLoaded", function () {
            // Utility function to handle AJAX errors
            function handleAjaxError(xhr) {
                console.error("An error occurred:", xhr.responseText);
            }

            // Attach event handlers for delete and edit actions
            function attachEventHandlers() {
                // Delete action handler
                $(document).on('click', '.delete_client_btn', function () {
                    var id = $(this).data("id");
                    $("#client_del_id").val(id);
                    $("#delete_client").modal("show");
                });

                // Confirm delete handler
                $(document).on('click', '#confirm_delete', function (e) {
                    e.preventDefault();
                    var id = $("#client_del_id").val();

                    $.ajax({
                        type: "GET",
                        url: `/admin/delete-category/${id}`,
                        success: function () {
                            $("#delete_client").modal("hide");
                            window.location.reload();
                        },
                        error: handleAjaxError
                    });
                });

                // Edit action handler
                $(document).on('click', '.edit_category_btn', function () {
                    const categoryId = $(this).data("id");

                    $.ajax({
                        url: `/admin/categories/${categoryId}/edit`,
                        type: "GET",
                        success: function (response) {
                            $("#edit_category_name").val(response.name);
                            $("#edit_category_short_name").val(response.short_name);
                            $(".edit_category_id").val(response.id);
                            const baseUrl = "{{ asset('storage/categories/') }}";
                            const imageUrl = response.image ? `${baseUrl}/${response.image}` : "";
                            $("#catimg").attr("src", imageUrl);
                            $("#edit_category").modal("show");
                        },
                        error: handleAjaxError
                    });
                });

                // Update form handler
                $('#editcategoryform').on('submit', function (e) {
                    e.preventDefault();
                    var formData = new FormData(this);
                    var categoryId = $('.edit_category_id').val();

                    $.ajax({
                        url: `/admin/edit-category/${categoryId}`,
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            $('#edit_category').modal('hide');
                            categorySearch();
                            alert('Category updated successfully');
                        },
                        error: handleAjaxError
                    });
                });
            }

            // Function to perform category search and update the list
            function categorySearch() {
                var searchQuery = $('#categorySearch').val();

                $.ajax({
                    url: "/admin/categories", // Ensure this URL is correct
                    type: "GET",
                    data: { search: searchQuery },
                    success: function (response) {
                        console.log(response); // Check the response in the browser console

                        var categories = response.categories;
                        var pagination = response.pagination || '';

                        // Handle no records found
                        if (categories.length === 0) {
                            $(".no-records-found").show();
                            $(".pagination-container").hide();
                        } else {
                            $(".no-records-found").hide();
                            $(".pagination-container").show().html(pagination);
                        }

                        // Clear previous results
                        var tbody = $("#categoriesTable tbody");
                        if (tbody.length === 0) {
                            console.error("Table body not found.");
                            return;
                        }

                        tbody.empty(); // Clear the table body before appending new rows

                        // Append categories to the table
                        $.each(categories, function (index, category) {
                            var row = `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${category.name}</td>
                                    <td>${category.short_name}</td>
                                    <td>
                                        <img src="/storage/categories/${category.image}" alt="${category.name}" width="50" height="50">
                                    </td>
                                    <td>
                                        <div class="Client_table_action_area">
                                            <button class="btn Client_table_action_icon px-2 edit_category_btn"
                                                data-id="${category.id}">
                                                <i class="tf-icons ti ti-pencil"></i>
                                            </button>
                                            <button class="btn Client_table_action_icon px-2 delete_client_btn"
                                                data-id="${category.id}" data-bs-toggle="modal"
                                                data-bs-target="#delete_client">
                                                <i class="tf-icons ti ti-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            `;
                            tbody.append(row); // Append the row to the table body
                        });

                        // Reattach event handlers after updating the DOM
                        attachEventHandlers();
                    },
                    error: function (xhr) {
                        console.error("Error fetching categories:", xhr.responseText);
                    }
                });
            }

            // Initial setup
            attachEventHandlers();

            // Handle category search input
            $("#categorySearch").on("keyup", function () {
                categorySearch();
            });
        });
    </script>
@endsection
