@extends('backend.layouts.app')
@section('content')
    <style>
        .disabled {
            pointer-events: none;
        }

        .btn-danger {
            display: none;
            /* Ensure it's hidden by default */
        }

        .dev-hide {
            display: none !important;
        }
        .service-section.bg-primary {
            color: white;
        }
        .pop-service-section{
            margin-right: 2%;
        }
    </style>
    <div class="content-wrapper page_content_section_hp">
        <div class="container-xxl">
            <div class="client_list_area_hp Add_order_page_section">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="client_list_heading_area">
                                    <h4>
                                        Add Order
                                    </h4>
                                </div>
                            </div>

                        </div>
                        <form action="{{ route('add.order') }}" method="POST" id="addOrderFormValidation" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6 col-md-6 mb-2">
                                    <!-- Form Inputs for Client and Order Details -->
                                    <div class="row">
                                        <!-- Client Number -->
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-12 mb-3">
                                            <div class="form-group">
                                                <label for="client_num" class="form-label">Client Number</label>
                                                <input type="text" value="{{ old('mobile', $order->mobile ?? '') }}"
                                                    id="number" name="client_num" class="form-control"
                                                    placeholder="Client Number" />
                                            </div>
                                        </div>
                                        <!-- Client Name -->
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-12 mb-3">
                                            <div class="form-group">
                                                <label for="client_name" class="form-label">Client Name</label>
                                                <input type="text" id="client_name"
                                                    value="{{ old('name', $order->name ?? '') }}" name="client_name"
                                                    class="form-control" placeholder="Client Name" />
                                            </div>
                                        </div>
                                        <!-- Booking Date -->
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-12 mb-3">
                                            <div class="form-group">
                                                <input type="hidden" id="booking_date" value="{{ $currentdate }}"
                                                    name="booking_date" class="form-control" />
                                            </div>
                                        </div>
                                        <!-- Booking Time -->
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-12 mb-3">
                                            <div class="form-group">
                                                <input type="hidden" id="booking_time" value="{{ $currenttime }}"
                                                    name="booking_time" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="col-xl-12">

                                        </div>
                                        <!-- Gross Total Section -->
                                        <div class="col-xl-12 col-lg-12 col-md-12 col-12 mb-3">
                                            <div class="row justify-content-between">
                                                <input type="hidden" name="gross_total" id="gross_total" />
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-12">
                                                    <h6>Gross Total:</h6>
                                                </div>
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-12 text-end">
                                                    <h6 id="grossTotal">0.0</h6>
                                                </div>
                                            </div>
                                            <div class="row justify-content-between">
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-12">
                                                    <h6>Discount Amount:</h6>
                                                </div>
                                                <div id="discountAmount" class="col-xl-4 col-lg-4 col-md-4 col-12 text-end">
                                                    <h6>0.0</h6>
                                                </div>
                                            </div>
                                            <div class="row justify-content-between">
                                                <input type="hidden" name="total_qty" id="total_qty" />
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-12">
                                                    <h6>Total Count:</h6>
                                                </div>
                                                <div id="totalQty" class="col-xl-4 col-lg-4 col-md-4 col-12 text-end">
                                                    <h6>0 pc</h6>
                                                </div>
                                            </div>
                                            <div class="row justify-content-between">
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-12">
                                                    <h6>Total Amount:</h6>
                                                </div>
                                                <div id="totalAmount" class="col-xl-4 col-lg-4 col-md-4 col-12 text-end">
                                                    <h6>0</h6>
                                                </div>
                                            </div>
                                            <hr class="px-2">
                                        </div>
                                        <!-- Delivery Date -->
                                        <div class="col-xl-4 col-lg-4 col-md-4 col-12 mb-3">
                                            <div class="form-group">
                                                <label for="delivery_date" class="form-label">Delivery Date</label>
                                                <input type="date" id="delivery_date"
                                                    value="{{ old('delivery_date', $order->delivery_date ?? '') }}"
                                                    name="delivery_date" class="form-control" />
                                            </div>
                                        </div>
                                        <!-- Delivery Time -->
                                        <div class="col-xl-4 col-lg-4 col-md-4 col-12 mb-3">
                                            <div class="form-group">
                                                <label for="delivery_time" class="form-label">Delivery Time</label>
                                                <select id="delivery_time" name="delivery_time" class="form-control valid">
                                                    @foreach ($timeSlots['time_ranges'] as $time)
                                                        <option value="{{ $time['start'] }}"
                                                            {{ old('delivery_time', $order->delivery_time ?? '') == $time['start'] ? 'selected' : '' }}>
                                                            {{ $time['range'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <!-- Discount Offer -->
                                        <div class="col-xl-4 col-lg-4 col-md-4 col-12 mb-3">
                                            <div class="form-group">
                                                <label for="discount" class="form-label">Discount Offer</label>
                                                <select name="discount" id="discount" class="form-select">
                                                    <option value="0" selected>Select Discount Offer</option>
                                                    @foreach ($discounts as $discount)
                                                        <option value="{{ $discount->amount }}">{{ $discount->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="Add_order_btn_area text-end">
                                                <button class="btn w-100" type="button" data-bs-toggle="modal"
                                                    data-bs-target="#CreateOrder">Save</button>
                                            </div>
                                        </div>
                                        <!-- Create Order Model -->
                                        <div class="modal fade" id="CreateOrder" tabindex="-1"
                                            aria-labelledby="CreateOrderLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="CreateOrderLabel">Create Order</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body text-center">
                                                        <h5>Would you like to Create a New Order?</h5>
                                                        <button type="submit" class="btn btn-primary" id="yesButton"
                                                            data-bs-toggle="modal" data-bs-target="#yes">Yes</button>
                                                        <button type="button" class="btn btn-primary"
                                                            data-bs-dismiss="modal">No</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- end -->
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 mb-2">
                                    <!-- Product Items Section -->
                                    <div class="client_list_area_hp">
                                        <div class="client_list_heading_area w-100">
                                            <div class="client_list_heading_search_area w-100">
                                                <i class="menu-icon tf-icons ti ti-search"></i>
                                                <input type="search" class="form-control" placeholder="Searching ..." id="searchItem" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" id="searchData">
                                        <div id="productItemError" class="alert alert-danger" style="display: none;">
                                            Please add at least one product item.
                                        </div>
                                        <!-- Loop through the products and their grouped details -->
                                        @foreach ($groupedProductItems as $groupedProductItem)
                                            @php
                                                $productItem = $groupedProductItem['product_item'];
                                                $groupedDetails = $groupedProductItem['grouped_details'];
                                            @endphp
                                            <div class="border rounded p-2 mb-2">
                                                <div class="row">
                                                    <div class="col-lg-9 col-md-9 mainopdiv">
                                                        <h6 class="mb-2 text-dark searchProductName" data-name="{{ $productItem->name }}">{{ $productItem->name }}</h6>

                                                        <div id="categories-{{ $productItem->id }}" class="category-section mb-3">
                                                            <!-- Display all categories for this product -->
                                                            @foreach ($groupedDetails as $category => $services)
                                                                <span
                                                                    onclick="selectCategory(this, '{{ $category }}', '{{ $productItem->id }}')"
                                                                    class="badge mb-2 subcategory bg-secondary"
                                                                    id="category-{{ $productItem->id }}-{{ $category }}">{{ $category }}
                                                                </span>
                                                            @endforeach
                                                        </div>

                                                        <div id="service-group-{{ $productItem->id }}" class="service-group">
                                                            <!-- Show services for the first category by default -->
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 text-center">
                                                        <img class="mb-2" src="{{ url('images/categories_img/' . $productItem->image) }}" alt="{{ $productItem->name }}" style="width: 50px;">
                                                        <div class="Add_order_btn_area">
                                                            <button type="button" id="addbtnpreview" class="btn add-product-btn" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight" data-product-name="{{ $productItem->name }}" data-images="{{ url('images/categories_img/' . $productItem->image) }}" data-product-id="{{ $productItem->id }}">Add</button>
                                                            <button class="btn btn-danger dev-hide waves-effect waves-light" id="productId{{ $productItem->id }}" type="button" onclick="removeProductItem('{{ $productItem->id }}')">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- Offcanvas Right Panel -->
                                    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
                                        <div class="offcanvas-header border-bottom">
                                            <h5 id="offcanvasRightLabel">Item Details</h5>
                                            <button id="addOrderModel" type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                        </div>
                                        <div class="offcanvas-body mainopdiv">
                                            <!-- Product Name -->
                                            <div class="border-bottom mb-4">
                                                <h6 class="mb-2 text-dark" id="popupProductName">Select Product Name</h6>
                                            </div>

                                            <!-- Categories -->
                                            <div class="border-bottom mb-4">
                                                <h6 class="mb-2 text-dark">Select Categories</h6>
                                                <div id="popupCategories">
                                                    <!-- Categories will be dynamically populated here -->
                                                </div>
                                            </div>

                                            <!-- Services -->
                                            <div class="border-bottom mb-4">
                                                <h6 class="mb-2 text-dark">Select Services</h6>
                                                <div id="popupServices">
                                                    <!-- Services will be dynamically populated based on selected category -->
                                                </div>
                                            </div>

                                            <!-- Garment Details (optional section based on requirements) -->
                                            <div class="border-bottom mb-4" id="garmentDetailsContainer" style="display: none;">
                                                <h6 class="mb-2 text-dark">Garment Details</h6>
                                                <div id="garmentDetails">
                                                    <!-- Garment details will be dynamically populated here -->
                                                </div>
                                                <button type="button" class="btn btn-success mt-2" id="addGarmentBtn">Add Garment</button>
                                            </div>

                                            <!-- Quantity Input -->
                                            <div class="border-bottom mb-4">
                                                <div class="input-group">
                                                    <label for="qtyPlsMns" class="form-label">Count</label>
                                                    <input type="hidden" class="form-control" value="" id="productName" name="productName" placeholder=""/>
                                                    <input type="hidden" class="form-control" value="" id="productCategory" name="productCategory" placeholder=""/>
                                                    <input type="hidden" class="form-control" value="" id="productservice" name="productservice" placeholder=""/>
                                                    <input type="hidden" class="form-control" value="" id="productprice" name="productprice" placeholder=""/>
                                                    <div class="input-group mb-3">
                                                        <button type="button" class="input-group-text decrease"><i class="fa-solid fa-minus"></i></button>
                                                        <input type="text" class="form-control text-center piece-count" value="0" id="qtyPlsMns" name="qty" placeholder="0"/>
                                                        <button type="button" class="input-group-text increase"><i class="fa-solid fa-plus"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="offcanvas-footer px-4 pb-2">
                                            <button type="button" id="addRightOdrbtn" class="btn w-100 btn-primary">Add</button>
                                        </div>
                                    </div>
                                </div>
                                <!-- Create Order Modal -->
                                <div class="modal fade" id="CreateOrder" tabindex="-1" aria-labelledby="CreateOrderLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="CreateOrderLabel">Create Order</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body text-center">
                                                <h5>Would you like to create a new order?</h5>
                                                <button type="submit" class="btn btn-primary" id="yesButton"
                                                    data-bs-toggle="modal" data-bs-target="#yes">Yes</button>
                                                <button type="button" class="btn btn-primary"
                                                    data-bs-dismiss="modal">No</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script>
            var groupedProductItems = @json($groupedProductItems); // Passing PHP data to JavaScript

            function selectCategory(element, category, productId) {
                // Get the services for the selected category
                var productItem = groupedProductItems.find(item => item.product_item.id === parseInt(productId));
                var services = productItem.grouped_details[category];

                // Update the services in the service group div
                var serviceGroup = document.getElementById('service-group-' + productId);
                serviceGroup.innerHTML = ''; // Clear previous content

                var serviceDetailsGrid = document.createElement('div');
                serviceDetailsGrid.classList.add('service-details-grid');

                // Select the categories specifically for the current product
                var categorySection = document.querySelector('#categories-' + productId);  // Ensure the category section is specific to this product
                var categoryBadges = categorySection.children;  // Get the badges for the current product

                // Loop through the category badges to activate the selected one
                for (var i = 0; i < categoryBadges.length; i++) {
                    var badgeText = categoryBadges[i].textContent.trim().replace(/,/g, '');
                    var cleanCategory = category.replace(/,/g, '').trim();

                    if (badgeText === cleanCategory) {
                        categoryBadges[i].classList.add('bg-primary');
                    } else {
                        categoryBadges[i].classList.remove('bg-primary');
                    }
                }

                // Loop through the services and populate the details
                for (var service in services) {
                    var serviceSection = document.createElement('div');
                    serviceSection.classList.add('service-section');

                    // Create the service name element
                    var serviceName = document.createElement('div');
                    serviceName.classList.add('service-name');
                    serviceName.textContent = service;

                    // Create the service details element
                    var serviceDetails = document.createElement('div');
                    serviceDetails.classList.add('service-details');

                    services[service].forEach(function(detail) {
                        var priceItem = document.createElement('div');
                        priceItem.classList.add('price-item');

                        var priceValue = document.createElement('div');
                        priceValue.classList.add('price-value');
                        priceValue.textContent = '₹ ' + detail.price + '/pc';

                        priceItem.appendChild(priceValue);
                        serviceDetails.appendChild(priceItem);
                    });

                    serviceSection.appendChild(serviceName);
                    serviceSection.appendChild(serviceDetails);
                    serviceDetailsGrid.appendChild(serviceSection);
                }

                serviceGroup.appendChild(serviceDetailsGrid);
            }


            // Initialize the first category for each product on page load
            document.addEventListener('DOMContentLoaded', function() {
                @foreach ($groupedProductItems as $groupedProductItem)
                    @php
                        $firstCategory = array_key_first($groupedProductItem['grouped_details']->toArray());
                    @endphp
                    selectCategory(
                        document.getElementById('category-{{ $groupedProductItem['product_item']->id }}-{{ $firstCategory }}'),
                        '{{ $firstCategory }}',
                        '{{ $groupedProductItem['product_item']->id }}'
                    );
                @endforeach
            });


            // Add click event listener for the "Add" button
            document.querySelectorAll('.add-product-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var productName = btn.getAttribute('data-product-name');
                    var productId = btn.getAttribute('data-product-id');

                    // Update the product name in the offcanvas
                    document.getElementById('popupProductName').textContent = productName;
                    $('#productName').val(productName);

                    // Get the product item from the groupedProductItems array
                    var productItem = groupedProductItems.find(item => item.product_item.id === parseInt(productId));
                    var categories = productItem.grouped_details;

                    // Populate categories in the offcanvas
                    var categoryContainer = document.getElementById('popupCategories');
                    categoryContainer.innerHTML = ''; // Clear existing categories

                    for (var category in categories) {
                        var categoryBadge = document.createElement('span');
                        categoryBadge.textContent = category;
                        categoryBadge.classList.add('badge', 'mb-2', 'subcategory', 'bg-secondary','pop-service-section');
                        categoryBadge.onclick = function() {

                            selectPopupCategory(this, this.textContent, productId);
                        };
                        categoryContainer.appendChild(categoryBadge);
                    }

                    // Automatically select the first category and show services
                    var firstCategory = Object.keys(categories)[0];
                    selectPopupCategory(null, firstCategory, productId);
                });
            });

            function selectPopupCategory(element, category, productId) {
                // Get the services for the selected category
                var productItem = groupedProductItems.find(item => item.product_item.id === parseInt(productId));
                var services = productItem.grouped_details[category];


                // Update services in the popup
                var serviceGroup = document.getElementById('popupServices');
                serviceGroup.innerHTML = ''; // Clear previous services

                var serviceDetailsGrid = document.createElement('div');
                serviceDetailsGrid.classList.add('service-details-grid');

                var categoryBadges = document.getElementById('popupCategories').children;
                for (var i = 0; i < categoryBadges.length; i++) {
                    if (categoryBadges[i].textContent === category) {
                        categoryBadges[i].classList.add('bg-primary');
                        $('#productCategory').val(categoryBadges[i].textContent);
                    } else {
                        categoryBadges[i].classList.remove('bg-primary');
                    }
                }

                // Use let here to create block scope for each iteration
                for (let service in services) {
                    var serviceSection = document.createElement('div');
                    serviceSection.classList.add('service-section');

                    // Set the data-category attribute for the service section
                    serviceSection.setAttribute('data-service', service);

                    // serviceSection.setAttribute('data-price', detail.price);

                    // Make the service clickable by adding a click event listener
                    serviceSection.addEventListener('click', function() {
                        handleServiceClick(service, services[service]); // Call function when clicked
                    });

                    // Create the service name element
                    var serviceName = document.createElement('div');
                    serviceName.classList.add('service-name');
                    serviceName.textContent = service;

                    // Create the service details element
                    var serviceDetails = document.createElement('div');
                    serviceDetails.classList.add('service-details');

                    services[service].forEach(function(detail) {
                        var priceItem = document.createElement('div');
                        priceItem.classList.add('price-item');

                        var priceValue = document.createElement('div');
                        priceValue.classList.add('price-value');
                        priceValue.textContent = '₹ ' + detail.price + '/pc';

                        priceItem.appendChild(priceValue);
                        serviceDetails.appendChild(priceItem);
                    });

                    serviceSection.appendChild(serviceName);
                    serviceSection.appendChild(serviceDetails);
                    serviceDetailsGrid.appendChild(serviceSection);
                }

                serviceGroup.appendChild(serviceDetailsGrid);
            }

            // This is the function that will handle the click event

            // Maintain state for selected services
            let selectedServices = [];

            function handleServiceClick(serviceName, serviceDetails) {
                // Find the selected service details
                const selectedService = serviceDetails.find(detail => detail.service === serviceName);

                if (!selectedService) return; // Exit if no matching service is found

                // Case 1: If 'DC' is selected, deselect 'SP' and 'ST'
                if (serviceName === 'DC') {
                    // Deselect SP and ST
                    $("#popupServices .service-details-grid .service-section[data-service='SP']").removeClass("bg-primary");
                    $("#popupServices .service-details-grid .service-section[data-service='ST']").removeClass("bg-primary");

                    // Clear the selected services array and add only DC
                    selectedServices = [selectedService];

                    // Set the DC service name and price to input fields
                    $("#productservice").val(selectedService.service);
                    $("#productprice").val(selectedService.price); // Set only DC price
                    $("#popupServices .service-details-grid .service-section[data-service='" + serviceName + "']").addClass("bg-primary");

                    return; // Exit here because only DC is selected
                }

                // Case 2: If 'SP' or 'ST' is selected and DC is already selected, clear inputs
                if (selectedServices.some(service => service.service === 'DC')) {
                    // Clear input fields and deselect DC
                    $("#productservice").val('');
                    $("#productprice").val('');
                    selectedServices = []; // Reset selected services
                    $("#popupServices .service-details-grid .service-section[data-service='DC']").removeClass("bg-primary");
                }

                // Case 3: If 'SP' is selected
                if (serviceName === 'SP') {
                    // Check if 'SP' is already selected; if yes, remove it, otherwise add it
                    const index = selectedServices.findIndex(service => service.service === 'SP');
                    if (index !== -1) {
                        selectedServices.splice(index, 1); // Remove 'SP' if it's already selected
                    } else {
                        selectedServices.push(selectedService); // Add 'SP'
                    }
                }

                // Case 4: If 'ST' is selected
                else if (serviceName === 'ST') {
                    // Check if 'ST' is already selected; if yes, remove it, otherwise add it
                    const index = selectedServices.findIndex(service => service.service === 'ST');
                    if (index !== -1) {
                        selectedServices.splice(index, 1); // Remove 'ST' if it's already selected
                    } else {
                        selectedServices.push(selectedService); // Add 'ST'
                    }
                }

                // Calculate the total price correctly
                const totalPrice = selectedServices.reduce((sum, service) => {
                    return sum + parseFloat(service.price); // Ensure price is a number before summing
                }, 0);

                // Create a comma-separated service name string
                const selectedNames = selectedServices.map(service => service.service).join(", ");

                // Set the combined service names and total price to input fields
                $("#productservice").val(selectedNames);
                $("#productprice").val(totalPrice); // Set total price and ensure it's in correct format

                // Toggle 'bg-primary' class for the selected service
                $("#popupServices .service-details-grid .service-section[data-service='" + serviceName + "']").toggleClass("bg-primary");
            }


            document.addEventListener("DOMContentLoaded", function () {
                const $numberInput = $("#number");
                const $clientNameInput = $("#client_name");
                const $searchItemInput = $('#searchItem');
                const $searchData = $('#searchData .border');
                const $productItemError = $('#productItemError');

                // Debounce function to limit the rate of function execution
                function debounce(fn, delay) {
                    let timeoutId;
                    return function (...args) {
                        if (timeoutId) clearTimeout(timeoutId);
                        timeoutId = setTimeout(() => fn.apply(this, args), delay);
                    };
                }

                // Fetch client name when number input length is 10
                $numberInput.on("keyup", debounce(function () {
                    const clientNum = $(this).val().trim();

                    if (clientNum.length === 10) {
                        $.ajax({
                            url: "/admin/fetch-client-name",
                            method: "GET",
                            data: { client_num: clientNum },
                            success: (response) => {
                                if (response.success) {
                                    $clientNameInput.val(response.client_name);
                                } else {
                                    console.error(response.message);
                                }
                            },
                            error: (xhr, status, error) => console.error("Error fetching client name:", error),
                        });
                    } else if (clientNum.length < 10) {
                        $clientNameInput.val(''); // Clear the client name input
                    }
                }, 300));  // Debounce with 300ms delay

                // Search product items by name
                $searchItemInput.on('keyup', debounce(function () {
                    const searchValue = $(this).val().toLowerCase();

                    let visibleCount = 0;
                    $searchData.each(function () {
                        const productName = $(this).find('.searchProductName').data('name').toLowerCase();
                        const isVisible = productName.includes(searchValue);
                        $(this).toggle(isVisible);

                        if (isVisible) visibleCount++;
                    });

                    // Show or hide the error message based on visible product count
                    $productItemError.toggle(visibleCount === 0);
                }, 300));  // Debounce with 300ms delay
            });


            // Get references to the elements
            const qtyInput = document.getElementById("qtyPlsMns");
            const increaseBtn = document.querySelector(".increase");
            const decreaseBtn = document.querySelector(".decrease");

            // Increase button click event
            increaseBtn.addEventListener("click", function() {
                let currentValue = parseInt(qtyInput.value);
                if (!isNaN(currentValue)) {
                    qtyInput.value = currentValue + 1; // Increment value by 1
                }
            });

            // Decrease button click event
            decreaseBtn.addEventListener("click", function() {
                let currentValue = parseInt(qtyInput.value);
                if (!isNaN(currentValue) && currentValue > 0) {
                    qtyInput.value = currentValue - 1; // Decrement value by 1 (but not below 0)
                }
            });

            document.getElementById('addRightOdrbtn').addEventListener('click', function () {
                var itemName = document.getElementById('productName').value;
                var itemCategory = document.getElementById('productCategory').value;
                var itemService = document.getElementById('productservice').value;
                var itemQty = parseInt(document.getElementById('qtyPlsMns').value);
                var itemPrice = parseFloat(document.getElementById('productprice').value);
                var total = itemQty * itemPrice;

                // Check if we are updating an existing item
                var existingRow = document.querySelector(`.row.border[data-item-name="${itemName}"][data-item-category="${itemCategory}"]`);

                if (existingRow) {
                    // Update the existing row
                    var existingQtyElement = existingRow.querySelector('#itemqty');
                    var existingPriceElement = existingRow.querySelector('#qtyxprice');

                    var newQty = itemQty;  // Update quantity directly from input
                    var newTotal = newQty * itemPrice;

                    // Update the DOM with the new values
                    existingQtyElement.textContent = newQty;
                    existingPriceElement.textContent = newTotal.toFixed(2);
                } else {
                    // Add new row if item doesn't exist
                    var rowDiv = document.createElement('div');
                    rowDiv.classList.add('row', 'border');
                    rowDiv.dataset.itemName = itemName;
                    rowDiv.dataset.itemCategory = itemCategory;

                    rowDiv.innerHTML = `
                        <div class="col-md-1">
                            <i class="fa fa-trash" aria-hidden="true"></i>
                            <i class="fa fa-pencil edit-item" aria-hidden="true"></i>
                        </div>
                        <div class="col-md-8">
                            <input type="hidden" name="itemname" value="${itemName}">
                            <input type="hidden" name="itemcategory" value="${itemCategory}">
                            <input type="hidden" name="itemservice" value="${itemService}">
                            <input type="hidden" name="itemqty" value="${itemQty}">
                            <input type="hidden" name="itemprice" value="${itemPrice.toFixed(2)}">
                            <input type="hidden" name="qtyxprice" value="${total.toFixed(2)}">
                            <p><span id="itemname">${itemName}</span> <span id="itemcategory">(${itemCategory})</span></p>
                            <p>Service: (<span id="itemservice">${itemService}</span>)</p>
                        </div>
                        <div class="col-md-3">
                            <p><span id="itemqty">${itemQty}</span> x <span id="itemprice">${itemPrice.toFixed(2)}</span> = <span id="qtyxprice">${total.toFixed(2)}</span></p>
                        </div>
                    `;

                    document.querySelector('.col-xl-12').appendChild(rowDiv);

                    // Add event listener to the edit button
                    rowDiv.querySelector('.edit-item').addEventListener('click', function () {
                        openEditItemPopup(itemName, itemCategory, itemService, itemQty, itemPrice);
                    });
                }

                resetOffcanvasInputs(); // Reset input fields after adding/updating

                var offcanvasElement = document.getElementById('offcanvasRight');
                var offcanvasInstance = bootstrap.Offcanvas.getInstance(offcanvasElement);
                if (!offcanvasInstance) {
                    offcanvasInstance = new bootstrap.Offcanvas(offcanvasElement);
                }
                offcanvasInstance.hide();
            });

            // Function to reset the input fields
            function resetOffcanvasInputs() {
                document.getElementById('productName').value = '';
                document.getElementById('productCategory').value = '';
                document.getElementById('productservice').value = '';
                document.getElementById('qtyPlsMns').value = 0;
                document.getElementById('productprice').value = '';
            }

            // Function to open the edit item popup with pre-filled values
            function openEditItemPopup(itemName, itemCategory, itemService, itemQty, itemPrice) {
                document.getElementById('productName').value = itemName;
                document.getElementById('productCategory').value = itemCategory;
                document.getElementById('productservice').value = itemService;
                document.getElementById('qtyPlsMns').value = itemQty;
                document.getElementById('productprice').value = itemPrice;
                // Sabse pehle purane selected services ko clear kar do
                $("#popupServices .service-details-grid .service-section").removeClass("bg-primary");

                // Check karo ki itemService defined aur valid ho
                if (itemService && itemService.length > 0) {
                    // Convert the comma-separated category string into an array
                    var serviceArray = itemService.split(',').map(service => service.trim());

                    // Define karo services list jo check karni hai
                    const services = ['DC', 'SP', 'ST'];

                    // Naye services ko set karo
                    services.forEach(service => {
                        if (serviceArray.includes(service)) {
                            $(`#popupServices .service-details-grid .service-section[data-service='${service}']`).addClass("bg-primary");
                        }
                    });
                }

                var offcanvasElement = document.getElementById('offcanvasRight');
                var offcanvasInstance = bootstrap.Offcanvas.getInstance(offcanvasElement);
                if (!offcanvasInstance) {
                    offcanvasInstance = new bootstrap.Offcanvas(offcanvasElement);
                }
                offcanvasInstance.show();
            }
        </script>
    @endsection
