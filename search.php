<?php
$search_term = $_POST['search'] ?? '';
$query = "SELECT * FROM products WHERE name LIKE '%$search_term%'";
$result = mysqli_query($conn, $query);
?>
