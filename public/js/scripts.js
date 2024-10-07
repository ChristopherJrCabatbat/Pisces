// Burger Sidebar Toggle
document.getElementById("burgerBtn").addEventListener("click", function() {
    const sidebar = document.getElementById("sidebar");
    sidebar.classList.toggle("active");
});

// Close Sidebar When Clicking Outside
document.addEventListener("click", function(event) {
    const sidebar = document.getElementById("sidebar");
    const burgerBtn = document.getElementById("burgerBtn");

    // Check if the click is outside the sidebar and the burger button
    if (!sidebar.contains(event.target) && !burgerBtn.contains(event.target)) {
        sidebar.classList.add("active"); // Hide sidebar on outside click
    }
});