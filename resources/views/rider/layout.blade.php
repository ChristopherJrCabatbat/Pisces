<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Pisces Coffee Hub - Rider</title>

    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo-icon.png') }}">
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-styles.css') }}">

    <script src="https://kit.fontawesome.com/f416851b63.js" crossorigin="anonymous"></script>

    @yield('styles-links')

</head>

<body>
    @yield('modals')

    <header>
        <nav class="navbar navbar-expand-lg fixed-top" style="background-color: #e3f2fd;">
            <div class="container">
                <a class="navbar-brand" href="{{ route('user.dashboard') }}">
                    <img src="{{ asset('images/logo-name.png') }}" width="148" height="" alt="Pisces logo">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll"
                    aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarScroll">
                    <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">
                        @yield('topbar')
                    </ul>

                    <div class="d-flex align-items-center">

                        <!-- Heart Icon -->
                        <div class="icon-wrapper">
                            <a href="#" class="nav-icon">
                                <i class="fa fa-bell" id="heart-icon" title="View Favorites"></i>
                                <span class="notification-badge" id="heart-badge">0</span>
                            </a>
                        </div>


                        <!-- User Dropdown -->
                        <ul class="navbar-nav ms-4 my-2 my-lg-0 navbar-nav-scroll">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
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
            </div>
        </nav>
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
    
            // Set the icon based on the type
            let icon = type === 'error'
                ? '<i class="fa fa-circle-xmark"></i>'
                : '<i class="fa fa-circle-check"></i>';
    
            customToast.innerHTML = `${icon} ${msg}`;
            customToastBox.appendChild(customToast);
    
            // Add class for error or success styles
            if (type === 'error') {
                customToast.classList.add('error');
            }
    
            // Remove the toast after 3 seconds
            setTimeout(() => {
                customToast.remove();
            }, 3000);
        }
    
        // Check if a toast message exists in the session
        @if (session('toast'))
            const toastData = @json(session('toast'));
            showToast(toastData.message, toastData.type);
        @endif
    </script>
    

    @yield('scripts')

    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('bootstrap/js/bootstrap.js') }}"></script>
    <script src="{{ asset('bootstrap/js/bootstrap.bundle.js') }}"></script>

</body>

</html>