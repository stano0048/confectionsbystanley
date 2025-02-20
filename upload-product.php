<?php
// Database connection
$host = "localhost"; // Change if needed
$username = "root"; // Change if needed
$password = ""; // Change if needed
$database = "shop"; // Your database name

$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = $_POST['price'];
    $category = trim($_POST['category']);
    $stock = $_POST['stock'];
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $is_menu_item = isset($_POST['is_menu_item']) ? 1 : 0;
    
    // Image upload handling
    $target_dir = "uploads/";
    $image_name = basename($_FILES["image"]["name"]);
    $target_file = $target_dir . time() . "_" . $image_name; // Prevent filename conflicts
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $allowed_types = ["jpg", "jpeg", "png", "gif", "webp"];

    if (!in_array($imageFileType, $allowed_types)) {
        die("Error: Only JPG, JPEG, PNG, GIF & WEBP files are allowed.");
    }

    if ($_FILES["image"]["size"] > 5 * 1024 * 1024) { // 5MB limit
        die("Error: Image size should not exceed 5MB.");
    }

    if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        die("Error: There was an issue uploading the image.");
    }

    // Insert data into database
    $sql = "INSERT INTO products (name, description, price, image, category, stock, is_featured, is_menu_item) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdssiii", $name, $description, $price, $target_file, $category, $stock, $is_featured, $is_menu_item);

    if ($stmt->execute()) {
        echo "Product uploaded successfully.";
        echo "<br><a href='admin-dashboard.html'>Go Back</a>";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close connections
    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>
