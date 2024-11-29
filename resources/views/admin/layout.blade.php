<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- <title>@yield('title')</title> --}}
    <title>Pisces Coffee Hub</title>

    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo-icon.png') }}">
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-styles.css') }}">

    <script src="https://kit.fontawesome.com/f416851b63.js" crossorigin="anonymous"></script>

    @yield('styles-links')

    <style>
        /* Spinner overlay styles */
        .spinner-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            /* background-color: rgba(255, 255, 255, 0.4); */
            background-color: rgb(72, 64, 69, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1050;
            /* Higher than modal */
        }

        .spinner-border {
            color: #f81d0b;
            font-size: 1.2rem
        }
    </style>

</head>

<body>
    @yield('modals')

    <!-- Image Modal -->
    <div id="imageModal" class="image-modal">
        <span class="close-modal">&times;</span>
        <img class="modal-content" id="modalImage">
    </div>

    <header>
        {{-- Top Nav --}}
        <nav class="navbar navbar-expand-lg fixed-top" style="background-color: #e3f2fd;">
            <div class="container d-flex justify-content-between align-items-center">
                <!-- Burger Icon -->
                <button class="burger-icon" id="burgerBtn">&#9776;</button>

                <!-- Logo -->
                <a class="navbar-brand" href="{{ route('user.dashboard') }}">
                    <img src="{{ asset('images/logo-name.png') }}" width="148" height="" alt="Pisces logo">
                </a>

                <!-- Right-side User Info -->
                <div class="" id="">
                    <ul class="navbar-nav me-auto my-2 my-lg-0">
                        <li class="nav-item dropdown position-relative">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                {{ Auth::user()->first_name }}
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#"><i
                                            class="fa-solid fa-user me-2"></i>Profile</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button class="dropdown-item" type="submit"><i
                                                class="fa-solid fa-right-from-bracket me-2"></i>Log out</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>

            </div>
        </nav>

        {{-- Sidebar --}}
        <div class="sidebar" id="sidebar">
            <ul>
                @yield('sidebar')
            </ul>
        </div>
    </header>

    <main>
        @yield('main-content')
    </main>

    <div id="customToastBox"></div>
    <script>
        let customToastBox = document.getElementById('customToastBox');

        function showToast(msg, type) {
            let customToast = document.createElement('div');
            customToast.classList.add('custom-toast');
            let icon = type === 'error' ? '<i class="fa fa-circle-xmark"></i>' : '<i class="fa fa-circle-check"></i>';
            customToast.innerHTML = `${icon} ${msg}`;
            customToastBox.appendChild(customToast);

            if (type === 'error') {
                customToast.classList.add('error');
            }

            setTimeout(() => {
                customToast.remove();
            }, 3000);
        }

        @if (session('toast'))
            const toastData = @json(session('toast'));
            showToast(toastData.message, toastData.type);
            {{ session()->forget('toast') }} // Clear toast session to prevent persistence
        @endif
    </script>

    <!-- Loading Spinner -->
    <div id="loadingSpinner" class="spinner-overlay d-none">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Updating, please wait...</span>
        </div>
    </div>




    {{-- auto change style unread --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const messageLinks = document.querySelectorAll('.message-a');

            messageLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const userId = this.dataset.userId; // Attach userId to <a>

                    // Mark messages as read via AJAX
                    fetch(`/admin/markAsRead/${userId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                        },
                    }).then(response => {
                        if (response.ok) {
                            // Update styling dynamically
                            this.querySelector('.message-name').classList.remove('fw-bold');
                            this.querySelector('.message-text').classList.remove('fw-bold');
                        }
                        window.location.href = this.href; // Redirect after marking as read
                    });
                });
            });
        });
    </script>



    @yield('scripts')

    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('bootstrap/js/bootstrap.js') }}"></script>
    <script src="{{ asset('bootstrap/js/bootstrap.bundle.js') }}"></script>

</body>

</html>
