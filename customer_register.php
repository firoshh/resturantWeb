<?php
session_start();
include 'header.php'; // Include the common header
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Register</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        function validateForm() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            if (password.length <= 6) {
                alert('Password must be greater than 6 characters.');
                return false;
            }
            if (password !== confirmPassword) {
                alert('Passwords do not match.');
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <header>
        <!-- Add any header content if needed -->
    </header>
    <form action="customer_register_process.php" method="POST" onsubmit="return validateForm()">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>
        <input type="hidden" name="role" value="customer">
        <input type="submit" value="Register">
    </form>
    <footer>
        <p>&copy; 2024 The Gallery Café. All rights reserved.</p>
        <p>
            <a href="privacy_policy.php">Privacy Policy</a> | 
            <a href="terms_of_service.php">Terms of Service</a> | 
            <a href="info.php">About Us</a> | 
            <a href="contact.php">Contact Us</a>

        </p>
    </footer>
</body>
</html>
