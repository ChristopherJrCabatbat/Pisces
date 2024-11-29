@extends('admin.layout')

@section('title', 'Customers')

@section('styles-links')
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
                    class="{{ request()->routeIs('admin.feedback') ? 'active-customer-route' : '' }}"><i
                        class="fa-solid fa-comments me-2"></i>Feedback
                    Collection</a></li>
            <li><a href="{{ route('admin.monitoring') }}"
                    class="{{ request()->routeIs('admin.monitoring') ? 'active-customer-route' : '' }}"><i
                        class="fa-solid fa-users-gear me-2"></i><span class="monitor-margin">Customer Activity</span>
                    <span class="monitor-margin">Monitoring</span></a></li>

            <li><a href="{{ route('admin.customerMessages') }}"
                    class="{{ request()->routeIs('admin.customerMessages') ? 'active-customer-route' : '' }} active-customer"><i
                        class="fa-solid fa-message me-2"></i> Customer Messages</a></li>
        </ul>
    </li>

@endsection


@section('main-content')
    <div class="main-content">

        <div class="current-file mb-3 d-flex">
            <div class="fw-bold"><i class="fa-solid fa-house me-2"></i><a href="{{ route('admin.dashboard') }}"
                    class="navigation">Dashboard</a> / <a href="#" class="navigation">Customers</a>
                / </div>
            <span class="faded-white ms-1">Messages</span>
        </div>

        <div class="table-container">

            <div class="taas-table mb-3 d-flex justify-content-between align-items-center">
                <!-- Left Section -->
                <div class="left d-flex">
                    <div class="d-flex custom-filter me-3">
                        <!-- Category Filter Section -->
                        <select id="categoryFilter" class="form-select custom-select" aria-label="Category select">
                            <option value="" selected>Default</option>
                            {{-- @foreach ($categories as $category)
                        <option value="{{ $category->category }}">{{ $category->category }}</option>
                    @endforeach --}}
                        </select>
                        <button id="filterButton" class="btn btn-primary custom-filter-btn button-wid">
                            <i class="fa-solid fa-sort me-2"></i>Filter
                        </button>

                    </div>

                </div>

                <!-- Right Section -->
                <div class="right d-flex gap-3">
                    <!-- Search -->
                    <div class="position-relative custom-search" method="GET" id="search-form">
                        <form action="">
                            <input type="search" placeholder="Search something..." class="form-control" id="search-input"
                                value="{{ request('search') }}">
                            <i class="fas fa-search custom-search-icon"></i> <!-- FontAwesome search icon -->
                        </form>
                    </div>
                </div>

            </div>

            <!-- Messenger-Style Messages Section -->
            <div class="messages-section">
                <div class="message-container">
                    <h2 class="h2 text-center">Customer Messages</h2>

                    @foreach ($userMessages as $data)
                        @php
                            $user = $data['user'];
                            $latestMessage = $data['latestMessage'];
                            $unreadCount = $data['unreadCount'];
                        @endphp

                        <a href="{{ route('admin.messageUser', $user->id) }}" class="message-a">
                            <div class="message-f">
                                <div class="message-avatar position-relative">
                                    <i class="fa-solid fa-user"></i>
                                    <!-- Unread Badge -->
                                    @if ($unreadCount > 0)
                                        <span
                                            class="badge bg-danger position-absolute top-0 start-100 translate-middle">{{ $unreadCount }}</span>
                                    @endif
                                </div>

                                <div class="message-content">
                                    <!-- Add fw-bold if there are unread messages -->
                                    <h5 class="message-name {{ $unreadCount > 0 ? 'fw-bold' : '' }}">
                                        {{ $user->first_name }} {{ $user->last_name }}
                                    </h5>
                                    <p class="message-text {{ $unreadCount > 0 ? 'fw-bold' : '' }}">
                                        @if ($latestMessage)
                                            @if ($latestMessage->user_id === auth()->id())
                                                You: {{ $latestMessage->message_text }}
                                            @else
                                                {{ $latestMessage->message_text }}
                                            @endif
                                        @else
                                            No messages yet
                                        @endif
                                    </p>
                                    <span class="message-time">
                                        @if ($latestMessage)
                                            {{ $latestMessage->created_at->diffForHumans() }}
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>



        </div>
    @endsection

    @section('scripts')
    @endsection
