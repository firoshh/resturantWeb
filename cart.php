<?php

session_start();
include 'header.php'; // Include the common header


include 'config.php'; // Ensure this file initializes the $conn variable

// Check if the cart is empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
    exit;
}

// Fetch cart items from the session
$cartItems = $_SESSION['cart'];

// Function to calculate the total cost of the cart
function calculateCartTotal($cartItems, $conn) {
    $total = 0;
    foreach ($cartItems as $itemId => $quantity) {
        // Ensure quantity is an integer
        $quantity = (int)$quantity;

        // Fetch item price from the database
        $query = "SELECT item_price FROM menu_items WHERE id = ?";
        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param('i', $itemId);
        $stmt->execute();
        $result = $stmt->get_result();
        $item = $result->fetch_assoc();

        if ($item) {
            // Ensure item_price is a number
            $itemPrice = (float)$item['item_price'];
            $total += $itemPrice * $quantity;
        }
    }
    return number_format($total, 2);
}

// Handle quantity update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_quantity'])) {
    $itemId = $_POST['item_id'];
    $newQuantity = $_POST['quantity'];

    if (isset($cartItems[$itemId])) {
        $cartItems[$itemId] = $newQuantity;
        $_SESSION['cart'] = $cartItems;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>

    </header>

    <main>
        <h1>Cart</h1>
        <div class="cart-container">
            <?php foreach ($cartItems as $itemId => $quantity): ?>
                <?php
                // Fetch item details from the database
                $query = "SELECT * FROM menu_items WHERE id = ?";
                $stmt = $conn->prepare($query);
                if ($stmt === false) {
                    die("Prepare failed: " . $conn->error);
                }
                $stmt->bind_param('i', $itemId);
                $stmt->execute();
                $result = $stmt->get_result();
                $item = $result->fetch_assoc();
                ?>
                <?php if ($item): ?>
                    <div class="cart-item">
                        <img src="images/<?php echo htmlspecialchars($item['item_image'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($item['item_name'], ENT_QUOTES, 'UTF-8'); ?>">
                        <div class="cart-item-details">
                            <h3><?php echo htmlspecialchars($item['item_name'], ENT_QUOTES, 'UTF-8'); ?></h3>
                            <p>Price: $<?php echo htmlspecialchars($item['item_price'], ENT_QUOTES, 'UTF-8'); ?></p>
                            <form action="cart.php" method="POST">
                                <input type="hidden" name="item_id" value="<?php echo htmlspecialchars((int)$itemId, ENT_QUOTES, 'UTF-8'); ?>">
                                <label for="quantity">Quantity:</label>
                                <input type="number" id="quantity" name="quantity" value="<?php echo htmlspecialchars((int)$quantity, ENT_QUOTES, 'UTF-8'); ?>" min="1" required>
                                <button type="submit" name="update_quantity" class="btn">Update Quantity</button>
                            </form>
                            <form action="remove_from_cart.php" method="POST">
                                <input type="hidden" name="item_id" value="<?php echo htmlspecialchars((int)$itemId, ENT_QUOTES, 'UTF-8'); ?>">
                                <button type="submit" class="btn">Remove from Cart</button>
                            </form>
                            
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>

            <div class="cart-summary">
                <p>Total: $<?php echo calculateCartTotal($cartItems, $conn); ?></p>
                <a href="checkout.php"><button class="btn">Proceed to Checkout</button></a>
            </div>
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
