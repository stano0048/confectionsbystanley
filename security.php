// Registration
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

// Login
if (password_verify($_POST['password'], $hashed_password)) {
    $_SESSION['user'] = $user;
}
