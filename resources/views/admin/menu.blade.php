@extends('admin.layout')

@section('title', 'Admin')

@section('styles-links')
@endsection

@section('sidebar')
    <li><a href="{{ route('admin.dashboard') }}" class="fs-5"><i class="fa-solid fa-house me-3"></i>Dashboard</a></li>
    <li><a href="#" class="active fs-5"><i class="fa-solid fa-utensils me-3"></i> Menu</a></li>
    <li><a href="#" class="fs-5"><i class="fa-solid fa-utensils me-3"></i> Users</a></li>
@endsection

@section('main-content')
    <div class="main-content">

        <div class="current-file mb-3 d-flex">
            <div class="fw-bold"><i class="fa-solid fa-house me-2"></i>Dashboard /</div> <span
                class="faded-white ms-1">Menu</span>
        </div>

        <div class="table-container">

            <div class="taas-table mb-3 d-flex justify-content-between align-items-center">
                <!-- Left Section -->
                <div class="left d-flex">
                    <div class="d-flex custom-filter me-3">
                        <select class="form-select custom-select" aria-label="Default select example">
                            <option selected>Open this select menu</option>
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>
                        <button type="submit" class="btn btn-primary custom-filter-btn">Filter</button>
                    </div>
                    <!-- Search Input with Icon -->
                    <div class="position-relative custom-search">
                        <input type="text" placeholder="Search something..." class="form-control" id="search-input">
                        <i class="fas fa-search custom-search-icon"></i> <!-- FontAwesome search icon -->
                    </div>
                </div>
                
                <!-- Right Section -->
                <div class="right">
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </div>

            <table class="table text-center">
                <thead class="table-light">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">First</th>
                        <th scope="col">Last</th>
                        <th scope="col">Handle</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row">1</th>
                        <td>Mark</td>
                        <td>Otto</td>
                        <td>@mdo</td>
                    </tr>
                    <tr>
                        <th scope="row">2</th>
                        <td>Jacob</td>
                        <td>Thornton</td>
                        <td>@fat</td>
                    </tr>
                    <tr>
                        <th scope="row">3</th>
                        {{-- <td colspan="2">Larry the Bird</td> --}}
                        <td>Larry</td>
                        <td>Bird</td>
                        <td>@twitter</td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
@endsection

@section('scripts')
@endsection
