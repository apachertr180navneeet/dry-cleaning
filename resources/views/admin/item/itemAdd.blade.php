@extends('backend.layouts.app')
@section('content')
<style>
    .pagination-container {
        display: flex;
        justify-content: end;
        margin-top: 20px;
    }
    .pagination-container svg {
        width: 30px;
    }

    .pagination-container nav .justify-between {
        display: none;
    }
    .no-records-found {
        text-align: center;
        color: red;
        margin-top: 20px;
        font-size: 18px;
        display: none; /* Hidden by default */
    }

    .add-button {
        margin-right: 15px;
    }
</style>

<div class="content-wrapper page_content_section_hp">
    <div class="container-xxl">
        <div class="client_list_area_hp">
            <div class="card">
                <div class="card-header">
                    <h4>Add Item</h4>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    <form id="item-form" action="{{ route('store.item') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="item_name" class="form-label">Item Name</label>
                                    <input type="text" name="item_name" class="form-control" placeholder="Enter Item Name" id="item_name" value="{{ old('item_name') }}">
                                    <span class="text-danger" id="item_name_error">
                                        @error('item_name')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="image" class="form-label">Item Image</label>
                                    <input type="file" name="image" class="form-control" placeholder="Enter Item Name" id="image" value="{{ old('item_name') }}">
                                    <span class="text-danger" id="item_name_error">
                                        @error('image')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                            </div>

                            <!-- Categories Section -->
                            <div class="col-md-12">
                                <div class="row" id="addcategory-container">
                                    <div class="row addcategory" id="addcategory1">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="category" class="form-label">Category</label>
                                                <select class="form-control category-select" id="category[1]" name="itemdetail[1][category]">
                                                    <option value="">Select Category</option>
                                                    @foreach ($categorys as $category)
                                                        <option value="{{$category->name}}" {{ old('itemdetail[1][category]') == $category->name ? 'selected' : '' }}>{{$category->name}}</option>
                                                    @endforeach
                                                </select>
                                                <span class="text-danger" id="category_error">
                                                    @error('itemdetail.1.category')
                                                        {{ $message }}
                                                    @enderror
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <button type="button" id="add-more" class="btn btn-primary"> + Category</button>
                                        </div>

                                        <!-- Services Section -->
                                        <div class="col-md-12 mt-4">
                                            <div class="row addservice-container" id="addservice-container1">
                                                <div class="row addservice" id="addservice1_1">
                                                    <div class="col-md-4">
                                                        <div class="form-group mb-3">
                                                            <label for="service" class="form-label">Service</label>
                                                            <select class="form-control service-select" id="service1_1" name="itemdetail[1][service][]">
                                                                <option value="">Select Service</option>
                                                                @foreach ($services as $service)
                                                                    <option value="{{$service->name}}" {{ old('itemdetail[1][service][0]') == $service->name ? 'selected' : '' }}>{{$service->name}}</option>
                                                                @endforeach
                                                            </select>
                                                            <span class="text-danger" id="service_error">
                                                                @error('itemdetail.1.service.0')
                                                                    {{ $message }}
                                                                @enderror
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="price" class="form-label">Price</label>
                                                        <input type="text" name="itemdetail[1][price][]" value="{{ old('itemdetail[1][price][0]') }}" class="form-control" placeholder="Enter price" id="price1_1">
                                                        <span class="text-danger" id="service_error">
                                                            @error('itemdetail.1.price.0')
                                                                {{ $message }}
                                                            @enderror
                                                        </span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <button type="button" class="btn btn-primary add-more-service" data-category-id="1">+ Service</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>

                <!-- Card Footer Section -->
                <div class="card-footer text-right">
                    <button type="submit" form="item-form" id="item-save" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- jQuery Script -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        var categoryIndex = 1; // Counter for categories
        var serviceIndex = 1; // Counter for services within each category

        // Function to disable selected categories in other dropdowns
        function disableSelectedCategories() {
            var selectedCategories = [];
            $('.category-select').each(function() {
                var selectedValue = $(this).val();
                if (selectedValue) {
                    selectedCategories.push(selectedValue);
                }
            });

            // Disable selected categories in all other dropdowns
            $('.category-select').each(function() {
                var currentSelect = $(this);
                currentSelect.find('option').each(function() {
                    var optionValue = $(this).val();
                    if (selectedCategories.includes(optionValue) && optionValue !== currentSelect.val()) {
                        $(this).attr('disabled', true);
                    } else {
                        $(this).attr('disabled', false);
                    }
                });
            });
        }

        // Add more categories with service section
        $('#add-more').click(function() {
            categoryIndex++;
            serviceIndex = 1; // Reset service index for the new category

            var newCategoryDiv = `
                <div class="row addcategory" id="addcategory${categoryIndex}">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select class="form-control category-select" id="category${categoryIndex}" name="itemdetail[${categoryIndex}][category]">
                                <option value="">Select Category</option>
                                @foreach ($categorys as $category)
                                    <option value="{{$category->name}}">{{$category->name}}</option>
                                @endforeach
                            </select>
                            <span class="text-danger category-error" id="category_error${categoryIndex}"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <button type="button" class="btn btn-danger remove-category" data-id="${categoryIndex}">- Category</button>
                    </div>

                    <!-- Services Section -->
                    <div class="col-md-12 mt-4">
                        <div class="row addservice-container" id="addservice-container${categoryIndex}">
                            <div class="row addservice" id="addservice${categoryIndex}_1">
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="service" class="form-label">Service</label>
                                        <select class="form-control service-select" id="service${categoryIndex}_1" name="itemdetail[${categoryIndex}][service][]">
                                            <option value="">Select Service</option>
                                            @foreach ($services as $service)
                                                <option value="{{$service->name}}">{{$service->name}}</option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger service-error" id="service_error${categoryIndex}_1"></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="price" class="form-label">Price</label>
                                    <input type="text" name="itemdetail[${categoryIndex}][price][]" class="form-control price-input" placeholder="Enter price" id="price${categoryIndex}_1">
                                    <span class="text-danger price-error" id="price_error${categoryIndex}_1"></span>
                                </div>
                                <div class="col-md-4">
                                    <button type="button" class="btn btn-primary add-more-service" data-category-id="${categoryIndex}">+ Service</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            $('#addcategory-container').append(newCategoryDiv);
            disableSelectedCategories(); // Update the disabled options after adding a new category
        });

        // Add more services for a specific category
        $(document).on('click', '.add-more-service', function() {
            var categoryId = $(this).data('category-id');
            serviceIndex++;
            var newServiceDiv = `
                <div class="row addservice" id="addservice${categoryId}_${serviceIndex}">
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label for="service" class="form-label">Service</label>
                            <select class="form-control service-select" id="service${categoryId}_${serviceIndex}" name="itemdetail[${categoryId}][service][]">
                                <option value="">Select Service</option>
                                @foreach ($services as $service)
                                    <option value="{{$service->name}}">{{$service->name}}</option>
                                @endforeach
                            </select>
                            <span class="text-danger service-error" id="service_error${categoryId}_${serviceIndex}"></span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="price" class="form-label">Price</label>
                        <input type="text" name="itemdetail[${categoryId}][price][]" class="form-control price-input" placeholder="Enter price" id="price${categoryId}_${serviceIndex}">
                        <span class="text-danger price-error" id="price_error${categoryId}_${serviceIndex}"></span>
                    </div>
                    <div class="col-md-4">
                        <button type="button" class="btn btn-danger remove-service" data-id="${categoryId}_${serviceIndex}">- Service</button>
                    </div>
                </div>
            `;
            $('#addservice-container' + categoryId).append(newServiceDiv);
        });

        // Remove category
        $(document).on('click', '.remove-category', function() {
            var id = $(this).data('id');
            $('#addcategory' + id).remove();
            disableSelectedCategories(); // Recalculate disabled options after removing a category
        });

        // Remove service
        $(document).on('click', '.remove-service', function() {
            var id = $(this).data('id');
            $('#addservice' + id).remove();
        });

        // When category is changed, update disabled options
        $(document).on('change', '.category-select', function() {
            disableSelectedCategories();
        });

        // Form validation on submit
        $('#item-form').submit(function(event) {
            var isValid = true;

            // Validate category fields
            $('.category-select').each(function() {
                var categoryId = $(this).attr('id');
                if (!$(this).val()) {
                    $('#category_error' + categoryId).text('Please select a category.');
                    isValid = false;
                } else {
                    $('#category_error' + categoryId).text('');
                }
            });

            // Validate service fields
            $('.service-select').each(function() {
                var serviceId = $(this).attr('id');
                if (!$(this).val()) {
                    $('#service_error' + serviceId).text('Please select a service.');
                    isValid = false;
                } else {
                    $('#service_error' + serviceId).text('');
                }
            });

            // Validate price fields
            $('.price-input').each(function() {
                var priceId = $(this).attr('id');
                if (!$(this).val()) {
                    $('#price_error' + priceId).text('Please enter a price.');
                    isValid = false;
                } else if (isNaN($(this).val()) || $(this).val() <= 0) {
                    $('#price_error' + priceId).text('Please enter a valid price.');
                    isValid = false;
                } else {
                    $('#price_error' + priceId).text('');
                }
            });

            // If any validation fails, prevent form submission
            if (!isValid) {
                event.preventDefault();
            }
        });

        // Call this function initially to handle pre-selected categories
        disableSelectedCategories();
    });
</script>


@endsection
