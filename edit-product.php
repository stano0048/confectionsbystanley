<?php
include('db.php');
$id = $_GET['id'];
$product_query = "SELECT * FROM products WHERE id = $id";
$product_result = mysqli_query($conn, $product_query);
$product = mysqli_fetch_assoc($product_result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    $sql = "UPDATE products SET name = '$name', price = '$price', description = '$description' WHERE id = $id";
    if (mysqli_query($conn, $sql)) {
        header('Location: admin-dashboard.php');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
</head>
<body>
    <h2>Edit Product</h2>
    <form method="POST">
        <label for="name">Product Name</label><br>
        <input type="text" name="name" value="<?= $product['name'] ?>" required><br>
        <label for="price">Price</label><br>
        <input type="number" name="price" value="<?= $product['price'] ?>" required><br>
        <label for="description">Description</label><br>
        <textarea name="description" required><?= $product['description'] ?></textarea><br>
        <button type="submit">Update Product</button>
    </form>
</body>
</html>
