@extends('admin.layout')

@section('title', 'Dashboard')

@section('styles-links')
    <style>
        /* Card hover effect */
        .hover-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .hover-card:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
    </style>
@endsection

@section('sidebar')
    <li>
        <a href="#" class="active fs-5 sidebar-font"><i class="fa-solid fa-house me-3"></i>Dashboard</a>
    </li>
    <li>
        <a href="/admin/menu" class="fs-5 sidebar-font"><i class="fa-solid fa-utensils me-3"></i> Menu</a>
    </li>

    <li class="position-relative">
        <a href="/admin/delivery" class="fs-5 sidebar-font">
            <i class="fa-solid fa-truck-fast me-3"></i>Delivery
            <!-- Badge for delivery statuses -->
            @if (isset($deliveryBadgeCount) && $deliveryBadgeCount > 0)
                <span class="badge position-absolute bg-danger translate-middle" style="left: 9.1rem; top: 1rem;">
                    {{ $deliveryBadgeCount }}
                </span>
            @endif
        </a>
    </li>

    <li>
        <a href="/admin/promotions" class="fs-5 sidebar-font"><i class="fa-solid fa-rectangle-ad me-3"></i>Promotions</a>
    </li>

    <li class="sidebar-item position-relative" id="customersDropdown">
        <a href="javascript:void(0)"
            class="fs-5 sidebar-font d-flex customers justify-content-between {{ request()->is('admin/updates', 'admin/feedback', 'admin/monitoring') ? 'active' : '' }}">
            <div>
                <i class="fa-solid fa-users me-3"></i>Customers
            </div>
            <!-- Unread messages badge -->
            @if (isset($totalUnreadCount) && $totalUnreadCount > 0)
                <span class="badge bg-danger position-absolute translate-middle" style="left: 10.5rem; top: 1rem;">
                    {{ $totalUnreadCount }}
                </span>
            @endif
            <div class="caret-icon">
                <i class="fa-solid fa-caret-right"></i>
            </div>
        </a>
        <!-- Dropdown menu -->
        <ul class="dropdown-customers" style="display: none;">
            <li>
                <a href="{{ route('admin.updates') }}"
                    class="{{ request()->routeIs('admin.updates') ? 'active-customer-route' : '' }}">
                    <i class="fa-solid fa-user-pen me-2"></i>Customer Updates
                </a>
            </li>
            <li>
                <a href="{{ route('admin.feedback') }}"
                    class="{{ request()->routeIs('admin.feedback') ? 'active-customer-route' : '' }}">
                    <i class="fa-solid fa-comments me-2"></i>Feedback Collection
                </a>
            </li>
            <li>
                <a href="{{ route('admin.customerMessages') }}"
                    class="{{ request()->routeIs('admin.customerMessages') ? 'active-customer-route' : '' }}">
                    <i class="fa-solid fa-message"></i> Customer Messages
                    <!-- Individual unread messages badge -->
                    @if (isset($totalUnreadCount) && $totalUnreadCount > 0)
                        <span class="badge bg-danger">
                            {{ $totalUnreadCount }}
                        </span>
                    @endif
                </a>
            </li>
        </ul>
    </li>

@endsection

@section('main-content')
    <div class="main-content">

        <div class="current-file mb-3 d-flex">
            <div class="fw-bold"><i class="fa-solid fa-house me-2"></i>Dashboard</div>
        </div>

        <!-- Dashboard Cards -->
        <div class="table-container mb-4">

            {{-- Summary --}}
            <div class="row summary">
                <!-- Users Card -->
                <div class="col-md-3">
                    <div class="card text-white bg-info mb-3 hover-card">
                        <div class="card-body">
                            <span class="icon-background"><i class="fas fa-users"></i></span>
                            <h2 class="card-title">{{ $userCount ?? 0 }}</h2>
                            <p class="card-text">Customers</p>
                        </div>
                    </div>
                </div>

                <!-- Deliveries Card -->
                <div class="col-md-3">
                    <div class="card text-white bg-primary mb-3 hover-card">
                        <div class="card-body">
                            <span class="icon-background"><i class="fas fa-truck"></i></span>
                            <h2 class="card-title">{{ $deliveryCount ?? 0 }}</h2>
                            <p class="card-text">Deliveries</p>
                        </div>
                    </div>
                </div>

                <!-- Menus Card -->
                <div class="col-md-3">
                    <div class="card text-white bg-warning mb-3 hover-card">
                        <div class="card-body">
                            <span class="icon-background"><i class="fas fa-utensils"></i></span>
                            <h2 class="card-title">{{ $menuCount ?? 0 }}</h2>
                            <p class="card-text">Menus</p>
                        </div>
                    </div>
                </div>

                <!-- Categories Card -->
                <div class="col-md-3">
                    <div class="card text-white bg-dark mb-3 hover-card">
                        <div class="card-body">
                            <span class="icon-background"><i class="fas fa-list"></i></span>
                            <h2 class="card-title">{{ $categoryCount ?? 0 }}</h2>
                            <p class="card-text">Categories</p>
                        </div>
                    </div>
                </div>
            </div>


            {{-- Analytics --}}

            {{-- Top Picks --}}
            <div class="analytics text-black mt-2">
                <h3 class="h3 mb-3">Top Picks</h3>
                {{-- Table --}}
                <table class="table text-center">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Image</th>
                            <th scope="col">Name</th>
                            <th scope="col">Category</th>
                            <th scope="col">Price</th>
                            {{-- <th scope="col">Description</th> --}}
                            <th scope="col">Total Orders</th>
                        </tr>
                    </thead>
                    <tbody id="menu-table-body">
                        @forelse ($topPicks as $menu)
                            <tr class="menu-row">
                                <!-- Image Column -->
                                <td>
                                    @if ($menu->image)
                                        <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}"
                                            class="img-fluid" width="50">
                                    @else
                                        <span>No Image</span>
                                    @endif
                                </td>
                                <!-- Name, Category, Description -->
                                <td>{{ $menu->name }}</td>
                                <td>{{ $menu->category }}</td>
                                <!-- Price (Remove trailing .00 if present) -->
                                <td>
                                    @if (floor($menu->price) == $menu->price)
                                        ₱{{ number_format($menu->price, 0) }}
                                    @else
                                        ₱{{ number_format($menu->price, 2) }}
                                    @endif
                                </td>
                                {{-- <td>{{ $menu->description }}</td> --}}
                                <!-- Total Orders Column -->
                                <td>{{ $menu->total_order_count }}</td>
                            </tr>
                        @empty
                            <tr id="no-menus-row">
                                <td colspan="6">No popular menus found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Customer Activity Tracking --}}
            <div class="analytics text-black mt-4">
                <h3 class="h3 mb-3">Customer Activity Tracking</h3>
                {{-- Table --}}
                <table class="table text-center">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Customer Name</th>
                            <th scope="col">Orders Delivered</th>
                            <th scope="col">Last Order Date</th>
                            <th scope="col">Last Login Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($topCustomers as $customer)
                            <tr>
                                <td>{{ $customer->first_name }} {{ $customer->last_name }}</td>
                                <td>{{ $customer->order_count }}</td>
                                <td>{{ $customer->last_order ? $customer->last_order->format('M. d, Y - g:i A') : 'N/A' }}
                                </td>
                                <td>{{ $customer->last_login_at ? $customer->last_login_at->format('M. d, Y - g:i A') : 'N/A' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">No customer activity found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Highest Rated Menus --}}
            <div class="analytics text-black mt-4">
                <h3 class="h3 mb-3">Highest Rated Menus</h3>
                {{-- Table --}}
                <table class="table text-center">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Image</th>
                            <th scope="col">Name</th>
                            <th scope="col">Category</th>
                            <th scope="col">Price</th>
                            <th scope="col">Rating</th>
                        </tr>
                    </thead>
                    <tbody id="menu-table-body">
                        @forelse ($highestRatedMenus  as $menu)
                            <tr class="menu-row">
                                <!-- Image Column -->
                                <td>
                                    @if ($menu->image)
                                        <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}"
                                            class="img-fluid" width="50">
                                    @else
                                        <span>No Image</span>
                                    @endif
                                </td>
                                <!-- Name, Category, Description -->
                                <td>{{ $menu->name }}</td>
                                <td>{{ $menu->category }}</td>
                                <!-- Price (Remove trailing .00 if present) -->
                                <td>
                                    @if (floor($menu->price) == $menu->price)
                                        ₱{{ number_format($menu->price, 0) }}
                                    @else
                                        ₱{{ number_format($menu->price, 2) }}
                                    @endif
                                </td>
                                <!-- Total Orders Column -->
                                <td>
                                    {{ number_format($menu->rating, 1) }} <i class="fa-solid fa-star"></i>
                                    ({{ $menu->review_count }})
                                </td>
                            </tr>
                        @empty
                            <tr id="no-menus-row">
                                <td colspan="6">No popular menus found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- <!-- Daily Sales -->
            <div class="bar-graph text-black mt-4" id="dailySalesContainer">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="h3 text-black">Daily Sales</h3>
                    <div>
                        <button id="toggleToMonthly" class="btn btn-primary">Switch to Monthly Sales</button>
                        <button id="printDailyReport" class="btn btn-secondary">Print Report</button>
                    </div>
                </div>
                <div>
                    <canvas id="dailySalesChart" width="400" height="200"></canvas>
                </div>
            </div>

            <!-- Monthly Sales -->
            <div class="bar-graph text-black mt-4" style="display: none;" id="monthlySalesContainer">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="h3 text-black">Monthly Sales</h3>
                    <div>
                        <button id="toggleToDaily" class="btn btn-primary">Switch to Daily Sales</button>
                        <button id="printMonthlyReport" class="btn btn-secondary">Print Report</button>
                    </div>
                </div>
                <div>
                    <canvas id="monthlySalesChart" width="400" height="200"></canvas>
                </div>
            </div> --}}

            <!-- Daily Sales -->
            <div class="bar-graph text-black mt-4" id="dailySalesContainer">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="h3 text-black">Daily Sales</h3>
                    <div>
                        <button id="toggleToMonthly" class="btn btn-primary">Switch to Monthly Sales</button>
                        <button id="printDailyReport" class="btn btn-secondary">Print Report</button>
                    </div>
                </div>
                <div>
                    <canvas id="dailySalesChart" width="400" height="200"></canvas>
                </div>
            </div>

            <!-- Monthly Sales -->
            <div class="bar-graph text-black mt-4" style="display: none;" id="monthlySalesContainer">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="h3 text-black">Monthly Sales</h3>
                    <div>
                        <button id="toggleToDaily" class="btn btn-primary">Switch to Daily Sales</button>
                        <button id="printMonthlyReport" class="btn btn-secondary">Print Report</button>
                    </div>
                </div>
                <div>
                    <canvas id="monthlySalesChart" width="400" height="200"></canvas>
                </div>
            </div>

        </div>

    </div>
@endsection

@section('scripts')

    {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Monthly Sales Data
        const monthlySales = @json($monthlySales);
        const monthlyLabels = Object.keys(monthlySales); // Month names
        const monthlyData = Object.values(monthlySales); // Total sales

        // Configure the Monthly Sales Chart
        const monthlyCtx = document.getElementById('monthlySalesChart').getContext('2d');
        new Chart(monthlyCtx, {
            type: 'bar',
            data: {
                labels: monthlyLabels,
                datasets: [{
                    label: 'Total Sales (₱)',
                    data: monthlyData,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Sales in ₱'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Months'
                        }
                    }
                }
            }
        });

        // Daily Sales Data
        const dailySales = @json($dailySales);
        const dailyLabels = Object.keys(dailySales); // Day names
        const dailyData = Object.values(dailySales); // Total sales

        // Configure the Daily Sales Chart
        const dailyCtx = document.getElementById('dailySalesChart').getContext('2d');
        new Chart(dailyCtx, {
            type: 'line',
            data: {
                labels: dailyLabels,
                datasets: [{
                    label: 'Total Sales (₱)',
                    data: dailyData,
                    fill: false,
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Sales in ₱'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Days'
                        }
                    }
                }
            }
        });

        // Toggle Button Functionality
        const toggleToMonthly = document.getElementById('toggleToMonthly');
        const toggleToDaily = document.getElementById('toggleToDaily');
        const dailySalesContainer = document.getElementById('dailySalesContainer');
        const monthlySalesContainer = document.getElementById('monthlySalesContainer');

        toggleToMonthly.addEventListener('click', () => {
            dailySalesContainer.style.display = 'none';
            monthlySalesContainer.style.display = 'block';
        });

        toggleToDaily.addEventListener('click', () => {
            monthlySalesContainer.style.display = 'none';
            dailySalesContainer.style.display = 'block';
        });

        // Print Report Functionality
        const printDailyReport = document.getElementById('printDailyReport');
        const printMonthlyReport = document.getElementById('printMonthlyReport');

        printDailyReport.addEventListener('click', () => {
            const reportContent = `
                Daily Sales Report
                ==================
                ${dailyLabels.map((label, index) => `${label}: ₱${dailyData[index].toLocaleString()}`).join('\n')}
                Total Sales: ₱${dailyData.reduce((a, b) => a + b, 0).toLocaleString()}
            `;
            printReport(reportContent);
        });

        printMonthlyReport.addEventListener('click', () => {
            const reportContent = `
                Monthly Sales Report
                ====================
                ${monthlyLabels.map((label, index) => `${label}: ₱${monthlyData[index].toLocaleString()}`).join('\n')}
                Total Sales: ₱${monthlyData.reduce((a, b) => a + b, 0).toLocaleString()}
            `;
            printReport(reportContent);
        });

        // Helper Function to Print Report
        function printReport(content) {
            const newWindow = window.open('', '_blank');
            newWindow.document.write('<pre>' + content + '</pre>');
            newWindow.document.close();
            newWindow.print();
        }
    </script> --}}

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Monthly Sales Data
        const monthlySales = @json($monthlySales);
        const monthlyLabels = Object.keys(monthlySales);
        const monthlyData = Object.values(monthlySales);

        // Configure the Monthly Sales Chart
        const monthlyCtx = document.getElementById('monthlySalesChart').getContext('2d');
        new Chart(monthlyCtx, {
            type: 'bar',
            data: {
                labels: monthlyLabels,
                datasets: [{
                    label: 'Total Sales (₱)',
                    data: monthlyData,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Sales in ₱'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Months'
                        }
                    }
                }
            }
        });

        // Daily Sales Data
        const dailySales = @json($dailySales);
        const dailyLabels = Object.keys(dailySales);
        const dailyData = Object.values(dailySales);

        // Configure the Daily Sales Chart
        const dailyCtx = document.getElementById('dailySalesChart').getContext('2d');
        new Chart(dailyCtx, {
            type: 'line',
            data: {
                labels: dailyLabels,
                datasets: [{
                    label: 'Total Sales (₱)',
                    data: dailyData,
                    fill: false,
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Sales in ₱'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Days'
                        }
                    }
                }
            }
        });

        // Toggle Button Functionality
        document.getElementById('toggleToMonthly').addEventListener('click', () => {
            document.getElementById('dailySalesContainer').style.display = 'none';
            document.getElementById('monthlySalesContainer').style.display = 'block';
        });

        document.getElementById('toggleToDaily').addEventListener('click', () => {
            document.getElementById('monthlySalesContainer').style.display = 'none';
            document.getElementById('dailySalesContainer').style.display = 'block';
        });

        // // Print Report Functionality
        // document.getElementById('printDailyReport').addEventListener('click', () => {
        //     const reportContent = `
    //         <h1>Daily Sales Report</h1>
    //         <table border="1" style="width: 100%; text-align: left; border-collapse: collapse;">
    //             <thead>
    //                 <tr>
    //                     <th>Date</th>
    //                     <th>Sales (₱)</th>
    //                 </tr>
    //             </thead>
    //             <tbody>
    //                 ${dailyLabels.map((label, index) => `<tr><td>${label}</td><td>₱${dailyData[index].toLocaleString()}</td></tr>`).join('')}
    //             </tbody>
    //             <tfoot>
    //                 <tr>
    //                     <td><strong>Total Sales</strong></td>
    //                     <td><strong>₱${dailyData.reduce((a, b) => a + b, 0).toLocaleString()}</strong></td>
    //                 </tr>
    //             </tfoot>
    //         </table>
    //     `;
        //     printStyledReport(reportContent);
        // });

        // document.getElementById('printMonthlyReport').addEventListener('click', () => {
        //     const reportContent = `
    //         <h1>Monthly Sales Report</h1>
    //         <table border="1" style="width: 100%; text-align: left; border-collapse: collapse;">
    //             <thead>
    //                 <tr>
    //                     <th>Month</th>
    //                     <th>Sales (₱)</th>
    //                 </tr>
    //             </thead>
    //             <tbody>
    //                 ${monthlyLabels.map((label, index) => `<tr><td>${label}</td><td>₱${monthlyData[index].toLocaleString()}</td></tr>`).join('')}
    //             </tbody>
    //             <tfoot>
    //                 <tr>
    //                     <td><strong>Total Sales</strong></td>
    //                     <td><strong>₱${monthlyData.reduce((a, b) => a + b, 0).toLocaleString()}</strong></td>
    //                 </tr>
    //             </tfoot>
    //         </table>
    //     `;
        //     printStyledReport(reportContent);
        // });

        // // Helper Function to Print Report with Styling
        // function printStyledReport(content) {
        //     const newWindow = window.open('', '_blank');
        //     newWindow.document.write(`
    //         <html>
    //         <head>
    //             <title>Sales Report</title>
    //             <style>
    //                 body { font-family: Arial, sans-serif; padding: 20px; }
    //                 h1 { text-align: center; }
    //                 table { margin: 20px auto; border: 1px solid #ddd; }
    //                 th, td { padding: 8px; border: 1px solid #ddd; }
    //                 th { background-color: #f4f4f4; }
    //             </style>
    //         </head>
    //         <body>${content}</body>
    //         </html>
    //     `);
        //     newWindow.document.close();
        //     newWindow.print();
        // }

        // Print Report Functionality
        document.getElementById('printDailyReport').addEventListener('click', () => {
            const month = new Date().toLocaleString('default', {
                month: 'long'
            }); // Get current month name
            const totalDailySales = dailyData.reduce((a, b) => Number(a) + Number(b),
                0); // Ensure numbers are summed
            const reportContent = `
        <div style="text-align: center;">
            <img src="{{ asset('images/logo-name.png') }}" alt="Company Logo" style="max-width: 150px; margin-bottom: 20px;">
            <h1>Daily Sales Report - ${month}</h1>
        </div>
        <table border="1" style="width: 80%; text-align: left; border-collapse: collapse; margin: 0 auto;">
            <thead>
                <tr style="background-color: #bababa;">
                    <th>Date</th>
                    <th>Sales (₱)</th>
                </tr>
            </thead>
            <tbody>
                ${dailyLabels.map((label, index) => `<tr><td>${month} ${label}</td><td>₱${Number(dailyData[index]).toLocaleString()}</td></tr>`).join('')}
            </tbody>
            <tfoot>
                <tr style="background-color: #f8f9fa;">
                    <td><strong>Total Sales</strong></td>
                    <td><strong>₱${totalDailySales.toLocaleString()}</strong></td>
                </tr>
            </tfoot>
        </table>
    `;
            printStyledReport(reportContent);
        });

        document.getElementById('printMonthlyReport').addEventListener('click', () => {
            const totalMonthlySales = monthlyData.reduce((a, b) => Number(a) + Number(b),
                0); // Ensure numbers are summed
            const reportContent = `
        <div style="text-align: center;">
            <img src="{{ asset('images/logo-name.png') }}" alt="Company Logo" style="max-width: 150px; margin-bottom: 20px;">
            <h1>Monthly Sales Report</h1>
        </div>
        <table border="1" style="width: 80%; text-align: left; border-collapse: collapse; margin: 0 auto;">
            <thead>
                <tr style="background-color: #bababa;">
                    <th>Month</th>
                    <th>Sales (₱)</th>
                </tr>
            </thead>
            <tbody>
                ${monthlyLabels.map((label, index) => `<tr><td>${label}</td><td>₱${Number(monthlyData[index]).toLocaleString()}</td></tr>`).join('')}
            </tbody>
            <tfoot>
                <tr style="background-color: #f8f9fa;">
                    <td><strong>Total Sales</strong></td>
                    <td><strong>₱${totalMonthlySales.toLocaleString()}</strong></td>
                </tr>
            </tfoot>
        </table>
    `;
            printStyledReport(reportContent);
        });

        // Helper Function to Print Report with Styling
        function printStyledReport(content) {
            const newWindow = window.open('', '_blank');
            newWindow.document.write(`
        <html>
        <head>
            <title>Sales Report</title>
            <style>
                body { font-family: Arial, sans-serif; padding: 20px; }
                h1 { text-align: center; font-size: 24px; }
                table { 
                    border: 1px solid #ddd; 
                    border-radius: 8px; 
                    overflow: hidden; 
                    box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1); 
                }
                th, td { 
                    padding: 10px; 
                    text-align: center; 
                    border: 1px solid #ddd; 
                }
                th { 
                    font-size: 16px; 
                    font-weight: bold; 
                    background-color: #bababa; 
                }
                tfoot td {
                    font-weight: bold;
                    background-color: #f8f9fa;
                }
                tr:nth-child(even) { background-color: #f2f2f2; }
                img { max-width: 150px; margin-bottom: 20px; }
            </style>
        </head>
        <body>${content}</body>
        </html>
    `);

            newWindow.document.close();

            // Wait for the logo image to load before printing
            const logoImage = newWindow.document.querySelector('img');
            if (logoImage) {
                logoImage.onload = () => {
                    newWindow.print();
                };
                logoImage.onerror = () => {
                    console.error("Logo image failed to load.");
                    newWindow.print(); // Print even if the image fails to load
                };
            } else {
                // If no image is present, proceed to print
                newWindow.print();
            }
        }
    </script>

@endsection
