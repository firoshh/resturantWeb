<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $itemId = $_POST['item_id'];
    $quantity = (int)$_POST['quantity']; // Ensure quantity is an integer

    // Validate quantity
    if ($quantity < 1) {
        header('Location: menu.php');
        exit;
    }

    // Initialize the cart if it's not set
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Add to cart session
    if (isset($_SESSION['cart'][$itemId])) {
        // Ensure the cart item is an integer before adding
        $_SESSION['cart'][$itemId] = (int)$_SESSION['cart'][$itemId] + $quantity;
    } else {
        $_SESSION['cart'][$itemId] = $quantity;
    }

    header('Location: cart.php');
    exit;
}
?>
