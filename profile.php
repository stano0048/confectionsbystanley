<?php
session_start();
include('db.php');

// Redirect to login page if not logged in
if (!isset($_SESSION['user'])) {
    header('Location: login.html');
    exit();
}

$user = $_SESSION['user'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Update user details
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    
    $update_query = "UPDATE users SET name = '$name', phone = '$phone'";
    
    if ($password) {
        // Hash password if provided
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $update_query .= ", password = '$hashed_password'";
    }

    $update_query .= " WHERE id = {$user['id']}";

    if (mysqli_query($conn, $update_query)) {
        // Update session user data
        $_SESSION['user']['name'] = $name;
        $_SESSION['user']['phone'] = $phone;

        // Send email notification
        $to = $user['email'];
        $subject = "Profile Details Updated";
        $message = "Hello {$user['name']},\n\nYour profile details have been successfully updated.\n\nRegards,\nBakery Team";
        mail($to, $subject, $message);

        echo "Profile updated successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="menu.php">Menu</a></li>
                <li><a href="cart.php">Cart</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <h1>Profile</h1>
    <form action="profile.php" method="POST">
        <label for="name">Name</label>
        <input type="text" name="name" value="<?= $user['name'] ?>" required>

        <label for="phone">Phone Number</label>
        <input type="text" name="phone" value="<?= $user['phone'] ?>" required>

        <label for="password">New Password</label>
        <input type="password" name="password">

        <button type="submit">Update Profile</button>
    </form>
</body>
</html>
