@extends('admin.layout')

@section('title', 'Edit Menu')

@section('styles-links')

@endsection

@section('sidebar')
    <li><a href="{{ route('admin.dashboard') }}" class="fs-5 sidebar-font"><i class="fa-solid fa-house me-3"></i>Dashboard</a>
    </li>
    <li><a href="/admin/menu" class="active fs-5 sidebar-font"><i class="fa-solid fa-utensils me-3"></i> Menu</a></li>
    <li class="add-categ"><a href="/admin/category" class="sidebar-font"><i class="fa-solid fa-list me-2"></i> Category</a></li>

    <li>
        <a href="/admin/delivery" class="fs-5 sidebar-font"><i class="fa-solid fa-truck-fast me-3"></i>Delivery</a>
    </li>

    <li class="sidebar-item" id="customersDropdown">
        <a href="javascript:void(0)"
            class="fs-5 sidebar-font d-flex customers justify-content-between {{ request()->is('admin/updates', 'admin/feedback', 'admin/monitoring') ? 'active' : '' }}">
            <div><i class="fa-solid fa-users me-3"></i>Customers</div>
            <div class="caret-icon">
                <i class="fa-solid fa-caret-right"></i>
            </div>
        </a>
        <!-- Dropdown menu -->
        <ul class="dropdown-customers" style="display: none;">
            <li><a href="{{ route('admin.updates') }}"
                    class="{{ request()->routeIs('admin.updates') ? 'active-customer-route' : '' }}"><i
                        class="fa-solid fa-user-pen me-2"></i>Customer Updates</a>
            </li>
            <li><a href="{{ route('admin.feedback') }}"
                    class="{{ request()->routeIs('admin.feedback') ? 'active-customer-route' : '' }}"><i
                        class="fa-solid fa-comments me-2"></i>Feedback
                    Collection</a></li>
            {{-- <li><a href="{{ route('admin.monitoring') }}"
                    class="{{ request()->routeIs('admin.monitoring') ? 'active-customer-route' : '' }}"><i
                        class="fa-solid fa-users-gear me-2"></i><span class="monitor-margin">Customer Activity</span>
                    <span class="monitor-margin">Monitoring</span></a></li> --}}
            <li><a href="{{ route('admin.customerMessages') }}"
                    class="{{ request()->routeIs('admin.customerMessages') ? 'active-customer-route' : '' }}"><i
                        class="fa-solid fa-message me-2"></i> Customer Messages</a></li>
        </ul>
    </li>

@endsection

@section('main-content')
    <div class="main-content">

        <div class="current-file mb-3 d-flex">
            <div class="fw-bold"><i class="fa-solid fa-house me-2"></i><a href="{{ route('admin.dashboard') }}"
                    class="navigation">Dashboard</a> / <a href="/admin/menu" class="navigation">Menu</a> /</div>
            <span class="faded-white ms-1">Edit Menu</span>
        </div>

        <div class="table-container p-4 text-black mb-4">

            <div class="taas-table d-flex justify-content-center align-items-center">
                <div class="h2"><i class="fa fa-edit me-2"></i>Edit Menu</div>
            </div>

            <form action="{{ route('admin.menu.update', $menus->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT') <!-- Use PUT method for updating -->

                <!-- Name -->
                <div class="mb-3 d-flex flex-column justify-content-start align-items-start">
                    <label for="name" class="form-label">Name:</label>
                    <input type="text" name="name" class="form-control" id="name"
                        value="{{ old('name', $menus->name) }}" required autofocus>
                </div>

                <div class="mb-3 w-100 d-flex justify-content-center align-items-start gap-2">

                    <!-- Price -->
                    <div class="w-75">
                        <label for="price" class="form-label">Price:</label>
                        <input type="text" name="price" class="form-control" id="price"
                            value="{{ old('price', $menus->price) }}" required>
                    </div>

                    <!-- Discount -->
                    <div class="w-100 d-flex flex-column h-50">
                        <label for="discount" class="form-label">Discount (%):</label>
                        <div class="d-flex gap-1">
                            <div>
                                <input type="number" name="discount" class="form-control" id="discount"
                                    value="{{ old('discount', $menus->discount) }}" min="0" max="100" required>
                            </div>
                            <div>
                                <button type="button" class="btn btn-danger" id="removeDiscountButton">Remove
                                    Discount</button>
                            </div>
                        </div>
                    </div>

                    <!-- Category -->
                    <div class="w-75">
                        <label for="category" class="form-label">Category:</label>
                        <select class="form-select" required name="category" id="category">
                            <option value="" disabled>Select Category</option>

                            <!-- Loop through categories and create option elements -->
                            @foreach ($categories as $category)
                                <option value="{{ $category->category }}"
                                    {{ old('category', $menus->category) === $category->category ? 'selected' : '' }}>
                                    {{ $category->category }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Availability -->
                    <div class="w-75 ms-2">
                        <label for="availability" class="form-label">Availability:</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="availability" name="availability"
                                value="Available"
                                {{ old('availability', $menus->availability) === 'Available' ? 'checked' : '' }}>
                            <label class="form-check-label" for="availability">{{ $menus->availability }}</label>
                        </div>
                    </div>
                </div>

                <!-- Current Image -->
                <div class="mb-3 d-flex flex-column justify-content-start align-items-start">
                    <label for="current_image" class="form-label">Current Image:</label>
                    <img src="{{ Storage::url($menus->image) }}" alt="{{ $menus->name }}" class="img-fluid"
                        width="150">
                </div>

                <!-- New Image -->
                <div class="mb-3 d-flex flex-column justify-content-start align-items-start">
                    <label for="image" class="form-label">New Image (optional):</label>
                    <input type="file" name="image" class="form-control" id="image" accept="image/*"
                        onchange="previewImage(event)">
                    <img id="imagePreview" src="#" alt="Selected Image Preview"
                        style="display:none; width:150px; margin-top:10px;">
                    @error('image')
                        <div class="error alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-3 d-flex flex-column justify-content-start align-items-start">
                    <label for="description" class="form-label">Description:</label>
                    <textarea name="description" id="description" cols="30" rows="5" class="form-control" required>{{ old('description', $menus->description) }}</textarea>
                </div>

                <div class="d-grid my-2">
                    <button class="btn btn-primary dark-blue" type="submit">Update Menu</button>
                </div>
            </form>

        </div>

    </div>
@endsection

@section('scripts')
    {{-- Remove Discount --}}
    <script>
        document.getElementById('removeDiscountButton').addEventListener('click', function() {
            document.getElementById('discount').value = 0; // Reset discount to 0
        });
    </script>

    {{-- Availability --}}
    <script>
        const availabilityToggle = document.getElementById('availability');
        const availabilityLabel = availabilityToggle.nextElementSibling;

        availabilityToggle.addEventListener('change', () => {
            availabilityLabel.textContent = availabilityToggle.checked ? 'Available' : 'Unavailable';
        });
    </script>
@endsection
