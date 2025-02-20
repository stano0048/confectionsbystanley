<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php'; // Include PHPMailer

$host = "localhost"; 
$username = "root"; // Your MySQL username
$password = ""; // Your MySQL password
$database = "your_database"; // Your database name

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle registration form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["register"])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $otp = rand(100000, 999999);
    $_SESSION['otp'] = $otp;
    $_SESSION['email'] = $email;
    $_SESSION['password'] = $password;

    // Check if email already exists
    $check_email = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $check_email->bind_param("s", $email);
    $check_email->execute();
    $result = $check_email->get_result();
    
    if ($result->num_rows > 0) {
        echo "Email already exists!";
        exit;
    }

    // Send OTP
    if (sendOTP($email, $otp)) {
        header("Location: verify-otp.php");
        exit();
    } else {
        echo "Failed to send OTP.";
    }
}

// OTP verification
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["verify_otp"])) {
    $entered_otp = $_POST['otp'];
    
    if ($entered_otp == $_SESSION['otp']) {
        $email = $_SESSION['email'];
        $password = $_SESSION['password'];
        
        // Insert user into database
        $insert = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
        $insert->bind_param("ss", $email, $password);
        
        if ($insert->execute()) {
            // Send welcome email
            sendWelcomeEmail($email);

            echo "Registration successful!";
            unset($_SESSION['otp']);
            unset($_SESSION['password']);
            unset($_SESSION['email']);
        } else {
            echo "Registration failed.";
        }
    } else {
        echo "Invalid OTP!";
    }
}

// Function to send OTP via email
function sendOTP($email, $otp) {
    $mail = new PHPMailer(true);
    
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'stanleymakanga45@gmail.com';
        $mail->Password = 'pthpaiqxsahgxnrn';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('stanleymakanga45@gmail.com', 'Your Website');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = "Your OTP Code";
        $mail->Body = "Your OTP code is <b>$otp</b>. It expires in 10 minutes.";

        return $mail->send();
    } catch (Exception $e) {
        return false;
    }
}

// Function to send welcome email after successful registration
function sendWelcomeEmail($email) {
    $mail = new PHPMailer(true);
    
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'stanleymakanga45@gmail.com';
        $mail->Password = 'pthpaiqxsahgxnrn';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('stanleymakanga45@gmail.com', 'Your Website');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = "Welcome to Our Website!";
        $mail->Body = "
            <h2>Welcome to Our Platform!</h2>
            <p>Dear User,</p>
            <p>Thank you for registering on our website. We're excited to have you on board.</p>
            <p>You can now log in and explore our services.</p>
            <br>
            <p>Best regards,</p>
            <p>Your Website Team</p>
        ";

        return $mail->send();
    } catch (Exception $e) {
        return false;
    }
}
?>
