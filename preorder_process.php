<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $item_name = $_POST['item_name'];
        $quantity = $_POST['quantity'];
        $preorder_date = $_POST['preorder_date'];
        $status = 'Pending'; // Default status for a new pre-order

        // Insert pre-order into database
        $stmt = $conn->prepare("INSERT INTO preorders (user_id, item_name, quantity, preorder_date, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $user_id, $item_name, $quantity, $preorder_date, $status);
        
        if ($stmt->execute()) {
            echo "Pre-order placed successfully.";
        } else {
            echo "Error: " . $stmt->error;
        }
        
        $stmt->close();
    } else {
        echo "You need to be logged in to place a pre-order.";
    }

    $conn->close();
} else {
    // Redirect to the pre-order form if accessed directly
    header("Location: preorder.php");
    exit();
}
?>
