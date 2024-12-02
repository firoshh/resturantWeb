<?php
session_start();
include 'config.php'; // Include your database connection file

// Validate user input
$username = $_POST['username'];
$password = $_POST['password'];

// Fetch user from database
$query = "SELECT * FROM users WHERE username = ? AND role = 'customer'";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    // Verify password
    if (password_verify($password, $user['password'])) {
        $_SESSION['username'] = $username;
        $_SESSION['role'] = 'customer';
        $_SESSION['user_id'] = $user['id']; // Set user_id in session
        header("Location: customer_dashboard.php");
        exit();
    } else {
        echo "Incorrect password.";
    }
} else {
    echo "User not found.";
}
?>
