<?php
session_start();

include 'config.php'; // Include database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Debugging output
    echo "Submitted Username: " . htmlspecialchars($username) . "<br>";
    echo "Submitted Password: " . htmlspecialchars($password) . "<br>";

    // Hardcoded credentials for staff login
    $staff_username = 'staff';
    $staff_password = 'staffpass123'; // Plain text password for simplicity

    // Check if credentials match
    if ($username === $staff_username && $password === $staff_password) {
        $_SESSION['username'] = $username;
        $_SESSION['role'] = 'staff';
        header("Location: staff_dashboard.php"); // Redirect to staff dashboard
        exit();
    } else {
        echo "Invalid username or password.";
    }
}
?>
