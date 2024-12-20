<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Pisces Coffee Hub</title>

    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo-icon.png') }}">
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
            <div class="d-flex flex-column content user-content p-0 w-100">

                <!-- Messages Section -->
                <div class="d-flex flex-column flex-grow-1 bg-light text-black rounded shadow-sm">
                    <!-- Header with Back Icon -->
                    <div
                        class="d-flex align-items-center justify-content-center position-relative py-3 px-3 border-bottom">
                        <button class="btn btn-light rounded-circle back-button position-absolute start-0 ms-3" onclick="history.back();">
                            <i class="fa-solid fa-arrow-left"></i>
                        </button>
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
                                    @if (strpos($message->message_text, 'Please complete your GCash transaction. Here are the details:') === false)
                                        <span
                                            class="text-muted align-self-center small me-3">{{ $message->created_at->diffForHumans() }}</span>
                                    @endif
                                    <div class="message bg-primary text-white px-3 py-2 rounded shadow-sm"
                                        style="max-width: 70%; {{ strpos($message->message_text, 'Please complete your GCash transaction. Here are the details:') !== false ? 'margin: 0 auto; max-width: 80%;' : '' }}">
                                        <p class="m-0">{{ $message->message_text }}</p>
                                    </div>
                                    @if (strpos($message->message_text, 'Please complete your GCash transaction. Here are the details:') === false)
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
                                    <div class="message bg-white border px-3 py-2 rounded shadow-sm 
                            @if (strpos($message->message_text, 'Please complete your GCash transaction. Here are the details:') !== false) gcash-message @endif"
                                        style="max-width: 70%; {{ strpos($message->message_text, 'Please complete your GCash transaction. Here are the details:') !== false ? 'margin: 0 auto; max-width: 80%;' : '' }}">
                                        <p class="m-0 {{ $message->is_read ? '' : 'fw-bold' }}">
                                            {{ $message->message_text }}
                                        </p>
                                    </div>
                                    @if (strpos($message->message_text, 'Please complete your GCash transaction. Here are the details:') === false)
                                        <span
                                            class="text-muted align-self-center small ms-3">{{ $message->created_at->diffForHumans() }}</span>
                                    @endif
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <!-- Input Section -->
                    <div class="d-flex border-top p-3 align-items-center">
                        <form id="sendMessageForm" class="d-flex w-100">
                            @csrf
                            <input type="text" id="messageInput" name="message_text"
                                class="form-control me-2 rounded-pill" placeholder="Type your message here..."
                                required autofocus />
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
        document.getElementById('sendMessageForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const messageInput = document.getElementById('messageInput');
            const chatBody = document.getElementById('chatBody');
            const messageText = messageInput.value.trim(); // Trim to avoid sending empty spaces

            if (!messageText) return; // Prevent sending empty messages

            // Send the message via fetch
            const response = await fetch('{{ route('user.sendMessage', ['userId' => 1]) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    message_text: messageText,
                }),
            });

            if (response.ok) {
                const result = await response.json();
                if (result.success) {
                    // Update the chat UI with the new message
                    const newMessage = `
                        <div class="d-flex align-items-start justify-content-end mb-4">
                            <span class="text-muted align-self-center small me-3">Just now</span>
                            <div class="message bg-primary text-white px-3 py-2 rounded shadow-sm" style="max-width: 70%;">
                                <p class="m-0">${result.message.message_text}</p>
                            </div>
                            <div class="message-avatar bg-primary text-white">
                                <i class="fa-solid fa-user"></i>
                            </div>
                        </div>
                    `;
                    chatBody.insertAdjacentHTML('beforeend', newMessage);

                    // Clear the input field
                    messageInput.value = '';

                    // Scroll to bottom after DOM update
                    setTimeout(() => {
                        chatBody.scrollTop = chatBody.scrollHeight;
                    }, 100); // Slight delay to ensure DOM updates
                }
            } else {
                alert('Failed to send the message. Please try again.');
            }
        });

        // Ensure scrolling to the bottom on page load
        window.addEventListener('load', () => {
            const chatBody = document.getElementById('chatBody');
            chatBody.scrollTop = chatBody.scrollHeight;
        });
    </script>

    <!-- Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Identify all messages in the DOM
            const messages = document.querySelectorAll('.message');

            messages.forEach(message => {
                // Check if message text contains GCash-related keywords
                if (message.textContent.includes(
                        'Please complete your GCash transaction. Here are the details:')) {
                    message.classList.add('gcash-message'); // Apply specific styling
                    message.style.margin = "0 auto"; // Center the message
                    message.style.maxWidth = "80%"; // Adjust width for centered messages
                }
            });
        });
    </script>

    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('bootstrap/js/bootstrap.js') }}"></script>
    <script src="{{ asset('bootstrap/js/bootstrap.bundle.js') }}"></script>

</body>

</html>
