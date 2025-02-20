<?php
session_start();
include('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $_SESSION['user'] = mysqli_fetch_assoc($result);
        header('Location: index.html');
    } else {
        echo "Invalid credentials";
    }
}
?>

<form method="POST">
    <label for="email">Email</label><br>
    <input type="email" name="email" required><br>
    <label for="password">Password</label><br>
    <input type="password" name="password" required><br>
    <button type="submit">Login</button>
</form>
