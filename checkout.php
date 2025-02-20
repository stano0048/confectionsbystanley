<?php
session_start();
include('db.php');

if (isset($_POST['checkout'])) {
    $user_id = $_SESSION['user']['id'];
    $total_price = $_POST['total_price'];

    $sql = "INSERT INTO orders (user_id, total_price) VALUES ('$user_id', '$total_price')";
    if (mysqli_query($conn, $sql)) {
        echo "Order placed successfully!";
    }
}
?>

<form method="POST">
    <label for="total_price">Total Price</label><br>
    <input type="number" name="total_price" required><br>
    <button type="submit" name="checkout">Checkout</button>
</form>
