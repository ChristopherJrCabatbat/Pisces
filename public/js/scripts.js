// Burger Sidebar Toggle
document.getElementById("burgerBtn").addEventListener("click", function () {
    const sidebar = document.getElementById("sidebar");
    sidebar.classList.toggle("active");
});

// Close Sidebar When Clicking Outside
document.addEventListener("click", function (event) {
    const sidebar = document.getElementById("sidebar");
    const burgerBtn = document.getElementById("burgerBtn");

    // Check if the click is outside the sidebar and the burger button
    if (!sidebar.contains(event.target) && !burgerBtn.contains(event.target)) {
        sidebar.classList.add("active"); // Hide sidebar on outside click
    }
});

// Sidebar
document.addEventListener("DOMContentLoaded", function () {
    const customersDropdown = document.getElementById("customersDropdown");
    const dropdownMenu = customersDropdown.querySelector(".dropdown-customers");
    const caretIcon = customersDropdown.querySelector(".caret-icon i"); // Target the FontAwesome <i> element

    // Check if dropdown is open initially based on the active route
    const isActiveRoute = document.querySelector(".active-customer");
    if (isActiveRoute) {
        dropdownMenu.style.display = "block"; // Open dropdown if on an active route
        caretIcon.classList.remove("fa-caret-right");
        caretIcon.classList.add("fa-caret-down");
    }

    // Handle click to toggle dropdown and caret icon
    customersDropdown.addEventListener("click", function () {
        // Toggle dropdown visibility
        dropdownMenu.style.display =
            dropdownMenu.style.display === "none" ? "block" : "none";

        // Toggle the caret icon direction (right vs down)
        caretIcon.classList.toggle("fa-caret-right");
        caretIcon.classList.toggle("fa-caret-down");
    });
});

// Image Preview
function previewImage(event) {
    const imagePreview = document.getElementById("imagePreview");
    const reader = new FileReader();

    reader.onload = function () {
        imagePreview.src = reader.result;
        imagePreview.style.display = "block"; // Show the preview image
    };

    if (event.target.files.length) {
        reader.readAsDataURL(event.target.files[0]); // Read the uploaded image file
    }
}

// Shopping Cart Plus Minus Quantity
function incrementQuantity(button) {
    let input = button.previousElementSibling;
    input.value = parseInt(input.value) + 1;
    updateCartItemQuantity(input.dataset.menuId, input.value);
    updateCartTotals();
}

function decrementQuantity(button) {
    let input = button.nextElementSibling;
    if (parseInt(input.value) > 1) {
        input.value = parseInt(input.value) - 1;
        updateCartItemQuantity(input.dataset.menuId, input.value);
        updateCartTotals();
    }
}

function updateCartItemQuantity(menuId, quantity) {
    fetch("/user/updateQuantity", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
        body: JSON.stringify({
            menu_id: menuId,
            quantity: quantity,
        }),
    })
        .then((response) => response.json())
        .then((data) => {
            if (!data.success) {
                console.error(data.message);
            }
        })
        .catch((error) => console.error("Error updating cart item:", error));
}

function updateCartTotals() {
    let rows = document.querySelectorAll(".menu-row");
    let totalPrice = 0;

    rows.forEach((row) => {
        let price = parseFloat(row.dataset.price); // Get original price from data attribute
        let quantity = parseInt(row.querySelector(".quantity-input").value); // Get updated quantity
        let itemTotal = price * quantity; // Calculate item total

        // Update the quantity in Cart Totals display
        let cartItem = document.querySelector(
            `.cart-item-${row.dataset.menuId}`
        );
        cartItem.querySelector(".cart-item-quantity").textContent =
            quantity > 1 ? `(${quantity})` : ""; // Show quantity if more than 1
        cartItem.querySelector(".cart-item-total").textContent =
            formatPrice(itemTotal);

        totalPrice += itemTotal; // Add to total price
    });

    document.querySelector("#total-price").textContent =
        formatPrice(totalPrice); // Update total price
}

// Helper function to format price
function formatPrice(price) {
    return price % 1 === 0 ? `₱${price}` : `₱${price.toFixed(2)}`;
}

// Function to open the image modal
function enlargeImage(imageSrc) {
    const modal = document.getElementById("imageModal");
    const modalImg = document.getElementById("modalImage");
    modal.style.display = "flex";
    modalImg.src = imageSrc;
}

// Close the modal when the user clicks the close button
document.querySelector(".close-modal").onclick = function () {
    document.getElementById("imageModal").style.display = "none";
};

// Close the modal when clicking outside the image
document.getElementById("imageModal").onclick = function (event) {
    if (event.target === this) {
        this.style.display = "none";
    }
};

// Attach click event to all images in the menu table
document.querySelectorAll("#menu-table-body img").forEach((img) => {
    img.style.cursor = "pointer";
    img.addEventListener("click", () => enlargeImage(img.src));
});


// Open modal without scrolling to the top
function openModal(event, modalId) {
    event.preventDefault(); // Prevent default link behavior
    const overlay = document.getElementById(modalId);
    const modal = overlay.querySelector('.custom-modal');

    overlay.classList.add('active');
    modal.classList.add('active');
}

// Close modal
function closeModal(modalId) {
    const overlay = document.getElementById(modalId);
    const modal = overlay.querySelector('.custom-modal');

    overlay.classList.remove('active');
    modal.classList.remove('active');
}
