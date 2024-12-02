<?php

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Gallery Café</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <nav>
            <a href="index.php">Home</a>
            <a href="menu.php">Menu</a>
            <a href="reservation.php">Reserve a Table</a>
            <a href="events.php">Events</a>
            <a href="promotions.php">Promotions</a>
            <a href="cart.php">Cart</a>
            <?php if (isset($_SESSION['username'])): ?>
                <a href="preorder.php">Pre-Order</a>
                <?php
                
                if ($_SESSION['role'] === 'admin') {
                    echo '<a href="admin_dashboard.php">My Profile</a>';
                } elseif ($_SESSION['role'] === 'staff') {
                    echo '<a href="staff_dashboard.php">My Profile</a>';
                } elseif ($_SESSION['role'] === 'customer') {
                    echo '<a href="customer_dashboard.php">My Profile</a>';
                }
                ?>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="customer_login.php">Login</a>
            <?php endif; ?>
        </nav>
    </header>
