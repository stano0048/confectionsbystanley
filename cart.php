<?php
session_start();
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_GET['add'])) {
    $product_id = $_GET['add'];
    $_SESSION['cart'][] = $product_id;
}

$cart_items = $_SESSION['cart'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopping Cart</title>
</head>
<body>
    <h2>Your Cart</h2>
    <ul>
        <?php foreach ($cart_items as $item_id) { ?>
            <li>Product <?= $item_id ?> <a href="remove.php?id=<?= $item_id ?>">Remove</a></li>
        <?php } ?>
    </ul>
    <a href="checkout.php">Proceed to Checkout</a>
</body>
</html>
