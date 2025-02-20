// Select dark mode toggle button
const darkModeToggle = document.getElementById("darkModeToggle");

// Function to check and apply system's dark mode preference
function applyDarkModePreference() {
    if (localStorage.getItem("darkMode") === "enabled" || 
        (window.matchMedia && window.matchMedia("(prefers-color-scheme: dark)").matches)) {
        document.body.classList.add("dark-mode");
        darkModeToggle.textContent = "☀️ Light Mode";
    } else {
        document.body.classList.remove("dark-mode");
        darkModeToggle.textContent = "🌙 Dark Mode";
    }
}

// Check local storage for dark mode preference
applyDarkModePreference();

// Listen for toggle button click to switch dark mode
darkModeToggle.addEventListener("click", () => {
    document.body.classList.toggle("dark-mode");

    // Update button text and local storage
    if (document.body.classList.contains("dark-mode")) {
        localStorage.setItem("darkMode", "enabled");
        darkModeToggle.textContent = "☀️ Light Mode";
    } else {
        localStorage.setItem("darkMode", "disabled");
        darkModeToggle.textContent = "🌙 Dark Mode";
    }
});

// Listen for system theme change and apply new theme if necessary
window.matchMedia("(prefers-color-scheme: dark)").addEventListener("change", applyDarkModePreference);
