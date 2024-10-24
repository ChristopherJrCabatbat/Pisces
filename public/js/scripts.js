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