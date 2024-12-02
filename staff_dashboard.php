<?php
session_start();
include 'config.php'; // Include database connection file

// Check if the user is logged in and is a staff member
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'staff') {
    header("Location: staff_login.php"); // Redirect to login if not a staff member
    exit();
}

// Handle reservation confirmation, modification, or cancellation
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['confirm_reservation'])) {
        $reservation_id = $_POST['reservation_id'];
        $stmt = $conn->prepare("UPDATE reservations SET status = 'confirmed' WHERE id = ?");
        $stmt->bind_param("i", $reservation_id);
        $stmt->execute();
        $stmt->close();
        echo "Reservation confirmed.";
    }

  // Handle message deletion
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['delete_message'])) {
        $message_id = intval($_POST['message_id']); // Ensure ID is an integer
    
        // Prepare and execute the SQL statement
        $stmt = $conn->prepare("DELETE FROM contact_messages WHERE id = ?");
        $stmt->bind_param("i", $message_id);
    
        if ($stmt->execute()) {
            echo "<p>Message deleted successfully.</p>";
        } else {
            echo "<p>Error deleting message: " . $conn->error . "</p>";
        }
    
        $stmt->close();
    }
}
  

    if (isset($_POST['modify_reservation'])) {
        $reservation_id = $_POST['reservation_id'];
        // Implement modification logic here
        echo "Reservation modified.";
    }

    if (isset($_POST['cancel_reservation'])) {
        $reservation_id = $_POST['reservation_id'];
        $stmt = $conn->prepare("UPDATE reservations SET status = 'cancelled' WHERE id = ?");
        $stmt->bind_param("i", $reservation_id);
        $stmt->execute();
        $stmt->close();
        echo "Reservation cancelled.";
    }

    if (isset($_POST['confirm_order'])) {
        $order_id = $_POST['order_id'];
        $stmt = $conn->prepare("UPDATE preorders SET status = 'confirmed' WHERE id = ?");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $stmt->close();
        echo "Order confirmed.";
    }

    if (isset($_POST['delete_order'])) {
        $order_id = intval($_POST['order_id']); // Ensure ID is an integer
    
        // Prepare and execute the SQL statement
        $stmt = $conn->prepare("DELETE FROM preorders WHERE id = ?");
        $stmt->bind_param("i", $order_id);
    
        if ($stmt->execute()) {
            echo "<p>Pre-Order deleted successfully.</p>";
        } else {
            echo "<p>Error deleting  pre-order: " . $conn->error . "</p>";
        }
    
        $stmt->close();
    }

    if (isset($_POST['cancel_order'])) {
        $order_id = $_POST['order_id'];
        $stmt = $conn->prepare("UPDATE preorders SET status = 'cancelled' WHERE id = ?");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $stmt->close();
        echo "Order cancelled.";
    }
}

// Fetch reservations
$reservations_stmt = $conn->prepare("SELECT id, name, email, phone, reservation_date, reservation_time, table_type, user_id, status FROM reservations");
$reservations_stmt->execute();
$reservations_result = $reservations_stmt->get_result();

// Fetch pre-orders
$preorders_stmt = $conn->prepare("SELECT id, item_name, quantity, preorder_date, status, user_id FROM preorders");
$preorders_stmt->execute();
$preorders_result = $preorders_stmt->get_result();

// Fetch customer messages
$messages_stmt = $conn->prepare("SELECT id, name, email, message, sent_at FROM contact_messages ORDER BY sent_at DESC");
$messages_stmt->execute();
$messages_result = $messages_stmt->get_result();
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard - The Gallery Café</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <?php include 'header.php'; // Include header ?>
    </header>

    <main>
        <h1>Staff Dashboard</h1>

        <h2>View Reservations</h2>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Table type</th>
                    <th>User ID</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $reservations_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['phone']); ?></td>
                        <td><?php echo htmlspecialchars($row['reservation_date']); ?></td>
                        <td><?php echo htmlspecialchars($row['reservation_time']); ?></td>
                        <td><?php echo htmlspecialchars($row['table_type']); ?></td>
                        <td><?php echo htmlspecialchars($row['user_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                        <td>
                            <form action="staff_dashboard.php" method="POST">
                                <input type="hidden" name="reservation_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                <input type="submit" name="confirm_reservation" value="Confirm">
                                <input type="submit" name="modify_reservation" value="Modify">
                                <input type="submit" name="cancel_reservation" value="Cancel">
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <h2>View Pre-Orders</h2>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>User ID</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $preorders_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['item_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                        <td><?php echo htmlspecialchars($row['preorder_date']); ?></td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                        <td><?php echo htmlspecialchars($row['user_id']); ?></td>
                        <td>
                            <form action="staff_dashboard.php" method="POST">
                                <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                <input type="submit" name="confirm_order" value="Confirm">
                                <input type="submit" name="delete_order" value="Delete">
                                <input type="submit" name="cancel_order" value="Cancel">
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <!-- View Contact Messages -->
        <h2>View Contact Messages</h2>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Message</th>
                    <th>Sent At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $messages_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['message']); ?></td>
                        <td><?php echo htmlspecialchars($row['sent_at']); ?></td>
                        <td>
                        <form action="staff_dashboard.php" method="POST">
    <input type="hidden" name="message_id" value="<?php echo htmlspecialchars($row['id']); ?>">
    <input type="submit" name="delete_message" value="Delete">
</form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </main>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <footer>
        <p>&copy; 2024 The Gallery Café. All rights reserved.</p>
    </footer>
</body>
</html>
