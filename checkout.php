<?php
session_start();
include 'header.php';
include 'config.php'; // Ensure this file initializes the $conn variable

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Update this to your actual login page
    exit();
}

// Check if the cart is empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    die("Your cart is empty.");
}

// Check if user_id is set in session
if (!isset($_SESSION['user_id'])) {
    die("User ID not found. Please log in again.");
}

// Fetch cart items from the session
$cartItems = $_SESSION['cart'];
$user_id = $_SESSION['user_id'];

// Begin transaction
$conn->begin_transaction();

try {
    // Insert the order into the orders table
    $stmt = $conn->prepare("INSERT INTO orders (user_id, order_date, total_amount, status, item_name) VALUES (?, NOW(), ?, 'Pending', ?)");
    if ($stmt === false) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    // Calculate the total amount and create a string of item names
    $total_amount = 0;
    $item_names = [];
    foreach ($cartItems as $itemId => $quantity) {
        $query = "SELECT item_price, item_name FROM menu_items WHERE id = ?";
        $itemStmt = $conn->prepare($query);
        if ($itemStmt === false) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        $itemStmt->bind_param("i", $itemId);
        $itemStmt->execute();
        $itemResult = $itemStmt->get_result();
        $item = $itemResult->fetch_assoc();
        $item_price = $item['item_price'];
        $item_name = $item['item_name'];
        $total_amount += $item_price * $quantity;
        $item_names[] = $item_name;
        $itemStmt->close();
    }

    // Join item names into a single string
    $item_names_string = implode(", ", $item_names);

    $stmt->bind_param("iis", $user_id, $total_amount, $item_names_string);
    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }
    $order_id = $stmt->insert_id; // Get the last inserted order ID
    $stmt->close();

    // Insert the order items into the order_items table
    $stmt = $conn->prepare("INSERT INTO order_items (order_id, item_id, quantity) VALUES (?, ?, ?)");
    if ($stmt === false) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    foreach ($cartItems as $itemId => $quantity) {
        $stmt->bind_param("iii", $order_id, $itemId, $quantity);
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }
    }
    $stmt->close();

    // Commit the transaction
    $conn->commit();

    // Clear the cart
    unset($_SESSION['cart']);

    echo "Order placed successfully.";

} catch (Exception $e) {
    $conn->rollback(); // Rollback the transaction on error
    die("Error placing order: " . $e->getMessage());
}

$conn->close();
?>
