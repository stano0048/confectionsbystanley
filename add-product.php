<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include('db.php');
    
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    $sql = "INSERT INTO products (name, price, description) VALUES ('$name', '$price', '$description')";
    if (mysqli_query($conn, $sql)) {
        header('Location: admin-dashboard.php');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product</title>
</head>
<body>
    <h2>Add Product</h2>
    <form method="POST">
        <label for="name">Product Name</label><br>
        <input type="text" name="name" required><br>
        <label for="price">Price</label><br>
        <input type="number" name="price" required><br>
        <label for="description">Description</label><br>
        <textarea name="description" required></textarea><br>
        <button type="submit">Add Product</button>
    </form>
</body>
</html>
