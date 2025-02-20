<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

if (!isset($_SESSION['otp']) || !isset($_SESSION['email'])) {
    die("No OTP request found.");
}

$email = $_SESSION['email'];

// Resend OTP logic
if (isset($_POST['resend'])) {
    if (time() < $_SESSION['otp_expiration']) {
        die("Please wait before requesting a new OTP.");
    }

    $_SESSION['otp'] = rand(100000, 999999);
    $_SESSION['otp_expiration'] = time() + 300;

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'your-email@gmail.com';
        $mail->Password = 'your-email-password';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('your-email@gmail.com', 'Your Website Name');
        $mail->addAddress($email);
        $mail->Subject = "New OTP Code";
        $mail->Body = "Your new OTP Code is: " . $_SESSION['otp'] . ". It expires in 5 minutes.";

        $mail->send();
        echo "A new OTP has been sent to your email.";
    } catch (Exception $e) {
        die("Error sending OTP: " . $mail->ErrorInfo);
    }
}

// Verify OTP
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["otp"])) {
    if (time() > $_SESSION['otp_expiration']) {
        die("OTP has expired. Please request a new one.");
    }

    if ($_POST["otp"] == $_SESSION['otp']) {
        // Database connection
        $conn = new mysqli("localhost", "root", "", "your_database_name");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $hashed_password = $_SESSION['password'];

        $stmt = $conn->prepare("INSERT INTO users (email, password, is_verified) VALUES (?, ?, 1)");
        $stmt->bind_param("ss", $email, $hashed_password);

        if ($stmt->execute()) {
            session_unset();
            session_destroy();
            echo "Registration successful! <a href='login.html'>Login here</a>";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "Invalid OTP. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Verify Your OTP</h1>
    <form action="verify-otp.php" method="POST">
        <label for="otp">Enter OTP</label>
        <input type="text" name="otp" required>
        <button type="submit">Verify</button>
    </form>
    <form action="verify-otp.php" method="POST">
        <button type="submit" name="resend">Resend OTP</button>
    </form>
</body>
</html>
