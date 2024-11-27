@extends('admin.layout')

@section('title', 'Customers')

@section('styles-links')
    <style>
        .message-avatar {
            width: 40px;
            height: 40px;
            background-color: #e0e0e0;
            color: #555;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-size: 1rem;
            /* Icon size */
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
    </style>
@endsection

@section('sidebar')
    <li>
        <a href="{{ route('admin.dashboard') }}" class="fs-5 sidebar-font">
            <i class="fa-solid fa-house me-3"></i>Dashboard
        </a>
    </li>
    <li>
        <a href="/admin/menu" class="fs-5 sidebar-font">
            <i class="fa-solid fa-utensils me-3"></i> Menu
        </a>
    </li>
    <li>
        <a href="/admin/delivery" class="fs-5 sidebar-font"><i class="fa-solid fa-truck-fast me-3"></i>Delivery</a>
    </li>

    <li class="sidebar-item" id="customersDropdown">
        <a href="javascript:void(0)" class="fs-5 sidebar-font d-flex customers justify-content-between active">
            <div><i class="fa-solid fa-users me-3"></i>Customers</div>
            <div class="caret-icon">
                <i class="fa-solid fa-caret-right"></i>
            </div>
        </a>
        <!-- Dropdown menu -->
        <ul class="dropdown-customers">
            <li><a href="{{ route('admin.updates') }}"
                    class="{{ request()->routeIs('admin.updates') ? 'active-customer-route' : '' }}"><i
                        class="fa-solid fa-user-pen me-2"></i>Customer Updates</a>
            </li>
            <li><a href="{{ route('admin.feedback') }}"
                    class="{{ request()->routeIs('admin.feedback') ? 'active-customer-route' : '' }} active-customer"><i
                        class="fa-solid fa-comments me-2"></i>Feedback
                    Collection</a></li>
            <li><a href="{{ route('admin.monitoring') }}"
                    class="{{ request()->routeIs('admin.monitoring') ? 'active-customer-route' : '' }}"><i
                        class="fa-solid fa-users-gear me-2"></i><span class="monitor-margin">Customer Activity</span>
                    <span class="monitor-margin">Monitoring</span></a></li>
        </ul>
    </li>

@endsection


@section('main-content')
    <div class="main-content">

        <!-- Breadcrumb Navigation -->
        <div class="current-file mb-3 d-flex">
            <div class="fw-bold">
                <i class="fa-solid fa-house me-2"></i>
                <a href="{{ route('admin.dashboard') }}" class="navigation">Dashboard</a> /
                <a href="#" class="navigation">Customers</a> /
                <a href="{{ route('admin.feedback') }}" class="navigation">Feedback Collection</a> /
            </div>
            <span class="faded-white ms-1">Message User</span>
        </div>

        <!-- Chat Interface -->
        <div class="messages-section-m mb-3">
            <div class="d-flex flex-column flex-grow-1 bg-light text-black rounded shadow-sm">
                <!-- Header with Back Icon -->
                <div class="d-flex align-items-center justify-content-center position-relative py-3 border-bottom">
                    <a href="{{ route('admin.feedback') }}"
                        class="btn btn-light rounded-circle back-button position-absolute start-0 ms-3">
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>
                    <div class="d-flex align-items-center">
                        <h3 class="ms-2 h3 fw-bold mb-0">{{ $user->first_name }} {{ $user->last_name }}</h3>
                    </div>
                </div>

                <!-- Chat Body -->
                <div class="shop-messages overflow-auto px-3 py-3">
                    @foreach ($messages as $message)
                        @if ($message->user_id === $user->id)
                            <!-- Message from User -->
                            <div class="d-flex align-items-start mb-4">
                                <!-- Updated User Icon -->
                                <div class="message-avatar">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                                <div class="message bg-white border px-3 py-2 rounded shadow-sm" style="max-width: 70%;">
                                    <p class="m-0">{{ $message->message_text }}</p>
                                </div>
                                <span
                                    class="text-muted align-self-center small ms-3">{{ $message->created_at->diffForHumans() }}</span>
                            </div>
                        @else
                            <!-- Message from Shop -->
                            <div class="d-flex align-items-start justify-content-end mb-4">
                                <span
                                    class="text-muted align-self-center small me-3">{{ $message->created_at->diffForHumans() }}</span>
                                <div class="message bg-primary text-white px-3 py-2 rounded shadow-sm"
                                    style="max-width: 70%;">
                                    <p class="m-0">{{ $message->message_text }}</p>
                                </div>

                                <img src="{{ asset('images/logo.jpg') }}" class="rounded-circle border ms-3"
                                    alt="Shop icon" style="width: 40px; height: 40px; object-fit: cover;">
                            </div>
                        @endif
                    @endforeach
                </div>

                <!-- Input Section -->
                <div class="d-flex border-top p-3 align-items-center">
                    <form action="{{ route('admin.sendMessage', $user->id) }}" method="POST" class="d-flex w-100">
                        @csrf
                        <input type="text" name="message_text" class="form-control me-2 rounded-pill"
                            placeholder="Type your message here..." required autofocus />
                        <button class="btn btn-primary rounded-pill px-4">
                            <i class="fa-solid fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
                
            </div>
        </div>




    </div>
@endsection

@section('scripts')
@endsection
