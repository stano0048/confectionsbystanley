<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

include('db.php'); // Your database connection

// Fetch products and orders
$product_query = "SELECT * FROM products";
$product_result = mysqli_query($conn, $product_query);

$order_query = "SELECT * FROM orders";
$order_result = mysqli_query($conn, $order_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="admin-dashboard.php">Dashboard</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <h2>Admin Panel</h2>

    <h3>Manage Products</h3>
    <a href="add-product.php">Add Product</a>
    <table>
        <tr>
            <th>Product Name</th>
            <th>Price</th>
            <th>Actions</th>
        </tr>
        <?php while($product = mysqli_fetch_assoc($product_result)) { ?>
            <tr>
                <td><?= $product['name'] ?></td>
                <td>$<?= $product['price'] ?></td>
                <td>
                    <a href="edit-product.php?id=<?= $product['id'] ?>">Edit</a>
                    <a href="delete-product.php?id=<?= $product['id'] ?>">Delete</a>
                </td>
            </tr>
        <?php } ?>
    </table>

    <h3>View Orders</h3>
    <table>
        <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th>Total Price</th>
            <th>Delivery</th>
        </tr>
        <?php while($order = mysqli_fetch_assoc($order_result)) { ?>
            <tr>
                <td><?= $order['product'] ?></td>
                <td><?= $order['quantity'] ?></td>
                <td>$<?= $order['total_price'] ?></td>
                <td><?= $order['delivery_method'] ?></td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>
