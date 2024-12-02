<?php
session_start();

if (!isset($_SESSION['username']) || !isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: customer_login.php"); // Redirect to login page
    exit();
}

include 'config.php';

$user_id = $_SESSION['user_id'];

// Fetch pre-orders for the logged-in customer
$stmt = $conn->prepare("SELECT * FROM preorders WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$preorders_result = $stmt->get_result();
$stmt->close();

// Fetch reservations for the logged-in customer
$stmt = $conn->prepare("SELECT * FROM reservations WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$reservations_result = $stmt->get_result();
$stmt->close();

// Fetch orders for the logged-in customer
$stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$orders_result = $stmt->get_result();
$stmt->close();

// Initialize variables for displaying forms
$show_username_form = isset($_POST['show_username_form']);
$show_password_form = isset($_POST['show_password_form']);

// Handle profile update form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update_username'])) {
        $new_username = $_POST['username'];

        if ($new_username) {
            $stmt = $conn->prepare("UPDATE users SET username = ? WHERE id = ?");
            $stmt->bind_param("si", $new_username, $user_id);
            $stmt->execute();
            $stmt->close();

            // Update session username
            $_SESSION['username'] = $new_username;
            echo "Username updated successfully.";
        }
    }

    if (isset($_POST['update_password'])) {
        $new_password = $_POST['password'];

        if ($new_password) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->bind_param("si", $hashed_password, $user_id);
            $stmt->execute();
            $stmt->close();

            echo "Password updated successfully.";
        }
    }

    // Handle order cancellation
    if (isset($_POST['cancel_order'])) {
        $order_id = $_POST['order_id'];

        // Begin a transaction
        $conn->begin_transaction();

        try {
            // Update the status of the order to 'Cancelled'
            $stmt = $conn->prepare("UPDATE orders SET status = 'Cancelled' WHERE id = ? AND user_id = ?");
            $stmt->bind_param("ii", $order_id, $user_id);
            $stmt->execute();
            $stmt->close();

            // Delete the corresponding items from the order_items table
            $stmt = $conn->prepare("DELETE FROM order_items WHERE order_id = ?");
            $stmt->bind_param("i", $order_id);
            $stmt->execute();
            $stmt->close();

            // Commit the transaction
            $conn->commit();
            echo "Order cancelled successfully.";
        } catch (Exception $e) {
            // Rollback the transaction on error
            $conn->rollback();
            echo "Failed to cancel order: " . $e->getMessage();
        }
    }

    // Handle reservation cancellation
    if (isset($_POST['cancel_reservation'])) {
        $reservation_id = $_POST['reservation_id'];

        // Begin a transaction
        $conn->begin_transaction();

        try {
            // Update the status of the reservation to 'Cancelled'
            $stmt = $conn->prepare("UPDATE reservations SET status = 'Cancelled' WHERE id = ? AND user_id = ?");
            $stmt->bind_param("ii", $reservation_id, $user_id);
            $stmt->execute();
            $stmt->close();

            // Commit the transaction
            $conn->commit();
            echo "Reservation cancelled successfully.";
        } catch (Exception $e) {
            // Rollback the transaction on error
            $conn->rollback();
            echo "Failed to cancel reservation: " . $e->getMessage();
        }
    }

    // Handle clear history
    if (isset($_POST['clear_history'])) {
        // Begin a transaction
        $conn->begin_transaction();

        try {
            // Delete canceled orders from the orders table
            $stmt = $conn->prepare("DELETE FROM orders WHERE user_id = ? AND status = 'Cancelled'");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->close();

            // Delete corresponding items from the order_items table
            $stmt = $conn->prepare("DELETE FROM order_items WHERE order_id IN (SELECT id FROM orders WHERE user_id = ? AND status = 'Cancelled')");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->close();

            // Commit the transaction
            $conn->commit();
            echo "History cleared successfully.";
        } catch (Exception $e) {
            // Rollback the transaction on error
            $conn->rollback();
            echo "Failed to clear history: " . $e->getMessage();
        }
    }
}

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard - The Gallery Café</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <?php include 'header.php'; // Include header ?>
    </header>

    <main>
        <h1>Customer Dashboard</h1>

        <div class="dashboard-section">
            <h2>Profile</h2>
            <form action="customer_dashboard.php" method="POST">
                <input type="submit" name="show_username_form" value="Change Username">
                <input type="submit" name="show_password_form" value="Change Password">
            </form>

            <?php if ($show_username_form): ?>
                <div class="form-group">
                    <h2>Change Username</h2>
                    <form action="customer_dashboard.php" method="POST">
                        <label for="username">New Username:</label>
                        <input type="text" id="username" name="username" placeholder="Enter new username" required>
                        <input type="submit" name="update_username" value="Update Username">
                    </form>
                </div>
            <?php endif; ?>

            <?php if ($show_password_form): ?>
                <div class="form-group">
                    <h2>Change Password</h2>
                    <form action="customer_dashboard.php" method="POST">
                        <label for="password">New Password:</label>
                        <input type="password" id="password" name="password" placeholder="Enter new password" required>
                        <input type="submit" name="update_password" value="Update Password">
                    </form>
                </div>
            <?php endif; ?>
        </div>

        <div class="dashboard-section">
            <h2>My Pre-Orders</h2>
            <div class="preorders">
                <?php while ($row = $preorders_result->fetch_assoc()): ?>
                    <div class="preorder">
                        <p><strong>Item: <?php echo htmlspecialchars($row['item_name']); ?></strong></p>
                        <p>Quantity: <?php echo htmlspecialchars($row['quantity']); ?></p>
                        <p>Date: <?php echo htmlspecialchars($row['preorder_date']); ?></p>
                        <p>Status: <?php echo htmlspecialchars($row['status']); ?></p>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>

        <div class="dashboard-section">
            <h2>My Reservations</h2>
            <div class="reservations">
                <?php while ($row = $reservations_result->fetch_assoc()): ?>
                    <div class="reservation">
                        <p><strong>Reservation ID: <?php echo htmlspecialchars($row['id']); ?></strong></p>
                        <p>Date: <?php echo htmlspecialchars($row['reservation_date']); ?></p>
                        <p>Time: <?php echo htmlspecialchars($row['reservation_time']); ?></p>
                        <p>Status: <?php echo htmlspecialchars($row['status']); ?></p>
                        <?php if ($row['status'] === 'Pending'): ?>
                            <form action="customer_dashboard.php" method="POST" style="display:inline;">
                                <input type="hidden" name="reservation_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                <input type="submit" name="cancel_reservation" value="Cancel Reservation">
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>

        <div class="dashboard-section">
            <h2>My Orders</h2>
            <div class="orders">
                <?php while ($row = $orders_result->fetch_assoc()): ?>
                    <div class="order">
                        <p><strong>Order ID: <?php echo htmlspecialchars($row['id']); ?></strong></p>
                        <p>Item ID: <?php echo htmlspecialchars($row['item_id']); ?></p>
                        <p>Total Amount: <?php echo htmlspecialchars($row['total_amount']); ?></p>
                        <p>Status: <?php echo htmlspecialchars($row['status']); ?></p>
                        <?php if ($row['status'] === 'Pending'): ?>
                            <form action="customer_dashboard.php" method="POST" style="display:inline;">
                                <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                <input type="submit" name="cancel_order" value="Cancel Order">
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            </div>
            <form action="customer_dashboard.php" method="POST">
                <input type="submit" name="clear_history" value="Clear Canceled Orders">
            </form>
        </div>
    </main>

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
