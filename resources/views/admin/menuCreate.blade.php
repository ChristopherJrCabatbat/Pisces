@extends('admin.layout')

@section('title', 'Add Menu')

@section('styles-links')

@endsection

@section('sidebar')
    <li><a href="{{ route('admin.dashboard') }}" class="fs-5 sidebar-font"><i class="fa-solid fa-house me-3"></i>Dashboard</a>
    </li>
    <li><a href="#" class="active fs-5 sidebar-font"><i class="fa-solid fa-utensils me-3"></i> Menu</a></li>
    <li class="add-categ"><a href="{{ route('admin.menuCreateCategory') }}" class="sidebar-font"><i
                class="fa-solid fa-plus me-2"></i> Add Category</a></li>
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
            <li><a href="{{ route('admin.monitoring') }}"
                    class="{{ request()->routeIs('admin.monitoring') ? 'active-customer-route' : '' }}"><i
                        class="fa-solid fa-users-gear me-2"></i>Customer Activity
                    <span class="monitor-margin">Monitoring</span></a></li>
        </ul>
    </li>

@endsection

@section('main-content')
    <div class="main-content">

        <div class="current-file mb-3 d-flex">
            <div class="fw-bold"><i class="fa-solid fa-house me-2"></i><a href="{{ route('admin.dashboard') }}"
                    class="navigation">Dashboard</a> / <a href="/admin/menu" class="navigation">Menu</a> /</div>
            <span class="faded-white ms-1">Add Menu</span>
        </div>

        <div class="table-container p-4 text-black mb-4">

            <div class="taas-table d-flex justify-content-center align-items-center">
                <div class="h2"><i class="fa-solid fa-plus me-2"></i>Add Menu</div>
            </div>

            <form action="{{ route('admin.menu.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Name -->
                <div class="mb-3 d-flex flex-column justify-content-start align-items-start">
                    <label for="name" class="form-label">Name:</label>
                    <input type="text" name="name" class="form-control" id="name" value="{{ old('name') }}"
                        required placeholder="e.g. Pisces Pizza" autofocus>
                </div>

                <!-- Price -->
                <div class="mb-3 d-flex flex-column justify-content-start align-items-start">
                    <label for="price" class="form-label">Price:</label>
                    <input type="text" name="price" class="form-control" id="price" value="{{ old('price') }}"
                        required placeholder="e.g. 199">
                    @error('price')
                        <div class="error alert alert-danger">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Category -->
                <div class="mb-3 d-flex flex-column justify-content-start align-items-start">
                    <label for="category" class="form-label">Category:</label>
                    <select class="form-select" required name="category" id="category">
                        <option value="" disabled selected>Select Category</option>

                        <!-- Loop through categories and create option elements -->
                        @foreach ($categories as $category)
                            <option value="{{ $category->category }}"
                                {{ old('category') === $category->category ? 'selected' : '' }}>
                                {{ $category->category }}
                            </option>
                        @endforeach
                    </select>
                </div>


                <!-- Image -->
                <div class="mb-3 d-flex flex-column justify-content-start align-items-start">
                    <label for="image" class="form-label">Image:</label>
                    <input type="file" name="image" class="form-control" id="image" accept="image/*"
                        onchange="previewImage(event)" required>
                    <img id="imagePreview" src="#" alt="Selected Image Preview"
                        style="display:none; width:150px; margin-top:10px;">
                    @error('image')
                        <div class="error alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-3 d-flex flex-column justify-content-start align-items-start">
                    <label for="description" class="form-label">Description:</label>
                    <textarea name="description" id="description" cols="30" rows="5" class="form-control" required
                        placeholder="Enter menu description here...">{{ old('description') }}</textarea>
                </div>

                <div class="d-grid my-2">
                    <button class="btn btn-primary dark-blue" type="submit">Add Menu</button>
                </div>
            </form>

        </div>

    </div>
@endsection

@section('scripts')
@endsection
