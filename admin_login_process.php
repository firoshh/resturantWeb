<?php
session_start();

include 'config.php'; // Include database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hardcoded credentials for admin login
    $admin_username = 'admin';
    $admin_password = 'adminpass123'; // Plain text password for simplicity

    // Check if credentials match
    if ($username === $admin_username && $password === $admin_password) {
        $_SESSION['username'] = $username;
        $_SESSION['role'] = 'admin';
        header("Location: admin_dashboard.php"); // Redirect to admin dashboard
        exit();
    } else {
        echo "Invalid username or password.";
    }
}
?>
