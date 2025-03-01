<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Pisces Coffee Hub</title>

    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo-home.png') }}">
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

    <script src="https://kit.fontawesome.com/f416851b63.js" crossorigin="anonymous"></script>

    <style>
        .main-content {
            margin-top: 18vh;
        }

        select {
            width: 30% !important;
        }

        .shop-messages {
            height: 56vh;
            overflow-x: hidden !important;
        }

        html {
            overflow: hidden;
        }

        .message {
            line-height: 1.5;
        }

        input.form-control {}

        .back-button {
            width: 40px;
            height: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            border: none;
        }

        .back-button i {
            font-size: 18px;
            color: #000;
        }

        .back-button:hover {
            background-color: #dedede;
        }

        .position-relative .back-button {
            z-index: 10;
        }
    </style>

</head>

<body>

    @if (session()->pull('showExperienceModal'))
        <div id="experienceModal" class="experience-modal-container">
            <div class="experience-modal-content">
                <!-- Close Button -->
                <button type="button" class="experience-modal-close" onclick="closeExperienceModal()">×</button>

                <!-- Modal Header and Text -->
                <h4 class="experience-modal-header">We value your feedback!</h4>
                <p class="experience-modal-text">Thank you for your continued support! We'd love to hear from you.
                    Please rate our service:</p>

                <!-- Feedback Form -->
                <form action="{{ route('user.submitExperience') }}" method="POST">
                    @csrf
                    <div class="experience-modal-form-group">
                        <label for="rating" class="experience-modal-label">Rate Us:</label>
                        <select name="rating" id="rating" class="experience-modal-select" required>
                            <option value="" disabled selected>Choose your rating</option>
                            <option value="1">1 - Poor</option>
                            <option value="2">2 - Fair</option>
                            <option value="3">3 - Good</option>
                            <option value="4">4 - Very Good</option>
                            <option value="5">5 - Excellent</option>
                        </select>
                    </div>
                    <div class="experience-modal-form-group">
                        <label for="feedback" class="experience-modal-label">Your Feedback:</label>
                        <textarea name="feedback" id="feedback" class="experience-modal-textarea" rows="4"
                            placeholder="Share your thoughts with us..."></textarea>
                    </div>
                    <button type="submit" class="experience-modal-button">Submit Feedback</button>
                </form>
            </div>
        </div>
        <script>
            // Open the modal on page load
            window.onload = function() {
                document.getElementById('experienceModal').style.display = 'flex';
            };

            // Close modal function
            function closeExperienceModal() {
                document.getElementById('experienceModal').style.display = 'none';
            }
        </script>
    @endif

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
                        <li class="nav-item">
                            <a class="nav-link fw-bold" aria-current="page"
                                href="{{ route('user.dashboard') }}">HOME</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-bold" aria-current="page" href="{{ route('user.menu') }}">MENU</a>
                        </li>
                        <li class="nav-item position-relative">
                            <a class="nav-link fw-bold" aria-current="page" href="{{ route('user.orders') }}">
                                ORDERS
                                @if ($pendingOrdersCount > 0)
                                    <span
                                        class="badge bg-danger position-absolute top-0 start-100 translate-middle-y-custom">
                                        {{ $pendingOrdersCount }}
                                    </span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-bold active" aria-current="page"
                                href="{{ route('user.messages') }}">MESSAGES</a>
                        </li>
                    </ul>

                    <div class="d-flex align-items-center">

                        <!-- Heart Icon -->
                        <div class="icon-wrapper">
                            <a href="{{ route('user.favorites') }}" class="nav-icon">
                                <i class="fa fa-heart" id="heart-icon" title="View Favorites"></i>
                                <span class="notification-badge" id="heart-badge">{{ $userFavorites ?? 0 }}</span>
                            </a>
                        </div>

                        <!-- Cart Icon -->
                        <div class="icon-wrapper ms-3">
                            <a href="{{ route('user.shoppingCart') }}" class="nav-icon">
                                <i class="fa-solid fa-cart-shopping" id="cart-icon" title="View Cart"></i>
                                <span class="notification-badge" id="cart-badge">{{ $userCart ?? 0 }}</span>
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
        <div class="container main-content d-flex flex-column align-items-center mb-5">
            {{-- Content --}}
            <div class="d-flex flex-column content user-content p-0 w-100 shop-messagess">

                <!-- Messages Section -->
                <div class="d-flex flex-column flex-grow-1 bg-light text-black rounded shadow-sm">
                    <!-- Header with Back Icon -->
                    <div
                        class="d-flex align-items-center justify-content-center position-relative py-3 px-3 border-bottom">
                        <a class="btn btn-light rounded-circle back-button position-absolute start-0 ms-3"
                            type="button" href="{{ route('user.messages') }}">
                            <i class="fa-solid fa-arrow-left"></i>
                        </a>
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('images/logo.jpg') }}" class="rounded-circle border me-2"
                                alt="Shop icon" style="width: 50px; height: 50px; object-fit: cover;">
                            <h3 class="ms-2 h3 fw-bold mb-0">PISCES COFFEE HUB</h3>
                        </div>
                    </div>

                    <!-- Chat Body -->
                    <div id="chatBody" class="shop-messages overflow-auto px-3 py-3">
                        @foreach ($messages as $message)
                            @if ($message->user_id === $user->id)
                                {{-- User --}}
                                <div class="d-flex align-items-start justify-content-end mb-4">
                                    @if (strpos(
                                            $message->message_text,
                                            'Please complete your GCash transaction. Kindly send the payment for the following orders:') === false)
                                        <span
                                            class="text-muted align-self-center small me-3">{{ $message->created_at->diffForHumans() }}</span>
                                    @endif
                                    <div class="message bg-primary text-white px-3 py-2 rounded shadow-sm"
                                        style="max-width: 70%; 
                                display: {{ $message->message_text && $message->message_text !== 'Sent an image' ? 'block' : 'none' }}; 
                                {{ strpos($message->message_text, 'Please complete your GCash transaction. Kindly send the payment for the following orders:') !== false ? 'margin: 0 auto; max-width: 73%;' : '' }}">
                                        @if ($message->message_text && $message->message_text !== 'Sent an image')
                                            <p class="m-0">{{ $message->message_text }}</p>
                                        @endif
                                    </div>
                                    @if ($message->image_url)
                                        <img src="{{ $message->image_url }}" alt="Sent Image"
                                            class="mt-2 rounded shadow-sm" height="310px" width="auto">
                                    @endif
                                    @if (strpos(
                                            $message->message_text,
                                            'Please complete your GCash transaction. Kindly send the payment for the following orders:') === false)
                                        <div class="message-avatar bg-primary text-white">
                                            <i class="fa-solid fa-user"></i>
                                        </div>
                                    @endif
                                </div>
                            @else
                                {{-- Pisces --}}
                                <div class="d-flex align-items-start mb-4">
                                    <img src="{{ asset('images/logo.jpg') }}" class="rounded-circle border me-3"
                                        alt="Shop icon" style="width: 40px; height: 40px; object-fit: cover;">
                                    @if ($message->image_url)
                                        <img src="{{ $message->image_url }}" alt="Received Image"
                                            class="mt-2 rounded shadow-sm" height="310px" width="auto">
                                    @endif
                                    <div class="message bg-white text-dark px-3 py-2 rounded shadow-sm"
                                        style="max-width: 70%; 
                                display: {{ $message->message_text && $message->message_text !== 'Sent an image' ? 'block' : 'none' }}; 
                                {{ strpos($message->message_text, 'Please complete your GCash transaction. Kindly send the payment for the following orders:') !== false ? 'margin: 0 auto; max-width: 73%;' : '' }}">
                                        @if ($message->message_text && $message->message_text !== 'Sent an image')
                                            <p class="m-0 {{ $message->is_read ? '' : 'fw-bold' }}">
                                                {{ $message->message_text }}
                                            </p>
                                        @endif
                                    </div>
                                    @if (strpos(
                                            $message->message_text,
                                            'Please complete your GCash transaction. Kindly send the payment for the following orders:') === false)
                                        <span
                                            class="text-muted align-self-center small ms-3">{{ $message->created_at->diffForHumans() }}</span>
                                    @endif
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <!-- Input Section -->
                    <div class="d-flex border-top p-3 align-items-center">
                        <form id="sendMessageForm" class="d-flex w-100" enctype="multipart/form-data">
                            @csrf
                            <input type="text" id="messageInput" name="message_text"
                                class="form-control me-2 rounded-pill" placeholder="Type your message here..."
                                autofocus />

                            <!-- Image Upload Button -->
                            <label for="imageUpload" class="btn btn-secondary rounded-pill px-3 me-2">
                                <i class="fa-solid fa-image"></i>
                                <input type="file" id="imageUpload" name="image" class="d-none"
                                    accept="image/*">
                            </label>

                            <button type="submit" class="btn btn-primary rounded-pill px-4">
                                <i class="fa-solid fa-paper-plane"></i>
                            </button>
                        </form>
                    </div>

                </div>

            </div>
        </div>
    </main>

    {{-- Submit message no reload and scroll bottom --}}
    <script>
        const sendMessageForm = document.getElementById('sendMessageForm');
        const messageInput = document.getElementById('messageInput');
        const imageInput = document.getElementById('imageUpload');
        const chatBody = document.getElementById('chatBody');

        // Handle form submission for text and image
        sendMessageForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData();
            const messageText = messageInput.value.trim();
            const imageFile = imageInput.files[0];

            // Validate input
            if (!messageText && !imageFile) {
                alert('Please enter a message or upload an image.');
                return;
            }

            if (messageText) formData.append('message_text', messageText);
            if (imageFile) formData.append('image', imageFile);

            formData.append('_token', '{{ csrf_token() }}'); // CSRF token

            try {
                const response = await fetch('{{ route('user.sendMessage', ['userId' => 1]) }}', {
                    method: 'POST',
                    body: formData,
                });

                const result = await response.json();
                if (response.ok && result.success) {
                    // Append the new message to the chat body
                    appendMessage(result.message);
                    // Clear the inputs
                    messageInput.value = '';
                    imageInput.value = '';
                } else {
                    alert('Failed to send the message. Please try again.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            }
        });

        // Automatically send the image when selected
        imageInput.addEventListener('change', async function() {
            if (imageInput.files.length === 0) return;

            const formData = new FormData();
            const imageFile = imageInput.files[0];

            formData.append('image', imageFile);
            formData.append('message_text', 'Sent an image'); // Automatically set message text for images
            formData.append('_token', '{{ csrf_token() }}'); // CSRF token

            try {
                const response = await fetch('{{ route('user.sendMessage', ['userId' => 1]) }}', {
                    method: 'POST',
                    body: formData,
                });

                const result = await response.json();
                if (response.ok && result.success) {
                    // Append the new image message to the chat body
                    appendMessage(result.message);
                    // Clear the file input
                    imageInput.value = '';
                } else {
                    alert('Failed to send the image. Please try again.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            }
        });

        // Append message to chat body
        function appendMessage(message) {
            const isGCashMessage = message.message_text && message.message_text.includes(
                'Please complete your GCash transaction. Kindly send the payment for the following orders:');
            const isImageOnlyMessage = message.message_text === 'Sent an image';

            let newMessage = `
                <div class="d-flex align-items-start justify-content-end mb-4">
                    ${!isGCashMessage ? `<span class="text-muted align-self-center small me-3">Just now</span>` : ''}
                    ${!isImageOnlyMessage ? `
                                                    <div class="message bg-primary text-white px-3 py-2 rounded shadow-sm"
                                                         style="max-width: ${isGCashMessage ? '73%' : '70%'}; 
                                                         ${isGCashMessage ? 'margin: 0 auto;' : ''};
                                                         display: ${message.message_text && message.message_text !== 'Sent an image' ? 'block' : 'none'};">
                                                        ${message.message_text && message.message_text !== 'Sent an image' ? `<p class="m-0">${message.message_text}</p>` : ''}
                                                    </div>
                                                ` : ''}
                    ${message.image_url ? `<img src="${message.image_url}" alt="Sent Image" class="mt-2 rounded shadow-sm" height="310px" width="auto">` : ''}
                    <div class="message-avatar bg-primary text-white">
                        <i class="fa-solid fa-user"></i>
                    </div>
                </div>
            `;
            chatBody.insertAdjacentHTML('beforeend', newMessage);

            // Scroll to the bottom of the chat body
            setTimeout(() => {
                chatBody.scrollTop = chatBody.scrollHeight;
            }, 100);
        }

        // Scroll chat body to the bottom on page load
        window.addEventListener('load', () => {
            chatBody.scrollTop = chatBody.scrollHeight;
        });
    </script>

    <!-- GCash Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Identify all messages in the DOM
            const messages = document.querySelectorAll('.message');

            messages.forEach(message => {
                // Check if message text contains GCash-related keywords
                if (message.textContent.includes(
                        'Please complete your GCash transaction. Kindly send the payment for the following orders:'
                    )) {
                    message.classList.add('gcash-message'); // Apply specific styling
                    message.style.margin = "0 auto"; // Center the message
                    message.style.maxWidth = "73%"; // Adjust width for centered messages
                }
            });
        });
    </script>

    {{-- Icon Actions Toast Message --}}
    <div id="customToastBox"></div>
    <script>
        let customToastBox = document.getElementById('customToastBox');

        function showToast(msg, type) {
            let customToast = document.createElement('div');
            customToast.classList.add('custom-toast');

            // Set the icon based on the type
            let icon = type === 'error' ?
                '<i class="fa fa-circle-xmark"></i>' :
                '<i class="fa fa-circle-check"></i>';

            customToast.innerHTML = `${icon} ${msg}`;
            customToastBox.appendChild(customToast);

            // Add class for error or success styles
            if (type === 'error') {
                customToast.classList.add('error');
            }

            setTimeout(() => {
                customToast.remove();
            }, 4500);
        }

        // Check if a toast message exists in the session
        @if (session('toast'))
            const toastData = @json(session('toast'));
            showToast(toastData.message, toastData.type);
        @endif
    </script>

    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('bootstrap/js/bootstrap.js') }}"></script>
    <script src="{{ asset('bootstrap/js/bootstrap.bundle.js') }}"></script>

</body>

</html>
