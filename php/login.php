<?php
session_start();
include 'db.php'; // Database connection

// Registration
if (isset($_POST['register'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Secure password
    $role = 'client'; // Default role for users

    $query = "INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$password', '$role')";
    mysqli_query($conn, $query);
    header('Location: login.php');
}

// Login
if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user;
        if ($user['role'] == 'admin') {
            header('Location: admin-dashboard.php');
        } else {
            header('Location: client-dashboard.php');
        }
    } else {
        echo "Invalid email or password.";
    }
}
?>