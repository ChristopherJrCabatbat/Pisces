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

// Shopping Cart Total Update Script
function updateCartTotals() {
    let rows = document.querySelectorAll(".menu-row");
    let totalPrice = 0;

    rows.forEach((row) => {
        let price = parseFloat(row.querySelector(".quantity-input").dataset.price); // Original price
        let discount = parseFloat(row.querySelector(".quantity-input").dataset.discount); // Discount
        let quantity = parseInt(row.querySelector(".quantity-input").value); // Updated quantity

        // Calculate discounted price
        let finalPrice = discount > 0 ? price * (1 - discount / 100) : price;
        let itemTotal = finalPrice * quantity; // Calculate item total

        // Update the quantity and item total in the Cart Totals section
        let cartItem = document.querySelector(`.cart-item-${row.dataset.menuId}`);
        if (cartItem) {
            cartItem.querySelector(".cart-item-quantity").textContent = quantity > 1 ? `(${quantity})` : ""; // Show quantity if > 1
            cartItem.querySelector(".cart-item-total").textContent = formatPrice(itemTotal); // Two decimal places for item totals
        }

        totalPrice += itemTotal; // Accumulate total price
    });

    // Update the grand total (rounded to nearest integer)
    document.querySelector("#total-price").textContent = formatRoundedPrice(totalPrice);
}

// Helper to format price with two decimal places (₱XXX.XX)
function formatPrice(price) {
    return "₱" + price.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, "$&,");
}

// Helper to format and round price (₱XXX)
function formatRoundedPrice(price) {
    return "₱" + Math.round(price).toLocaleString(); // Rounds and formats the total
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
    const modal = overlay.querySelector(".custom-modal");

    overlay.classList.add("active");
    modal.classList.add("active");
}

// Close modal
function closeModal(modalId) {
    const overlay = document.getElementById(modalId);
    const modal = overlay.querySelector(".custom-modal");

    overlay.classList.remove("active");
    modal.classList.remove("active");
}
