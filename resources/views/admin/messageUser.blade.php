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
    
    <li>
        <a href="/admin/promotions" class="fs-5 sidebar-font"><i class="fa-solid fa-rectangle-ad me-3"></i>Promotions</a>
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
            {{-- <li><a href="{{ route('admin.monitoring') }}"
                    class="{{ request()->routeIs('admin.monitoring') ? 'active-customer-route' : '' }}"><i
                        class="fa-solid fa-users-gear me-2"></i><span class="monitor-margin">Customer Activity</span>
                    <span class="monitor-margin">Monitoring</span></a></li> --}}
            <li><a href="{{ route('admin.customerMessages') }}"
                    class="{{ request()->routeIs('admin.customerMessages') ? 'active-customer-route' : '' }} active-customer"><i
                        class="fa-solid fa-message me-2"></i> Customer Messages</a></li>
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
                <a href="{{ route('admin.customerMessages') }}" class="navigation">Messages</a> /
            </div>
            <span class="faded-white ms-1">User</span>
        </div>


        <!-- Chat Interface -->
        <div class="messages-section-m mb-3">
            <div class="d-flex flex-column flex-grow-1 bg-light text-black rounded shadow-sm">
                <!-- Header with Back Icon -->
                <div class="d-flex align-items-center justify-content-center position-relative py-3 border-bottom">
                    <a href="{{ url()->previous() }}"
                        class="btn btn-light rounded-circle back-button position-absolute start-0 ms-3">
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>
                    <div class="d-flex align-items-center position-relative">
                        <h3 class="ms-2 h3 fw-bold mb-0">
                            {{ $user->first_name }} {{ $user->last_name }}
                        </h3>
                    </div>
                </div>

                {{-- Nasa important notes yung original chat body --}}
                <!-- Chat Body -->
                <div id="chatBody" class="shop-messages overflow-auto px-3 py-3">
                    @foreach ($messages as $message)
                        @if ($message->user_id === $user->id)
                            <!-- Message from User -->
                            <div class="d-flex align-items-start mb-4">
                                <!-- User Icon -->
                                @if (strpos(
                                        $message->message_text,
                                        'Please complete your GCash transaction. Kindly send the payment for the following orders:') === false)
                                    <div class="message-avatar">
                                        <i class="fa-solid fa-user"></i>
                                    </div>
                                @endif

                                <!-- Message Text -->
                                <div class="message bg-white border px-3 py-2 rounded shadow-sm 
                                    {{ $message->is_read || strpos($message->message_text, 'Please complete your GCash transaction. Kindly send the payment for the following orders:') !== false ? '' : 'fw-bold' }}"
                                    style="max-width: 70%; 
                                    display: {{ $message->message_text && $message->message_text !== 'Sent an image' ? 'block' : 'none' }};
                                    {{ strpos($message->message_text, 'Please complete your GCash transaction. Kindly send the payment for the following orders:') !== false ? 'margin: 0 auto; max-width: 78% !important;' : '' }}">

                                    @if ($message->message_text && $message->message_text !== 'Sent an image')
                                        <p class="m-0">{{ $message->message_text }}</p>
                                    @endif
                                </div>


                                <!-- User Sent Image -->
                                @if ($message->image_url)
                                    <img src="{{ $message->image_url }}" alt="Sent Image" class="mt-2 rounded shadow-sm"
                                        height="310px" width="auto">
                                @endif

                                <!-- Message Timestamp -->
                                @if (strpos(
                                        $message->message_text,
                                        'Please complete your GCash transaction. Kindly send the payment for the following orders:') === false)
                                    <span class="text-muted align-self-center small ms-3">
                                        {{ $message->created_at->diffForHumans() }}
                                    </span>
                                @endif
                            </div>
                        @else
                            <!-- Message from Admin -->
                            <div class="d-flex align-items-start justify-content-end mb-4">
                                <!-- Message Timestamp -->
                                @if (strpos(
                                        $message->message_text,
                                        'Please complete your GCash transaction. Kindly send the payment for the following orders:') === false)
                                    <span class="text-muted align-self-center small me-3">
                                        {{ $message->created_at->diffForHumans() }}
                                    </span>
                                @endif

                                <!-- Admin Sent Image -->
                                @if ($message->image_url)
                                    <img src="{{ $message->image_url }}" alt="Received Image"
                                        class="mt-2 rounded shadow-sm" height="310px" width="auto">
                                @endif

                                <!-- Admin Message Text -->
                                <div class="message bg-primary text-white px-3 py-2 rounded shadow-sm"
                                    style="max-width: 70%; 
                                    display: {{ $message->message_text && $message->message_text !== 'Sent an image' ? 'block' : 'none' }};
                                    {{ strpos($message->message_text, 'Please complete your GCash transaction. Kindly send the payment for the following orders:') !== false ? 'margin: 0 auto; max-width: 78% !important;' : '' }}">
                                    @if ($message->message_text && $message->message_text !== 'Sent an image')
                                        <p class="m-0">
                                            {{ $message->message_text }}
                                        </p>
                                    @endif
                                </div>

                                <!-- Admin Icon -->
                                <img src="{{ asset('images/logo.jpg') }}" class="rounded-circle border ms-3"
                                    alt="Shop icon" style="width: 40px; height: 40px; object-fit: cover;">
                            </div>
                        @endif
                    @endforeach
                </div>


                <!-- Input Section -->
                <div class="d-flex border-top p-3 align-items-center">
                    <form id="sendMessageForm" class="d-flex w-100" enctype="multipart/form-data">
                        @csrf
                        <input type="text" id="messageInput" name="message_text" class="form-control me-2 rounded-pill"
                            placeholder="Type your message here..." autofocus />

                        <!-- Image Upload Button -->
                        <label for="imageUpload" class="btn btn-secondary rounded-pill px-3 me-2">
                            <i class="fa-solid fa-image"></i>
                            <input type="file" id="imageUpload" name="image" class="d-none" accept="image/*">
                        </label>

                        <button type="submit" class="btn btn-primary rounded-pill px-4">
                            <i class="fa-solid fa-paper-plane"></i>
                        </button>
                    </form>
                </div>


            </div>

        </div>
    </div>


    </div>
@endsection

@section('scripts')

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
                const response = await fetch('{{ route('admin.sendMessage', $user->id) }}', {
                    method: 'POST',
                    body: formData,
                });

                const result = await response.json();
                if (response.ok && result.success) {
                    // Append the new message to the chat body
                    const newMessage = `
                        <div class="d-flex align-items-start justify-content-end mb-4">
                            <span class="text-muted align-self-center small me-3">Just now</span>
                            <div class="message bg-primary text-white px-3 py-2 rounded shadow-sm" style="max-width: 70%;">
                                ${result.message.message_text ? `<p class="m-0">${result.message.message_text}</p>` : ''}
                                ${result.message.image_url ? `<img src="${result.message.image_url}" class="rounded mt-2" alt="Sent Image" height="310px" width="auto">` : ''}
                            </div>
                             <img src="{{ asset('images/logo.jpg') }}" class="rounded-circle border ms-3"
                                    alt="Shop icon" style="width: 40px; height: 40px; object-fit: cover;">
                        </div>
                    `;
                    chatBody.insertAdjacentHTML('beforeend', newMessage);

                    // Clear the inputs
                    messageInput.value = '';
                    imageInput.value = '';

                    // Scroll to the bottom of the chat body
                    setTimeout(() => {
                        chatBody.scrollTop = chatBody.scrollHeight;
                    }, 100);
                } else {
                    alert('Failed to send the message. Please try again.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            }
        });

        // Automatically send the image when selected
        // imageInput.addEventListener('change', async function() {
        //     if (imageInput.files.length === 0) return;

        //     const formData = new FormData();
        //     const imageFile = imageInput.files[0];

        //     formData.append('image', imageFile);
        //     formData.append('_token', '{{ csrf_token() }}'); // CSRF token

        //     try {
        //         const response = await fetch('{{ route('admin.sendMessage', ['userId' => 1]) }}', {
        //             method: 'POST',
        //             body: formData,
        //         });

        //         const result = await response.json();
        //         if (response.ok && result.success) {
        //             // Append the new image message to the chat body
        //             const newMessage = `
    //         <div class="d-flex align-items-start justify-content-end mb-4">
    //             <span class="text-muted align-self-center small me-3">Just now</span>
    //             ${
    //                 result.message.image_url
    //                     ? `<img src="${result.message.image_url}" class="rounded mt-2" alt="Sent Image" height="310px" width="auto">`
    //                     : ''
    //             }
    //               <img src="{{ asset('images/logo.jpg') }}" class="rounded-circle border ms-3"
    //                             alt="Shop icon" style="width: 40px; height: 40px; object-fit: cover;">
    //         </div>
    //     `;
        //             chatBody.insertAdjacentHTML('beforeend', newMessage);

        //             // Clear the file input
        //             imageInput.value = '';

        //             // Scroll to the bottom of the chat body
        //             setTimeout(() => {
        //                 chatBody.scrollTop = chatBody.scrollHeight;
        //             }, 100);
        //         } else {
        //             alert('Failed to send the image. Please try again.');
        //         }
        //     } catch (error) {
        //         console.error('Error:', error);
        //         alert('An error occurred. Please try again.');
        //     }
        // });

        imageInput.addEventListener('change', async function() {
            if (imageInput.files.length === 0) return;

            const formData = new FormData();
            const imageFile = imageInput.files[0];

            formData.append('image', imageFile);
            formData.append('_token', '{{ csrf_token() }}'); // CSRF token

            try {
                const response = await fetch('{{ route('admin.sendMessage', $user->id) }}', {
                    method: 'POST',
                    body: formData,
                });

                const result = await response.json();
                if (response.ok && result.success) {
                    const newMessage = `
                <div class="d-flex align-items-start justify-content-end mb-4">
                    <span class="text-muted align-self-center small me-3">Just now</span>
                    ${result.message.image_url
                        ? `<img src="${result.message.image_url}" class="rounded mt-2" alt="Sent Image" height="310px" width="auto">`
                        : ''
                    }
                    <img src="{{ asset('images/logo.jpg') }}" class="rounded-circle border ms-3"
                        alt="Shop icon" style="width: 40px; height: 40px; object-fit: cover;">
                </div>
            `;
                    chatBody.insertAdjacentHTML('beforeend', newMessage);

                    imageInput.value = ''; // Clear the input
                    setTimeout(() => {
                        chatBody.scrollTop = chatBody.scrollHeight;
                    }, 100);
                } else {
                    alert('Failed to send the image. Please try again.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            }
        });


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
                    message.style.maxWidth = "78%"; // Adjust width for centered messages
                }
            });
        });
    </script>


@endsection
