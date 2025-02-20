<?php
// Include database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bakery_shop";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$product = $_POST['product'];
$quantity = $_POST['quantity'];
$delivery = $_POST['delivery'];

// Calculate the price based on product selection
$product_prices = [
    'bread' => 5.00,
    'cake' => 15.00
];

$total_price = $product_prices[$product] * $quantity;

// Insert order into database
$sql = "INSERT INTO orders (product, quantity, delivery_method, total_price) 
        VALUES ('$product', '$quantity', '$delivery', '$total_price')";

if ($conn->query($sql) === TRUE) {
    echo "Order placed successfully! Total: $" . $total_price;
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
