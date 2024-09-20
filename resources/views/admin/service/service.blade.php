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
                        <h4>Add services</h4>
                        <form action="{{ route('add.services') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row align-items-center justify-content-center">
                                <div class="col-xl-3 col-lg-5 col-md-6 col-12">
                                    <div class="mb-2">
                                        <label for="add_services_name" class="form-label">Services Name</label>
                                        <input type="text" name="servicesname" class="form-control" placeholder="Enter services Name" id="add_services_name">
                                    </div>
                                    <span class="alert text-danger" id="add_services_name_error">
                                        @error('servicesname')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                                <div class="col-xl-3 col-lg-5 col-md-6 col-12">
                                    <div class="mb-2">
                                        <label for="add_services_short_name" class="form-label">Services Short Name</label>
                                        <input type="text" name="servicesshortname" class="form-control" id="add_services_short_name" placeholder="Enter services Short Name">
                                    </div>
                                    <span class="alert text-danger" id="add_services_short_name_error">
                                        @error('servicesshortname')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                                <div class="col-xl-3 col-lg-5 col-md-6 col-12">
                                    <div class="mb-2">
                                        <label for="add_services_image" class="form-label">Services Image</label>
                                        <input type="file" name="servicesimage" class="form-control" id="add_services_image" placeholder="Enter services Short Name">
                                    </div>
                                    <span class="alert text-danger" id="add_services_image_error">
                                        @error('servicesimage')
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
            <div class="modal fade" id="edit_services" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Edit services</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="editservicesform" enctype="multipart/form-data">
                            @csrf
                            @method('POST')
                            <input type="hidden" class="edit_services_id" name="id" value="" />
                            <div class="modal-body">
                                <div class="mb-2">
                                    <label for="edit_services_name" class="form-label fw-blod">Services Name</label>
                                    <input type="text" name="servicesname" class="form-control" placeholder="Enter services Name" id="edit_services_name">
                                    <span id="edit_services_name_error" class="alert text-danger"></span>
                                </div>
                                <div class="mb-2">
                                    <label for="edit_services_short_name" class="form-label fw-blod">Services Short Name</label>
                                    <input type="text" name="servicesshortname" class="form-control" id="edit_services_short_name" placeholder="Enter services Short Name">
                                    <span id="edit_services_short_name_error" class="alert text-danger"></span>
                                </div>
                                <div class="mb-2">
                                    <label for="edit_services_image" class="form-label fw-blod">Services Image</label>
                                    <input type="file" name="servicesimage" class="form-control" id="edit_services_image" placeholder="Enter services Short Name">
                                    <span id="edit_services_image_error" class="alert text-danger"></span>
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
                            Are you sure you want to delete this services?
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
                            <h4>Services List</h4>
                            <div class="client_list_heading_search_area">
                                <i class="menu-icon tf-icons ti ti-search"></i>
                                <input type="search" id="servicesSearch" class="form-control"
                                    placeholder="Searching ...">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped">
                                        <thead class="table_head_1f446E">
                                            <tr>
                                                <th>S. No.</th>
                                                <th>Services Name</th>
                                                <th>Services Short Name</th>
                                                <th>Image</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $serialNumber = 1;
                                            @endphp
                                            @foreach ($services as $service)
                                                <tr>
                                                    <td>{{ $serialNumber++ }}</td>
                                                    <td>{{ $service->name }}</td>
                                                    <td>{{ $service->short_name }}</td>
                                                    <td>
                                                        <img src="{{ asset('storage/services/' . $service->image) }}" alt="{{ $service->name }}" width="50" height="50">
                                                    </td>
                                                    <td>
                                                        <div class="Client_table_action_area">
                                                            <button class="btn Client_table_action_icon px-2 edit_services_btn" data-id="{{ $service->id }}">
                                                                <i class="tf-icons ti ti-pencil"></i>
                                                            </button>

                                                            <button id="client_del_id"
                                                                class="btn Client_table_action_icon px-2 delete_client_btn"
                                                                data-id="{{ $service->id }}" data-bs-toggle="modal"
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
                                        @if ($services->count() > 0)
                                            <div class="pagination-container">
                                                {{ $services->links() }}
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

            // Utility function to handle AJAX success
            function handleAjaxSuccess(response, successCallback) {
                if (response.success) {
                    successCallback(response);
                } else {
                    // Handle validation errors
                    if (response.errors) {
                        $('#edit_services_name_error').text(response.errors.servicesname || '');
                        $('#edit_services_short_name_error').text(response.errors.servicesshortname || '');
                        $('#edit_services_image_error').text(response.errors.servicesimage || '');
                    }
                }
            }

            // Attach event handlers for delete and edit actions
            function attachEventHandlers() {
                $(document).on('click', '.delete_client_btn', function () {
                    var id = $(this).data("id");
                    $("#client_del_id").val(id);
                    $("#delete_client").modal("show");
                });

                $(document).on('click', '#confirm_delete', function (e) {
                    e.preventDefault();
                    var id = $("#client_del_id").val();
                    $.ajax({
                        type: "GET",
                        url: `/admin/delete-services/${id}`,
                        data: $("#deleteClientForm").serialize(),
                        success: function () {
                            $("#delete_client").modal("hide");
                            window.location.reload();
                        },
                        error: handleAjaxError
                    });
                });

                $(document).on('click', '.edit_services_btn', function () {
                    const servicesId = $(this).data("id");
                    $.ajax({
                        url: `/admin/services/${servicesId}/edit`,
                        type: "GET",
                        success: function (response) {
                            $("#edit_services_name").val(response.name);
                            $("#edit_services_short_name").val(response.short_name);
                            $(".edit_services_id").val(response.id);
                            const baseUrl = "{{ asset('storage/services/') }}";
                            const imageUrl = response.image ? `${baseUrl}/${response.image}` : "";
                            $("#catimg").attr("src", imageUrl);
                            $("#edit_services").modal("show");
                        },
                        error: handleAjaxError
                    });
                });

                $('#editservicesform').on('submit', function (e) {
                    e.preventDefault();
                    var formData = new FormData(this);
                    var servicesId = $('.edit_services_id').val();

                    $.ajax({
                        url: `/admin/edit-services/${servicesId}`,
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            handleAjaxSuccess(response, function () {
                                $('#edit_services').modal('hide');
                                servicesSearch(); // Ensure this is called after the modal is hidden
                                alert('services updated successfully');
                                window.location.reload(true);
                            });
                        },
                        error: function (xhr) {
                            handleAjaxError(xhr);
                            alert('An error occurred while updating the services');
                        }
                    });
                });
            }

            // Function to perform services search and update the list
            function servicesSearch() {
                var searchQuery = $('#servicesSearch').val();
                $.ajax({
                    url: "/admin/services",
                    type: "GET",
                    data: { search: searchQuery },
                    success: function (response) {
                        var services = response.services;
                        var pagination = response.pagination || ''; // Assuming pagination HTML is part of the response

                        // Handle no records found and pagination visibility
                        if (services.length === 0) {
                            $(".no-records-found").show();
                            $(".pagination-container").hide();
                        } else {
                            $(".no-records-found").hide();
                            $(".pagination-container").show().html(pagination);
                        }

                        // Clear previous results
                        var tbody = $("#servicesTable tbody");
                        tbody.empty();

                        // Append services to the table
                        $.each(services, function (index, services) {
                            var row = `
                                <tr>
                                    <td>${index + 1}</td> <!-- Changed from serialNumber++ to index + 1 -->
                                    <td>${services.name}</td>
                                    <td>${services.short_name}</td>
                                    <td>
                                        <img src="/storage/services/${services.image}" alt="${services.name}" width="50" height="50">
                                    </td>
                                    <td>
                                        <div class="Client_table_action_area">
                                            <button class="btn Client_table_action_icon px-2 edit_services_btn"
                                                data-id="${services.id}"
                                                data-name="${services.name}"
                                                data-short_name="${services.short_name}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#edit_services">
                                                <i class="tf-icons ti ti-pencil"></i>
                                            </button>
                                            <button class="btn Client_table_action_icon px-2 delete_client_btn"
                                                data-id="${services.id}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#delete_client">
                                                <i class="tf-icons ti ti-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            `;
                            tbody.append(row);
                        });

                        // Reattach event handlers after updating the DOM
                        attachEventHandlers();
                    },
                    error: handleAjaxError
                });
            }

            // Initial setup
            attachEventHandlers();

            // Handle services search input
            $("#servicesSearch").on("keyup", function () {
                servicesSearch();
            });
        });
    </script>
@endsection
