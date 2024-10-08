@extends('admin.layout')

@section('title', 'Admin')

@section('styles-links')
@endsection

@section('sidebar')
    <li>
        <a href="#" class="active fs-5"><i class="fa-solid fa-house me-3"></i>Dashboard</a>
    </li>
    <li>
        <a href="{{ route('admin.menu') }}" class="fs-5"><i class="fa-solid fa-utensils me-3"></i> Menu</a>
    </li>
    <li><a href="{{ route('admin.users') }}" class="fs-5"><i class="fa-solid fa-users me-3"></i> Users</a></li>
@endsection

@section('main-content')
    <div class="main-content">

        <div class="current-file mb-3 d-flex">
            <div class="fw-bold"><i class="fa-solid fa-house me-2"></i>Dashboard</div>
        </div>

        <div class="table-container"></div>

    </div>
@endsection

@section('scripts')
@endsection
