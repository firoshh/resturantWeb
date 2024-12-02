<?php
session_start();
include 'config.php'; // Ensure this file contains your database connection settings

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $reservation_date = $_POST['reservation_date'];
    $reservation_time = $_POST['reservation_time'];
    $table_type = $_POST['table_type']; // This should match the form name attribute

    // Assuming user_id is set in session
    $user_id = $_SESSION['user_id'] ?? null; 
    $status = 'Pending'; // or any default status you want

    // Prepare and execute the SQL query
    $stmt = $conn->prepare("INSERT INTO reservations (name, email, phone, reservation_date, reservation_time, table_type, user_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssssi", $name, $email, $phone, $reservation_date, $reservation_time, $table_type, $user_id);


    if ($stmt->execute()) {
        echo "Reservation created successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserve a Table</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header>
    <?php include 'header.php'; // Include header ?>
</header>
    <form action="reservation.php" method="POST">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        
        <label for="phone">Phone Number:</label>
        <input type="tel" id="phone" name="phone" required>
        
        <label for="reservation_date">Reservation Date:</label>
        <input type="date" id="reservation_date" name="reservation_date" required>
        
        <label for="reservation_time">Reservation Time:</label>
        <input type="time" id="reservation_time" name="reservation_time" required>
        
        
    <!-- Other fields -->
    <label for="table_type">Table Type:</label>
    <select id="table_type" name="table_type" required>
        <option value="">Select Table Type</option>
        <option value="Small">Small (2-4 seats)</option>
        <option value="Medium">Medium (5-8 seats)</option>
        <option value="Large">Large (9-12 seats)</option>
        <option value="VIP">VIP (14+ seats)</option>
    </select>
   


        
        <input type="submit" value="Reserve">
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
