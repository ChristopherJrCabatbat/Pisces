<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- <title>@yield('title')</title> --}}
    <title>Pisces Coffee Hub</title>

    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo-home.png') }}">
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-styles.css') }}">

    <script src="https://kit.fontawesome.com/f416851b63.js" crossorigin="anonymous"></script>

    @yield('styles-links')

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>
    @yield('modals')

    <!-- Image Modal -->
    <div id="imageModal" class="image-modal">
        <span class="close-modal">&times;</span>
        <img class="modal-content" id="modalImage">
    </div>

    <!-- Edit User Modal -->
    <div class="custom-modal-overlay" id="editProfileModal">
        <div class="custom-modal">
            <div class="custom-modal-header text-black">
                <h5 class="custom-modal-title">Edit Profile</h5>
                <button type="button" class="btn-close" onclick="closeModal('editProfileModal')"
                    aria-label="Close"></button>
            </div>
            <div class="custom-modal-body">
                <form id="editProfileForm" method="POST" action="{{ route('admin.userUpdate') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3 d-flex gap-2">
                        <!-- First Name -->
                        <div class="w-100">
                            <label for="firstName" class="form-label text-black">First Name:</label>
                            <input type="text" class="form-control" id="firstName" name="first_name"
                                value="{{ old('first_name', Auth::user()->first_name) }}" required>
                            @error('first_name')
                                <div><small class="text-danger">{{ $message }}</small></div>
                            @enderror
                        </div>

                        <!-- Last Name -->
                        <div class="w-100">
                            <label for="lastName" class="form-label text-black">Last Name:</label>
                            <input type="text" class="form-control" id="lastName" name="last_name"
                                value="{{ old('last_name', Auth::user()->last_name) }}" required>
                            @error('last_name')
                                <div><small class="text-danger">{{ $message }}</small></div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 d-flex gap-2">
                        <!-- Email -->
                        <div class="w-100">
                            <label for="email" class="form-label text-black">Email:</label>
                            <input type="email" class="form-control" id="email" name="email"
                                value="{{ old('email', Auth::user()->email) }}" required>
                            @error('email')
                                <div><small class="text-danger">{{ $message }}</small></div>
                            @enderror
                        </div>

                        <!-- Contact Number -->
                        <div class="w-75">
                            <label for="contactNumber" class="form-label text-black">Contact Number:</label>
                            <input type="text" class="form-control" id="contactNumber" name="contact_number"
                                value="{{ old('contact_number', Auth::user()->contact_number) }}" required>
                            @error('contact_number')
                                <div><small class="text-danger">{{ $message }}</small></div>
                            @enderror
                        </div>
                    </div>

                    <!-- Change Password -->
                    <div class="mb-3">
                        <label for="newPassword" class="form-label text-black">New Password:</label>
                        <input type="password" class="form-control" id="newPassword" name="password">
                        <small class="text-secondary">*Leave this blank if you don't want to change your
                            password.</small>
                        @error('password')
                            <div><small class="text-danger">{{ $message }}</small></div>
                        @enderror
                    </div>

                    <!-- Confirm New Password -->
                    <div class="mb-3">
                        <label for="confirmPassword" class="form-label text-black">Confirm New Password:</label>
                        <input type="password" class="form-control" id="confirmPassword" name="password_confirmation">
                        @error('password_confirmation')
                            <div><small class="text-danger">{{ $message }}</small></div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Save Changes</button>
                </form>
            </div>
        </div>
    </div>

    <header>
        {{-- Top Nav --}}
        <nav class="navbar navss navbar-expand-lg fixed-top" style="background-color: #e3f2fd;">
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
                            <a class="nav-link dropdown-toggle" href="#" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                {{ Auth::user()->first_name }}
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="#"
                                        onclick="openModal(event, 'editProfileModal')">
                                        <i class="fa-solid fa-user me-2"></i>Profile
                                    </a>
                                </li>
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

    {{-- Toast Message --}}
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
            }, 5500);
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

    {{-- Profile Modal Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const editProfileForm = document.getElementById('editProfileForm');
            const modalOverlay = document.getElementById('editProfileModal');

            // Reopen the modal if there are server-side validation errors
            if ({{ $errors->any() ? 'true' : 'false' }}) {
                modalOverlay.classList.add('active');
                modalOverlay.querySelector('.custom-modal').classList.add('active');
            }

            // Add client-side password validation
            editProfileForm.addEventListener('submit', (event) => {
                const password = document.getElementById('newPassword').value;
                const confirmPassword = document.getElementById('confirmPassword').value;

                // Clear previous error messages
                document.querySelectorAll('.validation-error').forEach(el => el.remove());

                if (password !== confirmPassword) {
                    event.preventDefault(); // Prevent form submission

                    // Display error message
                    const errorMsg = document.createElement('small');
                    errorMsg.className = 'text-danger validation-error';
                    errorMsg.textContent = 'Passwords do not match.';
                    document.getElementById('confirmPassword').after(errorMsg);
                }
            });
        });
    </script>

    @yield('scripts')

    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('bootstrap/js/bootstrap.js') }}"></script>
    <script src="{{ asset('bootstrap/js/bootstrap.bundle.js') }}"></script>

</body>

</html>
